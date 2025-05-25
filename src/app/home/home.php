<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
$stmt = $conn->prepare("SELECT city_name FROM City");
$stmt->execute();
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
  <title>MostWanted</title>
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
  <link rel="stylesheet" href="./home.css">
  <style>
    .nav-button {
      font-size: 45px;
    }
  </style>
</head>

<body>
  <div id="nav-placeholder"></div>

  <div class="hero-wrap ftco-degree-bg" style="background-image: url('../../images/background_img.jpg'); background-size: cover; background-position: center; height: 100vh; min-height: 600px; position: relative;">
    <div class="overlay" style="background-color: rgba(0, 0, 0, 0.5); position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
    <div class="container" style="position: relative; z-index: 1; height: 100%;">
      <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center" style="height: 100%;">
        <div class="col-lg-8 ftco-animate text-center">
          <div class="text w-100 mb-md-5 pb-md-5">
            <h1 class="mb-4" style="color: white; font-size: 2.8rem; font-weight: 700; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">Fast &amp; Easy Way To Rent A Car</h1>
            <p style="font-size: 1.2rem; color: #d8e2dc; margin-bottom: 2rem;">Wide Selection of Cars: From economy to luxury, find the perfect ride.</p>
            <div class="d-flex flex-column align-items-center">
              <a href="/MostWanted/src/app/cars/cars.php" class="btn btn-primary py-3 px-4" style="background-color: #fad643; border: none; color: #1f2029; font-weight: 600; margin-bottom: 1rem;">Easy steps for renting a car</a>
              <a href="/MostWanted/src/app/login/merchant-login.php" class="btn btn-outline-light py-3 px-4" style="border: 2px solid #fad643; color: #fad643; font-weight: 600;">Sign Up as Branch dealer</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="ftco-section ftco-no-pt bg-light" style="background-color:  #1f2029;">
    <div class="container col-12">
      <div class="row justify-content-center mb-5">
        <div class="col-md-12 heading-section text-center ftco-animate">
          <span class="subheading" style="color:  #d8e2dc;">Where We Operate</span>
          <h2 class="mb-2" style="color:  #ffeba7;">Choose Your Region</h2>
        </div>
      </div>
      <div class="row" id="region-list">
      </div>
    </div>
  </section>

  <section class="ftco-section bg-light">
    <div class="container">
      <div class="row" id="car-list"></div>
    </div>
  </section>



  <section class="ftco-section ftco-about">
    <div class="container about-container">
      <div class="row no-gutters">
        <div class="col-md-6 p-md-5 img img-2 d-flex justify-content-center align-items-center" style="background-image: url(../../images/background_img.jpg); width: 400px;
          height: 400px; border-radius: 50%;object-fit: cover;">
        </div>
        <div class="col-md-6 wrap-about ftco-animate">
          <div class="heading-section heading-section-white pl-md-5">
            <span class="subheading" style="color:  #ffeba7;">Our Story &amp; Vision</span>
            <h2 class="mb-4" style="color:fad643"><em>MostWanted</em></h2>
            <p style="color:  #ffeba7;">we are passionate about providing a seamless, reliable, and comfortable car
              rental experience.</p>
            <p style="color:  #ffeba7;"> Founded with the goal of meeting the needs of all types of customers, we offer
              a wide range of vehicles—from luxury and economy cars to SUVs—at competitive prices and with top-quality
              service. We believe that customer satisfaction comes first, which is why we offer flexible booking
              options, continuous support, and a fleet of modern, fully equipped vehicles. With us, your journey begins
              with safety and peace of mind.</p>

          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-counter ftco-section img bg-light" id="section-counter">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
          <div class="block-18">
            <div class="text text-border d-flex align-items-center">
              <strong class="number" data-number="60">0</strong>
              <span>Year <br>Experienced</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
          <div class="block-18">
            <div class="text text-border d-flex align-items-center">
              <strong class="number" data-number="1090">0</strong>
              <span>Total <br>Cars</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
          <div class="block-18">
            <div class="text text-border d-flex align-items-center">
              <strong class="number" data-number="2590">0</strong>
              <span>Happy <br>Customers</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
          <div class="block-18">
            <div class="text d-flex align-items-center">
              <strong class="number" data-number="67">0</strong>
              <span>Total <br>Branches</span>
            </div>
          </div>
        </div>
      </div>
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
  <script src="../../js/main.js"></script>

  <script src="./home.js"></script>
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