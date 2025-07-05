<?php
session_start();
require_once("../../config/db.php"); // volta 2 níveis para pegar o db.php

// Verifica se o paciente está logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php"); // login-paciente.php deve estar na mesma pasta (public/paciente/)
    exit();
}

$paciente_id = $_SESSION["paciente_id"];
$sucesso = "";
$erro = "";

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    // Validação simples
    if (empty($nome) || empty($email)) {
        $erro = "Nome e E-mail são obrigatórios.";
    } else {
        // Atualiza os dados
        $stmt = $conn->prepare("UPDATE pacientes SET nome = ?, telefone = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $telefone, $email, $paciente_id);

        if ($stmt->execute()) {
            $sucesso = "Dados atualizados com sucesso!";
        } else {
            $erro = "Erro ao atualizar: " . $conn->error;
        }
    }
}

// Buscar os dados atuais para preencher o formulário
$stmt = $conn->prepare("SELECT nome, telefone, email FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <!-- Corrigido o caminho para o CSS: volta 1 nível para ../css/style.css -->
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Editar Perfil</h1>

        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p style="color: green;"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($paciente["nome"]); ?>" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($paciente["telefone"]); ?>"><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($paciente["email"]); ?>" required><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <br>
        <a href="perfil-paciente.php"><button>Voltar ao Perfil</button></a>
    </main>
</body>
</html>
