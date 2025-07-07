<?php
session_start();
require_once("../../config/db.php");

// Verifica se o admin esta logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

$erro = "";
$sucesso = "";

// Verifica se o formulario foi enviado pelo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe os dados do formulario e remove espacos extras
    $nome = trim($_POST["nome"]);
    $crm = trim($_POST["crm"]);
    $especialidade = trim($_POST["especialidade"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    // Validacao simples
    if (empty($nome) || empty($crm) || empty($especialidade)) {
        $erro = "Preencha todos os campos obrigatórios (Nome, CRM, Especialidade).";
    } else {
        $stmt = $conn->prepare("INSERT INTO medicos (nome, crm, especialidade, telefone, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $crm, $especialidade, $telefone, $email);

        // Executa a query e verifica sucesso
        if ($stmt->execute()) {
            $sucesso = "Médico cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar médico: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Médico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body class="bg-light">
    <div class="container py-4" style="max-width: 600px;">
        <h1 class="mb-4 text-primary">Cadastrar Novo Médico</h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome*</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="crm" class="form-label">CRM*</label>
                <input type="text" id="crm" name="crm" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="especialidade" class="form-label">Especialidade*</label>
                <input type="text" id="especialidade" name="especialidade" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary rounded-pill w-100">Cadastrar</button>
        </form>

        <div class="mt-3 text-center">
            <a href="medicos.php" class="btn btn-outline-primary rounded-pill">← Voltar para a lista</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

