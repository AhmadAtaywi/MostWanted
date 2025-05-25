<?php
require '../../configDataBase/userDataBaseConnection.php';

$id = $_GET['car_id'];
$id = json_decode($id, true);
$carId = $id['id'];

if (!isset($_GET['car_id'])) {
    header("Location: /MostWanted/src/app/home/home.php}");
    exit;
}

$stmt = $conn->prepare("DELETE FROM Cars WHERE car_id = $carId");
$stmt->execute();

header("Location: /MostWanted/src/app/dashboard/cars.php?userEmail={$id['userEmail']}");
exit;
?>