<?php
require '../../configDataBase/userDataBaseConnection.php';
$clearData = new ClearData();
?>

<?php
$userEmail = $_GET['bookingData']; 

try {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->bindParam(':email', $userEmail);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>
                    console.log('Error: " . $e->getMessage() . "');
                </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $clearData->cleanInput($_POST['name']);
    $newUserEmail = $clearData->cleanInput($_POST['email']);
    $userPhone = $clearData->cleanInput($_POST['phone']);
    $userPassword = $clearData->cleanInput($_POST['password']);

    try {
        // Hash the password before storing it if it's not empty
        $passwordToStore = $userPassword;
        if (!empty($userPassword)) {
            $passwordToStore = password_hash($userPassword, PASSWORD_DEFAULT);
        } else {
            // If password field is empty, keep the existing hashed password
            $passwordToStore = $user['password'];
        }

        // Update Users table
        $stmt = $conn->prepare("UPDATE Users SET 
            name = :name,
            email = :newEmail,
            phone = :phone,
            password = :password,
            merchant_status = 'false'
            WHERE email = :email");

        $stmt->bindParam(':name', $userName);
        $stmt->bindParam(':newEmail', $newUserEmail);
        $stmt->bindParam(':email', $userEmail);
        $stmt->bindParam(':phone', $userPhone);
        $stmt->bindParam(':password', $passwordToStore);
        $stmt->execute();

        echo "<script>
                    setTimeout(() => {
                                window.location.href = '/MostWanted/src/app/home/home.php';
                            }, 50);;
                </script>";
    } catch (PDOException $e) {
        echo "<script>
                    console.log('Error: " . $e->getMessage() . "');
                </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="./profile.css">


    <style>
        body {
            background-color: #1f2029;
        }

        .form-section {
            background: #3f3f52;
            padding: 20px;
            border-radius: 10px;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 9999;
        }

        .avatar-preview {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ced4da;
            transition: 0.3s;
        }

        .avatar-preview:hover {
            transform: scale(1.03);
            border-color: #0d6efd;
        }

        label {
            color: #ffeba7;
        }

        input {
            background-color: #5e5f7b;
        }
    </style>
</head>

<body>
    <div id="nav-placeholder"></div>

    <div class="container123">

        <h2 class="text-center mb-4" style="color: #ffeba7;">Profile</h2>
        <div class="form__container123">
            <div class="col-md-8">
                <div class="form-section">
                    <form method="post" id="profile-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">phone number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <div class="button__container">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-outline-danger float-end cancel" onclick="backToHomePage()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container">
        <div class="toast align-items-center text-white bg-success border-0" id="success-toast" role="alert">
            <div class="d-flex">
                <div class="toast-body">Data saved successfully!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <div id="footer-placeholder"></div>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/jquery-migrate-3.0.1.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.easing.1.3.js"></script>
    <script src="../../js/jquery.waypoints.min.js"></script>
    <script src="../../js/jquery.stellar.min.js"></script>
    <script src="../../js/owl.carousel.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/jquery.animateNumber.min.js"></script>
    <script src="../../js/bootstrap-datepicker.js"></script>
    <script src="../../js/jquery.timepicker.min.js"></script>
    <script src="../../js/scrollax.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="../partials/nav.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="profile.js"></script>
    <script>
        function backToHomePage() {
            alert("successfully Sign out");
            window.location.href = "/MostWanted/src/app/home/home.php";
        }
    </script>
    <script src="profile.js"></script>
    <script src="../partials/nav.js"></script>

    <script>
        fetch('../partials/nav.html')
            .then(r => r.text())
            .then(html => {
                document.getElementById('nav-placeholder').innerHTML = html;
                initNav();
            });
    </script>

    <script>
        fetch('../partials/footer/footer.html')
            .then(r => r.text())
            .then(html => {
                document.getElementById('footer-placeholder').innerHTML = html;
            })
            .catch(err => console.error('Footer load failed:', err));
    </script>
</body>

</html>