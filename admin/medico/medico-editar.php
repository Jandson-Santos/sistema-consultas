<?php
session_start();
require_once("../../config/db.php");

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Receber o id do medico pelo GET
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: medicos.php");
    exit();
}

$id = intval($_GET["id"]);
$erro = "";
$sucesso = "";

// Buscar dados atuais do medico para preencher o formulario
$stmt = $conn->prepare("SELECT * FROM medicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: medicos.php");
    exit();
}

$medico = $result->fetch_assoc();

// Processar o formulario de edicao
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $crm = trim($_POST["crm"]);
    $especialidade = trim($_POST["especialidade"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (empty($nome) || empty($crm) || empty($especialidade)) {
        $erro = "Preencha todos os campos obrigatórios (Nome, CRM, Especialidade).";
    } else {
        $stmt = $conn->prepare("UPDATE medicos SET nome = ?, crm = ?, especialidade = ?, telefone = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $crm, $especialidade, $telefone, $email, $id);

        if ($stmt->execute()) {
            $sucesso = "Dados atualizados com sucesso!";
            // Atualiza os dados mostrados no formulario
            $medico["nome"] = $nome;
            $medico["crm"] = $crm;
            $medico["especialidade"] = $especialidade;
            $medico["telefone"] = $telefone;
            $medico["email"] = $email;
        } else {
            $erro = "Erro ao atualizar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Médico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body class="bg-light">
    <div class="container py-4" style="max-width: 600px;">
        <h1 class="mb-4 text-primary">Editar Médico</h1>

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
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($medico['nome']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="crm" class="form-label">CRM*</label>
                <input type="text" id="crm" name="crm" class="form-control" value="<?php echo htmlspecialchars($medico['crm']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="especialidade" class="form-label">Especialidade*</label>
                <input type="text" id="especialidade" name="especialidade" class="form-control" value="<?php echo htmlspecialchars($medico['especialidade']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo htmlspecialchars($medico['telefone']); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($medico['email']); ?>">
            </div>

            <button type="submit" class="btn btn-primary rounded-pill w-100">Salvar Alterações</button>
        </form>

        <div class="mt-3 text-center">
            <a href="medicos.php" class="btn btn-outline-primary rounded-pill">Voltar para lista</a>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
