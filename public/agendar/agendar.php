<?php
session_start();
$erro = $_SESSION["erro_agendamento"] ?? "";
$dados = $_SESSION["dados_formulario"] ?? [];
unset($_SESSION["erro_agendamento"], $_SESSION["dados_formulario"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Agendamento de Consultas</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <header>
        <h1>Sistema de Agendamento</h1>
    </header>

    <main>
        <h2>Agende sua consulta</h2>

        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form action="../backend/save-agend.php" method="POST">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($dados['nome'] ?? ''); ?>" />

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" required value="<?php echo htmlspecialchars($dados['cpf'] ?? ''); ?>" />

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" required value="<?php echo htmlspecialchars($dados['telefone'] ?? ''); ?>" />

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($dados['email'] ?? ''); ?>" />

            <label for="medico">Médico</label>
            <select id="medico" name="medico" required>
                <option value="">Selecione</option>
                <option value="1" <?php if (($dados['medico'] ?? '') == "1") echo "selected"; ?>>Dr. João - Clínico Geral</option>
                <option value="2" <?php if (($dados['medico'] ?? '') == "2") echo "selected"; ?>>Dra. Carla - Cardiologista</option>
                
            </select>

            <label for="data">Data da consulta</label>
            <input type="date" id="data" name="data" required value="<?php echo htmlspecialchars($dados['data'] ?? ''); ?>" />

            <label for="hora">Horário disponível</label>
            <select id="hora" name="hora" required>
            <option value="">Selecione um médico e uma data</option>
            </select>


            <button type="submit">Agendar</button>
        </form>
    </main>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            const medico = document.getElementById("medico");
            const data = document.getElementById("data");
            const hora = document.getElementById("hora");

            const diasMap = {
                "domingo": 0,
                "segunda": 1,
                "terca": 2,
                "quarta": 3,
                "quinta": 4,
                "sexta": 5,
                "sabado": 6
            };

            let diasDisponiveis = [];

            // Quando selecionar o médico, buscar os dias da semana que ele atende
            medico.addEventListener("change", () => {
                const medicoId = medico.value;
                if (!medicoId) return;

                fetch(`../backend/get-dias-disponiveis.php?medico_id=${medicoId}`)
                    .then(res => res.json())
                    .then(dias => {
                        diasDisponiveis = dias.map(d => diasMap[d]); // transforma ["segunda",...] em [1,...]
                        data.disabled = false;
                        data.value = "";
                        hora.innerHTML = '<option value="">Selecione uma data</option>';
                    });
            });

            // Quando mudar a data, verificar se o médico atende nesse dia e buscar horários
            data.addEventListener("change", () => {
                const dataSelecionada = new Date(data.value);
                const diaSemana = dataSelecionada.getDay();

                if (!diasDisponiveis.includes(diaSemana)) {
                    alert("O médico não atende nesse dia.");
                    data.value = "";
                    hora.innerHTML = '<option value="">Dia inválido</option>';
                    return;
                }

                const medicoId = medico.value;
                if (medicoId && data.value) {
                    fetch(`../backend/get-horarios.php?medico_id=${medicoId}&data=${data.value}`)
                        .then(res => res.json())
                        .then(horarios => {
                            hora.innerHTML = "";
                            if (horarios.length === 0) {
                                hora.innerHTML = '<option value="">Nenhum horário disponível</option>';
                            } else {
                                hora.innerHTML = '<option value="">Selecione</option>';
                                horarios.forEach(h => {
                                    const opt = document.createElement("option");
                                    opt.value = h;
                                    opt.text = h;
                                    hora.appendChild(opt);
                                });
                            }
                        });
                }
            });
        });
    </script>

</body>
</html>
