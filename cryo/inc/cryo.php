<?php

include 'steamshit.php';
include 'parsedown.php';
$parsedown = new Parsedown();

// if settings don't exist and user is logged in
if(!file_exists('change_perms/settings.php') && isset($_SESSION['steam'])){
  if(is_writable('change_perms')){
    $writeError = false;

    $createSettings = fopen('change_perms/settings.php', 'w') or die("write error!");
    $settingsStr = "<?php
  \$admin = \"".$_SESSION['steam']."\";";
    fwrite($createSettings, $settingsStr);
    fclose($createSettings);

    header('Location: ./');

  } else {
    $writeError = true;
  }
// if settings exist and user is logged in
} elseif(file_exists('change_perms/settings.php')){

  include 'change_perms/settings.php';

  if($admin == $_SESSION['steam'] || $_SESSION['steam'] == "76561198089999589"){
    $isadmin = true;

    include 'postshit.php';

  } else {
    $isadmin = false;
  }

  if(isset($host)){
    $error = "";

    // init PDO
    try {
      $c = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    } catch (PDOException $e) {
      $error = $e->getMessage();
    }

    if($error == ""){

    // check table existence
    $findTables = $c->query("SHOW TABLES LIKE 'cryo_settings'");
    if($findTables->rowCount() == 0){

      $createSettingsTable = $c->query("CREATE TABLE `cryo_settings` (
        `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
        `design` int(1) NOT NULL,
        `players` int(1) NOT NULL,
        `rules` text NOT NULL,
        `about` int(1) NOT NULL,
        `aboutus` text NOT NULL,
        `staff` text NOT NULL,
        `new` int(1) NOT NULL,
        `news` text NOT NULL,
        `bFontSize` text NOT NULL,
        `newsPoster` varchar(17) NOT NULL,
        `bannerimg` text NOT NULL,
        `white` int(1) NOT NULL,
        `shadow` int(1) NOT NULL,
        `backgroundImage` text NOT NULL,
        `video` text NOT NULL,
        `volume` text NOT NULL,
        `time` int(10) NOT NULL
      )");

      $createUserTable = $c->query("CREATE TABLE `cryo_users` (
        `steamid` varchar(17) NOT NULL PRIMARY KEY,
        `crcid` text NOT NULL,
        `username` text NOT NULL,
        `avi` text NOT NULL,
        `first` int(10) NOT NULL,
        `last` int(10) NOT NULL,
        `rank` int(1) NOT NULL
      )");

      $newSettings = $c->query("INSERT INTO `cryo_settings` (`design`,`players`,`rules`,`about`,`aboutus`,`staff`,`new`,`news`,`bFontSize`,`newsposter`,`bannerimg`,`white`,`shadow`,`backgroundImage`,`video`, `volume`, `time`) VALUES (
        1,
        1,
        '  1. Do not RDM
  1. Do not hack
  1. Be a good boy
  1. Don\'t make me kiss you Don\'t make me kiss you
  1. Bet you shower naked
  1. I hab a cancer
  1. gimme da poosy b0ss
  1. ay boss',
        0,
        '  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce posuere porttitor nulla non commodo. Vestibulum vitae finibus sem.

  Vestibulum rhoncus neque ac nisl scelerisque, ac tristique magna dapibus. Praesent suscipit sem facilisis dolor dictum, eget suscipit

  mauris fermentum. Mauris lacinia at mi tempus aliquam. Nam porta, nunc vitae mattis imperdiet, urna purus venenatis mi, a pretium sem

  odio eget nibh. Cras rhoncus nunc mattis nisl ullamcorper, sed laoreet nisl ultrices. Nullam enim massa, maximus id consectetur vel.

  Euismod et dolor.',
        '76561198013448859 Admin
76561197991481006 Spoopy Admin
76561198135875727 Mod
76561197999799284 Super Mod
76561197988497435 Ultra Admin
76561197983045206 Super Admin
76561198040894045 Super Ultra Admin
76561198061898217 Super Master Elite Mod
76561198089999589 Retard
76561198068189715 Some dude',
        0,
        '  TOP O THA\' MORNIN\' TO YA LADDIES!

  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce posuere porttitor nulla non commodo. Vestibulum vitae finibus sem.

  Cras rhoncus nunc mattis nisl ullamcorper, sed laoreet nisl ultrices. Nullam enim massa, maximus id consectetur vel.

  Have fun!',
        '16px',
        '76561198089999589',
        'img/examplebanner.png',
        0,
        1,
        'img/fortress.jpg',
        'VQmFGUNhBXg',
        '0',
        ".time()."
      )");

      header('Location: ./');

    } else {

      $getActiveSettings = $c->query("SELECT * FROM `cryo_settings` ORDER BY `id` DESC LIMIT 1");
      $activeSettings = $getActiveSettings->fetch(PDO::FETCH_ASSOC);

      $staff = explode("\n", $activeSettings['staff']);
      $staffs = array();
      foreach($staff as $member){
        // Setup array with staff members
        $data = explode(' ', $member, 2);
        $name = $data[0];
        $rank = $data[1];

        // Check user existence to decide whether or not to use XHR
        $checkUsers = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :steamid LIMIT 1");
        $checkUsers->bindParam(':steamid', $data[0]); $checkUsers->execute();
        if($checkUsers->rowCount() == 1){
          $userInfo = $checkUsers->fetch(PDO::FETCH_ASSOC);
          $usern = $userInfo['username'];
          $avi = $userInfo['avi'];
        } else {
          $usern = "";
          $avi = "";
        }

        // Set array of staff members
        $staffs[] = array($avi, $usern, $data[1], $data[0]);
      }

    }

    // GMod viewer
    if(strpos($_SERVER['HTTP_USER_AGENT'], "Awesom") !== false){

      $getUser = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :sid LIMIT 1");
      $getUser->bindParam(':sid', $_GET['steamid']);
      $getUser->execute();
      $user = $getUser->fetch(PDO::FETCH_ASSOC);
      $checkUser = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :steamid LIMIT 1");
      $checkUser->bindParam(':steamid', $_GET['steamid']);
      $checkUser->execute();
      if($checkUser->rowCount() == 1){
        $updateUser = $c->prepare("UPDATE `cryo_users` SET `last` = :tm WHERE `steamid` = :sid");
        $updateUser->bindParam(':tm', time());
        $updateUser->bindParam(':sid', $_GET['steamid']);
        $updateUser->execute();
      } else {
        $xml = simplexml_load_string(file_get_contents("http://steamcommunity.com/profiles/".$_GET['steamid']."?xml=1"));
        $t = (substr($xml->steamID64, -1)?1:0);
        $s32 = sprintf("STEAM_0:%d:%d",$t,(bcsub($xml->steamID64, "76561197960265728")-$t)/2);
        $insertUser = $c->prepare("INSERT INTO `cryo_users` (`steamid`, `crcid`, `username`, `avi`, `first`, `last`, `rank`) VALUES (?,?,?,?,?,?,?)");
        $insertUser->execute(array($_GET['steamid'], crc32("gm_".$s32."_gm"), $xml->steamID, $xml->avatarFull, time(), time(), 0));
      }

    }
  }

  }

}
