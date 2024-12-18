<?php  
session_start();
require_once '../config/db_connection.php';

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$admin_name = $_SESSION['admin_name'];

// Fetch all user data
try {
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching users: " . $e->getMessage());
}

// Handle Excel (CSV) Download
if (isset($_POST['download_excel'])) {
    $filename = "user_list_" . date('Y-m-d') . ".csv";

    // Set CSV Headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open output stream for CSV
    $output = fopen('php://output', 'w');

    // Add column headers
    $headers = [
        '#', 'Name', 'Address', 'Phone Number', 'Email',
        'Recording Consent', 'Injury Consent',
        'Emergency Contact 1 Name', 'Emergency Contact 1 Phone', 'Emergency Contact 1 Relationship',
        'Emergency Contact 2 Name', 'Emergency Contact 2 Phone', 'Emergency Contact 2 Relationship',
        'Created At', 'Signature Date'
    ];
    fputcsv($output, $headers);

    // Add user data row by row
    $counter = 1;
    foreach ($users as $user) {
        fputcsv($output, [
            $counter++,
            $user['name'],
            $user['address'] ?? '',
            $user['phone_number'] ?? '',
            $user['email'] ?? '',
            $user['recording_consent'] == 1 ? 'Yes' : 'No',
            $user['injury_loss_risk_consent'] == 1 ? 'Yes' : 'No',
            $user['emergency_contact_1_name'] ?? '',
            $user['emergency_contact_1_phone'] ?? '',
            $user['emergency_contact_1_relationship'] ?? '',
            $user['emergency_contact_2_name'] ?? '',
            $user['emergency_contact_2_phone'] ?? '',
            $user['emergency_contact_2_relationship'] ?? '',
            $user['created_at'] ?? '',
            $user['signature_date'] ?? ''
        ]);
    }

    fclose($output);
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { margin-bottom: 10px; }
    </style>
</head>
<body>
    <nav>
        <span>Welcome, Admin <?php echo htmlspecialchars($admin_name); ?></span>
        <a href="logout.php">Logout</a>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </nav>

    <h1>User List</h1>

    <!-- Download Button -->
    <form method="POST" style="margin-bottom: 10px;">
        <button type="submit" name="download_excel">Download All Users as Excel</button>
    </form>

    <!-- User Table -->
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Recording Consent</th>
            <th>Injury Consent</th>
            <th>Emergency Contact 1</th>
            <th>Emergency Contact 2</th>
            <th>Created & Signature</th>
        </tr>
        <?php if (!empty($users)): ?>
            <?php $counter = 1; ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['address'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                    <td><?php echo $user['recording_consent'] == 1 ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $user['injury_loss_risk_consent'] == 1 ? 'Yes' : 'No'; ?></td>
                    <td><?php echo nl2br("Name: {$user['emergency_contact_1_name']}\nPhone: {$user['emergency_contact_1_phone']}\nRelation: {$user['emergency_contact_1_relationship']}"); ?></td>
                    <td><?php echo nl2br("Name: {$user['emergency_contact_2_name']}\nPhone: {$user['emergency_contact_2_phone']}\nRelation: {$user['emergency_contact_2_relationship']}"); ?></td>
                    <td><?php echo nl2br("Created: {$user['created_at']}\nSignature: {$user['signature_date']}"); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">No Data Found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
