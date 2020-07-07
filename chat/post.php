<?php
include_once "../config/session.php";
include_once "../config/connection.php";
include_once "../config/database.php";

try
{
    $server = $server.';dbname=matcha';
    $db = new PDO($server, $root, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    $e->getMessage();
}

if (isset($_SESSION['username']) && isset($_GET['from']) && isset($_GET['to']) && isset($_GET['msg']))
{
    $sql = sprintf("INSERT INTO chats(`from`, `to`, `text`) VALUES ('%s', '%s', '%s')", $_GET['from'], $_GET['to'], $_GET['msg']);
    try
    {
        $db->exec($sql);
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
if (isset($_SESSION['username']) && isset($_GET['username']))
{
    $sql = "SELECT * FROM chats";
    try{
        foreach($db->query($sql) as $row)
        {
            if (strcmp($row['from'], $_SESSION['username']) == 0 && strcmp($row['to'], $_GET['username']) == 0)
                echo "<b>".$row['from'].":</b> ".$row['text']."<br/>";
            if (strcmp($row['from'], $_GET['username']) == 0 && strcmp($row['to'], $_SESSION['username']) == 0)
                echo "<b>".$row['from'].":</b> ".$row['text']."<br/>";
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
?>