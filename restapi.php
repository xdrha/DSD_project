<?php

include("message.php");
include("contact.php");

$data = json_decode(file_get_contents("php://input"));

$name = "Matej Drha";
$port = 3306;
$messages = array();
$conn;
$search_text = "";
$last_uuid = '';
$contacts = array();

function get_contacts()
{
    global $name, $contacts;

    $me = new Contact();
    $me->ip = "localhost";
    $me->username = $name;
    $me->online = "1";
    $me->queue = "";
    array_push($contacts, $me);

    $conn = open_connection("localhost");
    $result = $conn->query("SELECT * FROM contacts;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = new Contact();
            $c->ip = $row["ip"];
            $c->username = $row["username"];
            $c->online = $row["online"];
            $c->queue = $row["queue"];

            array_push($contacts, $c);
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
}

function get_username()
{
    global $name;
    echo $name;
}


function open_connection($dbhost)
{

    global $port, $conn;

    $dbuser = "root";
    $dbpass = "DSDproject2020";
    $db = "dsd_project";
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    else return $conn;
}

function close_connection($conn)
{
    $conn->close();
}

function get_messages()
{
    $conn = open_connection("localhost");

    global $messages, $name, $last_uuid, $data;

    $sql = "SELECT * FROM messages";


    if (!empty($data->search)) {
        $sql .= " WHERE message like '%$data->search%' OR  sender_name like '%$data->search%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $arr = array();
        $array = array();

        while ($row = $result->fetch_assoc()) {
            $m = new Message();
            $m->time = $row["time"];
            $m->sender_name = $row["sender_name"];
            $m->message = $row["message"];
            $m->delivered = $row["delivered"];
            $m->uuid = $row["uuid"];
            $m->build_html($name);

            $arr[] = $m->get_html();

            array_push($messages, $m);
            $last_uuid = $m->uuid;
        }

        $array['messages'] = $arr;
        $array['last_uuid'] = $last_uuid;
        $array['username'] = $name;
        echo json_encode($array);
    } else {
        echo "0 results";
    }

    close_connection($conn);
    get_contacts();
}

function remove_message()
{
    global $data;

    $conn = open_connection("localhost");

    $sql = "DELETE FROM messages WHERE uuid='$data->id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode("OK");
    } else {
        echo json_encode("Error deleting record: " . $conn->error);
    }

    close_connection($conn);
}

function add_message()
{
    global $data, $name, $contacts;

    $uuid = gen_uuid();
    $sql = "INSERT INTO messages(`time`, sender_name, message, uuid) values(NOW(), '$name', '$data->message', '$uuid')";

    get_contacts();
    foreach ($contacts as $c) {
        if ($c->online == "1") {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $conn->error;
            }

            close_connection($conn);
        }
    }
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
