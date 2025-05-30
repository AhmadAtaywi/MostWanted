<?php
require '../../configDataBase/userDataBaseConnection.php';
?>

<?php
$userEmail = $_GET['userEmail'];
$userEmail = json_encode($userEmail);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Request From Contact Us Dashboard</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        rel="stylesheet">
    <style>
        body {
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            background-color: #343a40;
        }

        .table {
            min-width: 1100px
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebar"
                aria-controls="sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand">Admin Panel</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <aside
                id="sidebar"
                class="collapse d-md-block bg-dark col-md-3 col-lg-2 vh-100 p-3 sidebar">
                <h3 class="text-center text-white py-4">Admin Panel</h3>
                <nav class="nav flex-column">
                    <a
                        class="nav-link text-white"
                        href="/MostWanted/src/app/dashboard/dashboard.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a
                        class="nav-link text-white"
                        href="/MostWanted/src/app/dashboard/cars.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-car me-2"></i>Cars</a>
                    <a
                        class="nav-link text-white"
                        href="/MostWanted/src/app/dashboard/users.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-users me-2"></i>Users</a>
                    <a
                        class="nav-link text-white" 
                        href="/MostWanted/src/app/dashboard/request-from-contact-us.php?userEmail=<?= json_decode($userEmail) ?>"><i class="fas fa-users me-2"></i>Request From Contact Us</a>
                    <a
                        href="javascript:void(0)"
                        class="nav-link text-white mt-3"
                        onclick="backToHomePage()">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </nav>
            </aside>

            <main class="col-12 col-md-9 col-lg-10 px-4 py-3">
                <nav class="navbar navbar-expand-lg navbar-dark bg-secondary mb-4 rounded">
                    <div class="container-fluid">
                        <span class="navbar-brand text-warning">
                            <?= json_decode($userEmail) ?>
                        </span>
                    </div>
                </nav>

                <h3 class="text-white mb-3">Request From Contact Us</h3>

                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-dark table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM Contact");
                            $stmt->execute();
                            $allUsers = $stmt->fetchAll();
                            foreach ($allUsers as $row) {
                                $messageAlert = json_encode($row['message']);
                                echo "<tr>";
                                echo "<td class='text-info'>{$row['name']}</td>";
                                echo "<td class='text-info'>{$row['email']}</td>";
                                echo "<td class='text-info'>{$row['subject']}</td>"; 
                                echo "<td onclick='displayMessage({$messageAlert})' style='color: #B6B09F'>Click to display the message</td>";
                                echo "</tr>";
                            } 
                            ?>
                        </tbody>  
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="messageModalBody">        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function backToHomePage() {
            alert("successfully Sign out");
            window.location.href = "/MostWanted/src/app/home/home.php";
        }

        function displayMessage(message) {
            const modalBody = document.getElementById("messageModalBody");
            modalBody.textContent = message;
            const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));
            messageModal.show();
        }
    </script>
</body>

</html>