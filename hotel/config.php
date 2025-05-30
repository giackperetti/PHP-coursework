<?php

function checkAuth()
{
    return isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']);
}

function getUserId()
{
    return isset($_COOKIE['user_id']) ? (int)$_COOKIE['user_id'] : null;
}

try {
    $conn = new mysqli("localhost", "root", "root", "5f_hotel");

    if ($conn->connect_error) {
        throw new Exception("Connessione fallita: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

function escape_output($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function validateDates($start_date, $end_date)
{
    $start = DateTime::createFromFormat('Y-m-d', $start_date);
    $end = DateTime::createFromFormat('Y-m-d', $end_date);
    $today = new DateTime();
    $today->setTime(0, 0, 0);

    return $start && $end &&
        $start->format('Y-m-d') === $start_date &&
        $end->format('Y-m-d') === $end_date &&
        $start >= $today &&
        $end > $start;
}
