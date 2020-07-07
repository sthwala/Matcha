<?php
include_once 'config/session.php';
include_once  'config/connection.php';
include_once 'config/database.php';

try{
        $server = $server.';dbname=matcha';
        $db = new PDO($server, $root, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
          
        $id = $_SESSION['id'];
        $stmt = $db->prepare("UPDATE user SET lastseen= 0  WHERE id =:id");
        $stmt->bindParam(':id',$_SESSION['id']);
        $stmt->execute();
}
catch(PDOException $ex)
{
    die("Failed to run query: " . $ex->getMessage());
}

    session_unset();
    session_destroy();
   header("Location: index.php");

 
?>