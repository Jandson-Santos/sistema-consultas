<?php
session_start();
require_once("../../config/db.php");


$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $senha = trim($_POST["senha"]);

    // Buscar paciente pelo login
    $stmt = $conn->prepare("SELECT id, nome, senha FROM pacientes WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $paciente = $resultado->fetch_assoc();

        // Verificar senha
        if ($senha === $paciente["senha"]) {
            // Login válido, iniciar sessão
            $_SESSION["paciente_id"] = $paciente["id"];
            $_SESSION["paciente_nome"] = $paciente["nome"];
            header("Location: perfil-paciente.php"); // página do perfil ou dashboard
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Login não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Paciente</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Login do Paciente</h1>

        <?php if ($erro): ?>
            <p style="color: red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="login">Login</label><br>
            <input type="text" id="login" name="login" required><br><br>

            <label for="senha">Senha</label><br>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit">Entrar</button>
            
        </form>
        <a href="../../index.php">← Voltar para a página inicial</a>
    </main>
</body>
</html>

