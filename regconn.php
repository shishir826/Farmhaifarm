<?php

$conn = mysqli_connect("localhost", "root", "", "farmfresh");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>