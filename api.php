<?php

include_once '/opt/lampp/htdocs/header.php';
$data_from_js = json_decode(file_get_contents("php://input"), true);

if (empty(trim($data_from_js['title'])) || empty(trim($data_from_js['text'])) || empty(trim($data_from_js['author']))) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'success' => false,
		'message' => 'ERROR: empty inputs.',
		'error' => true
	]);
	exit;
}

try {
	$stmt = $pdo->prepare('INSERT INTO posts(title, text, author) VALUES(:title, :text, :author)');
	$stmt->execute([
		':title' => $data_from_js['title'],
		':text' => $data_from_js['text'],
		':author' => $data_from_js['author']
	]);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'success' => true,
		'message' => 'Data is correct send to Database.',
		'error' => false
	]);
} catch(PDOException $error) {
	echo json_encode([
		'success' => false,
		'message' => 'ERROR. Data is not send to Database.',
		'error' => $error
	]);
}

