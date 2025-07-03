<?php
session_start();
require_once("../../config/db.php");

// Verifica se está logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];
$consulta_id = $_GET["id"] ?? null;

if (!$consulta_id) {
    echo "Consulta inválida.";
    exit();
}

// Busca a consulta
$stmt = $conn->prepare("SELECT consultas.*, medicos.nome AS nome_medico 
                        FROM consultas 
                        JOIN medicos ON consultas.medico_id = medicos.id 
                        WHERE consultas.id = ? AND consultas.paciente_id = ?");
$stmt->bind_param("ii", $consulta_id, $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo "Consulta não encontrada ou acesso não autorizado.";
    exit();
}

$consulta = $resultado->fetch_assoc();
$medico_id = $consulta["medico_id"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Remarcar Consulta</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Remarcar Consulta</h1>
        <p><strong>Médico:</strong> <?php echo htmlspecialchars($consulta["nome_medico"]); ?></p>
        <p><strong>Data atual:</strong> <?php echo $consulta["data"]; ?> às <?php echo $consulta["hora"]; ?></p>

        <form action="salvar-remarcacao.php" method="POST">
            <input type="hidden" name="consulta_id" value="<?php echo $consulta_id; ?>">
            <input type="hidden" name="medico_id" value="<?php echo $medico_id; ?>">

            <label for="data">Nova data:</label>
            <input type="date" id="data" name="data" required><br><br>

            <label for="hora">Novo horário:</label>
            <select id="hora" name="hora" required>
                <option value="">Selecione uma data primeiro</option>
            </select><br><br>

            <button type="submit">Confirmar Remarcação</button>
        </form>

        <a href="perfil-paciente.php"><button>Voltar</button></a>
    </main>

    <script>
    const dataInput = document.getElementById("data");
    const horaSelect = document.getElementById("hora");
    const medicoId = "<?php echo $medico_id; ?>";

    dataInput.addEventListener("change", () => {
        const data = dataInput.value;

        if (!data) return;

        fetch(`../../backend/get-horarios.php?medico_id=${medicoId}&data=${data}`)
            .then(res => res.json())
            .then(horarios => {
                horaSelect.innerHTML = "";

                if (horarios.length === 0) {
                    const opt = document.createElement("option");
                    opt.value = "";
                    opt.text = "Nenhum horário disponível";
                    horaSelect.appendChild(opt);
                } else {
                    const optPadrao = document.createElement("option");
                    optPadrao.value = "";
                    optPadrao.text = "Selecione";
                    horaSelect.appendChild(optPadrao);

                    horarios.forEach(h => {
                        const opt = document.createElement("option");
                        opt.value = h;
                        opt.text = h;
                        horaSelect.appendChild(opt);
                    });
                }
            });
    });
    </script>
</body>
</html>
