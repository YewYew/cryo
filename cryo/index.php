<?php
  // ini_set('display_errors', '-1');
  include 'inc/cryo.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cryo</title>
    <link rel="stylesheet" href="compiled/css/compiled.min.css">
    <script src="compiled/js/design2.min.js"></script>

    <style>
      body {
        background: url('<?= (!isset($activeSettings)||$activeSettings['backgroundImage']==''?'img/fortress.jpg':$activeSettings['backgroundImage']) ?>') no-repeat;
        background-color: black;
        background-size: cover;
        background-position: center;
        background-attachment: fixed
      }
      .background {
        background: url('<?= (!isset($activeSettings)||$activeSettings['backgroundImage']==''?'img/fortress.jpg':$activeSettings['backgroundImage']) ?>') no-repeat
      }
      #design2 .wrapper .content .post {
        font-size: <?= $activeSettings['bFontSize'] ?>
      }
      <?php if($activeSettings['white']){ ?>
        .overlay {
          background-color: rgba(255, 255, 255, 0.15);
        }
      <?php }
      if($activeSettings['shadow']){ ?>
        .wrapper {
          -webkit-box-shadow: 8px 8px 10px 0px rgba(0,0,0,0.3);
          -moz-box-shadow: 8px 8px 10px 0px rgba(0,0,0,0.3);
          box-shadow: 8px 8px 10px 0px rgba(0,0,0,0.3);
        }
      <?php } ?>
    </style>
  </head>
  <body>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '0',
          width: '0',
          playerVars: {
            listType: 'playlist',
            list: '<?= (!isset($activeSettings)?'PL-b_16QhC7cQqjkJStdOR2n7nUwm4jJPH':$activeSettings['video']) ?>'
          },
          events: {
            'onReady': onPlayerReady,
          }
        });
      }
      function onPlayerReady(event) {
        event.target.setVolume(<?= (!isset($activeSettings)?'20':$activeSettings['volume']) ?>);
        event.target.setLoop(1);
        event.target.playVideo();
      }
    </script>
    <div id="player"></div>
    <?php if($activeSettings['design'] == 1){ ?>
      <div id="design1">
      <div class="wrapper">
        <div class="background"></div>
        <div class="overlay"></div>
        <div class="content animated fadeInLeft">
          <?php
            if(!isset($user['username'])){
              $xml = simplexml_load_string(file_get_contents("http://steamcommunity.com/profiles/".$admin."?xml=1"));
          ?>
            <img class="avi" src="<?= $xml->avatarFull ?>" alt="">
            <h1><?= $xml->steamID ?></h1>
            <p>Last connected never!</p>
            <br/>
            <p><div id="hostname" class="cell"><div class="loader"></div></div></p>
            <p><div id="mapname" class="cell"><div class="loader"></div></div></p>
            <p><div id="gamemode" class="cell"><div class="loader"></div></div></p>
            <p><div id="players" class="cell"><div class="loader"></div></div></p>
          <?php } else { ?>
            <img class="avi" src="<?= $user['avi'] ?>" alt="">
            <h1><?= $user['username'] ?></h1>
            <p>Last connected <?= time_convert(time()-$user['last']) ?></p>
            <br/>
            <p><div id="hostname" class="cell"><div class="loader"></div></div></p>
            <p><div id="mapname" class="cell"><div class="loader"></div></div></p>
            <p><div id="gamemode" class="cell"><div class="loader"></div></div></p>
            <p><div id="players" class="cell"><div class="loader"></div></div></p>
          <?php } ?>
        </div>
      </div>
      <div class="wrapper">
        <div class="background"></div>
        <div class="overlay"></div>
        <div class="content animated fadeInRight">
          <h1 class="title">Rules</h1>
          <?= $parsedown->text($activeSettings['rules']) ?>
        </div>
      </div>
      <div class="wrapper">
        <div class="background"></div>
        <div class="overlay"></div>
        <div class="content animated fadeInLeft">
          <h1 class="title">About Us!</h1>
          <?= $parsedown->text($activeSettings['aboutus']) ?>
        </div>
      </div>
    </div>
    <?php }
    if($activeSettings['design'] == 2){ ?>
      <div id="design2">
        <div class="center">
          <img class="banner" src="<?= $activeSettings['bannerimg'] ?>" alt="">
          <table>
            <tbody>
              <tr>
                <td class="left-cell">
                  <div class="left">
                    <div class="wrapper">
                      <div class="background"></div>
                      <div class="overlay"></div>
                      <div class="content">
                        <?php
                          if(!isset($user['username'])){
                            $xml = simplexml_load_string(file_get_contents("http://steamcommunity.com/profiles/".$admin."?xml=1"));
                        ?>
                          <img class="avi" src="<?= $xml->avatarFull ?>" alt="">
                          <h1><?= $xml->steamID ?></h1>
                          <p><?= $xml->steamID64 ?></p>
                          <?php if($activeSettings['players']){ ?>
                            <p>Welcome to the server!</p>
                          <?php } ?>
                        <?php } else { ?>
                          <img class="avi" src="<?= $user['avi'] ?>" alt="">
                          <h1><?= $user['username'] ?></h1>
                          <p><?= $user['steamid'] ?></p>
                          <p>Last connected <?= time_convert(time()-$user['last']) ?></p>
                        <?php } ?>
                        <h3>Server Info</h3>
                        <div class="table">
                          <div class="cell">
                            <p><b>Hostname:</b><span id="hostname"><span class="loader"></span></span></p>
                          </div>
                          <div class="cell">
                            <p><b>Map:</b><span id="mapname"><span class="loader"></span></span></p>
                          </div>
                          <div class="cell">
                            <p><b>Gamemode:</b><span id="gamemode"><span class="loader"></span></span></p>
                          </div>
                          <div class="cell">
                            <p><b>Player Count:</b><span id="players"><span class="loader"></span></span></p>
                          </div>
                          <?php
                            $getLatest = $c->query("SELECT * FROM `cryo_users` ORDER BY `first` DESC");
                            $latest = $getLatest->fetch(PDO::FETCH_ASSOC);
                          ?>
                          <?php if($activeSettings['players']){ ?>
                            <div class="cell">
                              <p><b>Newest:</b><span id="newest"><?= ($latest['username']!==""?$latest['username']:'no one') ?></span></p>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="right-cell">
                  <div class="right">
                      <div class="wrapper">
                        <div class="background"></div>
                        <div class="overlay"></div>
                        <div class="about content">
                          <?php if($activeSettings['about']){ ?>
                            <h2>About Us</h2>
                            <div class="aboutus">
                              <?= $parsedown->text($activeSettings['aboutus']) ?>
                            </div>
                          <?php } else { ?>
                            <h2>Server Staff</h2>
                            <div class="staff">
                              <?php foreach($staffs as $member){ // make this use xml the first time retard
                                $checkDB = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :id64 LIMIT 1");
                                $checkDB->bindParam(':id64', $member[3]); $checkDB->execute();
                                if($checkDB->rowCount() == 0){
                                  $xml = simplexml_load_string(file_get_contents("http://steamcommunity.com/profiles/".$member[3]."?xml=1"));
                                  $addUser = $c->prepare("INSERT INTO `cryo_users` (`steamid`, `crcid`, `username`, `avi`, `first`, `last`) VALUES (?,?,?,?,?,?)");
                                  $t = (substr($xml->steamID64, -1)?1:0);
                                  $s32 = sprintf("STEAM_0:%d:%d",$t,(bcsub($xml->steamID64, "76561197960265728")-$t)/2);
                                  $userData = array($xml->steamID64,crc32("gm_".$s32."_gm"),$xml->steamID,$xml->avatarFull,time(),time());
                                  $addUser->execute($userData);
                                }
                              ?>
                                <div class="member" <?= ($member[0]==''&&$member[1]==''?'data-sid="'.$member[3].'"':'') ?>>
                                  <div class="info">
                                    <img src="<?= $member[0] ?>" alt="" />
                                    <p><b><?= $member[1] ?></b></p>
                                  </div>
                                  <p><small><?= $member[2] ?></small></p>
                                </div>
                              <?php } ?>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="left">
                    <div class="wrapper">
                      <div class="background"></div>
                      <div class="overlay"></div>
                      <div class="content">
                        <h2>Rules</h2>
                        <?= $parsedown->text($activeSettings['rules']) ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="right">
                    <div class="wrapper">
                      <div class="background"></div>
                      <div class="overlay"></div>
                      <div class="content">
                        <?php
                          $findTables = $c->query("SHOW TABLES LIKE 'playerinformation'");
                          $i = 0;
                          if(!$activeSettings['new']){
                        ?>
                          <h2><?= ($findTables->rowCount()==1?'Money':'Points') ?> Leaderboard</h2>
                          <div class="leaderboard">
                            <?php
                              // check table existence
                              if($findTables->rowCount() == 0){

                                // Check pointshop 1 existence
                                $findPS1Tables = $c->query("SHOW TABLES LIKE 'pointshop_data'");
                                if($findPS1Tables->rowCount() == 1){

                                  // Subquery to get top 3 pointshop & cryo users
                                  $topWallet = $c->query("SELECT * FROM `pointshop_data` WHERE `uniqueid` IN (SELECT `crcid` FROM `cryo_users`) ORDER BY `points` DESC LIMIT 3");

                                  while($r = $topWallet->fetch(PDO::FETCH_ASSOC)){
                                    $GetPlayerInfo = $c->prepare("SELECT * FROM `cryo_users` WHERE `crcid` = :crcid LIMIT 1");
                                    $GetPlayerInfo->bindParam(':crcid', $r['uniqueid']); $GetPlayerInfo->execute();
                                    $player = $GetPlayerInfo->fetch(PDO::FETCH_ASSOC);
                                  // disgusting conditional clusterfuck
                                ?>
                                  <div class="<?= ($i==0?'one':($i==1?'two':($i==2?'three':''))) ?>">
                                    <img src="<?= $player['avi'] ?>" alt="" />
                                    <p><?= $player['username'] ?> <span><?= number_format($r['points']) ?> points</span></p>
                                  </div>
                                  <?php $i++; }

                                } else {

                                  // Check pointshop 2 existence

                                  $findPS1Tables = $c->query("SHOW TABLES LIKE 'libk_player'");
                                  if($findPS1Tables->rowCount() == 1){

                                    // Subquery to get top 3 pointshop 2 & cryo users
                                    // have to use two queries thanks to pointshop 2's libk
                                    $getWallets = $c->query("SELECT * FROM `ps2_wallet` ORDER BY `points` DESC");
                                    while($r = $getWallets->fetch(PDO::FETCH_ASSOC)){
                                      $userInfo = $c->prepare("SELECT * FROM `libk_player` WHERE `id` = :ps2id AND `uid` IN (SELECT `crcid` FROM `cryo_users`) LIMIT 1");
                                      $userInfo->bindParam(':ps2id', $r['ownerId']);
                                      $userInfo->execute();
                                      if($userInfo->rowCount()==1){
                                        $ps2user = $userInfo->fetch(PDO::FETCH_ASSOC);
                                        $getUserInfo = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :ps264id LIMIT 1");
                                        $getUserInfo->bindParam(':ps264id', $ps2user['steam64']); $getUserInfo->execute();
                                        $player = $getUserInfo->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                          <div class="<?= ($i==0?'one':($i==1?'two':($i==2?'three':''))) ?>">
                                            <img src="<?= $player['avi'] ?>" alt="" />
                                            <p><?= $player['username'] ?> <span>$<?= number_format($r['points']) ?></span></p>
                                          </div>
                                        <?php
                                        $i++;
                                      }
                                    }
                                    $topWallet = $c->query("SELECT * FROM `ps2_wallet` WHERE `uid` IN (SELECT `crcid` FROM `cryo_users`) ORDER BY `points` DESC LIMIT 3");

                                  }
                                }

                              } else {
                                // Subquery to get top 3 rp & cryo users
                                $topWallet = $c->query("SELECT * FROM `darkrp_player` WHERE `uid` IN (SELECT `crcid` FROM `cryo_users`) ORDER BY `wallet` DESC LIMIT 3");
                                $i = 0;
                                while($r = $topWallet->fetch(PDO::FETCH_ASSOC)){
                                  $GetPlayerInfo = $c->prepare("SELECT * FROM `cryo_users` WHERE `crcid` = :crcid LIMIT 1");
                                  $GetPlayerInfo->bindParam(':crcid', $r['uid']); $GetPlayerInfo->execute();
                                  $player = $GetPlayerInfo->fetch(PDO::FETCH_ASSOC);
                                // disgusting conditional clusterfuck
                              ?>
                                <div class="<?= ($i==0?'one':($i==1?'two':($i==2?'three':''))) ?>">
                                  <img src="<?= $player['avi'] ?>" alt="" />
                                  <p><?= $r['rpname'] ?> <span>$<?= number_format($r['wallet']) ?></span></p>
                                </div>
                                <?php $i++; } ?>
                              <?php } ?>
                          </div>
                        <?php } else { ?>
                          <h2>News</h2>
                          <?php $posterInfo = $c->prepare("SELECT * FROM `cryo_users` WHERE `steamid` = :newsPoster LIMIT 1"); $posterInfo->bindParam(':newsPoster', $activeSettings['newsPoster']); $posterInfo->execute(); $newsPoster = $posterInfo->fetch(PDO::FETCH_ASSOC); ?>
                          <p><small>Posted 16 minutes ago by <img src="<?= $newsPoster['avi'] ?>" alt=""> <?= $newsPoster['username'] ?></small></p>
                          <div class="post">
                            <?= $parsedown->text($activeSettings['news']) ?>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>
    <?php }

    if(strpos($_SERVER['HTTP_USER_AGENT'], "Awesom") === false){ ?>
      <div class="admin">
        <div class="background"></div>
        <div class="overlay"></div>
        <?php if(!file_exists('change_perms/settings.php') && $writeError){ ?>
          <div class="error">
            Write error! Refer to the README!
          </div>
        <?php } ?>
        <?php if(!isset($_SESSION['steam'])){ ?>
          <form style="text-align:center;width:100%" method="POST">
            <input type="submit" name="login" value="Login">
          </form>
        <?php } if(isset($_SESSION['steam']) && $isadmin){ if(file_exists('change_perms/settings.php')){include 'change_perms/settings.php';} ?>
          <?php if($error !== "" && isset($host)){ ?>
            <div class="error">
              MySQL connection error!<br/>
              <pre><?= $error ?></pre>
            </div>
          <?php } ?>
          <form method="post">
            <div class="fgroup">
              <h4 style="margin-bottom:0">MySQL information</h4>
              <p style="margin:0"><small>If data does not appear, refresh!</small></p>
              <p>Host</p>
              <input type="text" name="host" placeholder="IP/localhost" autocomplete="off" value="<?= $host ?>">
            </div>
            <div class="fgroup">
              <p>User</p>
              <input type="text" name="user" placeholder="Username" autocomplete="off" value="<?= $user ?>">
              <!-- Fuck your shitty browsers autofilling data -->
              <input type="text" name="username" hidden>
            </div>
            <div class="fgroup">
              <p>Pass</p>
              <input type="password" name="password" hidden>
              <input type="password" name="pass" placeholder="Password" autocomplete="off" value="<?= $pass ?>">
            </div>
            <div class="fgroup">
              <p>Database name <small>Use your DarkRP DB to enable leaderboards</small></p>
              <input type="text" name="db" placeholder="DarkRP DB" value="<?= $db ?>">
            </div>
            <div class="fgroup">
              <h3 style="margin-bottom:0">Server information</h3>
              <p style="margin-top:0"><small>This is required for server info to function.<br/>Server <b>MUST</b> be query-able.</small></p>
            </div>
            <div class="fgroup">
              <p>IP <small>Must be an IP. Ex: 123.123.123.123</small></p>
              <input type="text" name="ip" value="<?= $ip ?>" placeholder="123.123.123.123">
            </div>
            <div class="fgroup">
              <p>Port</p>
              <input type="text" name="port" value="<?= $port ?>" placeholder="27015">
            </div>
            <div class="fgroup">
              <input type="submit" value="Save">
              <?php if(file_exists('change_perms/settings.php')){ ?>
                <small style="float:right">
                  <a href="change_perms/reset.php" style="color:red;text-decoration:none">Reset DB</a>
                </small>
              <?php } ?>
            </div>
          </form>
          <?php if($getActiveSettings->rowCount() !== 0){ ?>
            <form method="post">
              <div class="fgroup">
                <p>Config line</p>
                <input type="text" value="sv_loadingurl &quot;<?= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>?steamid=%s&quot;">
              </div>
              <h2>Configure</h2>
              <div class="fgroup">
                <p><b>Theme</b></p>
                <select name="design">
                  <option <?= ($activeSettings['design']==1?'selected':'') ?> value="1">Theme 1</option>
                  <option <?= ($activeSettings['design']==2?'selected':'') ?> value="2">Theme 2</option>
                </select>
              </div>
              <div class="fgroup">
                <p>Enable new players & Last connection?</p>
                <input type="checkbox" name="userDB" <?= ($activeSettings['players']?'checked':'') ?>>
              </div>
              <div class="fgroup">
                <p>Rules <small>Don't forget the "-"</small></p>
                <textarea name="rules" rows="8" cols="40"><?= $activeSettings['rules'] ?></textarea>
              </div>
              <?php if($activeSettings['design'] == 2){ ?>
                <div class="fgroup">
                  <p>Enable "About Us" instead of "Server Staff"</p>
                  <input type="checkbox" name="about" <?= ($activeSettings['about']?'checked':'') ?>>
                </div>
              <?php } ?>
              <div class="fgroup">
                <p>About Us</p>
                <textarea name="aboutus" rows="8" cols="40"><?= $activeSettings['aboutus'] ?></textarea>
              </div>
              <?php if($activeSettings['design'] == 2){ ?>
                <div class="fgroup">
                  <p>Server Staff <small>Visit the <a style="color:cyan;text-decoration:none" href="https://support.atomik.info">F.A.Q.</a> for syntax</small></p>
                  <textarea name="staff" rows="8" cols="40"><?= $activeSettings['staff'] ?></textarea>
                </div>
                <div class="fgroup">
                  <p>Enable "News" instead of "Leaderboard"</p>
                  <input type="checkbox" name="new" <?= ($activeSettings['new']?'checked':'') ?>>
                </div>
                <div id="last" class="fgroup">
                  <p>News</p>
                  <textarea name="news" rows="8" cols="40"><?= $activeSettings['news'] ?></textarea>
                </div>
                <div class="fgroup">
                  <p>News font size</p>
                  <input type="text" name="bFontSize" value="<?= $activeSettings['bFontSize'] ?>">
                </div>
              <?php } ?>
              <h2>Design</h2>
              <?php if($activeSettings['design'] == 2){ ?>
                <div class="fgroup">
                  <p>Banner image <small>Leave blank for no banner</small></p>
                  <input type="text" name="bannerimg" placeholder="Path to banner" value='<?= $activeSettings['bannerimg'] ?>'>
                </div>
              <?php } ?>
              <div class="fgroup">
                <p>Enable white glass</p>
                <input type="checkbox" name="white" <?= ($activeSettings['white']?'checked':'') ?>>
              </div>
              <div class="fgroup">
                <p>Enable glass shadow</p>
                <input type="checkbox" name="shadow" <?= ($activeSettings['shadow']?'checked':'') ?>>
              </div>
              <div class="fgroup">
                <p>Background image</p>
                <input type="text" name="backgroundImage" value="<?= $activeSettings['backgroundImage'] ?>">
              </div>
              <h2>Music</h2>
              <div class="fgroup">
                <p>YouTube playlist ID</p>
                <input type="text" name="video" value="<?= $activeSettings['video'] ?>">
              </div>
              <div class="fgroup">
                <p>Music volume <small>(0-100)</small></p>
                <input type="text" name="volume" value="<?= $activeSettings['volume'] ?>">
              </div>
              <input type="submit" value="Save Cryo">
              <small>
                <a style="float:right;text-decoration:none;color:cyan" href="inc/revert.php" title="Use the last save.">Revert changes</a>
              </small>
              <p>Last saved: <?= date('M d g:i A', $activeSettings['time']) ?></p>
            </form>
          <?php } ?>
        <?php } ?>
        <?php if(isset($_SESSION['steam'])){ ?>
          <?php if(!$isadmin){ ?>
            <div class="error">
              You aren't an admin!
            </div>
          <?php } ?>
          <form style="text-align:center;width:100%" method="post">
            <input type="submit" name="logout" value="Logout">
          </form>
        <?php } ?>
      </div>
    <?php } ?>

  </body>
</html>
