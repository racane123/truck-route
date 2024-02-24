<?php


$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'testdb';



$conn = mysqli_connect($host,$user,$password,$dbname);

if(!$conn){
    die("Connection to the database is not reaching");
}
