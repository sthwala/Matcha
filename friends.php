
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<h2><div class="header"sheight="50vw" width="60vw" >MATCHA</div></h2><br></br>
<header>
<button class="fa fa-search" style="font-size:24px;" style="float:left" onclick="window.location.href='search.php'">Search</button>
<ul>
  <li><button class="logout" onclick="window.location.href='notification.php'" id="notification">notification</button></li>
  <li><button class="logout" onclick="window.location.href='myaccount.php'">Profile</button></li>
  <li><button class="logout" onclick="window.location.href='mypictures.php'">Pictures</button></li>
  <li><button class="logout" onclick="window.location.href='logout.php'">Log out</button></li>
</ul>

<?php if(isset($result)) echo "$result" ?>
    
</header><br>

</body>
</html>

<?php

include_once 'config/session.php';
include_once 'config/connection.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try
{

$id = $_SESSION['id'];
$username2 = $_SESSION['username'];

$stmt = $db->prepare("UPDATE user SET lastseen = 1 WHERE id = ".$id."");
$stmt->execute();

$query = 'SELECT user_profile.age ,user_profile.city ,user_profile.sexuality FROM user INNER JOIN user_profile ON user_profile.user_id = user.id WHERE id='.$id.'';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$match = $stmt->fetch(PDO::FETCH_ASSOC);

// $users = "SELECT user.firstname ,user.lastname, user.username, user.email, user_profile.age, user_profile.sexuality, user_profile.city  FROM user INNER JOIN user_profile ON user.id = user_profile.user_id 
// WHERE  age=".$match['age']."  AND city='".$match['city']."' AND sexuality!='".$match['sexuality']."'";
$users = "SELECT user.firstname ,user.lastname, user.username, user.email, user_profile.age, user_profile.sexuality, user_profile.city  FROM user INNER JOIN user_profile ON user.id = user_profile.user_id 
WHERE  age=".$match['age']."  AND city='".$match['city']."' AND sexuality!='".$match['sexuality']."'";
$stmt = $db->prepare($users);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row)
     {

        $query = 'SELECT * FROM blockeduser WHERE (username=:username1 AND who_blocked=:username2) OR (username=:username2 AND who_blocked=:username1)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username1', $row['username']);
        $stmt->bindParam(':username2', $username2);
        $stmt->execute();        
        
        if(!($stmt->fetch(PDO::FETCH_ASSOC))){
           
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

        $sql = 'SELECT liked FROM likes WHERE username=:username AND who_liked=:who_liked ';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $row['username']);
        $stmt->bindParam(':who_liked', $username2);
        $stmt->execute();
        $like = $stmt->fetch(PDO::FETCH_ASSOC);

       echo '<div class="container"><div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td></div>';
       if($lastseen['lastseen'] == 1){
        echo "online";
        }
        else if ($lastseen['lastseen'] == 0){
        echo "offline";
        }

       echo '</td></tr><br></br> <td><div class="pictures"><img src=' .$profile_pic['image_name'].'  /></div></td><br>
        <td>username: ' . $row['username'] . '</td><br>
        <td>Age: ' . $row['age'] . '</td></tr><br>
        <td>Fame_rating: ' . $fame->fame_rating . '</td></tr></br></br>';

        
        if($like["liked"] == 1){
            echo '<a href="./likes.php?username='. $row['username'] .'"><button class="Button" id="cssColorChange id="note" onclick="NotifyMe(1,\''.$row['username'].'\')">UNLIKE</button></a>';
        }
        else if($like["liked"] == 0){
            echo '<a href="./likes.php?username='. $row['username'] .'"><button class="Button" id="cssColorChange id="note" onclick="NotifyMe(1,\''.$row['username'].'\')">LIKE</button></a>';
        }
        echo "<br>";
        echo '<button><a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a></button><br>
        <button><a href="./reportuser.php?username='. $row['username'] .'">Report</a></button><br>
        <button><a href="./blockuser.php?username='. $row['username'].'">Block</a></button><br>
        <button><a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a></button>
        
        </div>      
      </div>';
        
        }
    }

}

catch (PDOException $e)
{
    echo"Error Message From Friends.php ".$e->getMessage();
}

try{
$id = $_SESSION['id'];
$username2 = $_SESSION['username'];

$stmt = $db->prepare("UPDATE user SET lastseen = 1 WHERE id = ".$id."");
$stmt->execute();

$query = 'SELECT user_profile.age ,user_profile.city ,user_profile.sexuality FROM user INNER JOIN user_profile ON user_profile.user_id = user.id WHERE id='.$id.'';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$match = $stmt->fetch(PDO::FETCH_ASSOC);

$users = "SELECT user.firstname ,user.lastname, user.username, user.email, user_profile.age, user_profile.sexuality, user_profile.city  FROM user INNER JOIN user_profile ON user.id = user_profile.user_id WHERE id != ".$id."";
$stmt = $db->prepare($users);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row)
     {

        $query = 'SELECT * FROM blockeduser WHERE (username=:username1 AND who_blocked=:username2) OR (username=:username2 AND who_blocked=:username1)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username1', $row['username']);
        $stmt->bindParam(':username2', $username2);
        $stmt->execute();        
        
        if(!($stmt->fetch(PDO::FETCH_ASSOC))){
           
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

        $sql = 'SELECT liked FROM likes WHERE username=:username AND who_liked=:who_liked ';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $row['username']);
        $stmt->bindParam(':who_liked', $username2);
        $stmt->execute();
        $like = $stmt->fetch(PDO::FETCH_ASSOC);
        
       echo '<div class="profiles"><div class="head1"><h4>PROFILE</h4></div><tr><td>';
       if($lastseen['lastseen'] == 1){
        echo "online";
        }
        else if ($lastseen['lastseen'] == 0){
        echo "offline";
        }

       echo '</td></tr><br></br> <td><div class="pictures"><img src=' .$profile_pic['image_name'].'  /></div></td><br>
        <td>username: ' . $row['username'] . '</td><br>
        <td>Age: ' . $row['age'] . '</td></tr><br>
        <td>Fame_rating: ' . $fame->fame_rating . '</td></tr><br></br>';

        
        if($like["liked"] == 1){
            echo '<a href="./likes.php?username='. $row['username'] .'"><button class="Button" id="cssColorChange id="note" onclick="NotifyMe(1,\''.$row['username'].'\')">UNLIKE</button></a>';
        }
        else if($like["liked"] == 0){
            echo '<a href="./likes.php?username='. $row['username'] .'"><button class="Button" id="cssColorChange id="note" onclick="NotifyMe(1,\''.$row['username'].'\')">LIKE</button></a>';
        }
        echo "<br>";
        echo '<button><a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a></button><br>
        <button><a href="./reportuser.php?username='. $row['username'] .'">Report</a></button><br>
        <button><a href="./blockuser.php?username='. $row['username'].'">Block</a></button><br>
        <button><a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a></button>
        
        </div>      
      </div>';
        
        }
    }
}

catch (PDOException $e)
{
    echo"An error has occured.";
}
?>
<html><script>
  function NotifyMe(code, usr)
{
  var desc = "";
  if (code == 1)
    desc = "Likes";
  if (code == 2)
    desc = "Views";
  if (code == 3)
    desc = "chats";
  
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 &&this.status == 200)
        {
            document.getElementById("note").innerHTML = this.responseText;
        }
    };
    try
    {
        xmlhttp.open("GET", "./notify.php?desc=" + desc + "&usr=" + usr, true);
        xmlhttp.send();
    }
    catch(Exception)
    {

    }
}
function getNotif()
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 &&this.status == 200)
        {
            document.getElementById("notification").innerHTML = "notifications (" + this.responseText + ")";
        }
    };
    try
    {
        xmlhttp.open("GET", "./notify.php?notify=nje", true);
        xmlhttp.send();
    }
    catch(Exception)
    {

    }
}
window.setInterval(()=>{
            getNotif();
        }, 1000);
</script>
