<?php
$server = "****";
$username = "****";
$password = "****";
$dbname = "****";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
