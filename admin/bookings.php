<?php
require_once '../auth.php';
requireAdmin();
require_once '../connectdb.php';

$pageTitle = 'Manage Bookings';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $bookingId = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'Approved' || $action === 'Cancelled') {
        $stmt = $conn->prepare("UPDATE session_bookings SET booking_status = ? WHERE booking_id = ?");
        $stmt->bind_param("si", $action, $bookingId);
        if ($stmt->execute()) {
            header("Location: bookings.php?msg=Booking status synchronized successfully to " . $action);
            exit();
        }
    } elseif ($action === 'Delete') {
        $stmt = $conn->prepare("UPDATE session_bookings SET booking_status = 'Cancelled' WHERE booking_id = ?");
        $stmt->bind_param("i", $bookingId);
        if ($stmt->execute()) {
            header("Location: bookings.php?msg=Booking removed from live timetable and schedules");
            exit();
        }
    }
}

$query = "SELECT sb.*, m.full_name, t.trainer_name 
          FROM session_bookings sb 
          JOIN members m ON sb.member_id = m.member_id 
          JOIN trainers t ON sb.trainer_id = t.trainer_id 
          ORDER BY sb.session_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        .admin-content { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin: 0; }
        /* Style for the new header row layout */
        .header-container { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; }
        .btn-sm { padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 13px; color: white; display: inline-block; margin-right: 5px; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        /* Style for the dashboard shortcut link */
        .btn-dashboard { background: #34495e; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; }
        .btn-dashboard:hover { background: #2c3e50; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; background: #e9ecef; color: #495057; font-weight: bold; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-pending { background: #fff3cd; color: #856404; }
        .alert-success { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="admin-layout">
    <div class="admin-content">
        <div class="header-container">
            <h1>Bookings Management</h1>
            <a href="dashboard.php" class="btn-dashboard">Back to Dashboard ↗</a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Trainer</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['trainer_name']); ?></td>
                        <td><?php echo $row['session_date'] . ' | ' . $row['session_time']; ?></td>
                        <td>
                            <?php
                                $badgeStyle = 'status-pending';
                                if (strtolower($row['booking_status']) === 'approved') {
                                    $badgeStyle = 'status-approved';
                                } elseif (strtolower($row['booking_status']) === 'cancelled') {
                                    $badgeStyle = 'status-cancelled';
                                }
                            ?>
                            <span class="status-badge <?php echo $badgeStyle; ?>"><?php echo htmlspecialchars($row['booking_status']); ?></span>
                        </td>
                        <td>
                            <a href="booking_edit.php?id=<?php echo $row['booking_id']; ?>" class="btn-sm btn-primary">Edit</a>
                            
                            <?php if (strtolower($row['booking_status']) !== 'approved'): ?>
                                <a href="bookings.php?id=<?php echo $row['booking_id']; ?>&action=Approved" class="btn-sm btn-success">Approve</a>
                            <?php else: ?>
                                <a href="bookings.php?id=<?php echo $row['booking_id']; ?>&action=Cancelled" class="btn-sm btn-danger" style="background: #e67e22;">Cancel</a>
                            <?php endif; ?>
                            
                            <a href="bookings.php?id=<?php echo $row['booking_id']; ?>&action=Delete" class="btn-sm btn-danger" onclick="return confirm('Remove booking from dashboard and schedules?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No bookings found in the system.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>