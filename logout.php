<?php

include_once 'header.php';

$user = new User($pdo);
$user->logout();
header('Location: login.php');
exit();
