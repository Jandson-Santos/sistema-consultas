<?php
session_start();

$login = $_SESSION['login_paciente'] ?? null;
$senha = $_SESSION['senha_paciente'] ?? null;

unset($_SESSION['login_paciente'], $_SESSION['senha_paciente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Confirmação de Agendamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <main class="container my-5" style="max-width: 600px;">
        <div class="card shadow-sm p-4">
            <h1 class="mb-4">Consulta Agendada com Sucesso!</h1>
            <p>Seu login para acessar o sistema é: <strong><?php echo htmlspecialchars($login); ?></strong></p>
            <p>Sua senha inicial é: <strong><?php echo htmlspecialchars($senha); ?></strong></p>
            <p class="mt-3">Por favor, guarde essas informações para futuros acessos.</p>
            <a href="agendar.php" class="btn btn-primary mt-3">Agendar outra consulta</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

