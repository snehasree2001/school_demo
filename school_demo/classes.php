<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $sql = "INSERT INTO classes (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        header("Location: classes.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch classes
$class_result = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Manage Classes</h1>
    <h2>Add Class</h2>
    <form action="classes.php" method="post">
        <label for="name">Class Name:</label>
        <input type="text" id="name" name="name"><br>
        <input type="submit" value="Add Class">
    </form>

    <h2>Class List</h2>
    <table>
        <tr>
            <th>Class Name</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($class_result->num_rows > 0) {
            while($class_row = $class_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($class_row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($class_row["created_at"]) . "</td>";
                echo "<td>
                        <a href='edit_class.php?id=" . htmlspecialchars($class_row["class_id"]) . "'>Edit</a> |
                        <a href='delete_class.php?id=" . htmlspecialchars($class_row["class_id"]) . "'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No classes found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
