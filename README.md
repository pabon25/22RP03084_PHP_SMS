<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>22RP03084_PHP_SMS README</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        code {
            background: #f4f4f4;
            padding: 2px 4px;
            border-radius: 4px;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>22RP03084_PHP_SMS</h1>

    <h2>Overview</h2>
    <p>This is a Student Management System (SMS) built using PHP and MySQL. It includes functionality for user authentication, student management, and CRUD operations. The application uses a MySQL database with two tables: <code>user</code> and <code>student</code>.</p>

    <h2>File Structure</h2>
    <ul>
        <li><strong>conn.php</strong>: Handles database connection.</li>
        <li><strong>index.php</strong>: Login page. Initial entry point with credentials:
            <ul>
                <li><strong>USERNAME</strong>: <code>manager</code></li>
                <li><strong>PASSWORD</strong>: <code>Admin!123</code></li>
            </ul>
        </li>
        <li><strong>logout.php</strong>: Handles user logout.</li>
        <li><strong>manage.php</strong>: Contains all CRUD operations for managing student records.</li>
        <li><strong>style.css</strong>: Contains styling for the application.</li>
    </ul>

    <h2>Database Setup</h2>
    <p><strong>Database Name:</strong> SMS</p>
    <h3>Tables</h3>
    <ul>
        <li><code>user</code>: Stores user credentials.</li>
        <li><code>student</code>: Stores student information.</li>
    </ul>

    <h3>SQL Schema</h3>
    <pre><code>-- Create user table
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create student table
CREATE TABLE student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    reg_no VARCHAR(9) NOT NULL UNIQUE,
    email VARCHAR(100),
    phone VARCHAR(15)
);</code></pre>

    <h2>Usage</h2>
    <ol>
        <li><strong>Login</strong>: Use the credentials provided to log in.</li>
        <li><strong>Manage Students</strong>: After logging in, you can perform CRUD operations on student records via <code>manage.php</code>.</li>
        <li><strong>Logout</strong>: Use <code>logout.php</code> to end your session.</li>
    </ol>

    <h2>Styling</h2>
    <p>The application is styled using <code>style.css</code>, which applies a red theme to the interface.</p>

    <h2>Notes</h2>
    <ul>
        <li>Ensure that your web server and MySQL server are running.</li>
        <li>Update file permissions as needed for your environment.</li>
    </ul>

    <h2>License</h2>
    <p>This project is licensed under the MIT License - see the <code>LICENSE</code> file for details.</p>
</body>
</html>
