<?php 
include_once 'delete.php';
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<!DOCTYPE html>

<HTML>
    <HEAD>
    
    <link rel="stylesheet" type="text/css" href="style.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gallery</title>
 
    </HEAD>
    <BODY class= "bgi">

    
    <h2><div class="header"height="50vh" width="60vw" >MATCHA</div></h2><br></br> 
    <header><a href="logout.php"><button class="logout">Log out</button></a>
    <button class="logout" onclick="window.location.href='notification.php'"  id="notification">notification</button> 
            <div class="content" >
                    
            <a href="./friends.php" style="float: left"><button>Back</button></a></br></header> 
            
    
    </div>
   
    </header>
    
<?php 

if(isset($_SESSION['username']))
{
    $username = $_SESSION['username'];

    $stmt = 'SELECT * FROM pictures WHERE username =:username';

    $stmtp = $db->prepare($stmt);
    $stmtp->bindParam(':username', $username);
    $stmtp->execute();

    $images = $stmtp->fetchAll(PDO::FETCH_ASSOC);


foreach($images as $image)
{

       $stmt = ('SELECT * FROM profile_pic WHERE post_id = '.$image['id'].';');
       $stmt = $db->prepare($stmt);
       $stmt->execute();
       $likes = $stmt->rowCount();
   
       $sqlInsert = 'SELECT id FROM profile_pic WHERE id = :pictures';
       $stmt = $db->prepare($sqlInsert);
       $stmt->execute(array("pictures" => $image['id']));
       $row = $stmt->fetchAll();
    ?>
    
           <div class=image_border>
           <?php if(isset($result)) echo $result; ?>
          
                    <?php echo "Post by-".$image['username']?>
                    <?php if ($_SESSION['id'] == $image['id']) {?>
                    <a class=del href="?delete_id=<?php echo $image['image_name']?>" action="" type='submit' name='delimg' style="float: right" onclick="return confirm('Are you sure you want to delete this image?')">Delete image</a><br>
                    <?php } ?>
                    <br><a   href="<?php echo  $image['image_name']; ?>"><img class ="pictures"  src="<?php echo $image['image_name']; ?>"/>
                    </a><br>
                      <a href="profile_pic.php?pictures=<?php echo $image['id']?>" action="" type='submit' onclick="return confirm('Are you sure you want to set this item as profile picture?')">set as profile.pic</a>
            </div>

<?php
}
}
?>
   
</div>
</div><br>

<script>
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
</BODY>
</HTML>