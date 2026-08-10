<?php

require_once "auth.php";

function requireRole($requiredRole)
{
    if ($_SESSION['role'] !== $requiredRole) {
        die("Access denied.");
    }
}