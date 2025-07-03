<?php
session_start();
require_once("../../config/db.php");

// Verifica se o paciente está logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];

// Buscar consultas do paciente
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
    <title>Minhas Consultas</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Minhas Consultas</h1>
        <a href="perfil-paciente.php"><button>Voltar ao Perfil</button></a>
        <br><br>

        <?php if ($res->num_rows === 0): ?>
            <p>Você não possui consultas agendadas.</p>
        <?php else: ?>
            <table border="1" cellpadding="10">
                <thead>
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
                            <td><?php echo htmlspecialchars($row["status"]); ?></td>
                            <td>
                                <?php if ($row["status"] === "agendada" && strtotime($row["data"]) >= strtotime(date("Y-m-d"))): ?>
                                    <a href="remarcar-consulta.php?id=<?php echo $row["id"]; ?>">
                                        <button>Remarcar</button>
                                    </a>
                                <?php else: ?>
                                    <em>Indisponível</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>

