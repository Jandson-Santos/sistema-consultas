<?php
session_start();
require_once("../../config/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$sql = "
    SELECT 
        c.id, 
        c.data, 
        c.hora, 
        c.status, 
        p.nome AS paciente_nome, 
        m.nome AS medico_nome 
    FROM consultas c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    INNER JOIN medicos m ON c.medico_id = m.id
    ORDER BY c.data, c.hora
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Consultas Marcadas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
        }

        h1 {
            color: #0071bc;
            font-weight: 600;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .table thead th {
            background-color: #0071bc;
            color: white;
            font-weight: 600;
        }

        .status-agendada  { color: #28a745; font-weight: 600; }
        .status-concluida { color: #007bff; font-weight: 600; }
        .status-cancelada { color: #dc3545; font-weight: 600; }

        .btn-link-custom {
            color: #0071bc;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-link-custom:hover {
            color: #005a8f;
            text-decoration: underline;
        }

        main.container {
            max-width: 900px;
            padding-bottom: 3rem;
        }

        #calendar-container {
            max-width: 900px;
            margin: 3rem auto 4rem auto;
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem 2rem 2rem 2rem;
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
        }

        #calendar-title {
            text-align: center;
            color: #0071bc;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        .fc .fc-toolbar-title {
            color: #0071bc;
            font-weight: 700;
            font-size: 1.6rem;
        }

        .fc-theme-standard td.fc-day-today {
            background-color: #e6f0ff;
        }

        .fc .fc-button-primary {
            background-color: #0071bc;
            border-color: #0071bc;
        }

        .fc .fc-button-primary:hover,
        .fc .fc-button-primary:focus {
            background-color: #005a8f;
            border-color: #005a8f;
        }

        .fc-event {
            border-radius: 10px !important;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Consultas Marcadas</h1>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $result->data_seek(0); while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["paciente_nome"]) ?></td>
                                <td><?= htmlspecialchars($row["medico_nome"]) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($row["data"]))) ?></td>
                                <td><?= htmlspecialchars($row["hora"]) ?></td>
                                <td class="status-<?= strtolower($row["status"]) ?>">
                                    <?= htmlspecialchars(ucfirst($row["status"])) ?>
                                </td>
                                <td>
                                    <?php if ($row["status"] === "agendada"): ?>
                                        <a href="concluir-consulta.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary rounded-pill" onclick="return confirm('Deseja marcar esta consulta como concluída?')">Concluir</a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Nenhuma consulta agendada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div id="calendar-container">
            <h2 id="calendar-title">Visualização em Calendário</h2>
            <div id="calendar"></div>
        </div>

        <div class="text-center mt-4 mb-5">
            <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill">← Voltar para o painel</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: [
                <?php
                if ($result && $result->num_rows > 0) {
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        $dataHora = $row["data"] . "T" . $row["hora"];
                        $title = $row["paciente_nome"] . " com " . $row["medico_nome"];
                        $status = strtolower($row["status"]);
                        $color = match ($status) {
                            'agendada'  => '#28a745',
                            'concluida' => '#007bff',
                            'cancelada' => '#dc3545',
                            default     => '#6c757d'
                        };

                        echo "{
                            title: '" . addslashes($title) . "',
                            start: '$dataHora',
                            color: '$color'
                        },";
                    }
                }
                ?>
            ]
        });

        calendar.render();
    });
    </script>
</body>
</html>
