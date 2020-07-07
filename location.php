<?php
include_once "config/session.php";
include_once "config/connection.php";
include_once "config/database.php";

try
 {
    $server = $server.';dbname=matcha';
    $db = new PDO($server, $root, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }
 catch(PDOException $e)
 {
     $e->getMessage();
 }

if (isset($_SESSION['username']) && isset($_GET['lati']) && isset($_GET['long']))
{
    $show_loc = 0; 
    $sql = sprintf("INSERT INTO geolocation(`username`, `lati`, `long`, `show`) VALUES ('%s', '%s', '%s', '%s')", $_SESSION['username'], $_GET['lati'], $_GET['long'], $_POST['show']);
  
    try
    {
        $db->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
