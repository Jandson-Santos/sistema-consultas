<?php
session_start();
require_once("../../config/db.php");

// Verifica se o paciente está logado
if (!isset($_SESSION["paciente_id"])) {
    header("Location: login-paciente.php");
    exit();
}

// Pega o ID do paciente da sessão
$paciente_id = $_SESSION["paciente_id"];

// Busca os dados do paciente no banco
$stmt = $conn->prepare("SELECT nome, cpf, telefone, email FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se encontrou o paciente
if ($resultado->num_rows === 1) {
    $paciente = $resultado->fetch_assoc();
} else {
    echo "Paciente não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Clínica Pombos</title>
    <!-- Corrigido caminho do CSS -->
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <main>
        <h1>Olá, <?php echo htmlspecialchars($paciente["nome"]); ?>!</h1>
        <p><strong>CPF:</strong> <?php echo htmlspecialchars($paciente["cpf"]); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($paciente["telefone"]); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($paciente["email"]); ?></p>

        <br>
        <a href="editar-perfil.php"><button>Editar Dados</button></a>
        <a href="logout-paciente.php"><button>Sair</button></a>
        <br>
        <a href="consulta-paciente.php"><button>Minhas Consultas</button></a>

        <br><hr>
        <h2>Minhas Consultas</h2>

        <?php
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d");

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
            if ($c["data"] >= $dataAtual) {
                $consultasFuturas[] = $c;
            } else {
                $consultasPassadas[] = $c;
            }
        }
        ?>

        <h3>Futuras</h3>
        <?php if (empty($consultasFuturas)): ?>
            <p>Não há consultas futuras.</p>
        <?php else: ?>
            <ul>
            <?php foreach ($consultasFuturas as $c): ?>
                <li>
                    <?php echo "{$c['data']} às {$c['hora']} com Dr(a). {$c['nome_medico']}"; ?>
                    <a href="remarcar-consulta.php?id=<?php echo $c['id']; ?>"><button>Remarcar</button></a>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3>Passadas</h3>
        <?php if (empty($consultasPassadas)): ?>
            <p>Não há consultas passadas.</p>
        <?php else: ?>
            <ul>
            <?php foreach ($consultasPassadas as $c): ?>
                <li>
                    <?php echo "{$c['data']} às {$c['hora']} com Dr(a). {$c['nome_medico']}"; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </main>
</body>
</html>
