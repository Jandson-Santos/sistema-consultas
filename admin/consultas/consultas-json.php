<?php
require_once("../config/db.php");
session_start();

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Acesso negado"]);
    exit();
}

// Busca consultas para mostrar no calendario
$sql = "SELECT c.id, c.data, c.hora, c.status, 
               p.nome AS paciente, 
               m.nome AS medico 
        FROM consultas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN medicos m ON c.medico_id = m.id";

$result = $conn->query($sql);

$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $color = "#378006"; 
        if ($row['status'] === 'cancelada') {
            $color = "#dc3545"; 
        } elseif ($row['status'] === 'concluida') {
            $color = "#007bff";
        }

        $events[] = [
            "id" => $row["id"],
            "title" => $row["medico"] . " - " . $row["paciente"],
            "start" => $row["data"] . "T" . $row["hora"] . ":00",
            "color" => $color,
            "extendedProps" => [
                "paciente" => $row["paciente"],
                "medico" => $row["medico"],
                "status" => $row["status"],
            ],
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($events);
