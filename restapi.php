<?php

include("bike.php");
include("branch.php");
include("availability.php");

rear_configuration_file();
session_start();

$data = json_decode(file_get_contents("php://input"));

$address;
$ip;
$port = 3306;
$dbuser = "root";
$dbpass = "DSDproject2020";
$db = "dsd_project";
$bikes = array();
$branches = array();
$conn;
$search_text = "";
$last_uuid = '';

function rear_configuration_file()
{
    global $address, $ip;

    $myfile = fopen("configuration.txt", "r") or die("Unable to open file!");
    $address = trim(fgets($myfile));
    $ip = trim(fgets($myfile));
    fclose($myfile);
}

function retrive_branches()
{
    global $branches;
    $branches = array();

    $conn = open_connection("localhost");
    $result = $conn->query("SELECT * FROM branch;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = new Branch();
            $c->id = $row['id'];
            $c->ip = $row["ip"];
            $c->address = $row["address"];
            $c->queue = $row["queue"];
            $c->online = "0";

            array_push($branches, $c);
        }
    }
    close_connection($conn);
}

function get_branches($echo)
{
    global $address, $branches;
    $branches = array();

    $me = new Branch();
    $me->ip = "localhost";
    $me->address = $address;
    $me->online = TRUE;
    $me->queue = "";
    array_push($branches, $me);

    $arr = array();
    $array = array();

    $conn = open_connection("localhost");
    $result = $conn->query("SELECT * FROM branch;");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = new Branch();
            $c->id = $row['id'];
            $c->ip = $row["ip"];
            $c->address = $row["address"];
            $c->queue = $row["queue"];
            $c->online = check_connection($c);
            $c->build_html();

            array_push($branches, $c);
            $arr[] = $c->get_html();
        }
        if ($echo) {
            $array['branches'] = $arr;
            echo json_encode($array);
        }
    }
    close_connection($conn);
}


function check_connection($branch)
{
    //$_SESSION['db-con-lock-' . $branch->ip] = FALSE;
    if (!isset($_SESSION['db-con-lock-' . $branch->ip]) || !$_SESSION['db-con-lock-' . $branch->ip]) {

        $_SESSION['db-con-lock-' . $branch->ip] = true;

        global $port, $dbuser, $dbpass, $db;

        $conn = mysqli_init();
        mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 1);
        mysqli_options($conn, MYSQLI_OPT_READ_TIMEOUT, 1);
        mysqli_real_connect($conn, $branch->ip, $dbuser, $dbpass, $db, $port);

        if (mysqli_connect_errno()) {
            $_SESSION['db-con-lock-' . $branch->ip] = false;
            return FALSE;
        } else {
            if ($branch->queue != "") {
                update_db($branch);
            }
            $_SESSION['db-con-lock-' . $branch->ip] = false;
            return TRUE;
        }
    }
}

function update_db($branch)
{

    $conn = open_connection($branch->ip);

    if ($conn->multi_query($branch->queue) === TRUE) {
        echo "DB updated successfully";
        $branch->queue = "";
        update_branches("localhost", $branch, "remove");
    } else {
        error_log("E-------------------------------- error " . $conn->error);
        echo "Error updating DB: " . $conn->error;
    }

    close_connection($conn);
}

function add_branch()
{
    global $data, $branches, $bikes, $ip, $address;

    $newc = new Branch();
    $newc->ip = $data->ip;
    $newc->address = $data->address;
    $newc->queue = "";
    $array = array();

    if (check_connection($newc)) {
        $sql = "INSERT INTO branch(ip, address, queue) values('$data->ip', '$data->address', '');";

        get_branches(FALSE);
        foreach ($branches as $c) {
            if ($c->online == TRUE) {
                $conn = open_connection($c->ip);

                if ($conn->query($sql) === TRUE) {
                    error_log("+++++++++++++++++++++++++++++++++++++ pridal sa kontakt");
                } else {
                    error_log("+++++++++++++++++++++++++++++++++++++ chyba pri pridavani kontaktu" . $conn->error);
                }

                close_connection($conn);
            } else {
                foreach ($branches as $co) {
                    if ($co->online == TRUE) {
                        update_branches($co->ip, $c, $sql);
                    }
                }
            }
        }

        get_bikes();
        get_branches(FALSE);

        $conn = open_connection($data->ip);

        $sql = "DELETE from bike WHERE id > 0; DELETE from branch WHERE id > 0; DELETE from stock WHERE id > 0; ";

        foreach ($branches as $c) {
            if ($c->ip == "localhost") {
                $sql .= "INSERT INTO branch(ip, address, queue) values('$ip', '$address', ''); ";
                continue;
            }
            if ($c->ip == $data->ip) continue;

            $sql .= "INSERT INTO branch(ip, address, queue) values('$c->ip','$c->address',''); ";
            $sql .= "UPDATE branch SET queue = '" . mysqli_real_escape_string($conn, $c->queue) . "' WHERE ip = '$c->ip';";
        }

        foreach ($bikes as $m) {
            $sql .= "INSERT INTO bike(price, brand, model, uuid, branch_ip) values('$m->price', '$m->brand', '$m->model', '$m->uuid', '$m->branch_ip'); ";
            $sql .= "INSERT INTO stock(bike_uuid, in_stock) values('$m->uuid', '0'); ";
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

function remove_branch()
{
    global $data, $branches;

    $sql = "DELETE FROM branch WHERE id='$data->id';";

    get_branches(TRUE);
    foreach ($branches as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                error_log("+++++++++++++++++++++++++++++++++++++ odstranil sa kontakt");
            } else {
                error_log("+++++++++++++++++++++++++++++++++++++ chyba pri odstranovani kontaktu" . $conn->error);
            }

            close_connection($conn);
        } else {
            foreach ($branches as $co) {
                if ($co->online == TRUE) {
                    update_branches($co->ip, $c, $sql);
                }
            }
        }
    }
}

switch ($data->action) {

    case "get_bikes":
        get_bikes();
        break;
    case "remove_bike":
        remove_bike();
        break;
    case "add_bike":
        add_bike();
        break;
    case "get_last_uuid":
        get_last_uuid();
        break;
    case "get_address":
        get_address();
        break;
    case "get_branches":
        get_branches(TRUE);
        break;
    case "add_branch":
        add_branch();
        break;
    case "remove_branch":
        remove_branch();
        break;
    case "check_bike_availability":
        check_bike_availability();
        break;
    case "change_bike_count":
        change_bike_count();
        break;
    case "edit_bike":
        edit_bike();
        break;
}

function edit_bike()
{
    global $data, $branches, $ip;

    $uuid = gen_uuid();
    $sql = "UPDATE bike SET model='$data->model', brand='$data->brand', price='$data->price' WHERE uuid='$data->uuid'; ";

    get_branches(FALSE);
    foreach ($branches as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->multi_query($sql) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($branches as $co) {
                if ($co->online == TRUE) {
                    update_branches($co->ip, $c, $sql);
                }
            }
        }
    }
}

function change_bike_count()
{
    global $data, $branches;

    $sql = "UPDATE stock SET in_stock = '$data->count' WHERE bike_uuid='$data->id';";

    get_branches(FALSE);
    foreach ($branches as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->query($sql) === TRUE) {
                echo "Record edited successfully";
            } else {
                echo "Error editing record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($branches as $co) {
                if ($co->online == TRUE) {
                    update_branches($co->ip, $c, $sql);
                }
            }
        }
    }
}

function check_bike_availability()
{
    global $data, $branches;
    $array_entries = array();
    $array = array();

    get_branches(FALSE);
    foreach ($branches as $b) {

        $a = new Availability();
        $a->branch_address = $b->address;

        if ($b->online == TRUE) {
            $a->online = TRUE;
            $conn = open_connection($b->ip);
            $sql = "SELECT * FROM stock WHERE bike_uuid='$data->id';";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $a->count = $row['in_stock'];
                }
            }
            close_connection($conn);
        }

        $a->build_html($b->ip);
        array_push($array_entries, $a->html);
    }

    $array['entries'] = $array_entries;
    echo json_encode($array);
}

function get_address()
{
    global $address;
    echo $address;
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

function check_branch($ip)
{
    global $branches;

    foreach ($branches as $c) {
        if ($c->ip == $ip) return 0;
    }
    return 1;
}

function get_bikes()
{
    retrive_branches();

    $conn = open_connection("localhost");

    global $bikes, $address, $last_uuid, $data, $ip;

    $sql = "SELECT * FROM bike";

    if (!empty($data->search)) {
        $sql .= " WHERE model like '%$data->search%' OR  brand like '%$data->search%';";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $arr = array();
        $array = array();

        while ($row = $result->fetch_assoc()) {
            $m = new Bike();
            $m->price = $row["price"];
            $m->brand = $row["brand"];
            $m->branch_ip = $row["branch_ip"];
            $m->model = $row["model"];
            $m->uuid = $row["uuid"];
            $m->build_html($ip);

            $arr[] = $m->get_html();

            array_push($bikes, $m);
            $last_uuid = $m->uuid;
        }

        $array['bikes'] = $arr;
        $array['last_uuid'] = $last_uuid;
        $array['address'] = $address;
        $array['ip'] = $ip;
        echo json_encode($array);
    } else {
        echo "0 results";
    }

    close_connection($conn);
}

function remove_bike()
{
    global $data, $branches;

    $sql = "DELETE FROM bike WHERE uuid='$data->id'; DELETE FROM stock WHERE bike_uuid='$data->id'; ";

    get_branches(FALSE);
    foreach ($branches as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->multi_query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleteing record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($branches as $co) {
                if ($co->online == TRUE) {
                    update_branches($co->ip, $c, $sql);
                }
            }
        }
    }
}

function add_bike()
{
    global $data, $branches, $ip;

    $uuid = gen_uuid();
    $sql = "INSERT INTO bike(price, brand, model, uuid, branch_ip) values('$data->price', '$data->brand', '$data->model', '$uuid', '$ip'); INSERT INTO stock(bike_uuid, in_stock) values('$uuid', '0'); ";

    get_branches(FALSE);
    foreach ($branches as $c) {
        if ($c->online == TRUE) {
            $conn = open_connection($c->ip);

            if ($conn->multi_query($sql) === TRUE) {
                echo "Record added successfully";
            } else {
                echo "Error adding record: " . $conn->error;
            }

            close_connection($conn);
        } else {
            foreach ($branches as $co) {
                if ($co->online == TRUE) {
                    update_branches($co->ip, $c, $sql);
                }
            }
        }
    }
}

function update_branches($ip, $branch, $query)
{
    $conn = open_connection($ip);

    if ($query == "remove") {
        $sql = "UPDATE branch SET queue = '" . mysqli_real_escape_string($conn, "") . "' WHERE ip = '$branch->ip';";
    } else {
        $sql = "UPDATE branch SET queue = CONCAT(queue, '" . mysqli_real_escape_string($conn, $query) . "') WHERE ip = '$branch->ip';";
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
    $sql = "SELECT uuid from bike ORDER BY id DESC LIMIT 1;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $last_uuid = $row['uuid'];
            echo $last_uuid;
        }
    }

    close_connection($conn);
}
