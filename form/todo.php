<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("db.php");

$user_id = $_SESSION["user_id"];

// Handle Task Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task"])) {
    $task = $_POST["task"];
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $task);
        $stmt->execute();
    }
}

// Handle Task Deletion
if (isset($_GET["delete"])) {
    $task_id = $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    header("Location: todo.php"); // Refresh page
    exit();
}

// Fetch User's Tasks
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h3 class="todo">Welcome, <?php echo $_SESSION["username"]; ?>!</h3>

    <div class="container">
    <h2>My To-Do List</h2>
    <form method="POST">
        <input type="text" name="task" placeholder="Enter a new task..." required>
        <button type="submit" class="btn">Add Task</button>
    </form>

    <ul class="task-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row["task"]); ?>
                <a href="?delete=<?php echo $row["id"]; ?>" class="delete-btn">❌</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<!-- Logout button placed at the bottom -->
<a href="logout.php" class="btn btn-logout">Log Out</a>

</body>
</html>
