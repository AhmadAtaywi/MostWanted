<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
// fetch existed city names from the database
// and store them in local storage
// to use them in the cars.js file
// to filter the cars by city name
// and to show the cars in the selected city
$stmt = $conn->prepare("SELECT city_name FROM City"); // CityNames table as a view table
$stmt->execute();
// Set the fetch mode ensures that each row of the result is
// returned as an associative array, where the column names.
// are the keys of the array and the values are the corresponding
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$cities = $stmt->fetchAll();
$uniqueCities = [];
foreach ($cities as $city) {
    $cityName = $city['city_name'];
    if (!in_array($cityName, $uniqueCities)) {
        $uniqueCities[] = $cityName;
    }
}
$cityCount = count($uniqueCities);
$cityNames = json_encode($uniqueCities);
$cityCount = json_encode($cityCount);
echo "<script>var cities = $cityNames; var cityCount = $cityCount;</script>";
?>

<?php
$stmt = $conn->prepare("SELECT * FROM FullCarDetails");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$carsDetails = $stmt->fetchAll();
$carsDetails = json_encode($carsDetails, JSON_HEX_APOS | JSON_HEX_QUOT);
echo "<script>var carsDetails = " . $carsDetails . ";</script>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cars</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../../css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="../../css/animate.css">

    <link rel="stylesheet" href="../../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../../css/magnific-popup.css">

    <link rel="stylesheet" href="../../css/aos.css">

    <link rel="stylesheet" href="../../css/ionicons.min.css">

    <link rel="stylesheet" href="../../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../../css/jquery.timepicker.css">

    <link rel="stylesheet" href="../../css/flaticon.css">
    <link rel="stylesheet" href="../../css/icomoon.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="./cars.css">
    <style>
        .col-md-4 {
            background-color: #5e5f7b;
            border-radius: 20px;
        }

        .bg-light {
            background: #1f2029 !important;
        }

        h3 {
            color: #ffeba7;
        }

        .btn.btn-secondary {
            background: #ffeba7 !important;
            border: 1px solid #ffeba7 !important;
            color: #3f3f52 !important;
        }

        .car-wrap .text {
            border-top: none;
            padding: 20px 30px 30px;
            background-color: #3f3f52;
        }

        .car-wrap .text span.cat {
            font-weight: 400;
            color: #ffd893;
            display: block;
            margin-bottom: 0px;
        }

        .car-wrap .text .price span {
            font-size: 12px;
            font-weight: 400;
            color: #ffd893;
        }

        .justify-content-between {
            -webkit-box-pack: start !important;
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }

        .car-wrap .text {
            width: 300px;
        }
    </style>
</head>

<body>
    <div id="nav-placeholder"></div>

    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../../images/about-us.jpeg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <p class="breadcrumbs"><span class="mr-2"><a href="/MostWanted/src/app/home/home.php">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span><a href="/MostWanted/src/app/cars/cars.php">Cars <i
                                    class="ion-ios-arrow-forward"></i></a></span></p>
                    <h1 class="mb-3 bread" style="color:  #ffeba7;">Choose Your Region</h1>
                </div>
            </div>
        </div>
    </section>


    <section class="ftco-section bg-light">
        <div class="container col-12">
            <div class="row" id="car-list"></div>
        </div>
    </section>

    <div id="footer-placeholder"></div>

    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" />
        </svg></div>

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
    <script src="./cars.js"></script>
    <script src="../../js/main.js"></script>

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