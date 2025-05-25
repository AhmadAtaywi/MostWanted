<?php
require '../../configDataBase/userDataBaseConnection.php';

$id = $_GET['user_id'];
$id = json_decode($id, true);
$userId = $id['id'];

if (!isset($_GET['user_id'])) {
    header("Location: /MostWanted/src/app/home/home.php}");
    exit;
}

$stmt = $conn->prepare("DELETE FROM Users WHERE user_id = {$id['id']}");
$stmt->execute();

header("Location: /MostWanted/src/app/dashboard/users.php?userEmail={$id['userEmail']}");
exit;
?>