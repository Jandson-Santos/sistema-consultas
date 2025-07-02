<?php
session_start();
$erro = $_SESSION["erro_agendamento"] ?? "";
$dados = $_SESSION["dados_formulario"] ?? [];
unset($_SESSION["erro_agendamento"], $_SESSION["dados_formulario"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Agendamento de Consultas</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <header>
        <h1>Sistema de Agendamento</h1>
    </header>

    <main>
        <h2>Agende sua consulta</h2>

        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form action="../backend/save-agend.php" method="POST">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($dados['nome'] ?? ''); ?>" />

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" required value="<?php echo htmlspecialchars($dados['cpf'] ?? ''); ?>" />

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" required value="<?php echo htmlspecialchars($dados['telefone'] ?? ''); ?>" />

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($dados['email'] ?? ''); ?>" />

            <label for="medico">Médico</label>
            <select id="medico" name="medico" required>
                <option value="">Selecione</option>
                <option value="1" <?php if (($dados['medico'] ?? '') == "1") echo "selected"; ?>>Dr. João - Clínico Geral</option>
                <option value="2" <?php if (($dados['medico'] ?? '') == "2") echo "selected"; ?>>Dra. Carla - Cardiologista</option>
            </select>

            <label for="data">Data da consulta</label>
            <input type="date" id="data" name="data" required value="<?php echo htmlspecialchars($dados['data'] ?? ''); ?>" />

            <label for="hora">Hora da consulta</label>
            <input type="time" id="hora" name="hora" required value="<?php echo htmlspecialchars($dados['hora'] ?? ''); ?>" />

            <button type="submit">Agendar</button>
        </form>
    </main>
</body>
</html>
