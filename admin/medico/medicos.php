<?php
session_start();
// Ajuste do caminho para a conexão com banco
require_once("../../config/db.php");

// Verificar se admin está logado
if (!isset($_SESSION["admin_id"])) {
    // Ajuste do redirecionamento para login
    header("Location: ../login.php");
    exit();
}

// Buscar todos os médicos cadastrados no banco
$sql = "SELECT * FROM medicos ORDER BY nome";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Médicos</title>
    <link rel="stylesheet" href="../../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Médicos Cadastrados</h1>

        <a href="medico-novo.php"><button>Cadastrar Novo Médico</button></a>
        <br><br>

        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; max-width: 800px;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CRM</th>
                    <th>Especialidade</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($medico = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($medico['nome']); ?></td>
                            <td><?php echo htmlspecialchars($medico['crm']); ?></td>
                            <td><?php echo htmlspecialchars($medico['especialidade']); ?></td>
                            <td><?php echo htmlspecialchars($medico['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($medico['email']); ?></td>
                            <td>
                                <!-- Botões para editar e excluir -->
                                <a href="medico-editar.php?id=<?php echo $medico['id']; ?>">Editar</a> | 
                                <a href="medico-excluir.php?id=<?php echo $medico['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este médico?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">Nenhum médico cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <a href="../dashboard.php"><button>Voltar ao Painel</button></a>
    </main>
</body>
</html>

