<?php
require_once("../config/db.php");

if (!isset($_GET["medico_id"])) {
    echo json_encode([]);
    exit();
}

$medico_id = intval($_GET["medico_id"]);

$stmt = $conn->prepare("SELECT DISTINCT dia_semana FROM disponibilidades WHERE medico_id = ?");
$stmt->bind_param("i", $medico_id);
$stmt->execute();
$res = $stmt->get_result();

$dias = [];
while ($row = $res->fetch_assoc()) {
    $dia = strtolower($row["dia_semana"]);

    // Remover acentos
    $dia_sem_acento = preg_replace(
        ['/[áàãâä]/u', '/[éèêë]/u', '/[íìîï]/u', '/[óòõôö]/u', '/[úùûü]/u', '/[ç]/u'],
        ['a', 'e', 'i', 'o', 'u', 'c'],
        $dia
    );

    $dias[] = $dia_sem_acento;
}

echo json_encode($dias);
