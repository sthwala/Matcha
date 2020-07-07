<?php

include_once 'config/connection.php';
include_once 'config/database.php';
include_once 'config/session.php';
include_once 'uploadPic.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (isset($_POST['save']) && isset($_SESSION['username'])){
        
    $form_errors = array();
    $required_fields = array('id', 'firstname', 'lastname', 'email', 'username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
    $fields_to_check_length = array('username' => 4, 'password' => 6);
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));
    $form_errors = array_merge($form_errors, check_email($_POST));

    if (empty($form_errors)){
        $id = $_SESSION['id'];
        $firstname = htmlEntities($_POST['firstname']);
        $lastname = htmlEntities($_POST['lastname']);
        $username = htmlEntities($_POST['username']);
        $email = htmlEntities($_POST['email']);
        $age = htmlEntities($_POST['age']);
        $city = htmlEntities($_POST['city']);
        $password = htmlEntities($_POST['password']);

        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try{

    
            $stmt = $db->prepare("UPDATE user LEFT JOIN user_profile ON user_profile.user_id = user.id SET firstname = :firstname, lastname = :lastname, username = :username, email = :email, age = :age, city = :city, password = :hashed_password  WHERE id = $id");
            $stmt->bindParam(':firstname',$firstname);
            $stmt->bindParam(':lastname',$lastname);
            $stmt->bindParam(':username',$username);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':age',$age);
            $stmt->bindParam(':city',$city);
            $stmt->bindParam(':hashed_password',$hashed_password);
            
            $stmt->execute();
            $_SESSION['username'] = $username;
      
            $result = "<p style='padding: 15px; color: black;'>Account was successfully updated!</p>";
        
        }catch (PDOException $ex){
            $result = "<p style='padding: 15px; color: black'>An error occurred: ".$ex->getMessage()." </p>";
        }
    }
    else{
        if(count($form_errors) == 1){
            $result = "<p style='color: black;'> There was 1 error in the form<br>";
        }else{
            $result = "<p style='color: black;'> There were " .count($form_errors). " error in the form <br>";
        }
    }
}
?>
<!DOCTYPE html>

<HTML>
    <HEAD>
        <TITLE>my account</TITLE>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </HEAD>
    <BODY>

<h2><div class="header"height="50vh" width="60vw" >MATCHA</div></h2><br></br>
<header>
<button class="logout" onclick="window.location.href='notification.php'"  id="notification">notification</button>
<a href="logout.php"><button class="logout">Log out</button></a><br></br><br> 
            <a href="friends.php" style="float :left"><button>Back</button></a><br>  
</header>
              
     
</br>
  

<center><div class="my_account">
<div class="head"><h2>EDIT ACCOUNT</h2></div>

<form method="POST" enctype="multipart/form-data" class="form-horizontal" action="">

     
 <table class="table table-bordered table-responsive">
 
    <tr>
    <b><b>NOTE:</b>Your username must not be changed</b>
     <td><label class="control-label">firstname:</label></td>
        <td><input class="form-control" type="text" name="firstname" placeholder="firstname" value="" Required /></td>
    </tr>

    <tr>
     <td><label class="control-label">lastname:</label></td>
        <td><input class="form-control" type="text" name="lastname" placeholder="lastname" value="" Required/></td>
    </tr>
    
    <tr>
     <td><label class="control-label">username:</label></td>
        <td><input class="form-control" type="text" name="username" placeholder="username" value="" Required/></td>
    </tr>

    <tr>
     <td><label class="control-label">email:</label></td>
        <td><input class="form-control" type="text" name="email" placeholder="email" value="" Required/></td>
    </tr>

    <tr>
     <td><label class="control-label">age:</label></td>
        <td><input class="form-control" type="text" name="age" placeholder="age" value="" Required/></td>
    </tr>
    <tr>
     <td><label class="control-label">city:</label></td>
        <td><input class="form-control" type="text" name="city" placeholder="city" value="" Required/></td>
    </tr>

    <tr>
     <td><label class="control-label">password:</label></td>
        <td><input class="form-control" type="Password" name="password" placeholder="**********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" value="" Required/></td>
    </tr>
    
            

</tr>
    </table>
    
    <button type="submit" name="save" value="save">Save</button>
</form>

<form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="file" style = "display: inline-block;" accept="images/*" >
                    <input type='hidden' name='username' value="$username">
                    <button type="submit" name="submit" id="submit"  style = "display: inline-block;">Upload</button>
</form><br>


<form action="interest.php" method="post" name="myform">
  <select name="interest" id="interest">
  <option value="">Select Interest</option>
    <option type="hidden" value="cooking">Cooking</option>
    <option type="hidden" value="movies">Movies</option>
    <option type="hidden" value="games">Games</option>
    <option type="hidden" value="vegan">Vegan</option>
    <option type="hidden" value="animals">Animals</option>
    <option type="hidden" value="dance">Dance</option>
    </select>
    <button type="submit" name="send" >Submit</button>
  
</td>
</form><br>
<form action="" method="post">
  <input type="checkbox" name="show"> Do you want your location to be public<br>
  <button type="submit" name="submitshow" >Show</button>
</form>
  </div>

<?php
if(isset($_POST['submitshow'])){
    try{
        $isTrue = 0;
        if(isset($_POST['show'])){
            $isTrue = 1;
        }

        $sql = "UPDATE geolocation SET `show`=$isTrue WHERE username=:username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
    }catch (PDOException $ex){
    $result = "<p style='padding: 15px; color: black'>An error occurred: ".$ex->getMessage()." </p>";
}
 
}
?>




<center><div class="profiles">
<div class="head"><h2>PERSONAL DETAILS</h2></div>
<?php if(isset($result)) echo "<b>$result <b>" ?>
<?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
<?php
    $query = "SELECT firstname, lastname, username, email  FROM user WHERE username = '".$_SESSION['username']."' ";

    try
    {
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }
    $rows = $stmt->fetchAll();
?>
</tr>
<?php foreach($rows as $row): ?>
<tr>
<?php
    $sql = "SELECT image_name FROM pictures WHERE username = '".$row['username']."'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $pic = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<td><img src='<?php echo $pic['image_name'];?>' class="pictures" width="250px" height="300px"/></td></br></br>
    <td><?php echo "First Name:" ?></td></br>
    <td><?php echo htmlentities($row['firstname'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
    <td><?php echo "Last Name:" ?></td></br>
    <td><?php echo htmlentities($row['lastname'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
    <td><?php echo "Username:" ?></td></br>
    <td><?php echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
    <td><?php echo "Email:" ?></td></br>
    <td><?php echo htmlentities($row['email'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
    </br>
</tr></br>
<?php endforeach; ?>
</br>



</div></center>
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

