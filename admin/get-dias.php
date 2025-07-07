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
  $dias[] = strtolower($row["dia_semana"]);
}

echo json_encode($dias);
