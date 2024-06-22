<?php
include 'config.php';

// Fetch student data with the class name using a JOIN query
$sql = "SELECT students.id, students.name, students.email, students.created_at, students.image, classes.name as class_name 
        FROM students 
        JOIN classes ON students.class_id = classes.class_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Student List</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Creation Date</th>
            <th>Class Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["class_name"]) . "</td>";
                echo "<td><img src='uploads/" . htmlspecialchars($row["image"]) . "' alt='Student Image' width='50'></td>";
                echo "<td>
                        <a href='view.php?id=" . htmlspecialchars($row["id"]) . "'>View</a> |
                        <a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a> |
                        <a href='delete.php?id=" . htmlspecialchars($row["id"]) . "'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No students found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
