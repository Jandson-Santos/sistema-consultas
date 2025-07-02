<?php
session_start();
require_once("../config/db.php");

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Receber o id do médico via GET
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: medicos.php");
    exit();
}

$id = intval($_GET["id"]);
$erro = "";
$sucesso = "";

// Buscar dados atuais do médico para preencher o formulário
$stmt = $conn->prepare("SELECT * FROM medicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: medicos.php");
    exit();
}

$medico = $result->fetch_assoc();

// Processar o formulário de edição
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
            // Atualizar os dados mostrados no formulário
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
    <meta charset="UTF-8">
    <title>Editar Médico</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Editar Médico</h1>

        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p style="color: green;"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome*</label><br>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($medico['nome']); ?>" required><br><br>

            <label for="crm">CRM*</label><br>
            <input type="text" id="crm" name="crm" value="<?php echo htmlspecialchars($medico['crm']); ?>" required><br><br>

            <label for="especialidade">Especialidade*</label><br>
            <input type="text" id="especialidade" name="especialidade" value="<?php echo htmlspecialchars($medico['especialidade']); ?>" required><br><br>

            <label for="telefone">Telefone</label><br>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($medico['telefone']); ?>"><br><br>

            <label for="email">E-mail</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($medico['email']); ?>"><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <br>
        <a href="medicos.php"><button>Voltar para lista</button></a>
    </main>
</body>
</html>
