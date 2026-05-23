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
<style>
    .delete-header { max-width: 860px; margin: 2.5rem auto 0; padding: 0 1.5rem; }
    .delete-header .eyebrow { display: inline-flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1rem; }
    .delete-header h1 { margin: 0 0 0.75rem; font-size: 2.2rem; color: #111827; }
    .delete-header p { margin: 0; color: #475569; line-height: 1.8; max-width: 720px; }
    .confirm-panel { max-width: 620px; margin: 2rem auto 4rem auto; padding: 2.5rem 2rem; border-radius: 24px; box-shadow: 0 30px 70px rgba(15, 23, 42, 0.10); background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; text-align: center; }
    .confirm-panel .icon-wrap { width: 68px; height: 68px; margin: 0 auto 1rem; border-radius: 50%; background: #fee2e2; color: #b91c1c; display: grid; place-items: center; font-size: 1.5rem; }
    .confirm-panel h2 { margin: 0 0 0.75rem; font-size: 1.75rem; color: #111827; }
    .confirm-panel p { margin: 0 auto; color: #475569; font-size: 1rem; line-height: 1.75; max-width: 520px; }
    .confirm-panel p strong { color: #0f172a; }
    .confirm-panel .btn-group { justify-content: center; flex-wrap: wrap; gap: 1rem; margin-top: 2rem; }
    .btn-danger { background-color: #ef4444; }
    .btn-danger:hover { background-color: #dc2626; }
    @media (max-width: 768px) {
        .delete-header { padding: 0 1rem; }
        .confirm-panel { padding: 2rem 1.25rem; }
    }
</style>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <div class="delete-header">
            <div class="eyebrow"><i class="fas fa-user-slash"></i> Trainer Removal</div>
            <h1>Confirm Trainer Deletion</h1>
            <p>This action permanently removes the trainer from the system and deletes any linked bookings. Be sure you want to proceed before confirming.</p>
        </div>

        <div class="confirm-panel card fade-in">
            <div class="icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
            <h2>Delete <span style="color:#b91c1c;"><?php echo htmlspecialchars($trainer['trainer_name']); ?></span>?</h2>
            <p>Deleting this trainer will remove their profile and all scheduled sessions. This cannot be undone.</p>
            <form method="POST">
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Yes, Delete</button>
                    <a href="trainers.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../footer.php'; ?>
