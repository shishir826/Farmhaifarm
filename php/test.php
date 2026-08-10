<?php

require_once "database.php";

$stmt = $conn->query("SELECT * FROM users");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($users);
echo "</pre>";