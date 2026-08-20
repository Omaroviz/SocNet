<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
		$stmt->execute([':username' => $_POST['username']]);
		$user_login = $stmt->fetch();
		if ($user_login) {
			if (password_verify($_POST['password'], $user_login['password'])) {
				$token = bin2hex(random_bytes(32));
				$stmt = $pdo->prepare('DELETE FROM cookie_token WHERE id = :id');
				$stmt->execute([':id' => $user_login['id']]);
				$stmt = $pdo->prepare('INSERT INTO cookie_token(id, token) VALUES(:id, :token)');
				$stmt->execute([':id' => $user_login['id'], ':token' => $token]);
				setcookie("cookie_username", $user_login['username'], [
				    'expires' => time() + 86400 * 30,
				    'path' => '/',
				    'domain' => '',
				    'secure' => true,
				    'httponly' => true,
				    'samesite' => 'Strict'
				]);
				setcookie("cookie_token", $token, [
				    'expires' => time() + 86400 * 30,
				    'path' => '/',
				    'domain' => '',
				    'secure' => true,
				    'httponly' => true,
				    'samesite' => 'Strict'
				]);
				header('Location: index.php');
				exit();
			} else {
				die('Password is incorrect');
			}
		} else {
			die('User with this username not find');
		}
	} else {
		die('Error: username or password not inputed');
	}
}

?>

<!DOCTYPE html>
<html lang='ru'>
<head>
<title>login.php</title>
<head>
<body>

<form method='POST'>
<input type='text' name='username' placeholder='username' required><br>
<input type='password' name='password' placeholder='password' required><br>
<button type='submit' name='login_btn'>Enter</button>
</form>

</body>
</html>
