<?php
require_once("../config/db.php");
session_start();

if (!isset($_SESSION["admin_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Acesso negado"]);
    exit();
}

$sql = "SELECT c.id, c.data, c.hora, c.status, p.nome AS paciente_nome, m.nome AS medico_nome 
        FROM consultas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN medicos m ON c.medico_id = m.id";

$result = $conn->query($sql);

$events = [];

while ($row = $result->fetch_assoc()) {
    $title = "{$row['paciente_nome']} - {$row['medico_nome']} ({$row['status']})";

    $start = $row['data'] . 'T' . $row['hora'];

    $end_time = date('H:i:s', strtotime($row['hora'] . '+30 minutes'));
    $end = $row['data'] . 'T' . $end_time;

    $events[] = [
        "id" => $row['id'],
        "title" => $title,
        "start" => $start,
        "end" => $end,
        "status" => $row['status'],
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
