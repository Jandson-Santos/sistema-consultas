<?php
session_start();
require_once("../../config/db.php");

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$erro = "";
$sucesso = "";

function buscarMedicos($conn) {
    $medicos = [];
    $res = $conn->query("SELECT id, nome FROM medicos ORDER BY nome");
    while ($row = $res->fetch_assoc()) {
        $medicos[] = $row;
    }
    return $medicos;
}

if ($action === "excluir" && $id > 0) {
    // Excluir disponibilidade
    $stmt = $conn->prepare("DELETE FROM disponibilidades WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: disponi-medico.php");
        exit();
    } else {
        $erro = "Erro ao excluir o horário.";
    }
} elseif (($action === "editar" && $id > 0) || $action === "adicionar") {
    // Adicionar ou editar disponibilidade
    $medicos = buscarMedicos($conn);

    // Inicializa valores para o form
    $disponibilidade = [
        'medico_id' => '',
        'dia_semana' => '',
        'hora_inicio' => '',
        'hora_fim' => ''
    ];

    if ($action === "editar") {
        // Buscar dados para edicao
        $stmt = $conn->prepare("SELECT medico_id, dia_semana, hora_inicio, hora_fim FROM disponibilidades WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows !== 1) {
            die("Horário não encontrado.");
        }
        $disponibilidade = $res->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $medico_id = $_POST['medico_id'] ?? '';
        $dia_semana = $_POST['dia_semana'] ?? '';
        $hora_inicio = $_POST['hora_inicio'] ?? '';
        $hora_fim = $_POST['hora_fim'] ?? '';

        if (!$medico_id || !$dia_semana || !$hora_inicio || !$hora_fim) {
            $erro = "Preencha todos os campos.";
        } else {
            if ($action === "editar") {
                $stmt = $conn->prepare("UPDATE disponibilidades SET medico_id=?, dia_semana=?, hora_inicio=?, hora_fim=? WHERE id=?");
                $stmt->bind_param("isssi", $medico_id, $dia_semana, $hora_inicio, $hora_fim, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO disponibilidades (medico_id, dia_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $medico_id, $dia_semana, $hora_inicio, $hora_fim);
            }

            if ($stmt->execute()) {
                $sucesso = $action === "editar" ? "Horário atualizado com sucesso!" : "Horário adicionado com sucesso!";
                if ($action !== "editar") {
                    // Limpa form apos inserir novo horario
                    $disponibilidade = ['medico_id' => '', 'dia_semana' => '', 'hora_inicio' => '', 'hora_fim' => ''];
                }
            } else {
                $erro = "Erro ao salvar: " . $conn->error;
            }
        }
    }

    ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title><?= $action === "editar" ? "Editar Horário" : "Adicionar Horário" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body class="bg-light">
    <div class="container py-4" style="max-width: 600px;">
        <h1 class="mb-4 text-primary"><?= $action === "editar" ? "Editar Horário" : "Adicionar Novo Horário" ?></h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Médico</label>
                <select name="medico_id" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php foreach ($medicos as $med): ?>
                        <option value="<?= $med['id'] ?>" <?= ($disponibilidade['medico_id'] == $med['id']) ? "selected" : "" ?>>
                            <?= htmlspecialchars($med['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Dia da Semana</label>
                <select name="dia_semana" class="form-select" required>
                    <option value="">Selecione</option>
                    <?php
                    $dias = ["segunda", "terca", "quarta", "quinta", "sexta", "sabado", "domingo"];
                    foreach ($dias as $dia): ?>
                        <option value="<?= $dia ?>" <?= ($disponibilidade['dia_semana'] == $dia) ? "selected" : "" ?>>
                            <?= ucfirst($dia) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Hora Início</label>
                <input type="time" name="hora_inicio" class="form-control" required value="<?= htmlspecialchars($disponibilidade['hora_inicio']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Hora Fim</label>
                <input type="time" name="hora_fim" class="form-control" required value="<?= htmlspecialchars($disponibilidade['hora_fim']) ?>">
            </div>

            <button type="submit" class="btn btn-primary rounded-pill"><?= $action === "editar" ? "Salvar Alterações" : "Adicionar Horário" ?></button>
            <a href="disponi-medico.php" class="btn btn-outline-primary rounded-pill">Cancelar</a>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    exit();
} else {
    // listar disponibilidades
    $result = $conn->query("
        SELECT d.id, d.dia_semana, d.hora_inicio, d.hora_fim, m.nome 
        FROM disponibilidades d
        JOIN medicos m ON d.medico_id = m.id
        ORDER BY m.nome, FIELD(d.dia_semana, 'segunda','terca','quarta','quinta','sexta','sabado','domingo')
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidades dos Médicos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4 text-primary">Horários dos Médicos</h1>
        <a href="?action=adicionar" class="btn btn-primary rounded-pill mb-3">Adicionar Horário</a>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Médico</th>
                        <th>Dia da Semana</th>
                        <th>Hora Início</th>
                        <th>Hora Fim</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["nome"]) ?></td>
                            <td><?= ucfirst(htmlspecialchars($row["dia_semana"])) ?></td>
                            <td><?= htmlspecialchars($row["hora_inicio"]) ?></td>
                            <td><?= htmlspecialchars($row["hora_fim"]) ?></td>
                            <td>
                                <a href="?action=editar&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-pill me-1">Editar</a>
                                <a href="?action=excluir&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Tem certeza que deseja excluir este horário?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows === 0): ?>
                        <tr><td colspan="5" class="text-center">Nenhum horário cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill">Voltar ao Painel</a>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

