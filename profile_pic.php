<?php
include_once 'config/database.php';
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'mypictures.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $image_id = $_GET['pictures'];
    $username = $_SESSION['username'];
    $date = date('Y-m-d H:i:s');

    $sql = 'SELECT post_id FROM profile_pic WHERE username=:username';
    $stmt =$db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
if (!$row ){

    $sqlInsert = "INSERT INTO profile_pic (username, post_id, date) VALUES ('$username', '$image_id', now())" ;
    $stmt->bindParam(':post_id', $image_id);
    $stmt = $db->prepare($sqlInsert);
    $stmt->execute();
}
    else{
        $stmt = $db->prepare('UPDATE profile_pic SET post_id = :post_id WHERE username=:username');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':post_id', $image_id);
        $stmt->execute();
   }

?>