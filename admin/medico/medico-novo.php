<?php
session_start();
// Inclui o arquivo de conexão ao banco de dados (subindo duas pastas)
require_once("../../config/db.php");

// Verifica se o administrador está logado
if (!isset($_SESSION["admin_id"])) {
    // Se não estiver logado, redireciona para a página de login (uma pasta acima)
    header("Location: ../login.php");
    exit();
}

$erro = "";
$sucesso = "";

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe os dados do formulário e remove espaços extras
    $nome = trim($_POST["nome"]);
    $crm = trim($_POST["crm"]);
    $especialidade = trim($_POST["especialidade"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    // Validação simples: campos obrigatórios
    if (empty($nome) || empty($crm) || empty($especialidade)) {
        $erro = "Preencha todos os campos obrigatórios (Nome, CRM, Especialidade).";
    } else {
        // Prepara a query para inserção segura dos dados
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
    <!-- Link para o CSS (subindo duas pastas) -->
    <link rel="stylesheet" href="../../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Cadastrar Novo Médico</h1>

        <!-- Exibe mensagem de erro, se houver -->
        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <!-- Exibe mensagem de sucesso, se houver -->
        <?php if ($sucesso): ?>
            <p style="color: green;"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <!-- Formulário para cadastro de médico -->
        <form method="POST">
            <label for="nome">Nome*</label><br />
            <input type="text" id="nome" name="nome" required /><br /><br />

            <label for="crm">CRM*</label><br />
            <input type="text" id="crm" name="crm" required /><br /><br />

            <label for="especialidade">Especialidade*</label><br />
            <input type="text" id="especialidade" name="especialidade" required /><br /><br />

            <label for="telefone">Telefone</label><br />
            <input type="text" id="telefone" name="telefone" /><br /><br />

            <label for="email">E-mail</label><br />
            <input type="email" id="email" name="email" /><br /><br />

            <button type="submit">Cadastrar</button>
        </form>

        <br />
        <!-- Link para voltar para a lista de médicos -->
        <a href="medicos.php"><button>Voltar para lista</button></a>
    </main>
</body>
</html>
