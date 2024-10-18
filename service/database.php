<?php
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database_name = 'db_zapin';

    $db = mysqli_connect($hostname, $username, $password, $database_name);

    if($db->connect_error) {
        echo "koneksi database error";
        die("mati!!");
    }
?>