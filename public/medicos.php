<?php
require_once("../config/db.php");

// Buscar todos os médicos cadastrados
$stmt = $conn->query("SELECT nome, crm, especialidade, telefone, email FROM medicos");
$medicos = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nossos Médicos</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <main>
        <h1>Nossos Médicos</h1>

        <?php if (empty($medicos)): ?>
            <p>Nenhum médico cadastrado no momento.</p>
        <?php else: ?>
            <?php foreach ($medicos as $medico): ?>
                <div style="border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px;">
                    <strong>Nome:</strong> <?= htmlspecialchars($medico['nome']) ?><br>
                    <strong>CRM:</strong> <?= htmlspecialchars($medico['crm']) ?><br>
                    <strong>Especialidade:</strong> <?= htmlspecialchars($medico['especialidade']) ?><br>
                    <?php if ($medico['telefone']): ?>
                        <strong>Telefone:</strong> <?= htmlspecialchars($medico['telefone']) ?><br>
                    <?php endif; ?>
                    <?php if ($medico['email']): ?>
                        <strong>E-mail:</strong> <?= htmlspecialchars($medico['email']) ?><br>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="../index.php">
            <button>Voltar para a Página Inicial</button>
        </a>
    </main>
</body>
</html>
