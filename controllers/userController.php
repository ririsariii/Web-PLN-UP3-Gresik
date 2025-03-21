<?php
require_once "../models/UsersModel.php";

$userModel = new UsersModel($pdo);
$users = $userModel->getAllUsers();

header('Content-Type: application/json');
echo json_encode($users);
?>
