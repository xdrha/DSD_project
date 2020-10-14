<?php

include('message.php');

$name = "Matej Drha";
$port = 3306;
$messages = array();
$conn;
$search_text = "";

function OpenCon()
{

    global $port, $conn;

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

function SelectFromDB($conn)
{

    global $messages, $name, $search_text;

    $sql = "SELECT * FROM messages";
    if (isset($_POST['text'])) {
        $search_text = $_POST['text'];
        $sql .= " WHERE message like '%$search_text%' OR  sender_name like '%$search_text%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $m = new Message();
            $m->receiver_id = $row["node_id"];
            $m->time = $row["time"];
            $m->sender_name = $row["sender_name"];
            $m->message = $row["message"];
            $m->delivered = $row["delivered"];
            $m->receiver_name = $row["receiver_name"];
            $m->uuid = $row["uuid"];
            $m->build_html($name);

            array_push($messages, $m);
        }
    } else {
        echo "0 results";
    }
}

// function InsertToDB($conn)
// {

//     global $name, $content;

//     $receiverName = "jozko2";
//     $receiverIP = "jozkoip2";
//     $message = "HELLOJOZKO2";
//     $uuid = gen_uuid();

//     $sql = "INSERT INTO messages(node_id, `time`, `name`, receiver_name, message, uuid) values('$receiverIP', NOW(), '$name', '$receiverName', '$message', '$uuid')";
//     $result = $conn->query($sql);

//     if ($result != TRUE) $content = "Error: " . $sql . "<br>" . $conn->error;
// }

$c = OpenCon();
SelectFromDB($c);
CloseCon($c);

include('master.php');
