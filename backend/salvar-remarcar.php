<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];
$consulta_id = $_POST["consulta_id"] ?? null;
$medico_id = $_POST["medico_id"] ?? null;
$data = $_POST["data"] ?? null;
$hora = $_POST["hora"] ?? null;

    // Verificar se os dados foram enviados
    if (!$consulta_id || !$data || !$hora) {
        echo "Dados incompletos.";
        exit();
    }

// Verificar se a consulta pertence ao paciente
$stmt = $conn->prepare("SELECT id FROM consultas WHERE id = ? AND paciente_id = ?");
$stmt->bind_param("ii", $consulta_id, $paciente_id);
$stmt->execute();
$res = $stmt->get_result();

    if ($res->num_rows !== 1) {
        echo "Consulta não encontrada ou acesso negado.";
        exit();
    }

// Verificar se o horario ja esta ocupado
$confere = $conn->prepare("SELECT id FROM consultas WHERE medico_id = ? AND data = ? AND hora = ? AND id != ?");
$confere->bind_param("issi", $medico_id, $data, $hora, $consulta_id);
$confere->execute();
$existe = $confere->get_result();

    if ($existe->num_rows > 0) {
        echo "Este horário já está reservado.";
        exit();
    }

// Atualizar a consulta
$update = $conn->prepare("UPDATE consultas SET data = ?, hora = ? WHERE id = ?");
$update->bind_param("ssi", $data, $hora, $consulta_id);
$update->execute();

header("Location: /sistema-consultas/public/paciente/perfil-paciente.php");
exit();
?>
