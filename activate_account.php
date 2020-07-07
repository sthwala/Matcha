<?php
include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['token']) && !empty($_POST['token'])){

    $email = addslashes($_POST['email']);
    $token = addslashes($_POST['token']);
    $stmt = $db->prepare("SELECT * FROM user WHERE email = :email AND token = :token");
    $stmt->execute(['email' => $email, 'token' => $token]);

if ($stmt->rowCount()) {
 $stmt = $db->prepare("UPDATE user SET token = 1 WHERE token = :token");
 $stmt->execute(['token' => $token]);

if ($stmt->rowCount()) {
    echo "<script> alert('Your account has been actived, Please fill in your external information about you then login '); location.href='user_profile.php'</script>";
}
}
else{
echo "<script> alert('Could not verify. Incorrect code or email.'); location.href='activate_account.php' </script>";
}
}
 ?>
 

<!DOCTE html>

<HTML>
    <HEAD>
    <link rel="stylesheet" type="text/css" href="style.css"/>
        <TITLE>Sign Up</TITLE>
    <BODY class="indexb">
    <h2><div class="header"height="50vh" width="60vw" >MATCHA</div></h2><br><br>
    </HEAD>

            <center><div class="container">
       
                <div class="head1"><h2>Activate Your Account</h2></div>
               
                 <form method="post" action="">
               
                 Email:<br>
                  <input type="text" name="email"  value="" placeholder="Email" required><br>
                  activation code:<br>
                  <input type="text" name="token"  value="" placeholder="Enter code" required><br>
                  <button type="submit" name="submit" value="submit">submit</button><br>
                  
              </form>
              </div>
            
           
</BODY>
</HTML>