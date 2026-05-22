<?php
require_once '../auth.php';
requireAdmin();
require_once '../connectdb.php';
$pageTitle = 'Delete Trainer';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: trainers.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM trainers WHERE trainer_id = ?");
if (!$stmt) {
    header("Location: trainers.php");
    exit();
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$trainer = $result ? $result->fetch_assoc() : null;
$stmt->close();

if (!$trainer) {
    header("Location: trainers.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteStmt = $conn->prepare("DELETE FROM trainers WHERE trainer_id = ?");
    if ($deleteStmt) {
        $deleteStmt->bind_param('i', $id);
        $deleteStmt->execute();
        $deleteStmt->close();
    }
    header("Location: trainers.php?msg=Trainer deleted successfully");
    exit();
}
require_once '../header.php';
?>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <div class="confirm-box card fade-in">
            <h2><i class="fas fa-exclamation-triangle"></i> Delete Trainer</h2>
            <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($trainer['trainer_name']); ?></strong>?<br>All associated bookings will also be removed.</p>
            <form method="POST">
                <div class="btn-group" style="justify-content:center;">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Yes, Delete</button>
                    <a href="trainers.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../footer.php'; ?>
