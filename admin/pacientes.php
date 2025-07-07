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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
        }
        h1 {
            color: #0071bc;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .table thead th {
            background-color: #0071bc;
            color: white;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #0071bc;
            border-color: #0071bc;
        }
        .btn-primary:hover {
            background-color: #005a8f;
            border-color: #005a8f;
        }
        .container {
            max-width: 900px;
        }
        .voltar-link {
            display: inline-block;
            margin-top: 2rem;
            text-decoration: none;
            color: #0071bc;
            font-weight: 500;
        }
        .voltar-link:hover {
            color: #005a8f;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1>Pacientes Cadastrados</h1>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
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
                        <tr>
                            <td colspan="4" class="text-center">Nenhum paciente cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="dashboard.php" class="voltar-link">← Voltar para o painel</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
