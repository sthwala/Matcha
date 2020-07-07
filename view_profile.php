<?php

include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$email = isset($_POST['email']) ? $_POST['email'] : '';
$headers = isset($_POST['headers']) ? $_POST['headers'] : '';
$ms = isset($_POST['ms']) ? $_POST['ms'] : '';

?>
<!DOCTYPE html>
<HTML>
    <HEAD>
        <TITLE>user_profile.com</TITLE>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </HEAD>
<BODY>
<h2><div class="header"height="50vh" width="60vw" >MATCHA</div></h2><br>
<header><a href="logout.php"><button class="logout">LogOut</button></a>
<button class="logout" onclick="window.location.href='notification.php'"  id="notification">notification</button><br>
<a href="friends.php" style="float:left"><button>Back</button></a><br></header><br><br>   
                <div class="head1"><h3>PERSONAL DETAILS</h3></div>
               
                <?php if(isset($result)) echo "<b>$result <b>" ?>
                <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
                <?php
                    

                    try
                    {
                        
                        $server = $server.';dbname=matcha';
                        $db = new PDO($server, $root, $password);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $username = $_SESSION['username'];
                        $username1 = $_GET['username'];
                        
                        
                        $query = 'SELECT fame_rating FROM views WHERE username=:username';
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':username', $username1);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_OBJ);

                        if(!$row)
                        {
                           
                        $Insert = 'INSERT INTO views (username,fame_rating, date) VALUE( :username, 1, now())';
                        $stmt = $db->prepare($Insert);
                        $stmt->bindParam(':username', $username1);
                        $stmt->execute();

                        $sql = 'SELECT firstname, email FROM user ';
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $firstname = $row['firstname'];
                        $email = $row['email'];
            
                        $to= $email;
                        
                         $subject="www.noreply@matcha - YOUR PROFILE WAS VIEWED";
                         $headers .= "MIME-Version: 1.0"."\r\n";
                         $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                         $headers .= 'From: <www.noreply@matcha>'."\r\n";
                                        
                         $ms.="<html></body><div><div>Dear ".$firstname.",</div></br></br>";
                         $ms.="<div style='padding-top:8px;'> Your profile has been viewed by this user:.</div>
                         <br>\n\n(" .$username1.").\r\n<br>
                      
                         To continue use the link  -> Copy the link :
                         
                           
                             </div>
                             </body></html>";
                         mail($to,$subject,$ms,$headers);
    
                        $result = "<p style='padding: 20px; color: green;'>This user will be notified that you viewed their profile</p>";
                        
                        
                        }else{
                      
                        $row->fame_rating = $row->fame_rating + 1;
                        $rows->noti = $row->noti + 1;
                            
                        $sql = "UPDATE views SET fame_rating =:fame_rating  WHERE username=:username ";
                        $stmt= $db->prepare($sql);
                        $stmt->bindParam(':username', $username1);
                        $stmt->bindParam(':fame_rating',$row->fame_rating);
                        $stmt->execute();

                        $sql = "UPDATE user SET noti =:noti  WHERE username=:username ";
                        $stmt= $db->prepare($sql);
                        $stmt->bindParam(':username', $username1);
                        $stmt->bindParam(':noti',$rows->noti);
                        $stmt->execute();

                       
                        }
                        $username1 = $_GET['username'];
                        $query = "SELECT user.firstname, user.lastname, user.username, user_profile.gender, user_profile.biography, interests.interest, user_profile.age, user_profile.city, user_profile.status FROM user
                        INNER JOIN user_profile ON user_profile.user_id = user.id INNER JOIN interests ON interests.username = user.username WHERE user.username=:username1";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':username1', $username1);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        $query = 'SELECT post_id FROM profile_pic WHERE username=:username';
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':username', $_GET['username']);
                        $stmt->execute();
                        $pic_id = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        $query = "SELECT image_name FROM pictures WHERE id = '".$pic_id['post_id']."'";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $profile_pic = $stmt->fetch(PDO::FETCH_ASSOC);

                        
                        $query = 'SELECT fame_rating FROM views WHERE username=:username';
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':username', $_GET['username']);
                        $stmt->execute();
                        $fame = $stmt->fetch(PDO::FETCH_OBJ);

                    
                    }
                    catch(PDOException $ex)
                    {
                        echo "Failed to run query: " . $ex->getMessage();
                    }
                    
                ?>
                </tr>
                <?php

                $server = $server.';dbname=matcha';
                $db = new PDO($server, $root, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               
                foreach($rows as $row):
                   
                    ?>

                
                <tr>
                <center><div class="container"><td><div class="pictures"><img src=<?php echo $profile_pic['image_name'];?> /></td></br></br>
                    <td><?php echo "First Name : " ?></td>
                    <td><?php echo htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Last Name : " ?></td>
                    <td><?php echo htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Username : " ?></td>
                    <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Gender : " ?></td>
                    <td><?php echo htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Biography:" ?></td></br>
                    <td><?php echo htmlspecialchars($row['biography'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Interests:" ?></td></br>
                    <td><?php echo htmlspecialchars($row['interest'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Age : " ?></td>
                    <td><?php echo htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "City : " ?></td>
                    <td><?php echo htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Status:" ?></td></br>
                    <td><?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Fame_rating:" ?></td>
                    <td><?php echo htmlspecialchars($fame->fame_rating, ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <td><?php echo "Location:" ?></td><br>
                    <?php
                    $query = 'SELECT * FROM geolocation WHERE username=:username';
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':username', $_GET['username']);
                    $stmt->execute(); 
                    $loc = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($loc['show'] == 1){
                        ?> 
                    <td><?php echo htmlspecialchars($loc['lati'], ENT_QUOTES, 'UTF-8'); ?></td></br>
                    <td><?php echo htmlspecialchars($loc['long'], ENT_QUOTES, 'UTF-8'); ?></td></br></br>
                    <?php } ?>
                    </br>
                </tr></br></div></center>
                    <?php endforeach;
             
              ?>
                </div></br>



                </div>

                
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