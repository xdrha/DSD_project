<?php

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "Kokotinapicovina123";
    $db = "dsd_project";
    $port = 3306;

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db, $port);

    $sql = "DELETE FROM messages WHERE uuid='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
    header('Location: /script.php');
    exit();
}
