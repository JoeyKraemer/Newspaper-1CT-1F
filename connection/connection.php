<?php
session_start();
 $DB_host = "localhost";
$DB_user = "root";
$DB_pass = "qwerty";
$DB_name = "moviesdb";

try
{
     $DB_con = new PDO("mysql:host=mysql;dbname=login_demo;charset=utf8", "root", "qwerty");
     
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}
?>