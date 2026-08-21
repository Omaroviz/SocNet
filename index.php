<?php

include_once 'header.php';

$user = new User($pdo);

echo 'Hello, '.$user->name.'!';

Post::showAllPosts($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
	!empty($_POST['title']) &&
	!empty($_POST['text']) &&
	isset($_POST['new_post_btn'])
) {
	Post::newPost($pdo, $_POST['title'], $_POST['text'], $user->id);
	header('Location: '.$_SERVER['PHP_SELF']);
	exit();
}
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
<title>index.php</title>
</head>
<body>

<form method='POST'>
<h2>New post.</h2>
<input type='text' name='title' placeholder='title' required><br>
<input type='text' name='text' placeholder='text' required><br>
<button type='submit' name='new_post_btn'>Enter</button>
</form>

</body>
</html>
