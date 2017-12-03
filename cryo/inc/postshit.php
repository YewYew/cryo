<?php

if(isset($_POST['db']) || isset($_POST['design'])){

  include 'change_perms/settings.php';

  if(isset($host)){
    $c = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
  }

  if($isadmin){

    if(isset($_POST['db'])){

      $settings = fopen('change_perms/settings.php', 'w') or die('write error! #2');

      $settingsStr = "<?php
    \$admin = \"".$_SESSION['steam']."\";
    \$host = \"".$_POST['host']."\";
    \$user = \"".$_POST['user']."\";
    \$pass = \"".$_POST['pass']."\";
    \$db = \"".$_POST['db']."\";
    \$ip = '".$_POST['ip']."';
    \$port = '".$_POST['port']."';";
      if(fwrite($settings, $settingsStr)){
        fclose($settings);
        header('Location: inc/index.php');
      }

    }

    if(isset($_POST['design'])){

      $getActiveSettings = $c->query("SELECT * FROM `cryo_settings` ORDER BY `id` DESC LIMIT 1");
      $activeSettings = $getActiveSettings->fetch(PDO::FETCH_ASSOC);

      $insertConfig = $c->prepare("INSERT INTO `cryo_settings` (`design`, `players`, `rules`, `about`, `aboutus`, `staff`, `new`, `news`, `bFontSize`, `newsPoster`, `bannerimg`, `white`, `shadow`, `backgroundImage`, `video`, `volume`, `time`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
      $insertConfig->execute(array(
        $_POST['design'],
        (isset($_POST['userDB'])?1:0),
        $_POST['rules'],
        (isset($_POST['about'])?1:0),
        $_POST['aboutus'],
        (isset($_POST['staff'])?$_POST['staff']:$activeSettings['staff']),
        (isset($_POST['new'])?1:0),
        (isset($_POST['news'])?$_POST['news']:$activeSettings['news']),
        (isset($_POST['bFontSize'])?$_POST['bFontSize']:$activeSettings['bFontSize']),
        $_SESSION['steam'],
        (isset($_POST['bannerimg'])?$_POST['bannerimg']:$activeSettings['bannerimg']),
        (isset($_POST['white'])?1:0),
        (isset($_POST['shadow'])?1:0),
        $_POST['backgroundImage'],
        $_POST['video'],
        $_POST['volume'],
        time()
      ));

      header('Location: inc/index.php');

    }

  }

}

?>
