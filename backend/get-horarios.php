<?php
require_once("../config/db.php");

$medico_id = $_GET["medico_id"] ?? null;
$data = $_GET["data"] ?? null;

if (!$medico_id) {
    echo json_encode(["erro" => "ID do médico ausente"]);
    exit();
}

$dias_traducao = [
    "sunday"    => "domingo",
    "monday"    => "segunda",
    "tuesday"   => "terca",
    "wednesday" => "quarta",
    "thursday"  => "quinta",
    "friday"    => "sexta",
    "saturday"  => "sabado"
];

if ($data) {
    // Traduz o dia
    $dia_semana_en = strtolower(date("l", strtotime($data))); // ex: 'monday'
    $dia_semana = $dias_traducao[$dia_semana_en] ?? "";

    if (!$dia_semana) {
        echo json_encode([]);
        exit();
    }

    // Busca a disponibilidade do medico 
    $stmt = $conn->prepare("SELECT hora_inicio, hora_fim FROM disponibilidades WHERE medico_id = ? AND dia_semana = ?");
    $stmt->bind_param("is", $medico_id, $dia_semana);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo json_encode([]);
        exit();
    }

    $faixa = $res->fetch_assoc();
    $inicio = strtotime($faixa["hora_inicio"]);
    $fim = strtotime($faixa["hora_fim"]);

    $intervalo = 30 * 60; // intervalo de 30 minutos
    $horarios = [];

    for ($h = $inicio; $h < $fim; $h += $intervalo) {
        $hora_formatada = date("H:i", $h);

        // Verifica se o horario esta ocupado por outra consulta
        $confere = $conn->prepare("SELECT id FROM consultas WHERE medico_id = ? AND data = ? AND hora = ? AND status != 'cancelada'");
        $confere->bind_param("iss", $medico_id, $data, $hora_formatada);
        $confere->execute();
        $ocupado = $confere->get_result()->num_rows > 0;

        if (!$ocupado) {
            $horarios[] = $hora_formatada;
        }
    }

    echo json_encode($horarios);
    exit();
}

$stmt = $conn->prepare("SELECT dia_semana FROM disponibilidades WHERE medico_id = ?");
$stmt->bind_param("i", $medico_id);
$stmt->execute();
$res = $stmt->get_result();

$dias = [];
while ($row = $res->fetch_assoc()) {
    $dias[] = $row["dia_semana"];
}

echo json_encode($dias);
exit();
