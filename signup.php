<?php

include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'utilities.php';
include_once 'config/database.php';


$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
function check_emails($db, $email)
{
    $sql = "SELECT * FROM user";
    try
    {
        foreach($db->prepare($sql) as $row)
        {
            if ($email == $row['email'])
                return (TRUE);
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
    return (FALSE);
}
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $headers = isset($_POST['headers']) ? $_POST['headers'] : '';
    $ms = isset($_POST['ms']) ? $_POST['ms'] : '';

    $str = "/matcha/activate_account.php";
    $us = explode("?", $_SERVER['REQUEST_URI']);
    $us[0] = $str;
    $us = implode("?", $us);
    $url = "//{$_SERVER['HTTP_HOST']}{$us}";

    if (isset($_POST['signup'])){

        if (check_emails($db, $_POST['email']) === TRUE)
        $result = "<p style='padding: 20px; color: red;'>The email already exist. Please use anothere on.</p>";
    else{

        $form_errors = array();
        
        $required_fields = array('firstname', 'lastname', 'username', 'email', 'password');

        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
       
        $fields_to_check_length = array('username' => 10, 'password' => 10);

        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        $form_errors = array_merge($form_errors, check_email($_POST));
      
        if (empty($form_errors)){
            
            $firstname= htmlspecialchars($_POST['firstname']);
            $lastname = htmlspecialchars($_POST['lastname']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
      
            if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/",$password)) {
                echo "<script>alert('please match pattern.'); location.href='./index.php'; </script>";
            }else
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $token = mt_rand();
            $no_of_pictures = 0;
            $lastseen = 0;

            try{

                $sqlInsert = "INSERT INTO user (id, firstname, lastname, username, email, password, join_time, lastseen, token, no_of_pictures)
                      VALUES (null, :firstname, :lastname, :username, :email, :password , now() , :lastseen, :token, :no_of_pictures)";
                
                 $stmt = $db->prepare($sqlInsert);
                
                 $stmt->execute(array(
                    ':firstname' => $firstname,
                    ':lastname' => $lastname,
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':lastseen' => $lastseen,
                    ':token' => $token,
                    ':no_of_pictures' => $no_of_pictures
                 ));
                 
                 if ($stmt->rowCount()) {
                    echo "<script> alert('An email has been sent to you please check your email to verify account.'); location.href='activate_account.php'</script>";
                }
    
                else{
                echo "<script> alert('Oops! Something went wrong.'); location.href='signup.php' </script>";
                }

                //check if one new was created
                if ($stmt->rowCount() == 1){
                 
                    $to=$email;
                    $msg= "Thanks for new Registration.";   
                    $subject="Email Verification";
                    $headers .= "MIME-Version: 1.0"."\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                    $headers .= 'From:matcha <www.noreply@matcha>'."\r\n";
                    
                                   
                    $ms.="<html></body><div><div>Dear ".$firstname.",</div></br></br>";
                    $ms.="<div style='padding-top:8px;'>Your account information is successfully updated in our server, you can login with the following new credentials.</div>
                    <br>\n\nYour username : ".$username.".\r\n<br>
                    \n\nYour password : ".$password.".<br><br>\r\n
                    Copy activation code : ".$token.".<br>\r\n

                    Click here :\r\n
                    <a href='http:" . $url . "?token=" . $token . "&email=" . $email . "'>Go to link</a>
        
                    <br>Thank you!.<br>
                      
                        </div>
                        </body></html>";
                    mail($to,$subject,$ms,$headers); 

                    $result = "<p style='padding: 20px; color: green;'> Registration Successful </p>";
                }
                
            }catch (PDOException $ex){
                $result = "<p style='padding: 20px; color: red'>An error occurred: ".$ex->getMessage()."</p>";
            }

         }
        }
}

?>

<!DOCTYPE html>

<HTML>
    <HEAD>
        
        <TITLE>Sign Up</TITLE>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </HEAD>
    <BODY class="indexb">
    
            <h2><div class="header" height="50vh" width="60vw" >MATCHA</div></h2><br>

    
            <center><div class="container">
            <?php if(isset($result)) echo "$result" ?>

                <div class="head1"><h3>Sign In</h3></div>
                 <form method="POST" action="signup.php">

                  First name:<br>
                  <input type="text" name="firstname"  value="" placeholder="firstname" required><br>
                  Last name:<br>
                  <input type="text" name="lastname"  value="" placeholder="lastname" required><br>
                  Username<br>
                  <input type="text" name="username" value="" placeholder="username" required><br>
                  Email:<br>
                  <input type="email" name="email" value="" placeholder="Email" required><br>
                  Password:<br>
                  <input type="password" name="password" value=""  placeholder="*********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required><br>
                 
                  <button type="submit" name="signup" value="signup">Register</button><br>
                  <button><a href="./forgot_password.php">forgot my password ?</a></button><br>
                  <button><a href="./login.php">already a member? click here</a></button></center>
              </form>
              </div>
            
           
</BODY>
</HTML>
 
