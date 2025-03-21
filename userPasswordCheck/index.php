<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Basic Auth</title>
</head>

<body>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <label for="nome">Username:</label>
        <input type="text" id="nome" name="username" required />
        <br />
        <label for="nome">Password:</label>
        <input type="password" id="nome" name="password" required />
        <br />
        <input type="submit" value="Invia">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_REQUEST["username"]);
        $password = htmlspecialchars($_REQUEST["password"]);

        $users = [
            "root" => "admin123",
            "ciao" => "test",
        ];

        if (isset($users[$username]) && $users[$username] == $password) {
            if ($username == "root") {
                echo "<h1>Autenticato come amministratore</h1>";
            } else {
                echo "<h1>Autenticato come $username</h1>";
            }
        }
    }
    ?>
</body>

</html>
