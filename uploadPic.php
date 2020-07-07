<?php
include_once "config/connection.php";
include_once "config/session.php";
include_once "config/database.php";

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['submit'])) {


    $file = $_FILES['file'];
      
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    $allowed = array('jpg', 'jpeg', 'png');
    
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if($fileSize < 1000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt; 
                $fileDestination = 'images/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                 $username = $_SESSION['username'];
                
                                 
                try{

                    $sql = 'SELECT no_of_pictures FROM user WHERE username=:username ';
                    $stmt= $db->prepare($sql);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    $nop = $stmt->fetch(PDO::FETCH_ASSOC);
                   
                    if ($nop['no_of_pictures'] < 5)
                    {
                            $no_of_pictures = $nop['no_of_pictures'];
          
                            $username = $_SESSION['username'];
                            $insert_query="INSERT INTO pictures (username, image_name) VALUES(:username , '$fileDestination')";
                            $stmt = $db->prepare($insert_query);
                            $stmt->bindParam( ':username' ,$username);
                            $stmt->execute();

                            $no_of_pictures = $no_of_pictures + 1;
                            
                            $sql = "UPDATE user SET no_of_pictures = :no_of_pictures WHERE username=:username";
                            $stmt= $db->prepare($sql);
                            $stmt->bindParam(':no_of_pictures', $no_of_pictures);
                            $stmt->bindParam(':username', $username);
                            $stmt->execute();
               
                            $result = "<p style='padding: 20px; color: green;'>Upload was successful!</p>";
                    } 
                     
            } catch (PDOException $e) {
                echo '<p class="danger">Sorry, there was an error with PDO: '.$e.'</p>';
            }
            }else {
                $result = "<p style='padding: 20px; color: red;'>You have chosen a file of large size!</p>";
            }  
        }else {
          $result =  "<p style='padding: 20px; color: red;'>There was an error while uploading your file!</p>"; 
        }
    }else {
        $result =  "<p style='padding: 20px; color: red;'>You have choosen an invaild file type!</p>";
    }
    
} 
?>