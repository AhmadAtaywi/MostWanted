<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php

// Booking Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM RecentBooking");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<script>var bookingCount = {$user['COUNT(*)']};</script>";

// Users Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM Users");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<script>var userCount = {$user['COUNT(*)']};</script>";

// Cars Count
$stmt = $conn->prepare("SELECT COUNT(*) FROM Cars");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<script>var carCount = {$user['COUNT(*)']};</script>";

// fetch User
$userEmail = $_GET['userEmail'];
$stmt = $conn->prepare("SELECT * FROM Users where role_id = 2 And email = :email ");
$stmt->bindParam(':email', $userEmail);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<?php
// Get all bookings initially
$query = "SELECT * FROM RecentBookingCars";
$params = [];

// Check if date filters are provided
if (
    isset($_GET['dateFrom']) && !empty($_GET['dateFrom']) &&
    isset($_GET['dateTo']) && !empty($_GET['dateTo'])
) {
    $dateFrom = $_GET['dateFrom'];
    $dateTo = $_GET['dateTo'];

    $query .= " WHERE booking_date BETWEEN :dateFrom AND :dateTo";
    $params[':dateFrom'] = $dateFrom;
    $params[':dateTo'] = $dateTo . ' 23:59:59'; // Include entire end day
}

// Add sorting
$sortOption = $_GET['sortBy'] ?? 'date_desc';
switch ($sortOption) {
    case 'date_asc':
        $query .= " ORDER BY booking_date ASC";
        break;
    case 'count_asc':
        $query .= " ORDER BY count ASC";
        break;
    case 'count_desc':
        $query .= " ORDER BY count DESC";
        break;
    default: // date_desc
        $query .= " ORDER BY booking_date DESC";
}

// Execute the query
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard-style.css">
    <style>
        body {
            background-color: #495057;
        }
    </style>
</head>

<body>
    <!-- Sidebar (Same Sidebar as the Dashboard) -->
    <div class="sidebar bg-dark vh-100 p-3 position-fixed">
        <h3 class="text-center py-4 text-white">Admin Panel</h3>
        <nav class="nav flex-column">
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/dashboard.php?userEmail=<?= $userEmail ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/cars.php?userEmail=<?= $userEmail ?>"><i class="fas fa-car"></i> Cars</a>
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/users.php?userEmail=<?= $userEmail ?>"><i class="fas fa-users"></i> Users</a>
            <a class="nav-link text-white" onclick="backToHomePage()"><i class="fas fa-users"></i>Logout</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="content" style="padding:20px;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4">
            <a class="navbar-brand" style='color:#FFB22C'>Admin Dashboard: <?= $user['name']; ?></a>
        </nav>

        <div class="row mb-4" id="statsCards"></div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Recent Bookings</h3>
            <div class="d-flex gap-3">
                <div>
                    <label class="form-label me-2 text-white">From:</label>
                    <input type="date" id="dateFrom" class="form-control d-inline-block w-auto">
                </div>
                <div>
                    <label class="form-label me-2 text-white">To:</label>
                    <input type="date" id="dateTo" class="form-control d-inline-block w-auto">
                </div>
                <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>

        <table class="table table-dark table-hover table-bordered shadow-sm">
            <thead>
                <tr>
                    <th>Refer to Merchant</th>
                    <th>Booked By User</th>
                    <th>User Email</th>
                    <th>cars</th>
                    <th>Booking Date</th>
                    <th>Available Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($bookings as $row) {
                    if ($row['available'] == 'true') {
                        $status = 'Yse';
                    } else {
                        $status = 'No';
                    }
                    echo "<tr>";
                    echo "<td>{$row['user_name']}</td>";
                    echo "<td>{$row['full_name']}</td>";
                    echo "<td style = 'color:#129990'>{$row['email']}</td>";
                    echo "<td>{$row['car_type']}, {$row['car_model']}</td>";
                    echo "<td>{$row['booking_date']}</td>";
                    echo "<td style = 'color:#FFB22C'>$status</td>";
                    echo "<td style = 'color:#C40C0C'>{$row['count']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        function renderStats() {
            const statsRow = document.getElementById('statsCards');
            statsRow.innerHTML = '';
            const stats = [{
                    title: 'Total Cars',
                    value: carCount
                },
                {
                    title: 'Total Users',
                    value: userCount
                },
                {
                    title: 'Total Bookings',
                    value: bookingCount
                }
            ];
            stats.forEach(s => {
                const col = document.createElement('div');
                col.className = 'col-md-4';
                col.innerHTML = `
          <div class="card card-stat shadow-sm mb-3">
            <div class="card-body bg-secondary text-white">
              <h5 class="card-title">${s.title}</h5>
              <p class="card-text">${s.value}</p>
            </div>
          </div>
        `;
                statsRow.appendChild(col);
            });
        }

        function backToHomePage() {
            alert("successfully Sign out");
            window.location.href = "/MostWanted/src/app/home/home.php";
        }

        renderStats();

        function applyFilters() {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;

            // Reload the page with new filter parameters
            window.location.href = `?userEmail=<?= $userEmail ?>&dateFrom=${dateFrom}&dateTo=${dateTo}`;
        }

        // Initialize date inputs with current values from URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('dateFrom')) {
                document.getElementById('dateFrom').value = urlParams.get('dateFrom');
            }
            if (urlParams.has('dateTo')) {
                document.getElementById('dateTo').value = urlParams.get('dateTo');
            }
        });
    </script>
</body>

</html>