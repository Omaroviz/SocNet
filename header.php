<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$host = 'localhost';
$db   = 'SocNet';
$user = 'root';
$pass = ''; // В XAMPP/LAMPP на Linux пароль по умолчанию пустой
$charset = 'utf8mb4';

// Настройки подключения (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Дополнительные важные параметры безопасности и удобства
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Включает выброс исключений при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Возвращает данные в виде удобных массивов
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Отключает эмуляцию подзапросов для безопасности
];

try {
    // Создаем объект подключения
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // В случае ошибки выводим безопасное сообщение
    die("Ошибка подключения к БД: " . $e->getMessage());
}

class User {
	public $name, $username, $token, $pdo, $id;
	public function __construct($pdo) {
		if (!$_COOKIE['cookie_username'] || !$_COOKIE['cookie_token']) {
			header('Location: login.php');
			exit();
		}
		$cookie_username = $_COOKIE['cookie_username'];
		$cookie_token = $_COOKIE['cookie_token'];
		$this->pdo = $pdo;
		$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
		$stmt->execute([':username' => $cookie_username]);
		$user_from_db = $stmt->fetch();
		if ($user_from_db) {
			$stmt = $pdo->prepare('SELECT * FROM cookie_token WHERE id = :id');
			$stmt->execute([':id' => $user_from_db['id']]);
			$user_from_db_cookie = $stmt->fetch();
			$cookie_username = $_COOKIE['cookie_username'];
			if (hash_equals($user_from_db_cookie['token'], $cookie_token)) {
				$this->id = $user_from_db['id'];	
				$this->name = $user_from_db['name'];	
				$this->username = $user_from_db['username'];	
				$this->token = $user_from_db_cookie['token'];	
			} else {
				echo 'test';
				// Здесь должны удаляться куки
			}
		} else {
			die('User is not real');
		}
	}
	public function logout() {
		setcookie("cookie_username", $user_login['username'], [
			'expires' => time() - 3600,
			'path' => '/',
			'domain' => '',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Strict'
		]);
		setcookie("cookie_token", $token, [
			'expires' => time() - 3600,
			'path' => '/',
			'domain' => '',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Strict'
		]);
		$stmt = $pdo->prepare('DELETE FROM cookie_token WHERE id = :id');
		$stmt->execute([':id' => $this->id]);
	}
	static function info($pdo, $id) {
		$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
		$stmt->execute([':id' => $id]);
		$user_info = $stmt->fetch();
		return $user_info;
	}
}

class Post {
	public $id, $title, $text, $author, $created_at;
	public function __construct($pdo, $id) {
		$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
		$stmt->execute([':id' => $id]);
		$post = $stmt->fetchAll();
	}
	static function showAllPosts($pdo) {
		$stmt = $pdo->query('SELECT * FROM posts');
		$posts = $stmt->fetchAll();
		foreach ($posts as $post) {
			$title = $post['title'];
			$text = $post['text']; // senbonzakura
			$author = User::info($pdo, $post['author']);
			echo <<<_END
				<div style='background-color: grey; padding: 5px; margin: 10px 0'>
				<b>{$title}</b><br>
				{$text}<br>
				<small>{$author['username']}</small>
				</div>
				_END;
		}
	}
	static function newPost($pdo, $title, $text, $author) {
		$stmt = $pdo->prepare('INSERT INTO posts(title, text, author) VALUES(:title, :text, :author)');
		$stmt->execute([
			':title' => $title,
			':text' => $text,
			':author' => $author
		]);
	}
	
	static function deletePost($pdo, $id) {
		
	}
}































