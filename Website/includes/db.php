<?php
require 'config.php';

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Failed to connect to database" . mysqli_connect_error());
}

mysqli_select_db($conn,'M3_School') or die(mysqli_error($conn));