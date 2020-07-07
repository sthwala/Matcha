<?php
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if((isset($_GET['username'])) && (isset($_SESSION['username']))){

    $username = $_GET['username'];
    $username1 = $_SESSION['username'];

     try{
            $sqlInsert = 'INSERT INTO  blockeduser (id, username, who_blocked, date)
            VALUES (null ,  :username, :who_blocked, now())';
            $stmt = $db->prepare($sqlInsert);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':who_blocked', $username1);
            $stmt->execute();

            $result = "You have blocked someone";

            header("Location:friends.php");
    }
    catch(PDOException $e)
    {
        echo "ERROR".$e->getMessage();
    }
}
?>