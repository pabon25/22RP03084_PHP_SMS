<?php
session_start();
include 'conn.php';

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['save'])) {
    $reg_no = htmlspecialchars($_POST['reg_no']);
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $dept = htmlspecialchars($_POST['dept']);
    $address = htmlspecialchars($_POST['address']);
    $errors = [];

    // Validation
    if (empty($reg_no) || !preg_match("/^[a-zA-Z0-9]{9}$/", $reg_no)) $errors['reg_no'] = "Reg No must be exactly 9 digits and character long";
    if (empty($fullname)) $errors['fullname'] = "Full name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email is required";
    if (empty($phone) || !preg_match("/^\d{10,13}$/", $phone)) $errors['phone'] = "Valid phone number is required";
    if (empty($age) || !filter_var($age, FILTER_VALIDATE_INT) || $age < 20 || $age > 100) $errors['age'] = "Valid age is required";
    if (empty($gender)) $errors['gender'] = "Gender is required";
    if (empty($dept)) $errors['dept'] = "Department is required";
    if (empty($address)) $errors['address'] = "Address is required";

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO student (reg_no, fullname, email, phone, age, gender, dept, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE fullname=?, email=?, phone=?, age=?, gender=?, dept=?, address=?");
        mysqli_stmt_bind_param($stmt, "ssssissssssssss", $reg_no, $fullname, $email, $phone, $age, $gender, $dept, $address, $fullname, $email, $phone, $age, $gender, $dept, $address);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: manage.php");
        exit();
    }
}

if (isset($_GET['delete'])) {
    $reg_no = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM student WHERE reg_no=?");
    mysqli_stmt_bind_param($stmt, "s", $reg_no);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: manage.php");
    exit();
}

$student = [];
if (isset($_GET['edit'])) {
    $reg_no = $_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM student WHERE reg_no = ?");
    mysqli_stmt_bind_param($stmt, "s", $reg_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

$students = mysqli_query($conn, "SELECT * FROM student");
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="?logout=true" class="btn btn-danger">Logout</a>
    </header>

    <div class="container">
        <h2><?php echo isset($student['reg_no']) ? 'Update Student' : 'Add New Student'; ?></h2>
        <form action="manage.php" method="post">
            <label for="reg_no">Reg No:</label>
            <input type="text" id="reg_no" name="reg_no" value="<?php echo htmlspecialchars($student['reg_no'] ?? ''); ?>">
            <?php if (isset($errors['reg_no'])): ?><span class="error">* <?php echo htmlspecialchars($errors['reg_no']); ?></span><?php endif; ?><br><br>

            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student['fullname'] ?? ''); ?>">
            <?php if (isset($errors['fullname'])): ?><span class="error">* <?php echo htmlspecialchars($errors['fullname']); ?></span><?php endif; ?><br><br>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>">
            <?php if (isset($errors['email'])): ?><span class="error">* <?php echo htmlspecialchars($errors['email']); ?></span><?php endif; ?><br><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
            <?php if (isset($errors['phone'])): ?><span class="error">* <?php echo htmlspecialchars($errors['phone']); ?></span><?php endif; ?><br><br>

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($student['age'] ?? ''); ?>">
            <?php if (isset($errors['age'])): ?><span class="error">* <?php echo htmlspecialchars($errors['age']); ?></span><?php endif; ?><br><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?php if (isset($student['gender']) && $student['gender'] == "Male") echo "selected"; ?>>Male</option>
                <option value="Female" <?php if (isset($student['gender']) && $student['gender'] == "Female") echo "selected"; ?>>Female</option>
                <option value="Other" <?php if (isset($student['gender']) && $student['gender'] == "Other") echo "selected"; ?>>Other</option>
            </select>
            <?php if (isset($errors['gender'])): ?><span class="error">* <?php echo htmlspecialchars($errors['gender']); ?></span><?php endif; ?><br><br>

            <label for="dept">Department:</label>
            <input type="text" id="dept" name="dept" value="<?php echo htmlspecialchars($student['dept'] ?? ''); ?>">
            <?php if (isset($errors['dept'])): ?><span class="error">* <?php echo htmlspecialchars($errors['dept']); ?></span><?php endif; ?><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
            <?php if (isset($errors['address'])): ?><span class="error">* <?php echo htmlspecialchars($errors['address']); ?></span><?php endif; ?><br><br>

            <button type="submit" name="save">Save Student</button>
        </form>

        <h2>Student List</h2>
        <table>
            <tr>
                <th>Reg No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Department</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
            <?php while ($student = mysqli_fetch_assoc($students)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['reg_no']); ?></td>
                    <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                    <td><?php echo htmlspecialchars($student['age']); ?></td>
                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                    <td><?php echo htmlspecialchars($student['dept']); ?></td>
                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                    <td>
                        <a href="manage.php?edit=<?php echo htmlspecialchars($student['reg_no']); ?>" class="btn">Edit</a> | 
                        <a href="manage.php?delete=<?php echo htmlspecialchars($student['reg_no']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
