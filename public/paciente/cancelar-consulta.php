<?php
session_start();
require_once("../../config/db.php");

// Verifica se o paciente esta logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];
$consulta_id = $_GET["id"] ?? null;

if (!$consulta_id || !is_numeric($consulta_id)) {
    echo "Consulta inválida.";
    exit();
}

// Atualiza a consulta para status cancelada apenas se ela for do paciente logado
$stmt = $conn->prepare("UPDATE consultas SET status = 'cancelada' WHERE id = ? AND paciente_id = ?");
$stmt->bind_param("ii", $consulta_id, $paciente_id);

if ($stmt->execute()) {
    header("Location: perfil-paciente.php?cancelamento=sucesso");
} else {
    echo "Erro ao cancelar consulta.";
}
exit();
?>
