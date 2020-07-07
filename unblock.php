
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<h2><div class="header"><img src="heart.jpg" height="50vh" width="60vw" >MATCHA</div></h2><br></br>
<header><a href="logout.php"><button class="logout">LogOut</button></a><br><br> 
<a href="friends.php" style="float :left">Back to home</a><br></header><br>
</body>
</html>
<?php
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
    //$username = $_GET['username'];

    $query = 'SELECT * FROM user ';
    $stmt = $db->prepare($query);
    //$stmt->bindParam(':username', $row['username']);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //echo $rows['username'];
    foreach($rows as $row)
    {

       $query = 'SELECT * FROM blockeduser WHERE (username=:username1 AND who_blocked=:username2) OR (username=:username2 AND who_blocked=:username1)';
       $stmt = $db->prepare($query);
       $stmt->bindParam(':username1', $row['username']);
       $stmt->bindParam(':username2', $username2);
       $stmt->execute();        
       
       if($stmt->fetch(PDO::FETCH_ASSOC)){
          
       $query = 'SELECT post_id FROM profile_pic WHERE username=:username';
       $stmt = $db->prepare($query);
       $stmt->bindParam(':username', $row['username']);
       $stmt->execute();
       $pic_id = $stmt->fetch(PDO::FETCH_ASSOC);
         
       $query = "SELECT image_name FROM pictures WHERE id = '".$pic_id['post_id']."'";
       $stmt = $db->prepare($query);
       $stmt->execute();
       $profile_pic = $stmt->fetch(PDO::FETCH_ASSOC);

       $query = 'SELECT fame_rating FROM views WHERE username=:username';
       $stmt = $db->prepare($query);
       $stmt->bindParam(':username', $row['username']);
       $stmt->execute();
       $fame = $stmt->fetch(PDO::FETCH_OBJ);

       $query = "SELECT lastseen FROM user WHERE username=:username";
       $stmt = $db->prepare($query);
       $stmt->bindParam(':username', $row['username']);
       $stmt->execute();
       $lastseen = $stmt->fetch(PDO::FETCH_ASSOC);
       
      echo '<div class="profiles"><div class="head1"><h2>PROFILE</h2></div><tr><td>';
      if($lastseen['lastseen'] == 1){
       echo "online";
       }
       else if ($lastseen['lastseen'] == 0){
       echo "offline";
       }

      echo '</td></tr><br></br> <td><div class="pictures"><img src=' .$profile_pic['image_name'].'  /></div></td><br></br>
       <td>firstname: ' . $row['firstname'] . '</td><br></br>
       <td>lastname: ' . $row['lastname'] . '</td><br></br>
       <td>username: ' . $row['username'] . '</td><br></br>
       <td>Age: ' . $row['age'] . '</td></tr><br></br>
       <td>Fame_rating: ' . $fame->fame_rating . '</td></tr><br></br>

       <a href="./likes.php?username='.$row['username'].'"><button class="Button" id="cssColorChange type="button" onclick="notifyLike()"">LIKE</button><br>
       <a href="./view_profile.php?username='. $row['username'] .'" type="button" onclick="notifyView()">View Profile</a><br>
       <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
       <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
       <a href="./chat/index1.php?username='. $row['username'].'" type="button" onclick="notifyView()><style="float: right">Chat</a><br>
       
       </div><br>       
     </div>';
       
       }
   }

}

catch (PDOException $e)
{
   echo"error message".$e->getMessage();
}
?>
