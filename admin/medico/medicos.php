<?php
session_start();
require_once("../../config/db.php");

// Verificar se admin esta logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

// Buscar todos os medicos cadastrados no banco
$sql = "SELECT * FROM medicos ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Médicos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
            padding: 2rem 1rem;
        }
        h1 {
            color: #0071bc;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn-primary:hover {
            background-color: #005a8f;
            border-color: #005a8f;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #b02a37;
            border-color: #b02a37;
        }
        .table thead th {
            background-color: #0071bc;
            color: white;
            font-weight: 600;
        }
        .container {
            max-width: 900px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Médicos Cadastrados</h1>

        <div class="mb-3 text-end">
            <a href="medico-novo.php" class="btn btn-primary rounded-pill">Cadastrar Novo Médico</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CRM</th>
                        <th>Especialidade</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($medico = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($medico['nome']); ?></td>
                                <td><?php echo htmlspecialchars($medico['crm']); ?></td>
                                <td><?php echo htmlspecialchars($medico['especialidade']); ?></td>
                                <td><?php echo htmlspecialchars($medico['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($medico['email']); ?></td>
                                <td>
                                    <a href="medico-editar.php?id=<?php echo $medico['id']; ?>" class="btn btn-sm btn-primary rounded-pill me-1">Editar</a>
                                    <a href="medico-excluir.php?id=<?php echo $medico['id']; ?>" 
                                       class="btn btn-sm btn-danger rounded-pill"
                                       onclick="return confirm('Tem certeza que deseja excluir este médico?');">
                                        Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Nenhum médico cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill">Voltar ao Painel</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


