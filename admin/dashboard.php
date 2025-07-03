<?php
session_start();


// Verificar se está logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$admin_nome = $_SESSION["admin_nome"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Bem-vindo, <?php echo htmlspecialchars($admin_nome); ?>!</h1>
        <p>O que você deseja gerenciar?</p>

        <ul style="list-style: none; padding: 0;">
            <li><a href="medico/medicos.php"><button>Médicos</button></a></li>
            <a href="medico/new-disponi.php">
            <button>Definir Horários dos Médicos</button>
            </a>
            <li><a href="pacientes.php"><button>Pacientes</button></a></li>
            <li><a href="consultas.php"><button>Consultas</button></a></li>
        </ul>

        <br>
        <a href="logout.php"><button style="background-color: red; color: white;">Sair</button></a>
    </main>
</body>
</html>
