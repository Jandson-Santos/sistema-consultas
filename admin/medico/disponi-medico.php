<?php
session_start();
require_once("../config/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("
    SELECT d.id, d.dia_semana, d.hora_inicio, d.hora_fim, m.nome 
    FROM disponibilidades d
    JOIN medicos m ON d.medico_id = m.id
    ORDER BY m.nome, FIELD(d.dia_semana, 'segunda','terca','quarta','quinta','sexta','sabado','domingo')
");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidades dos Médicos</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Horários dos Médicos</h1>
        <a href="disponibilidade-nova.php"><button>Adicionar Horário</button></a>
        <br><br>
        <table border="1" cellpadding="8">
            <tr>
                <th>Médico</th>
                <th>Dia da Semana</th>
                <th>Hora Início</th>
                <th>Hora Fim</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["nome"]; ?></td>
                    <td><?php echo ucfirst($row["dia_semana"]); ?></td>
                    <td><?php echo $row["hora_inicio"]; ?></td>
                    <td><?php echo $row["hora_fim"]; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
