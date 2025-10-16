<?php
// WARNING: This version stores passwords as plaintext. This is UNSAFE and NOT RECOMMENDED!
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo "<p style='color:red;'>❌ Passwords do not match.</p>";
    } else {
        // Check if account exists
        $sql = "SELECT * FROM users WHERE email = ? AND phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            // Store password as plaintext (UNSAFE!)
            $update = "UPDATE users SET password = ? WHERE email = ? AND phone = ?";
            $stmt_update = $conn->prepare($update);
            $stmt_update->bind_param("sss", $new_password, $email, $phone);
            
            if ($stmt_update->execute()) {
                echo "<p style='color:green;'>✅ Password successfully reset! You can now <a href='login.php'>login</a>.</p>";
            } else {
                echo "<p style='color:red;'>⚠️ Error updating password. Please try again later.</p>";
            }

            $stmt_update->close();
        } else {
            echo "<p style='color:red;'>No account found with that email and phone number.</p>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>