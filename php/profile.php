<?php

require_once "auth.php";

echo "You are logged in!<br>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Name: " . $_SESSION['name'] . "<br>";
echo "Role: " . $_SESSION['role'];