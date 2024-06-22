<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT students.id, students.name, students.email, students.address, students.created_at, students.image, classes.name as class_name 
            FROM students 
            JOIN classes ON students.class_id = classes.class_id 
            WHERE students.id = $id";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>View Student</h1>
    <p>Name: <?php echo htmlspecialchars($student['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
    <p>Address: <?php echo htmlspecialchars($student['address']); ?></p>
    <p>Class: <?php echo htmlspecialchars($student['class_name']); ?></p>
    <p>Created At: <?php echo htmlspecialchars($student['created_at']); ?></p>
    <p><img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Student Image" width="200"></p>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>

<?php
$conn->close();
?>
