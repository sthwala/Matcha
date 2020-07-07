<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<h2><div class="header" height="50vh" width="60vw" >MATCHA</div></h2><br></br>
<header>
<button class="logout" onclick="window.location.href='notification.php'"  id="notification">notification</button>
<button class="logout" onclick="window.location.href='myaccount.php'" >Profile</button>
<button class="logout" onclick="window.location.href='mypictures.php'" >Pictures</button> 
<a href="logout.php"><button class="logout">Log out</button></a><br><br>
<a href="friends.php" style="float:left"><button>Back</button></a><br></header><br>

<div class="profiles">
<form action="" method="POST">
  <H2>SEARCH</H2>
  <p>Age</p>
    <input type="text" name="age">
  <p>Location (city)</p>
    <input type="text" name="location">
  <p>Fame rating</p>
    <input type="text" name="fame">
  <p>Interests</p>
    <input type="text" name="interest"><br>
    <input type="submit" value="search" name="search">

</form>
</div>
<div class="profiles">
<form action="" method="POST">
<H2>FILTER</H2>
<label for="searchtype">Age</label><br></br>
<select class="form-control" name="age" value="Age">
<option value="none">Age</option>
    <option value="teenager">18-20</option>
    <option value="young_adult">21-25</option>
    <option value="adult">26+</option>
</select><br></br>

<label for="searchtype">Fame-rating</label><br></br>
<select class="form-control" name="fame_rating">
<option value="none">Fame Rating</option>
    <option value="beginner">Beginner</option>
    <option value="well-known">Well Known</option>
    <option value="famous">Famous</option>
    <option value="masterLove">Master Romance</option>
</select><br></br>
<input type="submit" value="filter" name="filter"/>
</form>

</div>

<div class="profiles">
<form action="" method="POST">
<H2>SORT</H2>
<label for="searchtype">Age</label><br></br>
<select class="form-control" name="age">
<option value="none">Age</option>
    <option value="ascending">Ascending</option>
    <option value="descending">Descending</option>
</select><br></br>

<label for="searchtype">Fame-rating</label><br></br>
<select class="form-control" name="fame_rating">
<option value="none">Fame Rating</option>
    <option value="ascending">Ascending</option>
    <option value="descending">Descending</option>
</select><br></br>

<input type="submit" value="sort" name="sort"/>
</form>

</div>
</body>
</html>
<?php
include_once "config/connection.php";
include_once "config/session.php";
include_once "config/database.php";
include_once "utilities.php";


$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['search'])){
    try{


            //$username2 = $_SESSION['username'];
            $username = $_GET['username'];

            $age = htmlspecialchars($_POST["age"]);
            $location = htmlspecialchars($_POST["location"]);
            $fame = htmlspecialchars($_POST["fame"]);
            $interest = htmlspecialchars($_POST["interest"]);

            if(!empty($age)){
              $sql = "SELECT * FROM user LEFT JOIN user_profile ON user_profile.user_id = user.id LEFT JOIN views ON views.id = user.id LEFT JOIN interests ON interests.username = user.username WHERE (`age` LIKE '%".$age."%')";
              if (!empty($location))
                $sql .= " AND city LIKE '%$location%'";
              if (!empty($fame))
                $sql .= " AND fame_rating LIKE '%$fame%'";
              if (!empty($interest))
                $sql .= " AND interest LIKE '%$interest%'";
              $stmt = $db->prepare($sql);
              $stmt->execute();
            }

            else if(!empty($location)){
              $sql = "SELECT * FROM user LEFT JOIN user_profile ON user_profile.user_id = user.id LEFT JOIN views ON views.id = user.id LEFT JOIN interests ON interests.username = user.username WHERE (`city` LIKE '%".$location."%')";
              if (!empty($age))
                $sql .= " AND age LIKE '%$age%'";
              if (!empty($fame))
                $sql .= " AND fame_rating LIKE '%$fame%'";
              if (!empty($interest))
                $sql .= " AND interest LIKE '%$interest%'";
              $stmt = $db->prepare($sql);
              $stmt->execute();

            }
            else if(!empty($fame)){
              $sql = "SELECT * FROM user LEFT JOIN user_profile ON user_profile.user_id = user.id LEFT JOIN views ON views.id = user.id LEFT JOIN interests ON interests.username = user.username WHERE (`fame_rating` LIKE '%".$fame."%')";
              if (!empty($age))
                $sql .= " AND age LIKE '%$age%'";
              if (!empty($location))
                $sql .= " AND location LIKE '%$location%'";
              if (!empty($interest))
                $sql .= " AND interest LIKE '%$interest%'";
              $stmt = $db->prepare($sql);
              $stmt->execute();
            }

            else if(!empty($interest)){
              $sql = "SELECT * FROM user LEFT JOIN user_profile ON user_profile.user_id = user.id LEFT JOIN views ON views.id = user.id LEFT JOIN interests ON interests.username = user.username WHERE (`interest` LIKE '%".$interest."%')";
              if (!empty($age))
                $sql .= " AND age LIKE '%$age%'";
                if (!empty($location))
                $sql .= " AND location LIKE '%$location%'";
              if (!empty($fame))
                $sql .= " AND fame_rating LIKE '%$fame%'";
              $stmt = $db->prepare($sql);
              $stmt->execute();
            }

                        
                        if($count = $stmt->rowCount() > 0){

                            foreach($rows =$stmt->fetchAll(PDO::FETCH_ASSOC) as $row){

                                $query = 'SELECT * FROM blockeduser WHERE (username=:username1 AND who_blocked=:username2) OR (username=:username2 AND who_blocked=:username1)';
                                $stmt = $db->prepare($query);
                                $stmt->bindParam(':username1', $row['username']);
                                $stmt->bindParam(':username2', $username2);
                                $stmt->execute();

                                if (!$block = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo $block;

                                    $query = 'SELECT post_id FROM profile_pic WHERE username=:username';
                                    $stmt = $db->prepare($query);
                                    $stmt->bindParam(':username', $row['username']);
                                    $stmt->execute();
                                    $pic_id = $stmt->fetch(PDO::FETCH_ASSOC);
                                    
                                    $query = "SELECT image_name FROM pictures WHERE id = '".$pic_id['post_id']."'";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute();
                                    $profile_pic = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                                    $firstname = $row['firstname'];
                                    $lastname = $row['lastname'];
                                    $username = $row['username'];
                                    $age = $row['age'];
                                    $city = $row['city'];
    
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
                                    echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                                    <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                                    <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                                    <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a><br>
                                    
                                    </div>       
                                  </div>';
                                }
                            }
                        }
                        else{
                            echo "No results";
                        }
                    }
                    catch(PDOException $e)
                    {
                        echo "ERROR".$e->getMessage();
             }
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
            //document.getElementById("note").innerHTML = this.responseText;
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
<?php
try{     
   
  include_once "config/connection.php";
  include_once "config/session.php";
  include_once "config/database.php";
  include_once "utilities.php";
  
  $server = $server.';dbname=matcha';
  $db = new PDO($server, $root, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  
} catch (Exception $e) {
  echo 'Not Connected'.$e->getMessage();
  
}


$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['username'])){
    header("Location: login.php");
}
//THIS IS FILTERINNG BY AGE
$age = '';
$fame = '';
$gender = '';
$output = '';
$username1 = $_SESSION['username'];

$username = $_GET['username'];

$stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id');
$stmtq->bindParam(':id', $_GET['id']);
$stmtq->execute();
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);


$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['filter'])){

$age = $_POST['age'];
$fame = $_POST['fame_rating'];
$interest = $_POST['interest'];
$username = $_SESSION['username'];


$output = "";
$stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id');
$stmtq->execute(array(
        ':age' => $age,
        ':fame_rating' => $fame,
        ':gender' => $gender,
        ':interest' => $interest
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);

if($age == "none"){
    if($fame == "beginner"){
        $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating < 10');
        $stmtq->execute(array(
            ':fame_rating' => $fame
        ));
        $stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($stmtq as $row) {
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
                echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
                 echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                 <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                 <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                 <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
                 
                 </div>      
               </div>';
        
        }
        
    }
    else if($fame == "well-known"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 10 AND fame_rating < 20');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
    
    }
}

else if($fame == "famous"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 20 AND fame_rating < 30');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);

foreach ($stmtq as $row) {
if($username != $row['username']){
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
    
}
   
                    
    }
}

else if($fame == "masterLove"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 30');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
if($username != $row['username']){
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
    
}
   
                    
    }
}
    else{
    echo "The Was No Users with That Fame Rating";
    }
}
//AGE IS YOUNG-----------------------------------------------------------------------------------------
if($age == "teenager" ){

$stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE age >= 18 AND age < 21');
$stmtq->execute(array(
        ':age' => $age
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
if($fame == "beginner"){
        $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating < 10 AND age >= 18 AND age < 21');
        $stmtq->execute(array(
            ':fame' => $fame
        ));
        $stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmtq as $row) {
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
            echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
        }
    }
    else if($fame == "well-known"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 10 AND fame_rating < 20 AND age >= 18 AND age < 21');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
                    
    }
}

else if($fame == "famous"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.idWHERE fame_rating >= 20 AND fame_rating < 30 AND age >= 18 AND age < 21');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
    
                    
    }
}

else if($fame == "masterLove"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 30 AND age >= 18 AND age < 21');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
                    
    }
}
    else{
    foreach ($stmtq as $row) {
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
        echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
         echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
         <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
         <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
         <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
         
         </div>      
       </div>';
                    
        }
    }
}


//AGE IS teenager----------------------------------------------------------------------------------
if($age == "young_adult"){
$stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE age >= 21 AND age <= 25');
$stmtq->execute(array(
        ':age' => $age
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
if($fame == "beginner"){
        $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating < 10 AND age >= 21 AND age <= 25');
        $stmtq->execute(array(
            ':fame' => $fame
        ));
        $stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmtq as $row) {
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
            echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
             echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
             <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
             <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
             <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
             
             </div>      
           </div>';
        }
    }
    else if($fame == "well-known"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 10 AND fame_rating < 20 AND age >= 21 AND age <= 25 ');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
                    
    }
}

else if($fame == "famous"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 20 AND fame_rating < 30 AND age >= 21 AND age <= 25');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
                    
    }
}

else if($fame == "masterLove"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 30  AND age >= 21 AND age <= 25');

$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
                    
    }
}
    else{
    foreach ($stmtq as $row) {
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
      $like = $stmt->fetch(PDO::FETCH_ASSOC);;
        echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
         echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
         <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
         <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
         <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
         
         </div>      
       </div>';
        
        }
    }
}


//AGE IS ADULT-------------------------------------------------------------------------------------
if($age == "adult"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN  views ON user.id = views.id  WHERE age >= 26');
$stmtq->execute(array(
        ':age' => $age
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);

    if($fame == "beginner"){
        $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id  WHERE fame_rating < 10 AND age >= 26 ');

        $stmtq->execute(array(
            ':fame' => $fame
        ));
        $stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
        foreach ($stmtq as $row) {
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
            echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
             echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
             <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
             <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
             <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
             
             </div>      
           </div>';
                    
        }
    }
    else if($fame == "well-known"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id  WHERE fame_rating >= 10 AND fame_rating < 20 AND age >=26');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
    }
}

else if($fame == "famous"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 20 AND fame_rating < 30 AND age >=26');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
     
     </div>      
   </div>';
    }
}

else if($fame == "masterLove"){
    $stmtq = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id WHERE fame_rating >= 30 AND age >=26 ');
$stmtq->execute(array(
        ':fame' => $fame
));
$stmtq = $stmtq->fetchAll(PDO::FETCH_ASSOC);
foreach ($stmtq as $row) {
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
    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
    }
}
    else{
    foreach ($stmtq as $row) {
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
        echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
        echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
        <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
        <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
        <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
        
        </div>      
      </div>';
        }
    }
}
}
else{
    
    }

?>

 <?php
try{     
   
  include_once "config/connection.php";
  include_once "config/session.php";
  include_once "config/database.php";
  include_once "utilities.php";
  
  $server = $server.';dbname=matcha';
  $db = new PDO($server, $root, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  
} catch (Exception $e) {
  echo 'Not Connected'.$e->getMessage();
  
}

if(isset($_POST['sort'])){
    $ages = $_POST['age'];
    $fames = $_POST['fame_rating'];
    
    $output = "";
    $stmt = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id');
    $stmt->execute();
    $stmt = $stmt->fetchAll();

        if($fames == "ascending"){
                $stmt = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id ORDER BY fame_rating ASC');
                $stmt->execute();
                $stmt = $stmt->fetchAll();
                foreach ($stmt as $row) {
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
                echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
                 echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                 <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                 <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                 <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
                 
                 </div>      
               </div>';
                }
            }
    
                else if($fames == "descending"){
                    $stmt = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id ORDER BY fame_rating DESC');
                $stmt->execute();
                $stmt = $stmt->fetchAll();
                foreach ($stmt as $row) {
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
                    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
                     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
                     
                     </div>      
                   </div>';
                }
            }

        else if($ages == "ascending"){
                    $stmt = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id ORDER BY age ASC');
                    $stmt->execute();
                    $stmt = $stmt->fetchAll();
                    foreach ($stmt as $row) {
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
                    echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
                     echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                     <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                     <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                     <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
                     
                     </div>      
                   </div>';
                    }
                }
        
            else if($ages == "descending"){
                        $stmt = $db->prepare('SELECT * FROM user INNER JOIN user_profile ON user.id = user_profile.user_id INNER JOIN views ON user.id = views.id ORDER BY age DESC');
                    $stmt->execute();
                    $stmt = $stmt->fetchAll();
                    foreach ($stmt as $row) {
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
                        echo '<div class="profiles"><div class="head1"><h4>suggested user</h4></div><tr><td>';
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
                         echo '<a href="./view_profile.php?username='. $row['username'] .'" id="note" onclick="NotifyMe(2,\''.$row['username'].'\')">View Profile</a><br>
                         <a href="./reportuser.php?username='. $row['username'] .'">Report</a><br>
                         <a href="./blockuser.php?username='. $row['username'].'">Block</a><br>
                         <a href="./chat/index1.php?username='. $row['username'].'" id="note" onclick="NotifyMe(3,\''.$row['username'].'\')"><style="float: right">Chat</a>
                         
                         </div>      
                       </div>';
                    }
                }
        }
 

       ?>
                    


