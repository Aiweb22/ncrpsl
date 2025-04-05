
<?php
// Database සබඳතාව
$servername = "144.76.57.59";
$username = "u2925671_cNmUeOGPNP";
$password = "^M7mqsu7hKC@34Cg=fLbZ3KB";
$dbname = "s2925671_core";

// MySQL සබඳතාව සෑදීම
$conn = new mysqli($servername, $username, $password, $dbname);

// සබඳතාව පරීක්ෂා කිරීම
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Deposit Process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_username = $_POST['username'];
    $deposit_amount = (int)$_POST['amount'];

    // Allowed deposit amounts
    $allowed_amounts = [1, 2, 4, 5, 6, 8, 9, 10, 13, 15, 19, 21];

    // Check if deposit amount is valid
    if (!in_array($deposit_amount, $allowed_amounts)) {
        die("Invalid deposit amount! You can only deposit: " . implode(", ", $allowed_amounts));
    }

    // Get current credits
    $sql = "SELECT credits FROM accounts WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $player_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_credits = $row['credits'];

        // Update credits
        $new_credits = $current_credits + $deposit_amount;
        $update_sql = "UPDATE accounts SET credits = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $new_credits, $player_username);
        
        if ($update_stmt->execute()) {
            echo "Deposit successful! New balance: " . $new_credits;
        } else {
            echo "Error updating credits.";
        }
    } else {
        echo "User not found.";
    }
}

$conn->close();
?>

