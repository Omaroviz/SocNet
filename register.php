<?php
include_once 'header.php';
echo 'TEST';

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
	!empty($_POST['name']) &&
	!empty($_POST['username']) &&
	!empty($_POST['password']) &&
	isset($_POST['register_btn'])
) {
	$stmt = $pdo->prepare("INSERT INTO users(name, username, password) VALUES(:name, :username, :password)");
	$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$stmt->execute([
		':name' => $_POST['name'],
		':username' => $_POST['username'],
		':password' => $hashed_password
	]);
	header('Location: '.$_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
<title>index.php</title>
</head>
<body>

<form method='POST'>
<input type='text' name='name' placeholder='name' required><br>
<input type='text' name='username' placeholder='username' required><br>
<input type='text' name='password' placeholder='password' required><br>
<button type='submit' name='register_btn'>Enter</button>
</form>

</body>
</html>




