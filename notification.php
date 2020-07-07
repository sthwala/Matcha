<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<h2><div class="header"height="50vh" width="60vw" >MATCHA</div></h2><br></br>
<header>

<button class="logout" onclick="window.location.href='myaccount.php'" >Profile</button>
<button class="logout" onclick="window.location.href='mypictures.php'" >Pictures</button> 
<a href="logout.php"><button class="logout">Log out</button></a><br>
<a href="./friends.php" style="float : left"><button>Back</button></a><br></header><br></br></header>


<?php

include_once 'config/session.php';
include_once 'config/connection.php';
include_once 'config/database.php';
include_once 'utilities.php';
try{

    $server = $server.';dbname=matcha';
    $db = new PDO($server, $root, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $username1 = $_GET['username'];

        $sql = "SELECT `id`,`user_to`, `user_from`, `Description`, `status` FROM notifications ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row){
        
        $query = 'SELECT post_id FROM profile_pic WHERE username=:user_from';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_from', $row['user_from']);
        $stmt->execute();
        $pic_id = $stmt->fetch(PDO::FETCH_ASSOC);
          
        $query = "SELECT image_name FROM pictures WHERE id = '".$pic_id['post_id']."'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $profile_pic = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['user_from'] != $username){
          if($row['user_to'] == $username){
            echo '<div style="border: 2px solid gray; class="pictures1"><img src=' .$profile_pic['image_name'].'  /><br>';
            echo "Description of nofification :".$row['Description']."<br>";
            echo "From :".$row['user_from']."<br>";
            echo "Status :".$row['status']."<br>";
            echo '<a href="./view_profile.php?username='. $row['user_from'] .'">View Profile</a><br>
           <br></div><br><br>';
          }

        }

          $stmt = $db->prepare("UPDATE notifications SET `status`= 0 WHERE id=:id AND user_to=:usr");
          $stmt->bindParam(':id',$row['id']);
          $stmt->bindParam(':usr', $_SESSION['username']);
          $stmt->execute();
       }
}
}
catch (PDOException $e)
{
    echo"error message".$e->getMessage();
}
?>

</body>
</html>