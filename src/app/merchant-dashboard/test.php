<?php
require '../../configDataBase/userDataBaseConnection.php';
$clearData = new ClearData();
?>

<?php
// fetch User
$userEmail = $_GET['userEmail'];
$userEmail = json_encode($userEmail);

$stmt = $conn->prepare("SELECT * FROM Users where role_id = 3 And email = :email ");
$stmt->bindParam(':email', json_decode($userEmail));
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carType = $_POST['carType'];
    $carModel = $_POST['carModel'];
    $modelYear = $_POST['modelYear'];
    $plateNumber = $_POST['plateNumber'];
    $cityName = $_POST['cityName'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $fuel = $_POST['fuel'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];
    $availableDate = $_POST['availableDate'];
    $urlImage = $_POST['image'];

    try {
        // First insert into Cars table
        $stmt = $conn->prepare("INSERT INTO Cars (user_name, car_type, car_model, model_year, plate_number, price, available_time, available, count, city_name) 
        VALUES (:user_name, :car_type, :car_model, :model_year, :plate_number, :price, :available_time, 'true', 0, :city_name)");

        $stmt->bindParam(':user_name', json_decode($userEmail));
        $stmt->bindParam(':car_type', $carType);
        $stmt->bindParam(':car_model', $carModel);
        $stmt->bindParam(':model_year', $modelYear);
        $stmt->bindParam(':plate_number', $plateNumber);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':available_time', $availableDate);
        $stmt->bindParam(':city_name', $cityName);

        $stmt->execute();
        $carId = $conn->lastInsertId(); // Get the auto-incremented ID

        // Then insert into CarDetails
        $stmt = $conn->prepare("INSERT INTO CarDetails (description, color, fuel, seats, img, car_id) 
        VALUES (:description, :color, :fuel, :seats, :img, :car_id)");

        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':fuel', $fuel);
        $stmt->bindParam(':seats', $seats);
        $stmt->bindParam(':car_id', $carId);
        $stmt->bindParam(':img', $urlImage);

        $stmt->execute();
        header("Location: /MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail=" . json_decode($userEmail));
        exit;
    } catch (PDOException $e) {
        echo "<script>console.log({$e->getMessage()});</script>";
    }
}
?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Merchant Cars Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #495057; }
    /* ensure on md+ sidebar is static */
    #sidebar.position-md-static { position: static!important; }

  </style>
</head>
<body>

  <!-- Mobile sidebar toggler -->
  <nav class="navbar navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebar"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <span class="navbar-brand">Merchant Panel</span>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar collapsible on xs, static on md+ -->
      <aside
        id="sidebar"
        class="collapse d-md-block bg-dark col-md-3 col-lg-2 vh-100 p-3 position-fixed position-md-static"
      >
        <h3 class="text-center text-white py-4">Merchant Panel</h3>
        <nav class="nav flex-column">
          <a class="nav-link text-white"
             href="/MostWanted/src/app/merchant-dashboard/merchant-dashboard.php?userEmail=<?= json_decode($userEmail) ?>">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
          </a>
          <a class="nav-link text-white active"
             href="/MostWanted/src/app/merchant-dashboard/merchant-cars.php?userEmail=<?= json_decode($userEmail) ?>">
            <i class="fas fa-car me-2"></i>Cars
          </a>
          <a class="nav-link text-white mt-3" onclick="backToHomePage()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
          </a>
        </nav>
      </aside>

      <!-- Main content -->
      <main class="col-12 col-md-9 col-lg-10 ms-md-auto px-4 py-3">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4 rounded">
          <div class="container-fluid px-0">
            <span class="navbar-brand text-warning">
              Cars: <?= json_decode($userEmail) ?>
            </span>
          </div>
        </nav>

        <!-- Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3 class="text-white">Available Cars</h3>
          <button id="addCarBtn" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Car
          </button>
        </div>

        <!-- Responsive table -->
        <div class="table-responsive shadow-sm rounded">
          <table class="table table-dark table-hover table-bordered mb-0">
            <thead>
              <tr>
                <th>Added By Merchant</th>
                <th>Cars</th>
                <th>Plate Number</th>
                <th>Price Per Day</th>
                <th>Available</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody >
                <?php
                $stmt = $conn->prepare("SELECT * FROM Cars Where user_name = :user_name");
                $stmt->bindParam(':user_name', json_decode($userEmail));
                $stmt->execute();
                $cars = $stmt->fetchAll(); 
                foreach ($cars as $row) {
                    $data = [
                        'id' => json_encode($row['car_id']),
                        'userEmail' => json_decode($userEmail)
                    ];
                    $data = json_encode($data);
                    echo "<tr>";
                    echo "<td class='text-info'>{$row['user_name']}</td>";
                    echo "<td>{$row['car_type']}, {$row['car_model']}</td>";
                    echo "<td class='text-warning'>{$row['plate_number']}</td>";
                    echo "<td>{$row['price']} JD</td>";
                    echo "<td class='text-warning'>{$row['available']}</td>";
                    echo "<td>
                            <a href='/MostWanted/src/app/merchant-dashboard/update.php?car_id={$data}' class='btn btn-warning btn-sm me-1'>Edit</a>
                            <a href='/MostWanted/src/app/merchant-dashboard/delete.php?car_id={$data}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <!-- Add Car Modal -->
  <div class="modal fade" id="carModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form method="post" id="carForm">
          <input type="hidden" name="action" id="formAction" value="add">
          <input type="hidden" name="carId" id="carId">
          <input type="hidden" name="userEmail" value="<?= htmlspecialchars($userEmail) ?>">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Add New Car</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">Car Type</label>
                <input type="text" class="form-control" id="carType" name="carType" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Car Model</label>
                <input type="text" class="form-control" id="carModel" name="carModel" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Model Year</label>
                <input type="number" class="form-control" id="modelYear" name="modelYear" min="1900" max="2099" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Plate Number</label>
                <input type="text" class="form-control" id="plateNumber" name="plateNumber" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">City Name</label>
                <select class="form-select" name="cityName" id="cityName">
                  <option>zarqa</option><option>amman</option><option>irbid</option><option>mafraq</option>
                  <option>maan</option><option>ajloun</option><option>aqaba</option><option>salt</option>
                  <option>madaba</option><option>karak</option><option>tafilah</option><option>jarash</option>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" placeholder="e.g. White">
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Fuel</label>
                <select class="form-select" id="fuel" name="fuel">
                  <option>Hybrid</option><option>Diesel</option><option>Electric</option>
                </select>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1" required>
              </div>
              <div class="col-12 col-md-4">
                <label class="form-label">Price Per Day ($)</label>
                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label">Image</label>
                <textarea class="form-control" id="image" name="image" rows="2" placeholder="add url image only!!"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Available Dates</label>
                <div class="input-group mb-2">
                  <input type="date" class="form-control" id="availableDateInput" name="availableDate">
                  <button class="btn btn-outline-secondary" type="button" id="addDateBtn">
                    <i class="fas fa-calendar-plus"></i> Add Date
                  </button>
                </div>
                <ul class="list-group" id="datesList"></ul>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="saveBtn">Save Car</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script>
    function backToHomePage() {
      alert("successfully Sign out");
      window.location.href = "/MostWanted/src/app/home/home.php";
    }

    let availableDatesTemp = [];
    let editingIndex = null;
    const bsModal = new bootstrap.Modal(document.getElementById('carModal'));

    // function renderTable() {
    //   const tbody = document.getElementById('carsTableBody');
    //   tbody.innerHTML = '';
    //   cars.forEach((c, i) => {
    //     const tr = document.createElement('tr');
    //     tr.innerHTML = `
    //       <td>${i + 1}</td>
    //       <td>${c.car_type}</td>
    //       <td>${c.car_model}</td>
    //       <td>$${c.price.toFixed(2)}</td>
    //       <td>${c.available_time.length > 0 ? 'Yes' : 'No'}</td>
    //       <td>
    //         <button class="btn btn-sm btn-light me-1" onclick="editCar(${i})">
    //           <i class="fas fa-edit"></i>
    //         </button>
    //         <button class="btn btn-sm btn-danger" onclick="deleteCar(${i})">
    //           <i class="fas fa-trash"></i>
    //         </button>
    //       </td>`;
    //     tbody.appendChild(tr);
    //   });
    // }

    document.getElementById('addCarBtn').addEventListener('click', () => {
      editingIndex = null;
      document.getElementById('modalTitle').textContent = 'Add New Car';
      document.getElementById('carForm').reset();
      availableDatesTemp = [];
      updateDatesList();
      bsModal.show();
    });

    document.getElementById('addDateBtn').addEventListener('click', () => {
      const dt = document.getElementById('availableDateInput').value;
      if (!dt) return;
      availableDatesTemp.push(dt);
      updateDatesList();
      document.getElementById('availableDateInput').value = '';
    });

    function updateDatesList() {
      const ul = document.getElementById('datesList');
      ul.innerHTML = '';
      availableDatesTemp.forEach((d, i) => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.textContent = d;
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm btn-danger';
        btn.innerHTML = '<i class="fas fa-times"></i>';
        btn.onclick = () => {
          availableDatesTemp.splice(i, 1);
          updateDatesList();
        };
        li.appendChild(btn);
        ul.appendChild(li);
      });
    }

    // renderTable();
  </script>
</body>
</html>
