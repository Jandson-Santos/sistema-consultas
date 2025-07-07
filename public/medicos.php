<?php
require_once("../config/db.php");

// buscar todos os medicos salvos
$stmt = $conn->query("SELECT nome, crm, especialidade, telefone, email FROM medicos");
$medicos = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nossos Médicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        main {
            flex: 1;
        }

        .bg-header {
            background-color: #0071bc;
            color: white;
            padding: 40px 0;
        }

        .medico-card {
            border-left: 5px solid #00a859;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .medico-card:hover {
            transform: translateY(-5px);
        }

        .btn-voltar-medicos {
            margin-top: 40px;
        }

        .rodape {
            background-color: #004c85;
            color: white;
            text-align: center;
            padding: 30px 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <header class="bg-header text-center">
        <div class="container">
            <h1 class="mb-2">Clínica Pombos</h1>
            <h2 class="h4">Nossa equipe médica</h2>
        </div>
    </header>

    <main class="container py-5">
        <?php if (empty($medicos)): ?>
            <div class="alert alert-warning text-center">Nenhum médico cadastrado no momento.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($medicos as $medico): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-4 bg-white medico-card h-100">
                            <h4 class="text-primary"><?= htmlspecialchars($medico['nome']) ?></h4>
                            <p><strong>CRM:</strong> <?= htmlspecialchars($medico['crm']) ?></p>
                            <p><strong>Especialidade:</strong> <?= htmlspecialchars($medico['especialidade']) ?></p>
                            <?php if ($medico['telefone']): ?>
                                <p><strong>Telefone:</strong> <?= htmlspecialchars($medico['telefone']) ?></p>
                            <?php endif; ?>
                            <?php if ($medico['email']): ?>
                                <p><strong>E-mail:</strong> <?= htmlspecialchars($medico['email']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="../index.php" class="btn btn-outline-primary btn-voltar-medicos mt-5">← Voltar para a Página Inicial</a>
        </div>
    </main>

    <footer class="rodape">
        <p>© <?= date("Y") ?> Clínica Pombos. Todos os direitos reservados.</p>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



