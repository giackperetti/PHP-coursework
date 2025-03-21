<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Palindroma/Anagramma</title>
</head>

<body>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <label for="nome">Parola 1:</label>
        <input type="text" id="nome" name="parola1" />
        <br />
        <label for="nome">Parola 2:</label>
        <input type="text" id="nome" name="parola2" />
        <br />
        <input type="submit" value="Invia">
    </form>

    <?php
    function isPalindrome($a)
    {
        return ($a == strrev($a));
    }

    function isAnagram($a, $b)
    {
        return (count_chars($a, 1) == count_chars($b, 1));
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $parola1 = strtolower(htmlspecialchars($_REQUEST['parola1']));
        $parola2 = strtolower(htmlspecialchars($_REQUEST['parola2']));

        $anagram_str = (isAnagram($parola1, $parola2)) ? "Una parola e' anagramma della altra" : "Le parole non sono un anagramma";
        $parola1_palindrome_str = (isPalindrome($parola1)) ? "Parola1 e' palindroma" : "Parola1 non e' palindroma";
        $parola2_palindrome_str = (isPalindrome($parola2)) ? "Parola2 e' palindroma" : "Parola2 non e' palindroma";

        echo "<br/>Parola1: $parola1<br/>Parola2: $parola2";
        echo "<br/><br />$anagram_str<br/>$parola1_palindrome_str<br/>$parola2_palindrome_str";
    }
    ?>
</body>

</html>