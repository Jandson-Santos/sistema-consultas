<?php
session_start();
require_once("../../config/db.php");

if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];
$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (empty($nome) || empty($email)) {
        $erro = "Nome e E-mail são obrigatórios.";
    } else {
        $stmt = $conn->prepare("UPDATE pacientes SET nome = ?, telefone = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $telefone, $email, $paciente_id);

        if ($stmt->execute()) {
            $sucesso = "Dados atualizados com sucesso!";
        } else {
            $erro = "Erro ao atualizar: " . $conn->error;
        }
    }
}

$stmt = $conn->prepare("SELECT nome, telefone, email FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Perfil - Clínica Pombos</title>
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
            max-width: 500px;
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
        label {
            font-weight: 600;
            color: #0071bc;
        }
        .btn-primary {
            background-color: #0071bc;
            border-color: #0071bc;
        }
        .btn-primary:hover {
            background-color: #005a8f;
            border-color: #005a8f;
        }
        .btn-voltar {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <main>
        <h1>Editar Perfil</h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($paciente["nome"]); ?>" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo htmlspecialchars($paciente["telefone"]); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($paciente["email"]); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </form>

        <a href="perfil-paciente.php" class="btn btn-outline-primary w-100 btn-voltar">Voltar ao Perfil</a>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

