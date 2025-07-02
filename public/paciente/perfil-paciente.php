<?php
session_start();
require_once("../../config/db.php"); // Caminho para o arquivo de conexão ao banco

// Verifica se o paciente está logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

// Pega o ID do paciente da sessão
$paciente_id = $_SESSION["paciente_id"];

// Busca os dados do paciente no banco
$stmt = $conn->prepare("SELECT nome, cpf, telefone, email FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se encontrou o paciente
if ($resultado->num_rows === 1) {
    $paciente = $resultado->fetch_assoc();
} else {
    echo "Paciente não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Clínica Pombos</title>
    <!-- Corrigido caminho do CSS -->
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Olá, <?php echo htmlspecialchars($paciente["nome"]); ?>!</h1>
        <p><strong>CPF:</strong> <?php echo htmlspecialchars($paciente["cpf"]); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($paciente["telefone"]); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($paciente["email"]); ?></p>

        <br>
        <a href="editar-perfil.php"><button>Editar Dados</button></a>
        <a href="logout-paciente.php"><button>Sair</button></a>
    </main>
</body>
</html>
