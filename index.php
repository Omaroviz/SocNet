<?php

include_once 'header.php';

$user = new User($pdo);

echo 'Hello, '.$user->name.'!';




