<?php
require '../../configDataBase/userDataBaseConnection.php';

$id = $_GET['car_id'];
$id = json_decode($id, true);
$carId = $id['id'];
// Check if the ID is set in the URL
// If not, redirect to the index page
// If the id in URL not found(false) navigate the condition from (false to true) using the not operator (!).
if (!isset($_GET['car_id'])) {
    header("Location: /MostWanted/src/app/home/home.php}");
    exit;
}

$stmt = $conn->prepare("DELETE FROM Cars WHERE car_id = $carId");
$stmt->execute();

header("Location: /MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail={$id['userEmail']}");
exit;
?>
