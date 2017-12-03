<?php


ini_set('display_errors', '-1');

include '../change_perms/settings.php';

$c = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);

$getActiveSettings = $c->query("SELECT * FROM `cryo_settings` ORDER BY `id` DESC LIMIT 1");
$activeSettings = $getActiveSettings->fetch(PDO::FETCH_ASSOC);

$deleteActive = $c->prepare("DELETE FROM `cryo_settings` WHERE `id` = :aid");
$deleteActive->bindParam(':aid', $activeSettings['id']);
$deleteActive->execute();

header('Location: index.php');
