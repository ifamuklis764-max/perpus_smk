<?php
session_start();

if (!isset($_SESSION['admin_login'])) {
    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($id === false || $id === null) {
    header('Location: dashboard.php');
    exit;
}

header('Location: edit.php?id=' . $id);
exit;
