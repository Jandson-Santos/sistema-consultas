<?php
session_start();
require_once("../../config/db.php");

$erro = $_SESSION["erro_agendamento"] ?? "";
$dados = $_SESSION["dados_formulario"] ?? [];
unset($_SESSION["erro_agendamento"], $_SESSION["dados_formulario"]);

$medicos = $conn->query("SELECT id, nome, especialidade FROM medicos");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Agendamento de Consultas</title>
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
      }
      header.topo-simples {
        background-color: #0071bc;
        color: white;
        padding: 1rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }
      .nome-clinica-interno {
        font-size: 2rem;
        font-weight: 600;
        margin: 0;
      }
      .subtitulo-clinica {
        font-size: 1.25rem;
        font-weight: 400;
        margin: 0;
        color: #d0e6fb;
      }
      main.agendamento {
        flex: 1;
        padding: 2rem 1rem;
        max-width: 600px;
        margin: 0 auto 3rem auto;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      }
      .alerta-erro {
        background-color: #ffe5e5;
        color: #c30000;
        padding: 1rem 1.5rem;
        border-left: 5px solid #c30000;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
      }
      label {
        font-weight: 600;
        color: #0071bc;
      }
    </style>
</head>
<body>

    <header class="topo-simples">
    <div class="container text-center">
        <h1 class="nome-clinica-interno">Clínica Pombos</h1>
        <h2 class="subtitulo-clinica">Agendamento de Consultas</h2>
    </div>
    </header>


    <main class="agendamento">

        <?php if ($erro): ?>
            <div class="alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form action="/sistema-consultas/backend/save-agend.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo htmlspecialchars($dados['nome'] ?? ''); ?>" />
                <div class="invalid-feedback">Por favor, preencha seu nome.</div>
            </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required value="<?php echo htmlspecialchars($dados['cpf'] ?? ''); ?>" />
            <div class="invalid-feedback">Por favor, preencha seu CPF.</div>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" required value="<?php echo htmlspecialchars($dados['telefone'] ?? ''); ?>" />
            <div class="invalid-feedback">Por favor, preencha seu telefone.</div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($dados['email'] ?? ''); ?>" />
            <div class="invalid-feedback">Por favor, preencha um e-mail válido.</div>
        </div>

        <div class="mb-3">
            <label for="medico" class="form-label">Médico</label>
            <select id="medico" name="medico" class="form-select" required>
                <option value="">Selecione</option>
                <?php while ($med = $medicos->fetch_assoc()): ?>
                    <option value="<?php echo $med['id']; ?>" <?php if (($dados['medico'] ?? '') == $med['id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($med['nome']) . " - " . htmlspecialchars($med['especialidade']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <div class="invalid-feedback">Por favor, selecione um médico.</div>
        </div>

        <div class="mb-3">
            <label for="data" class="form-label">Data da consulta</label>
            <input type="date" id="data" name="data" class="form-control" required disabled value="<?php echo htmlspecialchars($dados['data'] ?? ''); ?>" />
            <div class="invalid-feedback">Por favor, selecione uma data.</div>
        </div>

        <div class="mb-4">
            <label for="hora" class="form-label">Horário disponível</label>
            <select id="hora" name="hora" class="form-select" required disabled>
                <option value="">Selecione um médico e uma data</option>
            </select>
            <div class="invalid-feedback">Por favor, selecione um horário.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 mb-3">Agendar</button>

        <div class="d-grid">
        <a href="/sistema-consultas/index.php" class="btn btn-outline-primary rounded-pill">
             Voltar para a página inicial
        </a>
        </div>

    </form>
    </main>

    <footer class="text-white text-center py-3 mt-auto" style="background-color: #004c85;">
        <div class="container">
            <p class="mb-0">© <?php echo date("Y"); ?> Clínica Pombos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        });
    })();

    document.addEventListener("DOMContentLoaded", function () {
      const medico = document.getElementById("medico");
      const data = document.getElementById("data");
      const hora = document.getElementById("hora");

      const diasMap = {
          "domingo": 0, "segunda": 1, "terca": 2, "quarta": 3, "quinta": 4, "sexta": 5, "sabado": 6
      };

      let diasDisponiveis = [];

      medico.addEventListener("change", () => {
          const medicoId = medico.value;
          data.disabled = true;
          data.value = "";
          hora.disabled = true;
          if (!medicoId) {
              hora.innerHTML = '<option value="">Selecione um médico primeiro</option>';
              return;
          }

          fetch(`/sistema-consultas/backend/get-dias-disponi.php?medico_id=${medicoId}`)
            .then(res => res.json())
            .then(dias => {
                diasDisponiveis = dias.map(d => {
                    const normalizado = d.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    return diasMap[normalizado];
                });
                data.disabled = false;
                hora.innerHTML = '<option value="">Selecione uma data</option>';
              })
            .catch(() => {
                hora.innerHTML = '<option value="">Erro ao carregar dias</option>';
            });
      });

      data.addEventListener("change", () => {
        const dataSelecionada = new Date(data.value + "T00:00:00");
        const diaSemana = dataSelecionada.getDay();

            if (!diasDisponiveis.includes(diaSemana)) {
              alert("O médico não atende nesse dia.");
              data.value = "";
              hora.innerHTML = '<option value="">Dia inválido</option>';
              hora.disabled = true;
              return;
            }

        const medicoId = medico.value;
            if (medicoId && data.value) {
                fetch(`/sistema-consultas/backend/get-horarios.php?medico_id=${medicoId}&data=${data.value}`)
                .then(res => res.json())
                .then(horarios => {
                    hora.innerHTML = "";
                    if (horarios.length === 0) {
                        hora.innerHTML = '<option value="">Nenhum horário disponível</option>';
                        hora.disabled = true;
                    } else {
                        hora.innerHTML = '<option value="">Selecione</option>';
                        horarios.forEach(h => {
                            const opt = document.createElement("option");
                            opt.value = h;
                            opt.text = h;
                            hora.appendChild(opt);
                        });
                        hora.disabled = false;
                    }
                })
                .catch(() => {
                    hora.innerHTML = '<option value="">Erro ao carregar horários</option>';
                    hora.disabled = true;
                });
          }
      });
  });
</script>

</body>
</html>
