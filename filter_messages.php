<?php

if (isset($_POST['text'])) {
    $text = $_POST['text'];

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "Kokotinapicovina123";
    $db = "dsd_project";
    $port = 3306;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    $receiverName = "jozko2";
    $receiverIP = "jozkoip2";
    $uuid = gen_uuid();

    $sql = "SELECT * FROM messages WHERE message like '%$text%' OR  sender_name like '%$text%'";

    if ($conn->query($sql) === TRUE) {
        echo "Messages filtered successfully";
    } else {
        echo "Error filtering messages: " . $conn->error;
    }

    $conn->close();
    header('Location: /script.php');
    exit();
}
