<?php
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$headers = isset($_POST['headers']) ? $_POST['headers'] : '';
$ms = isset($_POST['ms']) ? $_POST['ms'] : '';

$username1 = $_SESSION['username']; 

$sql = 'SELECT post_id FROM profile_pic WHERE username=:username';
$stmt =$db->prepare($sql);
$stmt->bindParam(':username', $username1);
$stmt->execute();

if($count = $stmt->rowCount() > 0 && (isset($_GET['username'])) && (isset($_SESSION['username'])))
{
         $username = $_GET['username'];
         $username1 = $_SESSION['username'];
         $date = date('Y-m-d H:i:s');
        
             try{

                    $sql = 'SELECT username, who_liked FROM likes WHERE username=:username AND who_liked=:who_liked ';
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':who_liked', $username1);
                    $stmt->execute();


                    if (!$stmt->fetch(PDO::FETCH_ASSOC)){
                    
                    $sqlInsert = 'INSERT INTO likes (username, who_liked, liked, date)
                    VALUES (:username, :who_liked, 1, now())';
                    $stmt = $db->prepare($sqlInsert);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':who_liked', $username1);
                    $stmt->execute();
                    echo "<script type='text/javascript'>alert('You liked this user.');window.location = 'friends.php';</script>";
                    
                    

                    }else{
                    $delete = 'DELETE FROM likes WHERE username = :username AND who_liked = :who_liked';
                    $stmt = $db->prepare($delete);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':who_liked', $username1);
                    $stmt->execute();
                    echo "<script type='text/javascript'>alert('You unliked this user.');window.location = 'friends.php';</script>";

                }
                header("Location: friends.php");
            }
            catch(PDOException $e)
            {
                echo "ERROR".$e->getMessage();
            }
    }else{
    echo "<script type='text/javascript'>alert('Please set a profile picture first.');window.location = 'friends.php';</script>";
}



?>