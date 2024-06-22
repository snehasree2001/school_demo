<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $class_id = $_POST["class_id"];
        $image = $_FILES["image"]["name"];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image
        $valid = true;
        if (empty($name)) {
            echo "Name is required.";
            $valid = false;
        }
        if ($image && !in_array($imageFileType, ["jpg", "png"])) {
            echo "Only JPG and PNG files are allowed.";
            $valid = false;
        }

        if ($valid) {
            if ($image) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE students SET name='$name', email='$email', address='$address', class_id='$class_id', image='$image' WHERE id=$id";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                $sql = "UPDATE students SET name='$name', email='$email', address='$address', class_id='$class_id' WHERE id=$id";
            }

            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $sql = "SELECT * FROM students WHERE id = $id";
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();

    // Fetch classes for the dropdown
    $class_result = $conn->query("SELECT * FROM classes");
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Edit Student</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>"><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"><?php echo htmlspecialchars($student['address']); ?></textarea><br>

        <label for="class">Class:</label>
        <select id="class" name="class_id">
            <?php
            if ($class_result->num_rows > 0) {
                while ($class_row = $class_result->fetch_assoc()) {
                    $selected = $class_row["class_id"] == $student["class_id"] ? "selected" : "";
                    echo "<option value='" . htmlspecialchars($class_row["class_id"]) . "' $selected>" . htmlspecialchars($class_row["name"]) . "</option>";
                }
            }
            ?>
        </select><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br>
        <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" alt="Student Image" width="100"><br>

        <input type="submit" value="Update Student">
    </form>
</body>
</html>

<?php
$conn->close();
?>
