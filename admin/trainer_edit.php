<?php
require_once '../auth.php';
requireAdmin();
require_once '../connectdb.php';
$pageTitle = 'Edit Trainer';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: trainers.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM trainers WHERE trainer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trainer = $result ? $result->fetch_assoc() : null;
$stmt->close();

if (!$trainer) {
    header("Location: trainers.php");
    exit();
}

$errors = [];
$selectedDays = array_map('trim', explode(',', $trainer['available_days'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['trainer_name'] ?? '');
    $spec = trim($_POST['specialization'] ?? '');
    $days = isset($_POST['available_days']) ? implode(', ', $_POST['available_days']) : '';
    $time = trim($_POST['available_time'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');
    $fee = (float)($_POST['session_fee'] ?? 50);
    $status = $_POST['status'] ?? 'Available';

    if (empty($name) || empty($spec) || empty($days) || empty($time)) {
        $errors[] = 'All required fields must be filled.';
        $selectedDays = $_POST['available_days'] ?? [];
    } else {
        $stmt = $conn->prepare("UPDATE trainers SET trainer_name=?, specialization=?, available_days=?, available_time=?, contact_number=?, session_fee=?, status=? WHERE trainer_id=?");
        $stmt->bind_param("sssssisi", $name, $spec, $days, $time, $contact, $fee, $status, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: trainers.php?msg=Trainer updated successfully");
        exit();
    }
}
require_once '../header.php';
?>
<style>
    .admin-content { padding: 32px 28px; }
    .page-header { margin-bottom: 1.75rem; }
    .page-header h1 { font-size: 2.2rem; margin: 0 0 0.6rem; color: #111827; }
    .page-header p { color: #475569; line-height: 1.75; margin: 0; max-width: 760px; }
    .breadcrumb { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.92rem; color: #64748b; margin-bottom: 1.25rem; }
    .breadcrumb a { color: #3b82f6; text-decoration: none; }
    .card { background: #ffffff; border-radius: 24px; padding: 2rem; box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08); max-width: 980px; }
    .card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.75rem; }
    .card-header h2 { margin: 0; font-size: 1.5rem; color: #111827; }
    .card-header p { margin: 0.5rem 0 0; color: #64748b; max-width: 560px; line-height: 1.7; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.3rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.55rem; }
    .form-group.full-width { grid-column: 1 / -1; }
    label { font-weight: 600; color: #334155; }
    input[type="text"], input[type="number"], select { width: 100%; border: 1px solid #cbd5e1; border-radius: 14px; padding: 0.95rem 1.1rem; font-size: 0.98rem; color: #0f172a; background: #f8fafc; box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.06); }
    input[type="text"]:focus, input[type="number"]:focus, select:focus { outline: 2px solid rgba(59, 130, 246, 0.25); border-color: #93c5fd; }
    .checkbox-group { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0.75rem; }
    .checkbox-group label { display: inline-flex; align-items: center; gap: 0.55rem; padding: 0.85rem 1rem; border: 1px solid #e2e8f0; border-radius: 14px; background: #f8fafc; color: #334155; cursor: pointer; transition: all 0.2s ease; }
    .checkbox-group label:hover { border-color: #93c5fd; }
    .checkbox-group input { accent-color: #3b82f6; }
    .alert-danger { border-radius: 16px; border: 1px solid #fca5a5; background: #fee2e2; color: #991b1b; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: inline-flex; align-items: center; gap: 0.75rem; }
    .btn-group { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 1.5rem; }
    .btn-primary { background: #3b82f6; color: #ffffff; padding: 0.95rem 1.5rem; border-radius: 14px; border: none; font-weight: 700; cursor: pointer; transition: transform 0.15s ease, background 0.15s ease; }
    .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-secondary { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; padding: 0.95rem 1.5rem; border-radius: 14px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
    .btn-secondary:hover { background: #e2e8f0; }
    @media (max-width: 900px) {
        .form-grid { grid-template-columns: 1fr; }
        .checkbox-group { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
<div class="admin-layout">
    <?php include 'sidebar.php'; ?>
    <div class="admin-content">
        <div class="page-header">
            <div class="breadcrumb"><a href="trainers.php">Trainer Management</a> › Edit Trainer</div>
            <h1>Edit Personal Trainer</h1>
            <p>Update trainer details, availability, and session pricing from one polished admin form.</p>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Trainer profile</h2>
                    <p>Review and update the trainer’s working schedule and contact information.</p>
                </div>
                <div class="status-pill <?php echo strtolower($trainer['status']); ?>">Status: <?php echo htmlspecialchars($trainer['status']); ?></div>
            </div>

            <?php foreach ($errors as $err): ?>
                <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($err); ?></div>
            <?php endforeach; ?>

            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="trainer_name">Trainer Name *</label>
                        <input type="text" id="trainer_name" name="trainer_name" required value="<?php echo htmlspecialchars($trainer['trainer_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization *</label>
                        <select id="specialization" name="specialization" required>
                            <?php foreach (['Strength Training','Cardio & HIIT','Weight Loss','Rehabilitation','General Fitness'] as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $trainer['specialization']===$s?'selected':''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Available Days *</label>
                        <div class="checkbox-group">
                            <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d): ?>
                                <label><input type="checkbox" name="available_days[]" value="<?php echo $d; ?>" <?php echo in_array($d, $selectedDays)?'checked':''; ?>> <?php echo $d; ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="available_time">Available Time *</label>
                        <input type="text" id="available_time" name="available_time" required value="<?php echo htmlspecialchars($trainer['available_time']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($trainer['contact_number']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="session_fee">Session Fee (RM) *</label>
                        <input type="number" id="session_fee" name="session_fee" step="0.01" min="0.01" required value="<?php echo $trainer['session_fee']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Available" <?php echo $trainer['status']==='Available'?'selected':''; ?>>Available</option>
                            <option value="Busy" <?php echo $trainer['status']==='Busy'?'selected':''; ?>>Busy</option>
                        </select>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Trainer</button>
                    <a href="trainers.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../footer.php'; ?>
