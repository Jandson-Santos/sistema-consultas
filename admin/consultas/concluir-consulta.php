<?php
session_start();
require_once("../../config/db.php");

// Verifica se admin esta logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Verifica ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: consultas.php");
    exit();
}

$consulta_id = intval($_GET['id']);

// Atualiza o status
$stmt = $conn->prepare("UPDATE consultas SET status = 'concluida' WHERE id = ?");
$stmt->bind_param("i", $consulta_id);
$stmt->execute();

header("Location: consultas.php");
exit();
?>

