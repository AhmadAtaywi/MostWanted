<?php
require '../../configDataBase/userDataBaseConnection.php';
$clearData = new ClearData();
?>

<?php
// Sign In functionality form for user login
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['signInEmail']) && isset($_GET['signInPass'])) {
    $userEmailSignIn = $clearData->cleanInput($_GET['signInEmail']);
    $passwordSignIn = $clearData->cleanInput($_GET['signInPass']);
    $successSignIn = false;
    $emailErrorSignIn = $passwordErrorSignIn = "";


    // Validate user email input
    if (empty($userEmailSignIn)) {
        $emailErrorSignIn = "Email is required.";
    } else
        // Sanitize email input sanitization: means removing all illegal characters from the email address
        $userEmailSignIn = filter_var($userEmailSignIn, FILTER_SANITIZE_EMAIL);
    if (!filter_var($userEmailSignIn, FILTER_VALIDATE_EMAIL)) {
        $emailErrorSignIn = "Invalid email format.";
    }

    // Validate user password input
    if (empty($passwordSignIn)) {
        $passwordErrorSignIn = "Password is required.";
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $userEmailSignIn);
        $stmt->bindParam(':password', $passwordSignIn);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = array();

        if (!empty($user)) {
            $successSignIn = true;
            if ($successSignIn) {
                // store user data in an array and encode it as JSON                
                $data['name'] = $user['name'];
                $data['email'] = $user['email'];
                $data['phone'] = $user['phone'];
                //header('Content-Type: application/json');                
                echo json_encode($data); // Output user data as JSON            

                // Store user information in local storage
                echo "<script>
                        localStorage.setItem('userName', '" . $user['name'] . "');
                        localStorage.setItem('userEmail', '" . $user['email'] . "');
                        localStorage.setItem('userPhone', '" . $user['phone'] . "');                        
                    </script>";

                // Redirect to the home page
                header("Location: /MostWanted/src/app/home/home.php?user_email=" . $user['email']);
                exit();
            }
        } else {
            echo "No user found with the provided email and password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
//Sign Up functionality form for user registration
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['signUpName']) && isset($_GET['signUpEmail']) && isset($_GET['signUpPhoneNumber']) && isset($_GET['signUpPass'])) {
    $userNameSignUp = $clearData->cleanInput($_GET['signUpName']);
    $userEmailSignUp = $clearData->cleanInput($_GET['signUpEmail']);
    $userPhoneNumberSignUp = $clearData->cleanInput($_GET['signUpPhoneNumber']);
    $userPasswordSignUp = $clearData->cleanInput($_GET['signUpPass']);
    $successSignUp = false;
    $nameErrorSignUp = $emailErrorSignUp = $phoneNumberErrorSignUp = $passwordErrorSignUp = "";

    // Validate user name input
    if (empty($userNameSignUp)) {
        $nameErrorSignUp = "Name is required.";
    }

    // Validate user email input
    if (empty($userEmailSignUp)) {
        $emailErrorSignUp = "Email is required.";
    } else
        // Sanitize email input sanitization: means removing all illegal characters from the email address
        $userEmailSignUp = filter_var($userEmailSignUp, FILTER_SANITIZE_EMAIL);
    if (!filter_var($userEmailSignUp, FILTER_VALIDATE_EMAIL)) {
        $emailErrorSignUp = "Invalid email format.";
    }

    // Validate user phone number input
    if (empty($userPhoneNumberSignUp)) {
        $phoneNumberErrorSignUp = "Phone number is required.";
    }

    // Validate user password input
    if (empty($userPasswordSignUp)) {
        $passwordErrorSignUp = "Password is required.";
    }

    try {
        // Check if the user already exists
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $userEmailSignUp);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['email'] == $userEmailSignUp) {
            echo "User already exists with the provided email and password.";
        } else {
            $stmt = $conn->prepare("INSERT INTO Users (name, email, phone, password, role_id) VALUES (:name, :email, :phone, :password, 1)");
            $stmt->bindParam(':name', $userNameSignUp);
            $stmt->bindParam(':email', $userEmailSignUp);
            $stmt->bindParam(':phone', $userPhoneNumberSignUp);
            $stmt->bindParam(':password', $userPasswordSignUp);
            $stmt->execute();
            echo "User registered successfully.";
            $successSignUp = true;
            if ($successSignUp) {
                // $newUserId = $conn->lastInsertId(); // Get the ID of the newly inserted user
                header("Location: /MostWanted/src/app/home/home.php?user_name=" . $userNameSignUp);
                exit();
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MostWanted SignIn</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="./login.css">
</head>

<body>

    <a href="/MostWanted/src/app/home/home.php" class="logo">
        <img src="../../images/logo1.png" alt="">
    </a>

    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <h6 class="mb-0 pb-3"><span>Sign In </span><span>Sign Up</span></h6>
                        <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Sign In</h4>
                                            <form method="get">
                                                <div class="form-group">
                                                    <input type="email" name="signInEmail" class="form-style" placeholder="email" id="logemail"
                                                        autocomplete="off"><span class="errorMessage"><?php if (!empty($emailErrorSignIn)) {
                                                                                                            echo $emailErrorSignIn;
                                                                                                        } ?></span>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="signInPass" class="form-style" placeholder="password"
                                                        id="logpass" autocomplete="off"><span class="errorMessage"><?php if (!empty($passwordErrorSignIn)) {
                                                                                                                        echo $passwordErrorSignIn;
                                                                                                                    } ?></span>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" id="loginBtn" class="btn mt-4">Sign In</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Sign Up</h4>
                                            <form method="get">
                                                <div class="form-group">
                                                    <input type="text" name="signUpName" class="form-style" placeholder="full name" id="logname"
                                                        autocomplete="off"><span class="errorMessage"><?php if (!empty($nameErrorSignUp)) {
                                                                                                            echo $nameErrorSignUp;
                                                                                                        } ?></span>
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="email" name="signUpEmail" class="form-style" placeholder="email" id="logemail"
                                                        autocomplete="off"><span class="errorMessage"><?php if (!empty($emailErrorSignUp)) {
                                                                                                            echo $emailErrorSignUp;
                                                                                                        } ?></span>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="text" name="signUpPhoneNumber" class="form-style" placeholder="phone number" id="phoneNumber" autocomplete="off"><span class="errorMessage"><?php if (!empty($phoneNumberErrorSignUp)) {
                                                                                                                                                                                                                    echo $phoneNumberErrorSignUp;
                                                                                                                                                                                                                } ?></span>
                                                    <i class="input-icon uil uil-phone"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="signUpPass" class="form-style" placeholder="password"
                                                        id="logpass" autocomplete="off"><span class="errorMessage"><?php if (!empty($passwordErrorSignUp)) {
                                                                                                                        echo $passwordErrorSignUp;
                                                                                                                    } ?></span>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" id="signupBtn" class="btn mt-4">Sign Up</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Oops!</h5>
                </div>
                <div class="modal-body">
                    <p id="errorMsg" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- <script src="./login.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" ...></script>
</body>

</html>