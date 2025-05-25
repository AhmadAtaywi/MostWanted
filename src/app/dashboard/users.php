<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
$userEmail = $_GET['userEmail'];
$userEmail = json_encode($userEmail);

$stmt = $conn->prepare("SELECT * FROM Users where role_id = 2 And email = :email ");
$stmt->bindParam(':email', $userEmail);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Users Dashboard</title>
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
    .table {
        min-width: 1100px
    }
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
        aria-controls="sidebar"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <span class="navbar-brand">Admin Panel</span>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar: collapses on xs, shows on md+ -->
      <aside 
        id="sidebar" 
        class="collapse d-md-block bg-dark col-md-3 col-lg-2 vh-100 p-3 sidebar"
      >
        <h3 class="text-center text-white py-4">Admin Panel</h3>
        <nav class="nav flex-column">
          <a 
            class="nav-link text-white" 
            href="/MostWanted/src/app/dashboard/dashboard.php?userEmail=<?= json_decode($userEmail) ?>"
          ><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
          <a 
            class="nav-link text-white" 
            href="/MostWanted/src/app/dashboard/cars.php?userEmail=<?= json_decode($userEmail) ?>"
          ><i class="fas fa-car me-2"></i>Cars</a>
          <a 
            class="nav-link text-white active" 
            href="/MostWanted/src/app/dashboard/users.php?userEmail=<?= json_decode($userEmail) ?>"
          ><i class="fas fa-users me-2"></i>Users</a>
          <a
            href="javascript:void(0)"
            class="nav-link text-white mt-3"
            onclick="backToHomePage()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
          </a>
        </nav>
      </aside>

      <!-- Main content -->
      <main class="col-12 col-md-9 col-lg-10 px-4 py-3">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4 rounded">
          <div class="container-fluid">
            <span class="navbar-brand text-warning">
               <?= json_decode($userEmail) ?>
            </span>
          </div>
        </nav>

        <h3 class="text-white mb-3">User List</h3>

        <!-- Responsive table wrapper -->
        <div class="table-responsive shadow-sm rounded">
          <table class="table table-dark table-hover table-bordered mb-0">
            <thead>
              <tr>
                <th>User Name</th>
                <th>User Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Merchant Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <?php
              $stmt = $conn->prepare("SELECT * FROM FullUserDetails");
              $stmt->execute();
              $allUsers = $stmt->fetchAll();                  
              foreach ($allUsers as $row) {
                  $data = json_encode([
                    'id'        => $row['user_id'],
                    'userEmail' => json_decode($userEmail)
                  ]);
                  echo "<tr>";
                  echo "<td class='text-success'>{$row['UserName']}</td>";
                  echo "<td class='text-info'>{$row['name']}</td>";
                  echo "<td class='text-primary'>{$row['email']}</td>";
                  echo "<td>{$row['phone']}</td>";
                  echo "<td class='text-warning'>{$row['merchant_status']}</td>";
                  echo "<td>
                          <a 
                            href='/MostWanted/src/app/dashboard/update-user.php?user_id={$data}' 
                            class='btn btn-warning btn-sm me-1'
                          ><i class='fas fa-edit'></i></a>
                          <a 
                            href='/MostWanted/src/app/dashboard/delete-user.php?user_id={$data}' 
                            class='btn btn-danger btn-sm'
                            onclick='return confirm(\"Are you sure?\")'
                          ><i class='fas fa-trash'></i></a>
                        </td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="userForm">
                <div class="modal-header">
                  <h5 class="modal-title" id="userModalTitle">Edit User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" id="userName" class="form-control" required />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="userEmail" class="form-control" required />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="userStatus" class="form-select">
                      <option>Active</option>
                      <option>Inactive</option>
                      <option>Rejected</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button 
                    type="button" 
                    class="btn btn-secondary" 
                    data-bs-dismiss="modal"
                  >Cancel</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script>
    function backToHomePage() {
      alert("successfully Sign out");
      window.location.href = "/MostWanted/src/app/home/home.php";
    }
  </script>
</body>
</html>