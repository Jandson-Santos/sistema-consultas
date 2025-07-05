<?php
require_once("../config/db.php");
session_start();

// Busca todos os pacientes
$sql = "SELECT nome, cpf, telefone, email FROM pacientes ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pacientes Cadastrados</title>
    <link rel="stylesheet" href="../public/css/style.css">
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
    </style>
</head>
<body>
    <h1>Lista de Pacientes</h1>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["nome"]) ?></td>
                        <td><?= htmlspecialchars($row["cpf"]) ?></td>
                        <td><?= htmlspecialchars($row["telefone"]) ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Nenhum paciente cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
