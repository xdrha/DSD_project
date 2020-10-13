<?php

$name = "Matej Drha";
$port = 3306;

function OpenCon()
{

    global $port;

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "Kokotinapicovina123";
    $db = "dsd_project";
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    else return $conn;
}

function CloseCon($conn)
{
    $conn->close();
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

function SelectFromDB($conn)
{

    global $content;

    $sql = "SELECT * FROM messages";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $content = '<table><tr><th>from';
        while ($row = $result->fetch_assoc()) {
            $content .= '<h2>' . "id: " . $row["id"] . " - node_id: " . $row["node_id"] . " " . $row["time"] . " " . $row["name"] . " " . $row["receiver_name"] . " " . $row["message"] . "<br>" . '</h2>';
        }
        $content .= '';
    } else {
        echo "0 results";
    }
}

function InsertToDB($conn)
{

    global $ipadress, $name, $content;

    $receiverName = "jozko2";
    $receiverIP = "jozkoip2";
    $message = "HELLOJOZKO2";
    $uuid = gen_uuid();

    $sql = "INSERT INTO messages(node_id, `time`, `name`, receiver_name, message, uuid) values('$receiverIP', NOW(), '$name', '$receiverName', '$message', '$uuid')";
    $result = $conn->query($sql);

    if ($result != TRUE) $content = "Error: " . $sql . "<br>" . $conn->error;
}

$c = OpenCon();
# InsertToDB($c);
SelectFromDB($c);
CloseCon($c);

include('master.php');
