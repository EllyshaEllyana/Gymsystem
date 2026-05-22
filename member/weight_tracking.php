<?php
require_once '../auth.php'; // Standard user session authentication check

// Double check permission role to make sure only members use this tracking utility
if ($_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}
require_once '../connectdb.php';

$pageTitle = 'My Weight Progress';
$errors = [];

// Find the member_id linked to this logged-in account user session
$userId = $_SESSION['user_id'];
$memberStmt = $conn->prepare("SELECT member_id FROM members WHERE user_id = ? LIMIT 1");
$memberStmt->bind_param("i", $userId);
$memberStmt->execute();
$memberData = $memberStmt->get_result()->fetch_assoc();
$memberStmt->close();

$memberId = $memberData['member_id'] ?? 0;

if (!$memberId) {
    die("Error: Member account profile link not found inside database schema structure.");
}

// 1. Handle New Weight Entry Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_weight'])) {
    $weight = (float)($_POST['weight'] ?? 0);
    $recordDate = $_POST['record_date'] ?? date('Y-m-d');

    if ($weight <= 20 || $weight > 300) {
        $errors[] = 'Please enter a realistic weight value (e.g., between 20kg and 300kg).';
    }

    if (empty($errors)) {
        $insertStmt = $conn->prepare("INSERT INTO weight_progress (member_id, weight, record_date) VALUES (?, ?, ?)");
        $insertStmt->bind_param("ids", $memberId, $weight, $recordDate);
        
        if ($insertStmt->execute()) {
            header("Location: weight_tracking.php?msg=Weight+progress+recorded+successfully");
            exit();
        } else {
            $errors[] = "Database Error: Unable to save tracking parameters log.";
        }
        $insertStmt->close();
    }
}

// 2. Fetch the Historical Weight Progress Log Entries for this Member
$historyQuery = $conn->prepare("SELECT * FROM weight_progress WHERE member_id = ? ORDER BY record_date DESC, weight_id DESC");
$historyQuery->bind_param("i", $memberId);
$historyQuery->execute();
$historyResult = $historyQuery->get_result();
$history = $historyResult->fetch_all(MYSQLI_ASSOC);
$historyQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: auto; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { margin: 0; font-size: 28px; color: #2c3e50; }
        h2 { font-size: 18px; margin-top: 0; margin-bottom: 20px; color: #34495e; }
        
        /* Item 4 requirement style match link navigation positioning */
        .btn-dashboard { background: #34495e; color: white; padding: 10px 18px; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; transition: background 0.2s; }
        .btn-dashboard:hover { background: #2c3e50; }
        
        /* Form Box Input Field Layouts */
        .weight-form { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 180px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        
        .btn-submit { background: #27ae60; color: white; padding: 11px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .btn-submit:hover { background: #219150; }

        /* Metrics Tracker Progress Table Style Hooks */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #34495e; color: white; text-align: left; padding: 12px; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 15px; }
        tr:hover { background: #f9f9f9; }
        
        .weight-badge { background: #e1f5fe; color: #0288d1; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .empty-msg { text-align: center; color: #7f8c8d; padding: 25px; font-style: italic; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { padding: 12px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    
    <div class="page-header">
        <h1>Fitness & Weight Tracking</h1>
        <a href="dashboard.php" class="btn-dashboard">Back to Dashboard ↗</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
    <?php endforeach; ?>

    <div class="card">
        <h2>Log Current Weight Metric</h2>
        <form class="weight-form" method="POST">
            <div class="form-group">
                <label>Weight (kg)</label>
                <input type="number" name="weight" step="0.1" placeholder="e.g. 74.5" required>
            </div>
            <div class="form-group">
                <label>Date of Check</label>
                <input type="date" name="record_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <button type="submit" name="log_weight" class="btn-submit">Save Metric Log</button>
        </form>
    </div>

    <div class="card">
        <h2>Personal Weight Tracking History</h2>
        <table>
            <thead>
                <tr>
                    <th>Entry Date Check</th>
                    <th>Recorded Weight Value</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="2" class="empty-msg">No progress logs recorded yet. Start tracking above!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($history as $row): ?>
                        <tr>
                            <td><strong><?php echo date('d M Y', strtotime($row['record_date'])); ?></strong></td>
                            <td><span class="weight-badge"><?php echo htmlspecialchars($row['weight']); ?> kg</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>