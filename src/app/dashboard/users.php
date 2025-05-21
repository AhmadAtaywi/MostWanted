<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
// fetch User
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Dashboard</title>
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
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/dashboard.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/cars.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-car"></i> Cars</a>
            <a class="nav-link text-white" href="/MostWanted/src/app/dashboard/users.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-users"></i> Users</a>
            <a class="nav-link text-white" onclick="backToHomePage()"><i class="fas fa-users"></i>Logout</a>
        </nav>
    </div>

    <!-- Content -->

    <div class="content" style="padding:20px;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4">
            <a class="navbar-brand" style='color:#FFB22C'>Users: <?= json_decode($userEmail); ?></a>
        </nav>

        <h3 class="mb-3">User List</h3>
        <table class="table table-dark table-hover table-bordered shadow-sm">
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
            </tbody>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM FullUserDetails");
                $stmt->execute();
                $user = $stmt->fetchAll(); // fetchAll: to get all rows                 
                foreach ($user as $row) {
                    $data = [
                        'id' => json_encode($row['user_id']),
                        'userEmail' => json_decode($userEmail)
                    ];
                    $data = json_encode($data);
                    echo "<tr>";
                    echo "<td style = 'color:#1F7D53'>{$row['UserName']}</td>";
                    echo "<td style = 'color:#5CB338'>{$row['name']}</td>";
                    echo "<td style = 'color:#129990'>{$row['email']}</td>";
                    echo "<td>{$row['phone']}</td>";
                    echo "<td style = 'color:#FFB22C'>{$row['merchant_status']}</td>";
                    echo "<td>                            
                            <a href='/MostWanted/src/app/dashboard/updateUser.php?car_id={$data}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='/MostWanted/src/app/dashboard/deleteUser.php?car_id={$data}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
    </script>
</body>

</html>