<?php


$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'testdb';
$port = 3308;


$conn = mysqli_connect($host,$user,$password,$dbname, $port);

if(!$conn){
    die("Connection to the database is not reaching");
}
