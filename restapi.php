<?php

include("message.php");
include("contact.php");

rear_configuration_file();
session_start();

$data = json_decode(file_get_contents("php://input"));

$name;
$ip;
$port = 3306;
$dbuser = "root";
$dbpass = "DSDproject2020";
$db = "dsd_project";
$messages = array();
$conn;
$search_text = "";
$last_uuid = '';
$contacts = array();

function rear_configuration_file()
{
    global $name, $ip;

    $myfile = fopen("configuration.txt", "r") or die("Unable to open file!");
    $name = trim(fgets($myfile));
    $ip = trim(fgets($myfile));
    fclose($myfile);
}

function retrive_contacts()
{
    global $contacts;
    $contacts = array();

    $conn = open_connection("localhost");
    $result = $conn->query("SELECT * FROM contacts;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = new Contact();
            $c->id = $row['id'];
            $c->ip = $row["ip"];
            $c->username = $row["username"];
            $c->queue = $row["queue"];
            $c->online = "0";

            array_push($contacts, $c);
        }
    }
    close_connection($conn);
}

function get_contacts()
{
    global $name, $contacts;
    $contacts = array();

    $me = new Contact();
    $me->ip = "localhost";
    $me->username = $name;
    $me->online = TRUE;
    $me->queue = "";
    array_push($contacts, $me);

    $arr = array();
    $array = array();

    $conn = open_connection("localhost");
    $result = $conn->query("SELECT * FROM contacts;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = new Contact();
            $c->id = $row['id'];
            $c->ip = $row["ip"];
            $c->username = $row["username"];
            $c->queue = $row["queue"];
            $c->online = check_connection($c);
            $c->build_html();

            array_push($contacts, $c);
            $arr[] = $c->get_html();
        }
        $array['contacts'] = $arr;
        echo json_encode($array);
    }
    close_connection($conn);
}


function check_connection($contact)
{
    //$_SESSION['db-con-lock-' . $contact->ip] = FALSE;
    if (!isset($_SESSION['db-con-lock-' . $contact->ip]) || !$_SESSION['db-con-lock-' . $contact->ip]) {

        $_SESSION['db-con-lock-' . $contact->ip] = true;

        global $port, $dbuser, $dbpass, $db;

        $conn = mysqli_init();
        mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 1);
        mysqli_options($conn, MYSQLI_OPT_READ_TIMEOUT, 1);
        mysqli_real_connect($conn, $contact->ip, $dbuser, $dbpass, $db, $port);

        if (mysqli_connect_errno()) {
            $_SESSION['db-con-lock-' . $contact->ip] = false;
            return FALSE;
        } else {
            if ($contact->queue != "") {
                update_db($contact);
            }
            $_SESSION['db-con-lock-' . $contact->ip] = false;
            return TRUE;
        }
    }
}

function update_db($contact)
{

    $conn = open_connection($contact->ip);

    if ($conn->multi_query($contact->queue) === TRUE) {
        echo "DB updated successfully";
        $contact->queue = "";
        update_contacts("localhost", $contact, "remove");
    } else {
        error_log("E-------------------------------- error " . $conn->error);
        echo "Error updating DB: " . $conn->error;
    }

    close_connection($conn);
}

function add_contact()
{
    global $data, $contacts, $messages, $ip, $name;

    $newc = new Contact();
    $newc->ip = $data->ip;
    $newc->username = $data->username;
    $newc->queue = "";
    $array = array();

    if (check_connection($newc)) {
        $sql = "INSERT INTO contacts(ip, username, queue) values('$data->ip', '$data->username', '');";

        get_contacts();
        foreach ($contacts as $c) {
            if ($c->online == TRUE) {
                $conn = open_connection($c->ip);

                if ($conn->query($sql) === TRUE) {
                    error_log("+++++++++++++++++++++++++++++++++++++ pridal sa kontakt");
                } else {
                    error_log("+++++++++++++++++++++++++++++++++++++ chyba pri pridavani kontaktu" . $conn->error);
                }

                close_connection($conn);
            } else {
                foreach ($contacts as $co) {
                    if ($co->online == TRUE) {
                        update_contacts($co->ip, $c, $sql);
                    }
                }
            }
        }

        get_messages();
        get_contacts();

        $conn = open_connection($data->ip);

        $sql = "DELETE from messages WHERE id > 0; DELETE from contacts WHERE id > 0; ";

        foreach ($contacts as $c) {
            if ($c->ip == "localhost") {
                $sql .= "INSERT INTO contacts(ip, username, queue) values('$ip', '$name', ''); ";
                continue;
            }
            if ($c->ip == $data->ip) continue;

            $sql .= "INSERT INTO contacts(ip, username, queue) values('$c->ip','$c->username',''); ";
            $sql .= "UPDATE contacts SET queue = '" . mysqli_real_escape_string($conn, $c->queue) . "' WHERE ip = '$c->ip';";
        }

        foreach ($messages as $m) {
            $sql .= "INSERT INTO messages(`time`, sender_name, message, uuid, sender_ip) values('$m->time', '$m->sender_name', '$m->message', '$m->uuid', '$m->sender_ip'); ";
        }

        error_log("/////////////////////////////" . $sql);

        if ($conn->multi_query($sql) === TRUE) {
            error_log("+++++++++++++++++++++++++++++++++++++ cooool skopirovalo to");
        } else {
            error_log("+++++++++++++++++++++++++++++++++++++ fnuk, nieco sa dojebalo" . $conn->error);
        }

        close_connection($conn);
        $array['result'] = "OK";
    } else {
        $array['result'] = "fail";
    }
    echo json_encode($array);
}

function remove_contact()
{
    global $data, $contacts;

    $sql = "DELETE FROM contacts WHERE id='$data->id';";

    get_contacts();
    foreach ($contacts as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                error_log("+++++++++++++++++++++++++++++++++++++ odstranil sa kontakt");
            } else {
                error_log("+++++++++++++++++++++++++++++++++++++ chyba pri odstranovani kontaktu" . $conn->error);
            }

            close_connection($conn);
        } else {
            foreach ($contacts as $co) {
                if ($co->online == TRUE) {
                    update_contacts($co->ip, $c, $sql);
                }
            }
        }
    }
}

switch ($data->action) {

    case "get_messages":
        get_messages();
        break;
    case "remove_message":
        remove_message();
        break;
    case "add_message":
        add_message();
        break;
    case "get_last_uuid":
        get_last_uuid();
        break;
    case "get_username":
        get_username();
        break;
    case "get_contacts":
        get_contacts();
        break;
    case "add_contact":
        add_contact();
        break;
    case "remove_contact":
        remove_contact();
        break;
}

function get_username()
{
    global $name;
    echo $name;
}


function open_connection($dbhost)
{

    global $port, $dbuser, $dbpass, $db;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_errno());
    } else return $conn;
}

function close_connection($conn)
{
    $conn->close();
}

function check_contact($ip)
{
    global $contacts;

    foreach ($contacts as $c) {
        if ($c->ip == $ip) return 0;
    }
    return 1;
}

function get_messages()
{
    retrive_contacts();

    $conn = open_connection("localhost");

    global $messages, $name, $last_uuid, $data, $ip;

    $sql = "SELECT * FROM messages";

    if (!empty($data->search)) {
        $sql .= " WHERE message like '%$data->search%' OR  sender_name like '%$data->search%'";
    }

    $sql .= " ORDER BY time ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $arr = array();
        $array = array();
        $new_contacts = "";

        while ($row = $result->fetch_assoc()) {
            $m = new Message();
            $m->time = $row["time"];
            $m->sender_name = $row["sender_name"];
            $m->sender_ip = $row["sender_ip"];
            $m->message = $row["message"];
            $m->uuid = $row["uuid"];
            $m->build_html($name);

            $arr[] = $m->get_html();

            array_push($messages, $m);
            $last_uuid = $m->uuid;
        }

        $array['messages'] = $arr;
        $array['new_contacts'] = $new_contacts;
        $array['last_uuid'] = $last_uuid;
        $array['username'] = $name;
        $array['ip'] = $ip;
        echo json_encode($array);
    } else {
        echo "0 results";
    }

    close_connection($conn);
}

function remove_message()
{
    global $data, $contacts;

    $conn = open_connection("localhost");

    $sql = "DELETE FROM messages WHERE uuid='$data->id';";

    get_contacts();
    foreach ($contacts as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleteing record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($contacts as $co) {
                if ($co->online == TRUE) {
                    update_contacts($co->ip, $c, $sql);
                }
            }
        }
    }
}

function add_message()
{
    global $data, $name, $contacts, $ip;

    $uuid = gen_uuid();
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO messages(`time`, sender_name, message, uuid, sender_ip) values('$date', '$name', '$data->message', '$uuid', '$ip'); ";

    get_contacts();
    foreach ($contacts as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                echo "Record added successfully";
            } else {
                echo "Error adding record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($contacts as $co) {
                if ($co->online == TRUE) {
                    update_contacts($co->ip, $c, $sql);
                }
            }
        }
    }
}

function update_contacts($ip, $contact, $query)
{
    $conn = open_connection($ip);

    if ($query == "remove") {
        $sql = "UPDATE contacts SET queue = '" . mysqli_real_escape_string($conn, "") . "' WHERE ip = '$contact->ip';";
    } else {
        $sql = "UPDATE contacts SET queue = CONCAT(queue, '" . mysqli_real_escape_string($conn, $query) . "') WHERE ip = '$contact->ip';";
    }

    if ($conn->query($sql) === TRUE) {
        error_log("-------------------------------------------------- dobre");
        echo "Record updated successfully";
    } else {
        error_log("-------------------------------------------------- zleee" . $conn->error);
        echo "Error updating record: " . $conn->error;
    }

    close_connection($conn);
}

function gen_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function get_last_uuid()
{
    $conn = open_connection("localhost");
    $sql = "SELECT uuid from messages ORDER BY id DESC LIMIT 1;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $last_uuid = $row['uuid'];
            echo $last_uuid;
        }
    }

    close_connection($conn);
}
