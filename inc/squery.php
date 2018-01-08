<?php

require 'squery/SourceQuery.class.php';

include '../change_perms/settings.php';

define( 'SQ_SERVER_ADDR', $ip );
define( 'SQ_SERVER_PORT', $port );
define( 'SQ_TIMEOUT',     3 );
define( 'SQ_ENGINE',      SourceQuery :: SOURCE );

$Query = new SourceQuery();

try
{
	$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );

  if(isset($_GET['hostname']) || isset($_GET['mapname']) || isset($_GET['gamemode']) || isset($_GET['players'])){
	  $Info = $Query->GetInfo();
  }
}
catch( Exception $e )
{
	$Exception = $e;
}

$Query->Disconnect();

if(isset($_GET['hostname'])){
  echo $Info['HostName'];
}

if(isset($_GET['mapname'])){
  echo $Info['Map'];
}

if(isset($_GET['gamemode'])){
  echo $Info['ModDesc'];
}

if(isset($_GET['players'])){
  echo $Info['Players'].'/'.$Info['MaxPlayers'];
}
