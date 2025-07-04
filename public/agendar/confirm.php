<?php
session_start();

// Recebe login e senha da sessão
$login = $_SESSION['login_paciente'] ?? null;
$senha = $_SESSION['senha_paciente'] ?? null;

// Limpa da sessão para não exibir depois
unset($_SESSION['login_paciente'], $_SESSION['senha_paciente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Confirmação de Agendamento</title>
</head>
<body>
    <h1>Consulta Agendada com Sucesso!</h1>

    <p>Seu login para acessar o sistema é: <strong><?php echo htmlspecialchars($login); ?></strong></p>
    <p>Sua senha inicial é: <strong><?php echo htmlspecialchars($senha); ?></strong></p>

    <p>Por favor, guarde essas informações para futuros acessos.</p>

    <a href="agendar.php">Agendar outra consulta</a>
</body>
</html>
