<?php

require_once "auth.php";

function requireRole($requiredRole)
{
    if ($_SESSION['role'] !== $requiredRole) {
        http_response_code(403);
        die("Access denied.");
    }
}