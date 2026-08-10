<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("You are not logged in.");
}

echo "You are logged in!<br>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Name: " . $_SESSION['name'] . "<br>";
echo "Role: " . $_SESSION['role'];