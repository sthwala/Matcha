
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<h2><div class="header"><img src="heart.jpg" height="50vh" width="60vw" >MATCHA</div></h2><br></br>
<header>
<button class="logout" onclick="window.location.href='myaccount.php'" >Myalias</button>
<button class="logout" onclick="window.location.href='mypictures.php'" >MyGallary</button> 
<a href="logout.php"><button class="logout">LogOut</button></a><br></br></header>
<?php
$latitude ="";
$longitude="";
?>
<p>Click the button to get your coordinates.</p>
<center>
<label class="control-label">Latitude:</label>
<input class="form-control" type="text" name="latitude" id="lati" placeholder="latitude" value="" />
<label class="control-label">Longitude:</label></td>
<input class="form-control" type="text" name="longitude" id="longi" placeholder="longitude" value=""/><br>
<button onclick="getLocation()">Search Location</button>
<button type="submit" name="save" value="save" onclick="saveGeo()">Save</button><br>
<p id="demo"></p>

<script>
var x = document.getElementById("demo");
var lati = 0;
var long = 0;

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
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
        $.getJSON('http://ip-api.com/json')
                    .done (function(location) {
                        x.innerHTML = "<input type='text' name='lat' value='" + location.lati + "' readonly /><br> " +
                            "<input type='text' name='long' value='" + location.long + "' readonly/>";
                    });
                x.innerHTML = "<p>Enter Your Street name</p>\n" +
                    "    <input type='text' name='address' placeholder='Street name' />";
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}
</script>

</body>
</html>
