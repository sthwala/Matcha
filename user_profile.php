<?php
include_once "config/connection.php";
include_once "config/session.php";
include_once "config/database.php";
include_once "utilities.php";

$server = $server.';dbname=matcha';
$db = new PDO($server, $root, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['submit'])){

    try{

        $gender = htmlEntities($_POST['gender']);
        $biography = htmlEntities($_POST['biography']);
        $age = htmlEntities($_POST['age']);
        $sexuality = htmlEntities($_POST['Interested in']);
        $city = htmlEntities($_POST['city']);
        $status = htmlEntities($_POST['status']);

        $insert = 'INSERT INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (:gender, :biography, :age, :sexuality, :city, :status)';
        $stmt = $db->prepare($insert);
        $stmt->bindParam(':gender',$gender);
        $stmt->bindParam(':biography', $biography);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':sexuality', $sexuality);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = "<p style='padding: 20px; color: black'>Your information has been successfully saved into our database:</p>";

}catch (PDOException $ex){
    $result = "<p style='padding: 20px; color: black'>An error occurred: ".$ex->getMessage()."</p>";
}
}
?>

<!DOCTYPE html>

<HTML>
    <HEAD>
        
        <TITLE>User profile</TITLE>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </HEAD>
    <BODY class="indexb">
    <h2><div class="header" height="50vh" width="60vw" >MATCHA</div></h2><br></br>
    <a href="logout.php"><button class="logout">LogOut</button></a><br></br>

    <?php
$latitude ="";
$longitude="";
?>
<center>
<p>Click the button to get your location.</p>

<label class="control-label">Latitude:</label>
<input class="form-control" type="text" name="latitude" id="lati" placeholder="latitude" value="" />
<label class="control-label">Longitude:</label></td>
<input class="form-control" type="text" name="longitude" id="longi" placeholder="longitude" value=""/><br>
<button onclick="getLocation()">Search Location</button>
<button type="submit" name="save" value="save" onclick="saveGeo()">Save</button><br>
<p id="geo"></p>

<script>
var x = document.getElementById("geo");
var lati = 0;
var long = 0;

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "Location not available";
    }
}

function showPosition(position) {
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;

            var latitude = document.getElementById("lati");
            var longitude = document.getElementById("longi");
            lati = latitude.value = position.coords.latitude;
            long =longitude.value = position.coords.longitude;
            
}

function saveGeo(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
            alert("Your location has been saved!");
        }else{
        }
    };
    xmlhttp.open("GET", "location.php?lati=" + lati  + "&long=" + long, true);
    xmlhttp.send();
}


function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "User location request timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
</script>
    
            <center><div class="container">
            <?php if(isset($result)) echo "$result" ?>

               
                 <div class="head2"><p>Please fill in additional information about yourself:</p></div>
                 <form method="POST" action="user_profile.php">
                Gender:<br>
                    <select class="form-control" style="width:140px; height:10px" name="gender" value="gender">
                        <option value="none">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select><br>
                  Age(18-70):<br>
                  <input type="text" name="age" value=""  placeholder="Age" required><br>
                  Sexual Preference:<br>
                  <select class="form-control" style="width:140px; height:10px" name="sexuality" value="sexuality">
                        <option value="none">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Both</option>
                    </select><br>
                  City:<br>
                  <select class="form-control" style="width:140px; height:10px" name="city" value="city">
                        <option value="none">Select</option>
                        <option value="johannesburg">Johannesburg</option>
                        <option value="sandton">Sandton</option>
                        <option value="soweto">Soweto</option>
                        <option value="pretoria">Pretoria</option>

                    </select><br>
                  Status:<br>
                  <input type="text" name="status" value=""  placeholder="status"style="width:200px; height:50px" required><br>
                  Biography:<br>
                  <input type="text" name="biography" value=""  placeholder="Biography" style="width:300px; height:100px" required><br>

                  <button type="submit" name="submit" value="submit">Submit</button><br>
                  <button><a href="./login.php">Login </a></button></center>
                 
              </form>
              </div>
            
           
</BODY>
</HTML>