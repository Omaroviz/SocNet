<?php

include_once 'header.php';

$user = new User($pdo);

echo 'Hello, '.$user->name.'!';

?>

<!DOCTYPE html>
<html lang='ru'>
<head>
<title>index.php</title>
</head>
<body>

<form method='POST'>
<h2>New post.</h2>
<input type='text' id='title' placeholder='title' required><br>
<input type='text' id='text' placeholder='text' required><br>
<button type='submit' id='btn'>Enter</button>
</form>
<?php Post::showAllPosts($pdo);?>
<script src='./script.js'></script>
</body>
</html>
