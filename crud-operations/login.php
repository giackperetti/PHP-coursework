<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT IDPersona, Password FROM persona WHERE Email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['IDPersona'];
            header("Location: index.php");
            exit();
        }
    }
    $error_message = "Email o password non validi";
}
?>

<?php include 'header.php'; ?>
<h1>Login</h1>

<?php if (isset($error_message)): ?>
    <div class="error"><?= $error_message ?></div>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>
</div>
</body>