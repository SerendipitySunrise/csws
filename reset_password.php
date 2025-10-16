<?php
include('includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        echo "<p style='color:red;'>⚠️ Passwords do not match.</p>";
    } else {
        $sql = "SELECT * FROM users WHERE email = ? AND phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
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
            echo "<p style='color:red;'>⚠️ No account found with that email and phone number.</p>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form method="POST">
        <h2>Reset Password</h2>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="text" name="phone" placeholder="Enter your phone number" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
