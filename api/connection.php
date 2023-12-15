<?php
include("db.php");
$database = 'fahad_pos';
$user     = 'root';
$pass     = '';
$host     = 'localhost';
$db       = new MysqliDb ($host, $user, $pass, $database);

$url = 'http://localhost:8080/fahadpos/';

?>