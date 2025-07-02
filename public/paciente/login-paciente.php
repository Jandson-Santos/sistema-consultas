<?php
session_start();
require_once("../../config/db.php"); // Caminho correto para o db.php

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    // Buscar paciente pelo email
    $stmt = $conn->prepare("SELECT id, nome, senha FROM pacientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $paciente = $resultado->fetch_assoc();

        // Verificar senha com hash
        if (password_verify($senha, $paciente["senha"])) {
            // Login válido, criar sessão
            $_SESSION["paciente_id"] = $paciente["id"];
            $_SESSION["paciente_nome"] = $paciente["nome"];
            header("Location: perfil-paciente.php"); // redireciona para o perfil do paciente
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Paciente</title>
    <!-- Corrigido caminho para CSS: voltar 1 pasta para ../css/style.css -->
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Login Paciente</h1>

        <?php if ($erro): ?>
            <p style="color:red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">E-mail:</label><br />
            <input type="email" id="email" name="email" required /><br /><br />

            <label for="senha">Senha:</label><br />
            <input type="password" id="senha" name="senha" required /><br /><br />

            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>

