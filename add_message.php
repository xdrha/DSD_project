<?php

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $name = $_POST['name'];

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "Kokotinapicovina123";
    $db = "dsd_project";
    $port = 3306;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    $receiverName = "jozko2";
    $receiverIP = "jozkoip2";
    $uuid = gen_uuid();

    $sql = "INSERT INTO messages(node_id, `time`, sender_name, receiver_name, message, uuid) values('$receiverIP', NOW(), '$name', '$receiverName', '$message', '$uuid')";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
    header('Location: /script.php');
    exit();
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
