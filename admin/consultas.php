<?php
require_once("../config/db.php");
session_start();

// Consulta as consultas com JOIN para pegar nome do paciente e do médico
$sql = "SELECT 
            c.id, c.data, c.hora, c.status, 
            p.nome AS paciente_nome, 
            m.nome AS medico_nome 
        FROM consultas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN medicos m ON c.medico_id = m.id
        ORDER BY c.data, c.hora";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Consultas Marcadas</title>
    <link rel="stylesheet" href="../public/css/style.css" />
    <style>
        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
        }
        .voltar {
            display: block;
            text-align: center;
            margin: 20px;
        }
        .status-agendada {
            color: green;
            font-weight: bold;
        }
        .status-cancelada {
            color: red;
            font-weight: bold;
        }
        .status-concluida {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Consultas Marcadas</h1>

    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["paciente_nome"]) ?></td>
                        <td><?= htmlspecialchars($row["medico_nome"]) ?></td>
                        <td><?= htmlspecialchars($row["data"]) ?></td>
                        <td><?= htmlspecialchars($row["hora"]) ?></td>
                        <td class="status-<?= strtolower($row["status"]) ?>">
                            <?= htmlspecialchars(ucfirst($row["status"])) ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhuma consulta agendada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="voltar">
        <a href="dashboard.php">← Voltar para o painel</a>
    </div>
</body>
</html>
