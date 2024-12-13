<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete User/Admin</title>
</head>
<body>
<h1>Delete User or Admin</h1>
<form method="POST" action="../controllers/user_controller.php">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<!-- ID dynamically inserted here -->">

    <p>Are you sure you want to delete this <!-- User or Admin -->?</p>



    <button type="submit">Confirm Deletion</button>
    <button type="button" onclick="window.history.back();">Cancel</button>
</form>
</body>
</html>
