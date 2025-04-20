<?php
session_start();
require 'db.php';

// Ensure only logged-in admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch admin details including the name
$adminStmt = $pdo->prepare("SELECT name FROM admins WHERE id = ?");
$adminStmt->execute([$_SESSION['admin_id']]);
$admin = $adminStmt->fetch(PDO::FETCH_ASSOC);

// Fetch some statistics
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();

// Recent alerts (if you have an alerts table, otherwise this can be modified)
$recentAlerts = [];
try {
    $stmt = $pdo->query("SELECT * FROM alerts ORDER BY created_at DESC LIMIT 5");
    $recentAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // If no alerts table exists, this will be an empty array
}

// Fetch fire alerts
$fireAlerts = $pdo->query("SELECT COUNT(*) FROM alerts WHERE type = 'fire'")->fetchColumn();

// Fetch recent activity (e.g., user registrations, admin logins)
$recentActivity = [];
try {
    $stmt = $pdo->query("
        SELECT 'User Registration' AS type, name, created_at FROM users
        UNION ALL
        SELECT 'Admin Login' AS type, name, created_at FROM admins
        ORDER BY created_at DESC LIMIT 5
    ");
    $recentActivity = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // If no activity logs exist, this will be an empty array
}

?>

<!DOCTYPE html>
<html lang="en">


<!-- HEADER -->
<?php
require_once 'templates/admin_header.php';
?>

<style>
    /* Floating for larger screens */
    .floating-container {
      position: fixed;
      top: 20%;
      right: 20px;
      width: 300px;
      z-index: 100;
    }

    /* For smaller screens, ensure it is a normal card */
    @media (max-width: 767.98px) {
      .floating-container {
        position: static;
        width: 100%;
        margin-top: 20px;
      }
    }
  </style>
 
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        require_once 'templates/admin_sidebar.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                require_once 'templates/admin_topbar.php';
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Users</div>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Admin Accounts</div>

                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $adminCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Logged In As</div>
                                                

                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo htmlspecialchars($admin['name']); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fire Alerts Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <h5 class="card-title">Fire Alerts</h5>
                                    <p class="card-text">
                                        Fire: <?php echo $fireAlerts; ?><br>
                                    </p>
                                </div>
                            </div>
                        </div>


                        
                        
                    </div>

                       <!-- Recent Activity Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3>Recent Activity</h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentActivity as $activity): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($activity['type']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['name']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['created_at']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Alerts Section -->
                        <?php if (!empty($recentAlerts)): ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3>Recent Alerts</h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Snapshot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentAlerts as $alert): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($alert['created_at']); ?></td>
                                            <td>
                                            <?php if (!empty($alert['snapshot'])): ?>
                                              <!-- Trigger Modal -->
                                            <a href="#" data-toggle="modal" data-target="#snapshotModal" data-img="<?= htmlspecialchars($alert['snapshot']); ?>">
                                                <img src="<?= htmlspecialchars($alert['snapshot']) ?>" alt="Snapshot" style="width: 80px; height: auto;">
                                            </a>
                                            <?php else: ?>
                                            No snapshot
                                            <?php endif; ?>   
                                            </td>                                     
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php
            require_once 'templates/admin_footer.php';
            ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>


    <script src="js/sweetalert.js"></script>
    <?php 
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

    ?>
    <script>
        swal({
    title: "<?php echo $_SESSION['status']; ?>",
    icon: "<?php echo $_SESSION['status-code']; ?>",
    button: "DONE",
    });
    </script>
    <?php
    unset($_SESSION['status']);
    }
    ?>

<!-- Image Modal -->
<div class="modal fade snapshot-modal" id="snapshotModal" tabindex="-1" role="dialog" aria-labelledby="snapshotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" id="modalImage" class="img-fluid" alt="Snapshot">
            </div>
        </div>
    </div>
</div>
</body>
<script>

$('#snapshotModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var imgSrc = button.data('img'); // Extract image source
    var modal = $(this);
    modal.find('#modalImage').attr('src', imgSrc); // Update modal image source
});

</script>
</html>


