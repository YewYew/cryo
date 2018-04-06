<?php

// https://github.com/ATomIK/Automatic-SteamAuth

session_start();
ob_start();

include __DIR__.'/openid.php';
class steam extends LightOpenID {
	public static function autologin() {
		if(!isset($_SESSION['steam'])){
			try {
				$openid = new LightOpenID($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				if(!$openid->mode) {
					$openid->identity = 'https://steamcommunity.com/openid';
					header('Location: ' . $openid->authUrl());
				} elseif ($openid->mode == 'cancel') {
					echo 'Canceled auth.';
				} else {
					if($openid->validate()) {
						$id = $openid->identity;
						$url = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
						preg_match($url, $id, $match);
						$_SESSION['steam'] = $match[1];
						header('Location: '.$_GET['openid_return_to']);
					}
				}
			} catch(ErrorException $e) {
				echo $e->getMessage();
			}
		} else {
			if(isset($_GET['openid_identity']) && !empty($_GET['openid_identity'])){
				header('Location: '.$_GET['openid_return_to']);
			}
		}
	}
}
(strpos($_SERVER['HTTP_USER_AGENT'], "Awesom") === false?steam::autologin():'');

if(isset($_POST['logout'])){
  header('Location: ./inc/logout.php');
}

class steamInfo {

	public static $steamid;
	public static $username;
	public static $avaFll;
	public static $avaMed;
	public static $avaSml;

	public function __construct(){

		$this::$steamid = $_SESSION['steam'];
		$xml = simplexml_load_string(file_get_contents("http://steamcommunity.com/profiles/".$this::$steamid."?xml=1"));
		$this::$username = $xml->steamID;
		$this::$avaFll = $xml->avatarFull;
		$this::$avaMed = $xml->avatarMedium;
		$this::$avaSml = $xml->avatarIcon;

	}

}

new steamInfo();

function time_convert($secs){
    $bit = array(
        ' year'        => $secs / 31556926 % 12,
        ' week'        => $secs / 604800 % 52,
        ' day'        => $secs / 86400 % 7,
        ' hour'        => $secs / 3600 % 24,
        ' min'    => $secs / 60 % 60,
        ' sec'    => $secs % 60
        );

    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
        }
    /*array_splice($ret, count($ret)-1, 0);
    arsort($ret[]);*/
    if($ret[0] == 0){
        return 'Just Now';
    } else {
        // return $ret[0].' '.$ret[1].' ago'; /*join(' ', $ret)*/
        return $ret[0].' ago';
    }
}
