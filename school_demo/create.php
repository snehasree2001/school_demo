<?php
// Include the database configuration file
include 'config.php';

// Fetch classes for the dropdown
$classes_result = $conn->query("SELECT class_id, name FROM classes");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = '';

    // Validate and handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($file_type, $allowed_types)) {
            $file_name = uniqid() . '-' . basename($_FILES['image']['name']);
            $upload_dir = 'uploads/';
            $upload_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                $image = $file_name;
            } else {
                echo "Failed to upload image.";
                exit;
            }
        } else {
            echo "Invalid image format.";
            exit;
        }
    }

    // Insert the new student into the database
    $stmt = $conn->prepare("INSERT INTO students (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $name, $email, $address, $class_id, $image);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Student</h2>
    <form action="create.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="class_id">Class</label>
            <select name="class_id" id="class_id" class="form-control" required>
                <?php while ($row = $classes_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['class_id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control" accept=".jpg, .jpeg, .png">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
