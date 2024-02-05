<?php
include 'connection.php';
// Define variables and initialize with empty values
$name = $student_id = $email = $department = $password = "";
$name_err = $student_id_err = $email_err = $department_err = $password_err = "";

// Define a variable to check if registration is successful
$registration_success = false;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate student ID
    if (empty(trim($_POST["student_id"]))) {
        $student_id_err = "Please enter your student ID.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM students WHERE student_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_student_id);

            // Set parameters
            $param_student_id = trim($_POST["student_id"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $student_id_err = "Already Registered Student ID.";
                } else {
                    $student_id = trim($_POST["student_id"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate department
    if (empty(trim($_POST["department"]))) {
        $department_err = "Please select your department.";
    } else {
        $department = trim($_POST["department"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($student_id_err) && empty($email_err) && empty($department_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO students (name, student_id, email, department, password) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_name, $param_student_id, $param_email, $param_department, $param_password);

            // Set parameters
            $param_name = $name;
            $param_student_id = $student_id;
            $param_email = $email;
            $param_department = $department;
            $param_password = $password; // Plain text password (not recommended, use password hashing)

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Set registration success flag to true
                $registration_success = true;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
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
    <title>Student Sign Up</title>
    <link rel="icon" href="images/SEU_logo.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/regstyle.css">
    <!-- Include jQuery -->
    <script src="script/jquery-3.6.0.min.js"></script>
    <!-- Include Bootstrap JavaScript -->
    <script src="script/bootstrap.min.js"></script>
    <script>
        // JavaScript function to show pop-up
        $(document).ready(function(){
            <?php if($registration_success): ?>
            // Show the pop-up if registration is successful
            $('#successModal').modal('show');
            <?php endif; ?>
        });
    </script>
</head>
<body>

<!-- Success pop-up modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Your registration is successful. Click OK to proceed to login.
            </div>
            <div class="modal-footer">
                <a href="index.php" class="btn btn-primary">OK</a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="signup-container">
        <div class="logo">
        <img src="images/SEU_logo.png" alt="Logo" width="75">
        </div>
        <h2>Student Registration</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="number" class="form-control" id="student_id" name="student_id" value="<?php echo $student_id; ?>">
                <span class="text-danger"><?php echo $student_id_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" id="department" name="department">
                    <option value="">Select your department</option>
                    <option value="CSE" <?php if ($department === 'CSE') echo 'selected'; ?>>CSE</option>
                    <option value="EEE" <?php if ($department === 'EEE') echo 'selected'; ?>>EEE</option>
                    <option value="Textile" <?php if ($department === 'Textile') echo 'selected'; ?>>Textile</option>
                </select>
                <span class="text-danger"><?php echo $department_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>">
                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-signup">Sign Up</button>
        </form>
        <div class="text-center mt-3">
            <p>Already Registered? <a href="index.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
