<?php
require '../../configDataBase/userDataBaseConnection.php';
$clearData = new ClearData();

$id = $_GET['car_id'];
$id = json_decode($id, true);
$carId = $id['id'];
$userEmail = $id['userEmail'];

$stmt = $conn->prepare("SELECT * FROM Cars WHERE car_id = {$id['id']}");
$stmt->execute();
$car = $stmt->fetch(); // fetch: to get a single row

$stmt = $conn->prepare("SELECT * FROM CarDetails WHERE car_id = {$id['id']}");
$stmt->execute();
$carDetails = $stmt->fetch(); // fetch: to get a single row

?>

<?php
// Check if the ID is set in the URL
if (!isset($_GET['car_id'])) {
    header("Location: /MostWanted/src/app/home/home.php}");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $carType = $clearData->cleanInput($_POST['car_type']);
    $carModel = $clearData->cleanInput($_POST['car_model']);
    $modelYear = $clearData->cleanInput($_POST['model_year']);
    $plateNumber = $clearData->cleanInput($_POST['plate_number']);
    $price = $clearData->cleanInput($_POST['price']);
    $availableTime = $clearData->cleanInput($_POST['available_time']);
    $available = $clearData->cleanInput($_POST['available']);
    $cityName = $clearData->cleanInput($_POST['city_name']);
    $description = $clearData->cleanInput($_POST['description']);
    $color = $clearData->cleanInput($_POST['color']);
    $fuel = $clearData->cleanInput($_POST['fuel']);
    $seats = $clearData->cleanInput($_POST['seats']);
    $urlImage = $clearData->cleanInput($_POST['img']);

    try {
        // Update Cars table
        $stmt = $conn->prepare("UPDATE Cars SET 
            user_name = :user_name,
            car_type = :car_type,
            car_model = :car_model,
            model_year = :model_year,
            plate_number = :plate_number,
            price = :price,
            available_time = :available_time,
            available = :available,
            city_name = :city_name
            WHERE car_id = {$carDetails['car_id']}");

        $stmt->bindParam(':user_name', $userEmail);
        $stmt->bindParam(':car_type', $carType);
        $stmt->bindParam(':car_model', $carModel);
        $stmt->bindParam(':model_year', $modelYear);
        $stmt->bindParam(':plate_number', $plateNumber);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':available_time', $availableTime);
        $stmt->bindParam(':available', $available);
        $stmt->bindParam(':city_name', $cityName);
        $stmt->execute();

        // Update CarDetails table
        $stmt = $conn->prepare("UPDATE CarDetails SET 
            description = :description,
            color = :color,
            fuel = :fuel,
            seats = :seats,
            img = :img            
            WHERE car_details_id = {$carDetails['car_details_id']} ");

        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':fuel', $fuel);
        $stmt->bindParam(':seats', $seats);
        $stmt->bindParam(':img', $urlImage);
        $stmt->execute();

        header("Location: /MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail={$id['userEmail']}");
        exit;
    } catch (PDOException $e) {
        echo "<script>
                    console.log('Error: " . $e->getMessage() . "');
                </script>";
    }
}

if (!$car) {
    header("Location: /MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail={$id['userEmail']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Edit Cars</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #495057;
    }
</style>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Car Details Edit</h1>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Car Type</label>
                <input type="text" class="form-control" name="car_type" value="<?= $car['car_type'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Car Model</label>
                <input type="text" class="form-control" name="car_model" value="<?= $car['car_model'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mode Year</label>
                <input type="text" class="form-control" name="model_year" value="<?= $car['model_year'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Plate Number</label>
                <input type="text" class="form-control" name="plate_number" value="<?= $car['plate_number'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" name="price" value="<?= $car['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available Time</label>
                <input type="date" step="0.01" class="form-control" name="available_time" value="<?= $car['available_time'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Available</label>
                <input type="text" step="0.01" class="form-control" name="available" value="<?= $car['available'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">City Name</label>
                <input type="text" step="0.01" class="form-control" name="city_name" value="<?= $car['city_name'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" step="0.01" class="form-control" name="description" value="<?= $carDetails['description'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="text" step="0.01" class="form-control" name="img" value="<?= $carDetails['img'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Color</label>
                <input type="text" step="0.01" class="form-control" name="color" value="<?= $carDetails['color'] ?>" required>
            </div>
            <div class="mb-3">
                <select class="form-select" id="fuel" name="fuel">
                    <option>Hybrid</option>
                    <option>Diesel</option>
                    <option>Electric</option>
                    <option>Hybrid, Electric</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Seats</label>
                <input type="number" step="0.01" class="form-control" name="seats" value="<?= $carDetails['seats'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail=<?= $userEmail ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>