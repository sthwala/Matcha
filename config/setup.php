<?php

    include_once 'connection.php';
    include_once 'database.php';

    try
    {
       

        $db = new PDO($server, $root, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $database = "CREATE DATABASE IF NOT EXISTS `matcha`";
        echo "Database created successfully.<br>";
        $create_dbs = $db->prepare($database);

        $create_dbs->execute();

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `blockeduser` (
           `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
           `username` varchar(255) NOT NULL,
           `who_blocked` varchar(255) NOT NULL,
           `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
           CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table BLOCKED created successfully.<br>";

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `chats` (
           `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
           `from` varchar(225) NOT NULL,
            `to` varchar(255) NOT NULL,
            `text` MEDIUMTEXT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table CHATS created successfully.<br>";

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `geolocation` (
           `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
           `username` varchar(255) NOT NULL,
           `lati` double NOT NULL,
            `long` double NOT NULL,
            `show` int(11) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table GEO created successfully.<br>";

       
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `likes` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` varchar(255) NOT NULL,
            `who_liked` varchar(255) NOT NULL,
            `liked` int(11) NOT NULL DEFAULT '0',
            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
           CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table LIKES created successfully.<br>";
       
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `pictures` (
             `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             `username` varchar(30) NOT NULL,
             `image_name` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table PICTURES created successfully.<br>";

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `interests` (
             `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             `username` varchar(30) NOT NULL,
             `interest` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table INTERESTS created successfully.<br>";
   
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `user` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `firstname` varchar(30) DEFAULT NULL,
            `lastname` varchar(30) DEFAULT NULL,
            `username` varchar(30) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `password` varchar(255) DEFAULT NULL,
            `join_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `lastseen` int(11) NOT NULL DEFAULT '0',
            `token` int(11) NOT NULL DEFAULT '0',
            `noti` int(5) not null DEFAULT '0',
            `no_of_pictures` int(11) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table USER created successfully.<br>";
       
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `user_profile` (
            `user_id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `gender` varchar(6) NOT NULL,
            `biography` MEDIUMTEXT NULL,
            `age` int(3) NOT NULL,
            `sexuality` varchar(255) NOT NULL,
            `city` varchar(255) NOT NULL,
            `Status` varchar(500) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table USER_PROFILE created successfully.<br>";

        
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `reporteduser` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` varchar(255) NOT NULL,
            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
       echo "Table REPORTED created successfully.<br>";
       
        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `views` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` varchar(255) NOT NULL,
            `fame_rating` int(11) NOT NULL DEFAULT '0',
            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table VIEWS created successfully.<br>";

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `profile_pic` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` varchar(255) NOT NULL,
            `post_id` int(11) NOT NULL,
            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $db->query("USE matcha");
        $db->query("CREATE TABLE IF NOT EXISTS `notifications` (
            `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_to` varchar(255) NOT NULL,
            `user_from` varchar(255) NOT NULL,
            `Description` varchar(1000) NOT NULL,
            `status` int(11) NOT NULL,
            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        echo "Table Notifications created successfully.<br>";

         
    }
    catch (PDOException $e) 
    {
        echo "Database failed".'<br>'.$e->getMessage();
    }

    $e_username = 'sthwala';
    $e_profilepic = 'images/efemale.jpg';
    $e_postId = 1;
    $e_firstname = 'Sinothando';
    $e_lastname = 'Thwala';
    $e_email = 'moetanalodimpho@gmail.com';
    $e_passwd = 'Thwala123';
    $e_hashed = password_hash($e_passwd, PASSWORD_DEFAULT);
    $e_token = '1';
    $e_gender = 'Female';
    $e_age = 22;
    $e_sexuality = 'Woman';
    $e_biography = 'i love to dance';
    $e_interests = 'music';
    $e_status = 'i love ice cream';
    $e_lastseen = '0';
    $e_latitude = '-26.2260'; 
    $e_longitude = '27.8941';
    $e_no_of_pictures = '1';
    $e_show = '1';
    $e_city = 'johannesburg';
    $e_fame_rating= '22';

    $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
     $sql->execute([$e_firstname, $e_lastname, $e_username, $e_email, $e_hashed, $e_lastseen, $e_token, $e_no_of_pictures]);
     $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
     $sql->execute([$e_gender, $e_biography, $e_age, $e_sexuality, $e_city, $e_status]);
     $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
     $sql->execute([$e_username, $e_fame_rating]);
     $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
     $sql->execute([$e_username, $e_interests]);
     $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
     $sql->execute([$e_username, $e_profilepic]);
     $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
     $sql->execute([$e_username, $e_postId]);
     $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
     $sql->execute([$e_username, $e_latitude, $e_longitude, $e_show]);
     

     $a_username = 'dee';
     $a_profilepic = 'images/afemale.jpeg';
     $a_postId = 2;
     $a_firstname = 'Dimphoo';
     $a_lastname = 'Moetanalo';
     $a_email = 'moetanalodimpho1@gmail.com';
     $a_passwd = 'deE123456!';
     $a_hashed = password_hash($a_passwd, PASSWORD_DEFAULT);
     $a_token = '1';
     $a_gender = 'Female';
     $a_age = 21;
     $a_sexuality= 'Man';
     $a_biography = 'I like selfies';
     $a_interests = 'Anything code related';
     $a_status = 'YOLO';
     $a_lastseen = '0';
     $a_no_of_pictures = '2';
     $a_show = '1';
     $a_latitude = '-25.8640';
     $a_longitude = '28.0889';
     $a_city = 'centurion';
     $a_fame_rating= '20';
 
 
     $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
      $sql->execute([$a_firstname, $a_lastname, $a_username, $a_email, $a_hashed, $a_lastseen, $a_token, $a_no_of_pictures]);
      $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
      $sql->execute([$a_gender, $a_biography,$a_age, $a_sexuality, $a_city, $a_status]);
      $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
      $sql->execute([$a_username, $a_fame_rating]);
      $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
      $sql->execute([$a_username, $a_interests]);
      $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
      $sql->execute([$a_username, $a_profilepic]);
      $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
      $sql->execute([$a_username, $a_postId]);
      $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
      $sql->execute([$a_username, $a_latitude, $a_longitude, $a_show]);
      

      $b_username = 'Gloria';
      $b_profilepic = 'images/bman.png';
      $b_postId = 3;
      $b_firstname = 'Gloria';
      $b_lastname = 'Baloyi';
      $b_email = 'gloria@gmail.com';
      $b_passwd = 'gloria1234';
      $b_hashed = password_hash($b_passwd, PASSWORD_DEFAULT);
      $b_token = '1';
      $b_gender = 'Female';
      $b_age = 21;
      $b_sexuality = 'Men';
      $b_biography = 'Food ';
      $b_interests = 'cooking';
      $b_status = 'food is life';
      $b_lastseen = '0';
      $b_no_of_pictures = '2';
      $b_show = '0';
      $b_latitude = '-26.2051254';
      $b_longitude = '28.039954599999998';
      $b_city = 'johannsburg';
      $b_fame_rating= '10';
  
  
      $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
       $sql->execute([$b_firstname, $b_lastname, $b_username, $b_email, $b_hashed, $b_lastseen, $b_token, $b_no_of_pictures]);
       $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
       $sql->execute([$b_gender, $b_biography,$b_age, $b_sexuality, $b_city, $b_status]);
       $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
       $sql->execute([$b_username, $b_fame_rating]);
       $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
       $sql->execute([$b_username, $b_interests]);
       $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
       $sql->execute([$b_username, $b_profilepic]);
       $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
       $sql->execute([$b_username, $b_postId]);
       $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
      $sql->execute([$b_username, $b_latitude, $b_longitude, $e_show]);

       $c_username = 'zanele';
       $c_profilepic = 'images/cman.jpeg';
       $c_postId = 4;
       $c_firstname = 'Zanele';
       $c_lastname = 'Xaba';
       $c_email = 'zanele@gmail.com';
       $c_passwd = 'zanele1234';
       $c_hashed = password_hash($c_passwd, PASSWORD_DEFAULT);
       $c_token = '1';
       $c_gender = 'Female';
       $c_age = 21;
       $c_sexuality = 'Men';
       $c_biography = 'I travel';
       $c_interests = 'boats';
       $c_status = 'travel and water';
       $c_lastseen = '0';
       $c_no_of_pictures = '2';
       $c_show = '0';
       $c_latitude = '-26.568051250';
       $c_longitude = '28.739954592';
       $c_city = 'johannesburg';
       $c_fame_rating= '40';
   
   
       $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
        $sql->execute([$c_firstname, $c_lastname, $c_username, $c_email, $c_hashed, $c_lastseen, $c_token, $c_no_of_pictures]);
        $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
        $sql->execute([$c_gender, $c_biography, $c_age, $c_sexuality, $c_city, $c_status]);
        $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
        $sql->execute([$c_username, $c_fame_rating]);
        $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
        $sql->execute([$c_username, $c_interests]);
        $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
        $sql->execute([$c_username, $c_profilepic]);
        $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
        $sql->execute([$c_username, $c_postId]);
        $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
      $sql->execute([$c_username, $c_latitude, $c_longitude, $c_show]);

        $ed_username = 'Katli';
        $ed_profilepic = 'images/edfemale.jpeg';
        $ed_postId = 5;
        $ed_firstname = 'Katlego';
        $ed_lastname = 'Matlale';
        $ed_email = 'katli@gmail.com';
        $ed_passwd = 'katli1234';
        $ed_hashed = password_hash($ed_passwd, PASSWORD_DEFAULT);
        $ed_token = '1';
        $ed_gender = 'Female';
        $ed_age = 27;
        $ed_sexuality = 'Man';
        $ed_biography = 'am a dog lover';
        $ed_interests = 'Dogs';
        $ed_status = 'i love music';
        $ed_lastseen = '0';
        $ed_no_of_pictures = '2';
        $ed_show = '1';
        $ed_latitude = '-26.2003271';
        $ed_longitude = '27.0399545';
        $ed_city = 'johannesburg';
        $ed_fame_rating= '22';
    
        $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
         $sql->execute([$ed_firstname, $ed_lastname, $ed_username, $ed_email, $ed_hashed, $ed_lastseen, $ed_token, $ed_no_of_pictures]);
         $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
         $sql->execute([$ed_gender, $ed_biography, $ed_age, $ed_sexuality, $ed_city, $ed_status]);
         $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
         $sql->execute([$ed_username, $ed_fame_rating]);
         $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
         $sql->execute([$ed_username, $ed_interests]);
         $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
         $sql->execute([$ed_username, $ed_profilepic]);
         $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
         $sql->execute([$ed_username, $ed_postId]);
         $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
      $sql->execute([$ed_username, $ed_latitude, $ed_longitude, $ed_show]);

         $ef_username = 'joy';
         $ef_profilepic = 'images/efman.jpeg';
         $ef_postId = 6;
         $ef_firstname = 'Joy';
         $ef_lastname = 'Maseko';
         $ef_email = 'joy@gmail.com';
         $ef_passwd = 'joy1234';
         $ef_hashed = password_hash($ef_passwd, PASSWORD_DEFAULT);
         $ef_token = '1';
         $ef_gender = 'Male';
         $ef_age = 28;
         $ef_sexuality = 'Women';
         $ef_biography = 'am free';
         $ef_interests = 'butterflies';
         $ef_status = 'free spirit';
         $ef_lastseen = '0';
         $ef_no_of_pictures = '2';
         $ef_show = '1';
         $ef_latitude = '-27.2055962';
         $ef_longitude = '28.039954599999998';
         $ef_city = 'sandton';
         $ef_fame_rating= '22';
     
        $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
          $sql->execute([$ef_firstname, $ef_lastname, $ef_username, $ef_email, $ef_hashed, $ef_lastseen, $ef_token, $ef_no_of_pictures]);
          $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
          $sql->execute([$ef_gender, $ef_biography, $ef_age, $ef_sexuality, $ef_city, $ef_status]);
          $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
          $sql->execute([$ef_username, $ef_fame_rating]);
          $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
          $sql->execute([$ef_username, $ef_interests]);
          $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
          $sql->execute([$ef_username, $ef_profilepic]);
          $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
          $sql->execute([$ef_username, $ef_postId]);
          $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
          $sql->execute([$ef_username, $ef_latitude, $ef_longitude, $ef_show]);

          $er_username = 'dineo';
          $er_profilepic = 'images/erfemale.jpeg';
          $er_postId = 7;
          $er_firstname = 'Dineo';
          $er_lastname = 'Mabuza';
          $er_email = 'dineo@gmail.com';
          $er_passwd = 'dineo1234';
          $er_hashed = password_hash($er_passwd, PASSWORD_DEFAULT);
          $er_token = '1';
          $er_gender = 'Female';
          $er_age = 24;
          $er_sexuality = 'Man';
          $er_biography = 'Foodie';
          $er_interests = 'food';
          $er_status = 'i love my pizza';
          $er_lastseen = '0';
          $er_no_of_pictures = '2';
          $er_show = '0';
          $er_latitude = '-27.2051251';
          $er_longitude = '26.0399548';
          $er_city = 'johannesburg';
          $er_fame_rating= '25';
      
      
          $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
           $sql->execute([$er_firstname, $er_lastname, $er_username, $er_email, $er_hashed, $er_lastseen, $er_token, $er_no_of_pictures]);
           $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
           $sql->execute([$er_gender, $er_biography, $er_age, $er_sexuality, $er_city, $er_status]);
           $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
           $sql->execute([$er_username, $er_fame_rating]);
           $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
           $sql->execute([$er_username, $er_interests]);
           $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
           $sql->execute([$er_username, $er_profilepic]);
           $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
           $sql->execute([$er_username, $er_postId]);
           $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
           $sql->execute([$er_username, $er_latitude, $er_longitude, $er_show]);

           $eh_username = 'bhutiza';
           $eh_profilepic = 'images/ehman.jpeg';
           $eh_postId = 8;
           $eh_firstname = 'Bhut';
           $eh_lastname = 'Bhuti';
           $eh_email = 'bhutiza@gmail.com';
           $eh_passwd = 'jaman1234';
           $eh_hashed = password_hash($eh_passwd, PASSWORD_DEFAULT);
           $eh_token = '1';
           $eh_gender = 'Male';
           $eh_age = 21;
           $eh_sexuality = 'Women';
           $eh_biography = 'I love dance';
           $eh_interests = 'Ballroom';
           $eh_status = '2 stepn';
           $eh_lastseen = '0';
           $eh_no_of_pictures = '2';
           $eh_show = '1';
           $eh_latitude = '-26.2057291';
           $eh_longitude = '28.4395454569998';
           $eh_city = 'sandton';
           $eh_fame_rating= '12';
       
       
           $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
            $sql->execute([$eh_firstname, $eh_lastname, $eh_username, $eh_email, $eh_hashed, $eh_lastseen, $eh_token, $eh_no_of_pictures]);
            $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
            $sql->execute([$eh_gender, $eh_biography, $eh_age, $eh_sexuality, $eh_city, $eh_status]);
            $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
            $sql->execute([$eh_username, $eh_fame_rating]);
            $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
            $sql->execute([$eh_username, $eh_interests]);
            $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
            $sql->execute([$eh_username, $eh_profilepic]);
            $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
            $sql->execute([$eh_username, $eh_postId]);
            $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
            $sql->execute([$eh_username, $eh_latitude, $eh_longitude, $eh_show]);
    
            
            $ez_username = 'Gash';
            $ez_profilepic = 'images/ezman.jpeg';
            $ez_postId = 9;
            $ez_firstname = 'Bafana';
            $ez_lastname = 'Nonkelela';
            $ez_email = 'gash@gmail.com';
            $ez_passwd = 'gash1234';
            $ez_hashed = password_hash($ez_passwd, PASSWORD_DEFAULT);
            $ez_token = '1';
            $ez_gender = 'Male';
            $ez_age = 26;
            $ez_sexuality = 'Women';
            $ez_biography = 'am a officer';
            $ez_interests = 'games';
            $ez_status = 'justic';
            $ez_lastseen = '0';
            $ez_no_of_pictures = '2';
            $ez_show = '0';
            $ez_latitude = '-26.2051251';
            $ez_longitude = '26.9839958';
            $ez_city = 'sandton';
            $ez_fame_rating= '20';
        
        
            $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
             $sql->execute([$ez_firstname, $ez_lastname, $ez_username, $ez_email, $ez_hashed, $ez_lastseen, $ez_token, $ez_no_of_pictures]);
             $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
             $sql->execute([$ez_gender, $ez_biography, $ez_age, $ez_sexuality, $ez_city, $e_status]);
             $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
             $sql->execute([$ez_username, $ez_fame_rating]);
             $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
             $sql->execute([$ez_username, $ez_interests]);
             $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
             $sql->execute([$ez_username, $ez_profilepic]);
             $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
             $sql->execute([$ez_username, $ez_postId]);
             $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
             $sql->execute([$ez_username, $ez_latitude, $ez_longitude, $ez_show]);

             $ea_username = 'zmabund';
             $ea_profilepic = 'images/eafemale.jpg';
             $ea_postId = 10;
             $ea_firstname = 'Zanele';
             $ea_lastname = 'Mabunda';
             $ea_email = 'zmabund@student.wethinkcode.co.za';
             $ea_passwd = 'zet1234';
             $ea_hashed = password_hash($ea_passwd, PASSWORD_DEFAULT);
             $ea_token = '1';
             $ea_gender = 'Female';
             $ea_age = 26;
             $ea_sexuality = 'Man';
             $ea_biography = 'am in HR';
             $ea_interests = 'vegan';
             $ea_status = 'i love food';
             $ea_lastseen = '0';
             $ea_no_of_pictures = '2';
             $ea_show = '1';
             $ea_latitude = '-27.2051251';
             $ea_longitude = '26.239954599995998';
             $ea_city = 'sandton';
             $ea_fame_rating= '32';
         
             $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
              $sql->execute([$ea_firstname, $ea_lastname, $ea_username, $ea_email, $ea_hashed, $ea_lastseen, $ea_token, $ea_no_of_pictures]);
              $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
              $sql->execute([$ea_gender, $ea_biography, $ea_age, $ea_sexuality, $ea_city, $ea_status]);
              $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
              $sql->execute([$ea_username, $ea_fame_rating]);
              $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
              $sql->execute([$ea_username, $ea_interests]);
              $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
              $sql->execute([$ea_username, $ea_profilepic]);
              $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
              $sql->execute([$ea_username, $ea_postId]);
              $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
              $sql->execute([$ea_username, $ea_latitude, $ea_longitude, $ea_show]);

              $ex_username = 'SacredKid';
              $ex_profilepic = 'images/exman.jpeg';
              $ex_postId = 11;
              $ex_firstname = 'Tebogo';
              $ex_lastname = 'Ramassia';
              $ex_email = 'kid@gmail.com';
              $ex_passwd = 'kid1234';
              $ex_hashed = password_hash($ex_passwd, PASSWORD_DEFAULT);
              $ex_token = '1';
              $ex_gender = 'Male';
              $ex_age = 18;
              $ex_sexuality = 'Women';
              $ex_biography = 'am a student';
              $ex_interests = 'games';
              $ex_status = 'keep it wavy';
              $ex_lastseen = '0';
              $ex_no_of_pictures = '2';
              $ex_show = '0';
              $ex_latitude = '-27.7551251';
              $ex_longitude = '27.2399998';
              $ex_city = 'johannesburg';
              $ex_fame_rating= '25';
          
          
              $sql = $db->prepare('INSERT IGNORE INTO user (firstname, lastname, username, email, password, lastseen, token, no_of_pictures) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
               $sql->execute([$ex_firstname, $ex_lastname, $ex_username, $ex_email, $ex_hashed, $ex_lastseen, $ex_token, $ex_no_of_pictures]);
               $sql = $db->prepare('INSERT IGNORE INTO user_profile (gender, biography, age, sexuality, city, status) VALUES (?, ?, ?, ?, ?, ?);');
               $sql->execute([$ex_gender, $ex_biography, $ex_age, $ex_sexuality, $ex_city, $ex_status]);
               $sql = $db->prepare('INSERT IGNORE INTO views (username, fame_rating) VALUES (?, ?);');
               $sql->execute([$ex_username, $ex_fame_rating]);
               $sql = $db->prepare('INSERT IGNORE INTO interests (username, interest) VALUES (?, ?);');
               $sql->execute([$ex_username, $ex_interests]);
               $sql = $db->prepare('INSERT IGNORE INTO pictures (username, image_name) VALUES (?, ?);');
               $sql->execute([$ex_username, $ex_profilepic]);
               $sql = $db->prepare('INSERT IGNORE INTO profile_pic (username, post_id) VALUES (?, ?);');
               $sql->execute([$ex_username, $ex_postId]);
               $sql = $db->prepare('INSERT IGNORE INTO geolocation (username, lati, `long`, `show`) VALUES (?, ?, ?, ?);');
               $sql->execute([$ex_username, $ex_latitude, $ex_longitude, $ex_show]);
               
       
?>
