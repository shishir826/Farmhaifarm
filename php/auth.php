<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in.");
}