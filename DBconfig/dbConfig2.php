<?php 
 
// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "siamcomp_db"; 
 
// Create database connection 

$db2 = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 

 
?>