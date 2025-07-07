<?php
session_start();
require_once("../../config/db.php");

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
    <meta charset="UTF-8" />
    <title>Remarcar Consulta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <main class="container my-4" style="max-width: 600px;">
        <h1 class="mb-4">Remarcar Consulta</h1>
        <p><strong>Médico:</strong> <?php echo htmlspecialchars($consulta["nome_medico"]); ?></p>
        <p><strong>Data atual:</strong> <?php echo $consulta["data"]; ?> às <?php echo $consulta["hora"]; ?></p>

        <form action="/sistema-consultas/backend/salvar-remarcar.php" method="POST" class="mt-4">
            <input type="hidden" name="consulta_id" value="<?php echo $consulta_id; ?>">
            <input type="hidden" name="medico_id" value="<?php echo $medico_id; ?>">

            <div class="mb-3">
                <label for="data" class="form-label">Nova data:</label>
                <input type="date" id="data" name="data" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="hora" class="form-label">Novo horário:</label>
                <select id="hora" name="hora" class="form-select" required>
                    <option value="">Selecione uma data primeiro</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Confirmar Remarcação</button>
            <a href="perfil-paciente.php" class="btn btn-outline-secondary ms-2">Voltar</a>
        </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

