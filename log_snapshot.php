<?php
session_start();
require 'db.php';

// Ensure only logged-in admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Fetch the snapshot path before deleting
    $stmt = $pdo->prepare("SELECT snapshot FROM alerts WHERE id = ?");
    $stmt->execute([$id]);
    $snapshot = $stmt->fetchColumn();

    // Delete DB entry
    $stmt = $pdo->prepare("DELETE FROM alerts WHERE id = ?");
    $stmt->execute([$id]);

    // Delete snapshot file
    if ($snapshot && file_exists($snapshot)) {
        unlink($snapshot);
    }

    $_SESSION['status'] = "Alert deleted.";
    $_SESSION['status-code'] = "success";
    header("Location: log_snapshot.php");
    exit();
}

// Fetch all alerts
$stmt = $pdo->query("SELECT * FROM alerts ORDER BY created_at DESC");
$alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'templates/admin_header.php'; ?>

<body id="page-top">
    <div id="wrapper">
        <?php require_once 'templates/admin_sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php require_once 'templates/admin_topbar.php'; ?>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Alert Log with Snapshots</h1>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Snapshot</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alerts as $alert): ?>
                            <tr>
                                <td><?= htmlspecialchars($alert['created_at']) ?></td>
                                <td><?= htmlspecialchars($alert['type']) ?></td>
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
                                <td>
                                    <form method="POST" onsubmit="return confirm('Delete this alert and its snapshot?');">
                                        <input type="hidden" name="delete_id" value="<?= $alert['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php require_once 'templates/admin_footer.php'; ?>
        </div>
    </div>

   <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/sweetalert.js"></script>
    <?php if (isset($_SESSION['status'])): ?>
        <script>
        swal({
            title: "<?= $_SESSION['status']; ?>",
            icon: "<?= $_SESSION['status-code']; ?>",
            button: "OK",
        });
        </script>
    <?php unset($_SESSION['status'], $_SESSION['status-code']); endif; ?>

<!-- Modal -->
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
