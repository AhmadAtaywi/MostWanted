<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
$bookingData = $_GET['bookingData'];
$bookingData = json_decode($bookingData, true);

try {
  $stmt = $conn->prepare("INSERT INTO Bookings (car_id, car_type, car_model, full_name, email, phone, booking_date, pickup_type, pickup_location, service, price, driving_license) 
                                  VALUES (:carId, :carType, :carModel, :fullName, :email, :phone, :date, :pickupType, :pickupLocation, :service, :price, :drivingLicense)");
  $stmt->bindParam(':carId', $bookingData['carId']);
  $stmt->bindParam(':carType', $bookingData['carType']);
  $stmt->bindParam(':carModel', $bookingData['carModel']);
  $stmt->bindParam(':fullName', $bookingData['fullName']);
  $stmt->bindParam(':email', $bookingData['email']);
  $stmt->bindParam(':phone', $bookingData['phone']);
  $stmt->bindParam(':date', date('Y-m-d h:m:s'));
  $stmt->bindParam(':pickupType', $bookingData['pickupType']);
  $stmt->bindParam(':pickupLocation', $bookingData['pickupLocation']);
  $stmt->bindParam(':service', $bookingData['service']);
  $stmt->bindParam(':price', $bookingData['price']);
  $stmt->bindParam(':drivingLicense', $bookingData['drivingLicense']);
  $stmt->execute();

  $stmt = $conn->prepare("UPDATE Cars SET available = 'false', count = count + 1 WHERE car_id = :carId");
  $stmt->bindParam(':carId', $bookingData['carId']);
  $stmt->execute();
} catch (PDOException $e) {
  echo "<script>console.log('Error: ' . '{$e->getMessage()}');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Car Details</title>
  <link rel="stylesheet" href="../../css/bootstrap.min.css">

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
  <link rel="stylesheet" href="./car-details.css">
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
                  class="ion-ios-arrow-forward"></i></a></span> <span>Car details <i
                class="ion-ios-arrow-forward"></i></span></p>
          <h1 class="mb-3 bread">Car Details</h1>
          <p class="mb-0"><a href="/MostWanted/src/app/cars/cars.php" class="btn btn-light">Choose Another Region</a></p>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section ftco-car-details">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="car-details">
            <div id="car-img" class="img rounded"></div>
            <div class="text text-center">
              <span id="car-city" class="subheading"></span>
              <h2 id="car-name"></h2>
              <p id="car-price" class="price"></p>
              <p id="car-description" class="mb-4"></p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md d-flex ftco-animate">
          <div class="media block-6 services">
            <div class="media-body py-md-4">
              <div class="d-flex mb-3 align-items-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-pistons"></span>
                </div>
                <div class="text">
                  <h3 class="heading mb-0 pl-3">
                    Color
                    <span id="car-color"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md d-flex ftco-animate">
          <div class="media block-6 services">
            <div class="media-body py-md-4">
              <div class="d-flex mb-3 align-items-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-diesel"></span>
                </div>
                <div class="text">
                  <h3 class="heading mb-0 pl-3">
                    Fuel
                    <span id="car-fuel"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md d-flex ftco-animate">
          <div class="media block-6 services">
            <div class="media-body py-md-4">
              <div class="d-flex mb-3 align-items-center">
                <div class="icon d-flex align-items-center justify-content-center">
                  <span class="flaticon-car-seat"></span>
                </div>
                <div class="text">
                  <h3 class="heading mb-0 pl-3">
                    Seats
                    <span id="car-seats"></span>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 pills">
          <div class="test d-flex justify-content-end">
            <button id="bookNowBtn" type="button" class="btn btn-primary py-2 mr-1" data-toggle="modal"
              data-target="#bookingModal">
              Book now
            </button>

          </div>
        </div>
      </div>
  </section>

  <script src="../partials/nav.js"></script>
  <div id="footer-placeholder"></div>

  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="./car-details.js"></script>

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

  <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="get" id="bookingForm" class="modal-content" style="background-color: #5e5f7b;color:#ffeba7;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="fullName">Full name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
          </div>
          <div class="form-group">
            <label for="emailAddr">Email address</label>
            <input type="email" class="form-control" id="emailAddr" name="emailAddr" required>
          </div>
          <div class="form-group">
            <label for="phoneNum">Phone number</label>
            <input type="tel" class="form-control" id="phoneNum" name="phoneNum" required>
          </div>

          <div class="form-group">
            <label for="availableDate">Available Date</label>
            <select id="availableDate" name="availableDate" class="form-control" required>
            </select>
          </div>

          <fieldset class="form-group">
            <legend>Pickup Option</legend>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="pickupType" id="pickupStore" value="store" checked>
              <label class="form-check-label" for="pickupStore">Pick up at Store</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="pickupType" id="pickupDelivery" value="delivery">
              <label class="form-check-label" for="pickupDelivery">Deliver to my Location</label>
            </div>
          </fieldset>

          <div class="form-group" id="pickupLocationGroup" style="display:none">
            <label for="pickupLocation">Pickup Location</label>
            <input type="text" class="form-control" id="pickupLocation">
          </div>

          <div class="form-group" id="priceGroup" style="display:none">
            <label for="computedPrice">Total Price</label>
            <input type="text" class="form-control" id="computedPrice" name="computedPrice" readonly>
          </div>

          <fieldset class="form-group">
            <legend>Choose Your Service</legend>
            <div>
              <div class="form-check"><input class="form-check-input" type="radio" name="service" id="s1" value="WeddingCeremony"
                  required><label class="form-check-label" for="Wedding Ceremony">Wedding Ceremony</label></div>
              <div class="form-check"><input class="form-check-input" type="radio" name="service" id="s2"
                  value="CityTransfer"><label class="form-check-label" for="City Transfer">City Transfer</label></div>
              <div class="form-check"><input class="form-check-input" type="radio" name="service" id="s3"
                  value="AirportTransfer"><label class="form-check-label" for="Airport Transfer">Airport Transfer</label></div>
              <div class="form-check"><input class="form-check-input" type="radio" name="service" id="s4"
                  value="WholeCityTour"><label class="form-check-label" for="Whole City Tour">Whole City Tour</label></div>
              <div class="form-check"><input class="form-check-input" type="radio" name="service" id="s5"
                  value="RentACar"><label class="form-check-label" for="Rent A Car">Rent A Car</label></div>
            </div>
          </fieldset>

          <div class="form-group" id="uploadGroup" style="display:none">
            <label for="uploadImg">Attach Your Driving license</label>
            <input type="file" class="form-control-file" id="uploadImg" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="bookingCancelBtn" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Booking</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="thankYouModal" tabindex="-1" role="dialog" aria-labelledby="thankYouLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="thankYouLabel">Thank You!</h5>
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Thanks for the booking, our team will contact you soon, You be redirect to booking page after 5sec
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


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