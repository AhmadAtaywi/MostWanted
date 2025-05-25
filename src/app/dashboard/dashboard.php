<?php
require '../../configDataBase/userDataBaseConnection.php';

$userEmail = $_GET['userEmail'];

// Booking Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM RecentBooking");
$stmt->execute();
$bookingCount = (int)$stmt->fetchColumn();

// Users Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM Users");
$stmt->execute();
$userCount = (int)$stmt->fetchColumn();

// Cars Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM Cars");
$stmt->execute();
$carCount = (int)$stmt->fetchColumn();

// fetch User
$stmt = $conn->prepare("SELECT * FROM Users WHERE role_id = 2 AND email = :email");
$stmt->bindParam(':email', $userEmail);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Build bookings query
$query = "SELECT * FROM RecentBookingCars"; 
$params = [];
if (!empty($_GET['dateFrom']) && !empty($_GET['dateTo'])) {
    $query .= " WHERE booking_date BETWEEN :dateFrom AND :dateTo";
    $params[':dateFrom'] = $_GET['dateFrom'];
    $params[':dateTo']   = $_GET['dateTo'] . ' 23:59:59';
}
$sort = $_GET['sortBy'] ?? 'date_desc';
switch ($sort) {
    case 'date_asc':   $query .= " ORDER BY booking_date ASC";  break;
    case 'count_asc':  $query .= " ORDER BY count ASC";         break;
    case 'count_desc': $query .= " ORDER BY count DESC";        break;
    default:           $query .= " ORDER BY booking_date DESC"; break;
}
$stmt = $conn->prepare($query);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_GET['userEmail'])) {
  header("Location: /MostWanted/src/app/home/home.php}");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    rel="stylesheet"
  >
  <style>
    body { background-color: #495057; }
    .sidebar .nav-link.active { background-color: #343a40; }
  </style>
</head>
<body>

  <!-- Mobile navbar to toggle sidebar -->
  <nav class="navbar navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebar"
        aria-controls="sidebar"
        aria-expanded="false"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <span class="navbar-brand">Admin Panel</span>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar: collapse on xs, always show on md+ -->
      <aside
        id="sidebar"
        class="collapse d-md-block bg-dark col-md-3 col-lg-2 vh-100 p-3 sidebar"
      >
        <h3 class="text-center text-white py-3">Admin Panel</h3>
        <nav class="nav flex-column">
          <a
            href="/MostWanted/src/app/dashboard/dashboard.php?userEmail=<?= $userEmail ?>"
            class="nav-link text-white"
          >
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
          </a>
          <a
            href="/MostWanted/src/app/dashboard/cars.php?userEmail=<?= $userEmail ?>"
            class="nav-link text-white"
          >
            <i class="fas fa-car me-2"></i>Cars
          </a>
          <a
            href="/MostWanted/src/app/dashboard/users.php?userEmail=<?= $userEmail ?>"
            class="nav-link text-white"
          >
            <i class="fas fa-users me-2"></i>Users
          </a>
          <a
            href="javascript:void(0)"
            class="nav-link text-white mt-3"
            onclick="backToHomePage()"
          >
            <i class="fas fa-sign-out-alt me-2"></i>Logout
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="col-12 col-md-9 col-lg-10 px-4 py-3">
        <!-- Header navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4 rounded">
          <div class="container-fluid">
            <span class="navbar-brand">
              <?= $user['name'] ?? '' ?>
            </span>
          </div>
        </nav>

        <!-- Stats Cards -->
        <div class="row mb-4" id="statsCards">
          <div class="col-12 col-sm-6 col-lg-4 mb-3">
            <div class="card text-white bg-secondary h-100">
              <div class="card-body">
                <h5 class="card-title">Total Cars</h5>
                <p class="card-text display-6"><?= $carCount ?></p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-4 mb-3">
            <div class="card text-white bg-secondary h-100">
              <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text display-6"><?= $userCount ?></p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-4 mb-3">
            <div class="card text-white bg-secondary h-100">
              <div class="card-body">
                <h5 class="card-title">Total Bookings</h5>
                <p class="card-text display-6"><?= $bookingCount ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
          <h4 class="text-white">Recent Bookings</h4>
          <form class="row gx-2 gy-2" onsubmit="applyFilters(); return false;">
            <div class="col-auto">
              <label for="dateFrom" class="form-label text-white">From</label>
              <input
                type="date"
                id="dateFrom"
                class="form-control"
                value="<?= $_GET['dateFrom'] ?? '' ?>"
              >
            </div>
            <div class="col-auto">
              <label for="dateTo" class="form-label text-white">To</label>
              <input
                type="date"
                id="dateTo"
                class="form-control"
                value="<?= $_GET['dateTo'] ?? '' ?>"
              >
            </div>
            <div class="col-auto align-self-end">
              <button class="btn btn-primary">Apply</button>
            </div>
          </form>
        </div>

        <!-- Responsive Table -->
        <div class="table-responsive shadow-sm rounded">
          <table class="table table-dark table-hover mb-0">
            <thead>
              <tr>
                <th>Refer to Merchant</th>
                <th>Booked By User</th>
                <th>Email</th>
                <th>Car</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Count</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookings as $row): ?>
                <tr>
                  <td><?= $row['user_name'] ?></td>
                  <td><?= $row['full_name'] ?></td>
                  <td class="text-info"><?= $row['email'] ?></td>
                  <td><?= "{$row['car_type']}, {$row['car_model']}" ?></td>
                  <td><?= $row['booking_date'] ?></td>
                  <td>
                    <?php if ($row['available'] === 'true'): ?>
                      <span class="badge bg-success">Yes</span>
                    <?php else: ?>
                      <span class="badge bg-danger">No</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge bg-warning text-dark"><?= (int)$row['count'] ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script>
    function backToHomePage() {
      alert("Successfully signed out");
      window.location.href = "/MostWanted/src/app/home/home.php";
    }
    function applyFilters() {
      const params = new URLSearchParams(window.location.search);
      params.set('userEmail', '<?= addslashes($userEmail) ?>');
      const from = document.getElementById('dateFrom').value;
      const to   = document.getElementById('dateTo').value;
      if (from) params.set('dateFrom', from); else params.delete('dateFrom');
      if (to)   params.set('dateTo', to);     else params.delete('dateTo');
      window.location.search = params.toString();
    }
  </script>
</body>
</html>