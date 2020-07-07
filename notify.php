<?php
include_once 'config/session.php';
include_once 'config/connection.php';
include_once 'config/database.php';
include_once 'utilities.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_SESSION['username']) && isset($_GET['usr']) && isset($_GET['desc'])){
    $usr_to = $_GET['usr'];
    $usr_from = $_SESSION['username'];
    $des = $_GET['desc'];
    try{
        $Status = 1;
        $sql = "INSERT INTO notifications (`user_to`, `user_from`, `Description`, `status`)VALUE('$usr_to','$usr_from', '$des', 1)";
        $stmt = $db->exec($sql);
    }
    catch(PDOException $e)
    {
        echo"ERROR".$e->getMessage();
    }

}
if (isset($_GET['notify']))
{
    $usr = $_SESSION['username'];
    $sql = "SELECT * FROM notifications";
    $i = 0;
    foreach($db->query($sql) as $row)
    {
        if (strcmp($row['user_to'], $_SESSION['username']) == 0 && $row['status'] == 1)
        $i++;
    }
    echo $i;
}
?>