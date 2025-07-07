<?php
session_start();
require_once("../../config/db.php");

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Receber o id do medico pelo GET
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: medicos.php");
    exit();
}

$id = intval($_GET["id"]);

// Excluir medico da tabela
$stmt = $conn->prepare("DELETE FROM medicos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: medicos.php?msg=excluido");
    exit();
} else {
    echo "Erro ao excluir médico: " . $conn->error;
}
