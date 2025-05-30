<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recv = trim($_POST['recv']);
    $subject = trim($_POST["subject"] ?? '');
    $message = trim($_POST["message"] ?? '');

    $from = "giack.peretti@gmail.com";

    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if ($recv && $subject && $message) {
        $mail_sent = mail($recv, $subject, $message, $headers);
        if ($mail_sent) {
            $status = "Email sent successfully to $recv.";
        } else {
            $status = "Failed to send email.";
        }
    } else {
        $status = "Please provide a valid recipient email, subject, and message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Send Email</title>
    <style>
        .status-message {
            text-align: center;
            margin-top: 1rem;
            color: #fab387;
        }
    </style>
</head>

<body>
    <div>
        <h1>Manda una Mail</h1>
        <?php if (isset($status)): ?>
            <p class="status-message"><?php echo htmlspecialchars($status); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="recv">Mail Ricevente:</label><br/>
            <input type="email" id="recv" name="recv" required><br/>

            <label for="oggetto">Oggetto:</label><br/>
            <input type="text" id="oggetto" name="oggetto" required><br/>

            <label for="messaggio">Messaggio:</label><br/>
            <textarea id="messaggio" name="messaggio" rows="5" required></textarea><br/>

            <input type="submit" value="Send Email">
        </form>
    </div>
</body>

</html>