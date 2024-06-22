<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Delete image from server
        $sql = "SELECT image FROM students WHERE id = $id";
        $result = $conn->query($sql);
        $student = $result->fetch_assoc();
        if (file_exists("uploads/" . $student['image'])) {
            unlink("uploads/" . $student['image']);
        }

        // Delete student from database
        $sql = "DELETE FROM students WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        $sql = "SELECT * FROM students WHERE id = $id";
        $result = $conn->query($sql);
        $student = $result->fetch_assoc();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Delete Student</h1>
    <p>Are you sure you want to delete the student <strong><?php echo htmlspecialchars($student['name']); ?></strong>?</p>
    <form action="delete.php?id=<?php echo $id; ?>" method="post">
        <input type="submit" value="Yes, Delete">
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>

<?php
$conn->close();
?>
