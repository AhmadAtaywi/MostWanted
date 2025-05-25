<?php
require '../../configDataBase/userDataBaseConnection.php';
$clearData = new ClearData();

$id = $_GET['user_id'];
$id = json_decode($id, true);
$carId = $id['id'];
$userEmail = $id['userEmail'];

$stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = {$id['id']}");
$stmt->execute();
$user = $stmt->fetch();

?>

<?php
if (!isset($_GET['user_id'])) {
    header("Location: /MostWanted/src/app/home/home.php}");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUserName = $clearData->cleanInput($_POST['name']);
    $newUserEmail = $clearData->cleanInput($_POST['email']);
    $userPhone = $clearData->cleanInput($_POST['phone']);
    $userPassword = $clearData->cleanInput($_POST['password']);
    $userMerchantStatus = $clearData->cleanInput($_POST['merchant_status']);

    try {
        $stmt = $conn->prepare("UPDATE Users SET 
            name = :name,
            email = :email,
            phone = :phone,
            password = :password,
            merchant_status = :merchant_status                        
            WHERE user_id = {$id['id']} ");

        $stmt->bindParam(':name', $newUserName);
        $stmt->bindParam(':email', $newUserEmail);
        $stmt->bindParam(':phone', $userPhone);
        $stmt->bindParam(':password', $userPassword);
        $stmt->bindParam(':merchant_status', $userMerchantStatus);
        $stmt->execute();

        header("Location: /MostWanted/src/app/dashboard/users.php?userEmail={$id['userEmail']}");
        exit;
    } catch (PDOException $e) {
        echo "<script>
                    console.log('Error: " . $e->getMessage() . "');
                </script>";
    }
}

if (!$user) {
    header("Location: /MostWanted/src/app/dashboard/cars.php?userEmail={$id['userEmail']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Edit Cars</title>
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
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?= $user['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="<?= $user['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="<?= $user['phone'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" name="password" value="<?= $user['password'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Merchant Status</label>
                <select class="form-select" id="merchant_status" name="merchant_status">
                    <option>false</option>
                    <option>true</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/MostWanted/src/app/dashboard/user.php?userEmail=<?= $userEmail ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>