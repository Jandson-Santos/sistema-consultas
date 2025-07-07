<?php
session_start();
require_once("../config/db.php");

// Verifica login admin
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Calendário de Consultas - Admin</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <h1>Calendário de Consultas - Administração</h1>

    <div id="calendar"></div>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {
                    url: 'consultas-json.php',
                    method: 'GET',
                    failure: function() {
                        alert('Erro ao carregar consultas.');
                    }
                },
                eventColor: '#378006',
                eventDidMount: function(info) {
                    var tooltip = new Tooltip(info.el, {
                        title: info.event.extendedProps.paciente + ' - Status: ' + info.event.extendedProps.status,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            });

            calendar.render();
        });
    </script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tooltip.js/1.3.3/tooltip.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltip.js/1.3.3/tooltip.min.js"></script>
</body>
</html>
