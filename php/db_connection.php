<?php
$mysqli = new mysqli('localhost', 'root', '', 'mambaling');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>