<?php
include_once "../config/connection.php";
include_once "../config/database.php";
include_once "../config/session.php";
include_once '../utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if((isset($_GET['username'])) && (isset($_SESSION['username']))){
$username = $_GET['username'];
$username1 = $_SESSION['username'];

 $sql = 'SELECT username , who_liked FROM likes WHERE (username=:username AND who_liked=:who_liked) OR (username=:who_liked AND who_liked=:username) ';
 $stmt = $db->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':who_liked', $username1);
$stmt->execute();
$connected = $stmt->rowCount();

if($connected == 2){

?>

<!DOCTYPE html>

<HTML>
    <HEAD>
        <TITLE>chat</TITLE>
        <link rel="stylesheet" type="text/css" href="../style.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </HEAD>
    <BODY class="bgi">

    
<!--<h2><div class="header" height ="50vh" width="60vw" >MATCHA</div></h2><br>-->
    <h2><div class="header"sheight="50vw" width="60vw" >MATCHA</div></h2><br></br>
    
     <!--<header><button class="logout" href="../logout.php">Log out</button>-->
  
  <header><a href="../logout.php"><button class="logout">LogOut</button></a><br></br>
  <button><a href="../friends.php" style="float : left">Back</a></button><br></header><br></br>

<?php
echo "YOUR A MATCH";
$query = "SELECT join_time, lastseen FROM user WHERE username=:username";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $_GET['username']);
$stmt->execute();
$lastseen = $stmt->fetch(PDO::FETCH_ASSOC);
?>

  <center><?php if($lastseen['lastseen'] == 1){
    echo "<b>".$_GET['username']."</b>".": is online";
    }
    else if ($lastseen['lastseen'] == 0){
    echo "<b>".$_GET['username']."</b>".": is offline "."<br>";
    echo "Lastseen : ".$lastseen['join_time'];
    }
    ?><br></center>
<div id="wrapper">
    <div id="menu">
    <?php if(isset($result)) echo $result ?>

        <p class="welcome">Welcome, <b><?php echo $_SESSION['username']; ?></b></p>
        <div style="clear:both"></div>
    </div>    
    <div id="chatbox">

    </div>
     
    <form>
        <input type="hidden" name = "from" value="<?php echo $_SESSION['username'];?>" id="from"/>
        <input type="hidden" name = "to" value = "<?php echo $_GET['username'];?>" id="to"/>
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <button class="button" type="button" onclick="mySubmit()" name="send">Send</button>
    </form>
    
</div>
 <script>
  
    function mySubmit()
    {
        if(window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange = function()
        {
            if (this.readyState == 4 &&this.status == 200)
            {
               document.getElementById("chatbox").innerHTML = this.responseText;
            }
        };
        try
        {
            var x = document.getElementById("from");
            var y = document.getElementById("to");
            var msg = document.getElementById("usermsg");
            x = x.value;
            y = y.value;
            msg = msg.value;
            xmlhttp.open("GET", "post.php?from=" + x + "&msg=" + msg + "&to=" + y, true);
            xmlhttp.send();
        }
        catch(Exception){}
    }
    function getMesages()
    {
        if(window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange = function()
        {
            if (this.readyState == 4 &&this.status == 200)
            {
                document.getElementById("chatbox").innerHTML = this.responseText;
            }
        };
        try
        {
            var y = document.getElementById("to");
            y = y.value;
            xmlhttp.open("GET", "post.php?username=" + y, true);
            xmlhttp.send();
        }
        catch(Exception){}
        }
        window.setInterval(()=>{
            getMesages();
        }, 1000);
</script> 
 
</BODY>
</HTML>
<?php
}else {

    echo "<script type='text/javascript'>alert('Sorry you are not connected to this user.');window.location = '../friends.php';</script>";

    }
 }
 else
     echo "<script type='text/javascript'>alert('you are not logged in.');window.location = '../login.php';</script>";
?>