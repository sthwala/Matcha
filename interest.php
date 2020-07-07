<?php
include_once 'config/connection.php';
include_once 'config/database.php';
include_once 'config/session.php';



if (isset($_POST['send']) && isset($_SESSION['username'])) {
 
    $interests = $_POST['interest'];
   $username = $_SESSION['username'];
   echo $username;

   try{
    $server = $server.';dbname=matcha';
   $db = new PDO($server, $root, $password);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO interests (username, interest)
    VALUES(:username, :interest)");
    $stmt->execute(array(':username' => $username, ':interest' => $interests));

    echo "New record created successfully"; 
  } catch(PDOException $ex) {
        echo "Error: ".$ex->getMessage();
    }
}
  ?>
  <!DOCTYPE html>
  <HTML><a href="friends.php" style="float :left"><button>Back</button></a><br></HTML>