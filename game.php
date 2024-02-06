<?php
// Start session
session_start();

include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: index.php");
    exit();
}
$student_id = $_SESSION["student_id"];

// Initialize conflict and success messages
$conflict_message = "";
$success_message = "";

// Process form submission and store game registration details in the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get game registration data from form
    $game_name = $_POST["game_name"];
    $board = $_POST["board"];
    $players_number = $_POST["players_number"];
    $slot = $_POST["slot"];
    $appointment_date = $_POST["appointment_date"];

    // Check if the same combination already exists
    $sql_check_conflict = "SELECT COUNT(*) FROM game_registration WHERE game_name = ? AND board = ? AND slot = ? AND apdate = ?";
    $stmt_check_conflict = $conn->prepare($sql_check_conflict);
    $stmt_check_conflict->bind_param("siss", $game_name, $board, $slot, $appointment_date);
    $stmt_check_conflict->execute();
    $stmt_check_conflict->bind_result($conflict_count);
    $stmt_check_conflict->fetch();
    $stmt_check_conflict->close();

    // If a conflict exists, suggest alternative options to the user
    if ($conflict_count > 0) {
        // Set conflict message
        $conflict_message = "There is a conflict with your selected options. Please choose a different game or available board on the selected time slot and date.";
    } else {
        // Prepare and execute SQL statement to insert game registration data
        $sql = "INSERT INTO game_registration (student_id, game_name, board, players_number, slot, apdate) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $student_id, $game_name, $board, $players_number, $slot, $appointment_date);
        $stmt->execute();
        $stmt->close();

        // Set success message
        $success_message = "Registration successful for $game_name on Board $board, Date: $appointment_date, Time: $slot.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Registration</title>
    <link rel="icon" href="images/SEU_logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/gamestyle.css">
</head>
<body>
    <div class="container">
        <div class="signup-container">
        <div class="logo">
        <img src="images/SEU_logo.png" alt="Logo" width="75">
        </div>
        <h2>Game Registration</h2>
        <?php if (!empty($conflict_message)) : ?>
            <div class="message error"><?php echo $conflict_message; ?></div>
        <?php elseif (!empty($success_message)) : ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form id="gameRegistrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="game_name">Game Name</label>
                <select name="game_name" id="game_name" class="form-control" required>
                    <option value="">Select One</option>
                    <option value="Table Tennis">Table Tennis</option>
                    <option value="Chess">Chess</option>
                    <option value="Carrom">Carrom</option>
                    <option value="Foosball">Foosball</option>
                </select>
            </div>
            <div class="form-group">
                <label for="board">Board ID</label>
                <select name="board" id="board" class="form-control" required>
                    <!-- Options will be dynamically generated based on selected game -->
                </select>
            </div>
            <div class="form-group">
                <label for="players_number">Players Number</label>
                <input type="text" id="players_number" name="players_number" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="appointment_date">Appointment Date</label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" required>
            </div>
            <div class="form-group">
                <label for="time_slot">Time Slot</label>
                <select name="slot" id="slot" class="form-control" required>
                    <option value="">Select One</option>
                    <?php
                    // Generating time slots from 9AM to 5PM
                    $start_time = strtotime('09:00');
                    $end_time = strtotime('17:00');
                    $interval = 60 * 60; // 1 hour interval
                    for ($i = $start_time; $i < $end_time; $i += $interval) {
                        $time_slot = date('H:i', $i) . '-' . date('H:i', $i + $interval);
                        echo '<option value="' . $time_slot . '">' . $time_slot . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="col text-left">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
        </form>
                
                <div class="col text-right">
                 <form id="logoutForm" action="logout.php" method="post">
                       <button type="submit" class="btn btn-danger">Logout</button>
                 </form>
                </div>
            </div>
    </div>
    <script src="script/gamejs.js"></script>
</body>
</html>
