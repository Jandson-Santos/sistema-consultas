<?php
session_start();
require_once("../../config/db.php");

if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];

$stmt = $conn->prepare("
    SELECT consultas.id, consultas.data, consultas.hora, consultas.status, 
           medicos.nome AS nome_medico, medicos.especialidade
    FROM consultas
    JOIN medicos ON consultas.medico_id = medicos.id
    WHERE consultas.paciente_id = ?
    ORDER BY consultas.data DESC, consultas.hora DESC
");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Minhas Consultas - Clínica Pombos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            max-width: 900px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
        }
        h1 {
            color: #0071bc;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: #0071bc;
            border-color: #0071bc;
        }
        .btn-primary:hover {
            background-color: #005a8f;
            border-color: #005a8f;
        }
        table {
            table-layout: fixed;
            word-wrap: break-word;
        }
        td, th {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

    <main>
        <h1>Minhas Consultas</h1>
        <a href="perfil-paciente.php" class="btn btn-outline-primary mb-4">Voltar ao Perfil</a>

        <?php if ($res->num_rows === 0): ?>
            <p>Você não possui consultas agendadas.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Médico</th>
                            <th>Especialidade</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["data"]); ?></td>
                                <td><?php echo htmlspecialchars($row["hora"]); ?></td>
                                <td><?php echo htmlspecialchars($row["nome_medico"]); ?></td>
                                <td><?php echo htmlspecialchars($row["especialidade"]); ?></td>
                                <td>
                                    <?php 
                                        $status = htmlspecialchars($row["status"]);
                                        if ($status === "agendada") {
                                            echo '<span class="badge bg-success">Agendada</span>';
                                        } elseif ($status === "concluida") {
                                            echo '<span class="badge bg-secondary">Concluída</span>';
                                        } else {
                                            echo '<span class="badge bg-warning text-dark">'.ucfirst($status).'</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($row["status"] === "agendada" && strtotime($row["data"]) >= strtotime(date("Y-m-d"))): ?>
                                        <a href="remarcar-consulta.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary btn-sm">Remarcar</a>
                                    <?php else: ?>
                                        <span class="text-muted"><em>Indisponível</em></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


