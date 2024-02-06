<?php
include 'connection.php';


// Define variables and initialize with empty values
$student_id = $password = "";
$student_id_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate student ID
    if (empty(trim($_POST["studentid"]))) {
        $student_id_err = "Please enter your student ID.";
    } else {
        $student_id = trim($_POST["studentid"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before querying the database
    if (empty($student_id_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT student_id, password FROM students WHERE student_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_student_id);

            // Set parameters
            $param_student_id = $student_id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if student ID exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($student_id, $db_password);
                    if ($stmt->fetch()) {
                        if ($password == $db_password) {
                            // Password is correct, start a new session and store student ID
                            session_start();
                            $_SESSION["student_id"] = $student_id;

                            // Redirect to game.php
                            header("location: game.php");
                            exit();
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "Invalid password.";
                        }
                    }
                } else {
                    // Display an error message if student ID doesn't exist
                    $student_id_err = "No account found with this student ID.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement here
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="icon" href="images/SEU_logo.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="login-container">
        <div class="logo">
            <img src="images/SEU_logo.png" alt="Logo" width="75">
        </div>
        <h2>Student Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="studentid">Student ID</label>
                <input type="text" class="form-control" id="studentid" name="studentid" value="<?php echo $student_id; ?>">
                <span class="text-danger"><?php echo $student_id_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-login">Login</button>
        </form>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="registration.php" class="btn btn-outline-primary btn-signup">Register</a></p>
        </div>
    </div>
</div>
<script src="script/bootstrap.min.js"></script>
</body>
</html>
