<?php
session_start();
require_once '../config/db_connection.php';

// Ensure the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$admin_name = $_SESSION['admin_name'];

// Define the default and maximum records per page
$defaultRecordsPerPage = 5; // Default number of records per page
$maxRecordsPerPage = 100; // Maximum allowed number of records per page

// Get user-defined records per page, capped at the maximum limit
$recordsPerPage = isset($_GET['records_per_page']) && is_numeric($_GET['records_per_page']) && (int)$_GET['records_per_page'] > 0
    ? min((int)$_GET['records_per_page'], $maxRecordsPerPage) // Cap at maxRecordsPerPage
    : $defaultRecordsPerPage;

// Function for pagination
function getPaginationData($conn, $table, $currentPage, $recordsPerPage, $search = '', $orderBy = 'id', $orderDir = 'ASC') {
    $offset = ($currentPage - 1) * $recordsPerPage;

    // Adjust WHERE clause for the search field
    $whereClause = '';
    if ($search) {
        if ($table === 'events') {
            $whereClause = "WHERE title LIKE :search OR description LIKE :search";
        } elseif ($table === 'users' || $table === 'admin_users') {
            $whereClause = "WHERE name LIKE :search";
        }
    }

    try {
        $query = "SELECT * FROM $table $whereClause ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";
      
        $stmt = $conn->prepare($query);

        if ($search) $stmt->bindValue(':search', "%$search%");
        $stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count query
        $countQuery = "SELECT COUNT(*) FROM $table $whereClause";
        $countStmt = $conn->prepare($countQuery);
        if ($search) $countStmt->bindValue(':search', "%$search%");
        $countStmt->execute();
        $totalRecords = $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $totalRecords];
    } catch (Exception $e) {
        die("Error fetching data from $table: " . $e->getMessage());
    }
}


try {
    // Events Section Data
    $eventsPage = $_GET['events_page'] ?? 1;
    $eventsSearch = $_GET['events_search'] ?? '';
    $eventsSort = $_GET['events_sort'] ?? 'start_time';
    $eventsOrder = $_GET['events_order'] ?? 'ASC';
    $eventsData = getPaginationData($conn, 'events', $eventsPage, $recordsPerPage, $eventsSearch, $eventsSort, $eventsOrder);

    // Users Section Data
    $usersPage = $_GET['users_page'] ?? 1;
    $usersSearch = $_GET['users_search'] ?? '';
    $usersSort = $_GET['users_sort'] ?? 'name';
    $usersOrder = $_GET['users_order'] ?? 'ASC';
    $usersData = getPaginationData($conn, 'users', $usersPage, $recordsPerPage, $usersSearch, $usersSort, $usersOrder);

    // Admin Users Section Data
    $adminsPage = $_GET['admins_page'] ?? 1;
    $adminsSearch = $_GET['admins_search'] ?? '';
    $adminsSort = $_GET['admins_sort'] ?? 'name';
    $adminsOrder = $_GET['admins_order'] ?? 'ASC';
    $adminsData = getPaginationData($conn, 'admin_users', $adminsPage, $recordsPerPage, $adminsSearch, $adminsSort, $adminsOrder);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script>
        // Apply the correct section on page load based on the URL parameter
// Set the recordsPerPage input field value on page load
window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    const recordsPerPage = params.get('records_per_page') || '5'; // Default to 5
    document.getElementById('records_per_page').value = recordsPerPage;

    const filterValue = params.get('filter') || 'all';
    document.getElementById('filter-select').value = filterValue;
    filterSection();
};


// Modify the filterSection function to dynamically update the URL parameter when the filter changes
function filterSection() {
    const filterValue = document.getElementById('filter-select').value;
    document.querySelectorAll('.manage-section').forEach(section => {
        section.style.display = section.id === filterValue || filterValue === 'all' ? 'block' : 'none';
    });

    // Update the URL to include the current filter value
    const url = new URL(window.location);
    url.searchParams.set('filter', filterValue);
    window.history.replaceState({}, '', url);
}


        function printSection(sectionId) {
            const content = document.getElementById(sectionId).outerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                <head><title>Print</title></head>
                <body>${content}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</head>
<body>
    <nav>
        <span>Welcome, Admin <?php echo htmlspecialchars($admin_name); ?></span>
        <a href="logout.php">Logout</a>
        <a href="admin_gallery.php">Gallery</a>
        <a href="users_list.php">View Users</a>
    </nav>

    <main>
        <h1>Admin Dashboard</h1>

        <!-- Top Filter -->
        <section>
            <label for="filter-select">Filter By:</label>
            <select id="filter-select" onchange="filterSection()">
                <option value="all">All</option>
                <option value="events-section">Manage Events</option>
                <option value="users-section">Manage Users</option>
                <option value="admins-section">Manage Admin Users</option>
            </select>
        </section>

        <!-- Manage Events Section -->
        <section id="events-section" class="manage-section">
    <h2>Manage Events</h2>
    <a href="../views/create_event.php"><button>Add New Event</button></a>
    <form method="GET" action="">
        <input type="hidden" name="filter" value="events-section">
        <input type="text" name="events_search" placeholder="Search events..." value="<?php echo htmlspecialchars($eventsSearch); ?>">
        <select name="events_sort">
            <option value="start_time" <?php echo $eventsSort === 'start_time' ? 'selected' : ''; ?>>Start Time</option>
            <option value="title" <?php echo $eventsSort === 'title' ? 'selected' : ''; ?>>Title</option>
        </select>
        <select name="events_order">
            <option value="ASC" <?php echo $eventsOrder === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
            <option value="DESC" <?php echo $eventsOrder === 'DESC' ? 'selected' : ''; ?>>Descending</option>
        </select>
        <label for="records_per_page">Results per page:</label>
        <input type="number" id="records_per_page" name="records_per_page" value="<?php echo $recordsPerPage; ?>" min="1">
        <button type="submit">Search & Sort</button>
    </form>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($eventsData['data'] as $event): ?>
        <tr>
            <td><?php echo htmlspecialchars($event['title']); ?></td>
            <td><?php echo htmlspecialchars($event['description']); ?></td>
            <td><?php echo htmlspecialchars($event['start_time']); ?></td>
            <td><?php echo htmlspecialchars($event['end_time']); ?></td>
            <td>
                <a href="../views/edit_event.php?id=<?php echo htmlspecialchars($event['id']); ?>"><button>Edit</button></a>
                <form method="POST" action="../controllers/event_controller.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this event?');">Delete</button>
                </form>
            </td>
            <td><a href="../views/event_details.php?id=<?= htmlspecialchars($event['id']); ?>"><button>See Details</button></a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div>
        <?php for ($i = 1; $i <= ceil($eventsData['total'] / $recordsPerPage); $i++): ?>
            <a href="?filter=events-section&events_page=<?php echo $i; ?>&events_search=<?php echo $eventsSearch; ?>&events_sort=<?php echo $eventsSort; ?>&events_order=<?php echo $eventsOrder; ?>&records_per_page=<?php echo $recordsPerPage; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</section>


        <section id="users-section" class="manage-section" style="display:none;">
    <h2>Manage Users</h2>
    <a href="../views/create_user.php"><button>Add New User</button></a>
    <form method="GET" action="">
    <input type="hidden" name="filter" value="users-section">
    <input type="hidden" name="users_page" value="<?php echo $usersPage; ?>">
    <input type="text" name="users_search" placeholder="Search users..." value="<?php echo htmlspecialchars($usersSearch); ?>">
    <select name="users_sort">
        <option value="name" <?php echo $usersSort === 'name' ? 'selected' : ''; ?>>Name</option>
        <option value="email" <?php echo $usersSort === 'email' ? 'selected' : ''; ?>>Email</option>
    </select>
    <select name="users_order">
        <option value="ASC" <?php echo $usersOrder === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
        <option value="DESC" <?php echo $usersOrder === 'DESC' ? 'selected' : ''; ?>>Descending</option>
    </select>
    <label for="records_per_page">Results per page:</label>
    <input type="number" id="records_per_page" name="records_per_page" value="<?php echo $recordsPerPage; ?>" min="1">
    <button type="submit">Search & Sort</button>
</form>

    <button onclick="printSection('users-section')">Print Users</button>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($usersData['data'] as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
            <td>
                <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>&role=user"><button>Edit</button></a>
                <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                </form>
            </td>
            <td>
    <a href="../views/user_details.php?id=<?= htmlspecialchars($user['id']); ?>&role=user"><button>See Details</button></a>
</td>

        </tr>
        <?php endforeach; ?>
    </table>
    <div>
    <?php for ($i = 1; $i <= ceil($usersData['total'] / $recordsPerPage); $i++): ?>
    <a href="?filter=users-section&users_page=<?php echo $i; ?>&users_search=<?php echo $usersSearch; ?>&users_sort=<?php echo $usersSort; ?>&users_order=<?php echo $usersOrder; ?>&records_per_page=<?php echo $recordsPerPage; ?>">
        <?php echo $i; ?>
    </a>
<?php endfor; ?>
    </div>
</section>


        <!-- Manage Admin Users Section -->
        <section id="admins-section" class="manage-section" style="display:none;">
    <h2>Manage Admin Users</h2>
    <form method="GET" action="">
    <input type="hidden" name="filter" value="admins-section">
    <input type="hidden" name="admins_page" value="<?php echo $adminsPage; ?>">
    <input type="text" name="admins_search" placeholder="Search admin users..." value="<?php echo htmlspecialchars($adminsSearch); ?>">
    <select name="admins_sort">
        <option value="name" <?php echo $adminsSort === 'name' ? 'selected' : ''; ?>>Name</option>
        <option value="email" <?php echo $adminsSort === 'email' ? 'selected' : ''; ?>>Email</option>
    </select>
    <select name="admins_order">
        <option value="ASC" <?php echo $adminsOrder === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
        <option value="DESC" <?php echo $adminsOrder === 'DESC' ? 'selected' : ''; ?>>Descending</option>
    </select>
    <label for="records_per_page">Results per page:</label>
    <input type="number" id="records_per_page" name="records_per_page" value="<?php echo $recordsPerPage; ?>" min="1">
    <button type="submit">Search & Sort</button>
</form>


    <button onclick="printSection('admins-section')">Print Admin Users</button>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($adminsData['data'] as $admin): ?>
        <tr>
            <td><?php echo htmlspecialchars($admin['name']); ?></td>
            <td><?php echo htmlspecialchars($admin['email']); ?></td>
            <td><?php echo htmlspecialchars($admin['phone_number']); ?></td>
            <td>
                <a href="../views/edit_user.php?id=<?php echo htmlspecialchars($admin['id']); ?>&role=admin"><button>Edit</button></a>
                <form method="POST" action="../controllers/user_controller.php" style="display:inline;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($admin['id']); ?>">
    <input type="hidden" name="role" value="admin"> <!-- Explicitly pass the role -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <button type="submit" onclick="return confirm('Are you sure you want to delete this admin user?');">Delete</button>
</form>


            </td>
            <td>
    <a href="../views/user_details.php?id=<?= htmlspecialchars($admin['id']); ?>&role=admin"><button>See Details</button></a>
</td>

        </tr>
        <?php endforeach; ?>
    </table>
    <div>
    <?php for ($i = 1; $i <= ceil($adminsData['total'] / $recordsPerPage); $i++): ?>
    <a href="?filter=admins-section&admins_page=<?php echo $i; ?>&admins_search=<?php echo $adminsSearch; ?>&admins_sort=<?php echo $adminsSort; ?>&admins_order=<?php echo $adminsOrder; ?>&records_per_page=<?php echo $recordsPerPage; ?>">
        <?php echo $i; ?>
    </a>
<?php endfor; ?>

    </div>
</section>


    </main>
</body>
</html>