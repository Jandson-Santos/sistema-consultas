<?php
session_start();
require_once("../../config/db.php"); 

$erro = "";
$sucesso = "";

// Processa envio do formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medico_id = $_POST["medico_id"];
    $dia_semana = $_POST["dia_semana"];
    $hora_inicio = $_POST["hora_inicio"];
    $hora_fim = $_POST["hora_fim"];

    if (!$medico_id || !$dia_semana || !$hora_inicio || !$hora_fim) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $conn->prepare("INSERT INTO disponibilidades (medico_id, dia_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $medico_id, $dia_semana, $hora_inicio, $hora_fim);

        if ($stmt->execute()) {
            $sucesso = "Disponibilidade cadastrada com sucesso!";
        } else {
            $erro = "Erro ao salvar: " . $conn->error;
        }
    }
}

// Buscar medicos
$medicos = $conn->query("SELECT id, nome FROM medicos ORDER BY nome");
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Disponibilidade</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>
<body>
    <main>
        <h1>Nova Disponibilidade</h1>

        <?php if ($erro): ?><p style="color:red"><?php echo $erro; ?></p><?php endif; ?>
        <?php if ($sucesso): ?><p style="color:green"><?php echo $sucesso; ?></p><?php endif; ?>

        <form method="POST">
            <label>Médico</label><br>
            <select name="medico_id" required>
                <option value="">Selecione</option>
                <?php while ($med = $medicos->fetch_assoc()): ?>
                    <option value="<?php echo $med["id"]; ?>"><?php echo $med["nome"]; ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Dia da Semana</label><br>
            <select name="dia_semana" required>
                <option value="">Selecione</option>
                <option value="segunda">Segunda</option>
                <option value="terca">Terça</option>
                <option value="quarta">Quarta</option>
                <option value="quinta">Quinta</option>
                <option value="sexta">Sexta</option>
                <option value="sabado">Sábado</option>
                <option value="domingo">Domingo</option>
            </select><br><br>

            <label>Hora Início</label><br>
            <input type="time" name="hora_inicio" required><br><br>

            <label>Hora Fim</label><br>
            <input type="time" name="hora_fim" required><br><br>

            <button type="submit">Salvar</button>
        </form>
        <br>
        <a href="../dashboard.php"><button>Voltar</button></a>
    </main>
</body>
</html>
