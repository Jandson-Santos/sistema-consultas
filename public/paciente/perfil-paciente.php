<?php
session_start();
require_once("../../config/db.php");

// Verifica se o paciente esta logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

$paciente_id = $_SESSION["paciente_id"];

// Busca os dados do paciente
$stmt = $conn->prepare("SELECT nome, cpf, telefone, email FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $paciente = $resultado->fetch_assoc();
    } else {
        echo "Paciente não encontrado.";
        exit();
    }

date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date("Y-m-d");

// Busca todas as consultas do paciente
$consulta = $conn->prepare("SELECT consultas.id, data, hora, status, nome AS nome_medico 
                            FROM consultas 
                            JOIN medicos ON consultas.medico_id = medicos.id 
                            WHERE paciente_id = ? 
                            ORDER BY data, hora");
$consulta->bind_param("i", $paciente_id);
$consulta->execute();
$res = $consulta->get_result();

$consultasFuturas = [];
$consultasPassadas = [];

    while ($c = $res->fetch_assoc()) {
        if ($c["status"] === "concluida" || $c["data"] < $dataAtual) {
            $consultasPassadas[] = $c;
        } elseif ($c["status"] === "agendada" && $c["data"] >= $dataAtual) {
            $consultasFuturas[] = $c;
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Meu Perfil - Clínica Pombos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }
        footer {
            background-color: #004c85;
            color: white;
            padding: 1rem 0;
            margin-top: auto;
            text-align: center;
            border-radius: 0.5rem;
        }
        h1, h2 {
            color: #0071bc;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #0071bc;
            border-color: #0071bc;
        }
        .btn-primary:hover {
            background-color: #005a8f;
            border-color: #005a8f;
        }
        .btn-cancelar {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        .btn-cancelar:hover {
            background-color: #b02a37;
            border-color: #b02a37;
        }
        .info-label {
            font-weight: 600;
            color: #0071bc;
        }
        .card {
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: white;
        }
        .consulta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }
        .consulta-detalhe {
            flex: 1;
        }
        .acoes-consulta > a {
            margin-left: 0.5rem;
        }
        @media (max-width: 576px) {
            .consulta-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .acoes-consulta > a {
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>

    <main class="container">
        <h1>Olá, <?php echo htmlspecialchars($paciente["nome"]); ?>!</h1>

        <div class="card">
            <h2>Seus Dados</h2>
            <p><span class="info-label">CPF:</span> <?php echo htmlspecialchars($paciente["cpf"]); ?></p>
            <p><span class="info-label">Telefone:</span> <?php echo htmlspecialchars($paciente["telefone"]); ?></p>
            <p><span class="info-label">E-mail:</span> <?php echo htmlspecialchars($paciente["email"]); ?></p>

            <div class="mt-3 d-flex flex-wrap gap-2">
                <a href="editar-perfil.php" class="btn btn-primary rounded-pill flex-grow-1 flex-sm-grow-0">Editar Dados</a>
                <a href="consulta-paciente.php" class="btn btn-primary rounded-pill flex-grow-1 flex-sm-grow-0">Minhas Consultas</a>
                <a href="logout-paciente.php" class="btn btn-outline-primary rounded-pill flex-grow-1 flex-sm-grow-0">Sair</a>
            </div>
        </div>

        <?php if (isset($_GET["cancelamento"]) && $_GET["cancelamento"] === "sucesso"): ?>
            <div class="alert alert-success" role="alert">
                Consulta cancelada com sucesso.
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Minhas Consultas</h2>

            <h3>Futuras</h3>
            <?php if (empty($consultasFuturas)): ?>
                <p>Não há consultas futuras.</p>
            <?php else: ?>
                <ul class="list-unstyled">
                <?php foreach ($consultasFuturas as $c): ?>
                    <li class="consulta-item">
                        <div class="consulta-detalhe">
                            <?php echo htmlspecialchars("{$c['data']} às {$c['hora']} com {$c['nome_medico']}"); ?>
                        </div>
                        <div class="acoes-consulta">
                            <a href="remarcar-consulta.php?id=<?php echo $c['id']; ?>" class="btn btn-primary btn-sm rounded-pill">Remarcar</a>
                            <a href="cancelar-consulta.php?id=<?php echo $c['id']; ?>"
                            class="btn btn-cancelar btn-sm rounded-pill"
                            onclick="return confirm('Deseja mesmo cancelar esta consulta?');">Cancelar</a>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h3 class="mt-4">Passadas</h3>
            <?php if (empty($consultasPassadas)): ?>
                <p>Não há consultas passadas.</p>
            <?php else: ?>
                <ul class="list-unstyled">
                <?php foreach ($consultasPassadas as $c): ?>
                    <li class="consulta-item">
                        <div class="consulta-detalhe">
                            <?php echo htmlspecialchars("{$c['data']} às {$c['hora']} com {$c['nome_medico']}"); ?>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
