<?php 

include_once 'config/connection.php';
include_once 'config/database.php';
//include_once 'config/setup.php';

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>

<HTML>
    <HEAD>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <TITLE>matcha</TITLE>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" class="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=pacifico' rel='stylesheet' type='text/css'>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 
    </HEAD>
    <BODY>
    <!--<div class="container"-->
    <h2><div class="header">MATCHA</div></h2>
    <header>
    <p>MEMBER <a href="login.php" class="btn btn-default btn-lg" role="button">LOGIN</a></p>
    <P>SIGN UP TO BE A MEMBER<a href="signup.php" class="btn btn-info" role="button">SIGNUP</a></P>
    </header>
        </div>
    </div>
    </div>  
    </FOOTER>
</HTML>
