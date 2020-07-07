<?php

include_once 'config/connection.php';
include_once 'config/session.php';
include_once 'config/database.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_GET['delete_id']))
{
    $stmt_select=$db->prepare('SELECT * FROM pictures WHERE image_name=:image_name');
    $stmt_select->execute(array(':image_name'=>$_GET['delete_id']));
    $imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
    unlink($imgRow['image_name']);
    $stmt_delete=$db->prepare('DELETE FROM pictures WHERE image_name =:image_name');
    $stmt_delete->bindParam(':image_name', $_GET['delete_id']);
    if($stmt_delete->execute())
    {
        ?>
        <script>
        alert("You have deleted one item");
        window.location.href=('mypictures.php');
        </script>
        <?php 
    }else
 
    ?>
        <script>
        alert("Can not delete item");
        window.location.href=('mypictures.php');
        </script>
        <?php 
 
}
 
?>