<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Agendamento de Consultas</title>
</head>

<body>
    <header>
        <h1>Sistemas de Agendamento</h1>
    </header>

    <main>
        <h2>Agende sua consulta</h2>
        <form action="../backend/save-agend.php" method="POST">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" required />

            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" required />

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" required />

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required />

            <label for="medico">Médico</label>
            <select id="medico" name="medico" required>
                <option value="">Selecione</option>
                <option value="1">Dr. João - Clínico Geral</option>
                <option value="2">Dra. Carla - Cardiologista</option>
            </select>

            <label for="data">Data da consulta</label>
            <input type="date", id="data", name="data", required />

            <label for="hora">Hata da consulta</label>
            <input type="time", id="hora", name="hora", required />

            <button type="submit">Agendar</button>

        </form>
    </main>

</body>

</html>
