<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once("../config/db.php");
    session_start();

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $medico_id = $_POST["medico"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];

    // Verifica se o horario ja esta ocupado para esse medico
    $confere = $conn->prepare("SELECT id FROM consultas WHERE medico_id = ? AND data = ? AND hora = ?");
    $confere->bind_param("iss", $medico_id, $data, $hora);
    $confere->execute();
    $res = $confere->get_result();

    if ($res->num_rows > 0) {
        $_SESSION["erro_agendamento"] = "Este horário já está reservado para este médico.";
        $_SESSION["dados_formulario"] = compact('nome', 'cpf', 'telefone', 'email', 'medico_id', 'data', 'hora');
        header("Location: ../public/agendar.php");
        exit();
    }

    // Verificar se o paciente ja existe pelo cpf
    $verifica = $conn->prepare("SELECT id, login FROM pacientes WHERE cpf = ?");
    $verifica->bind_param("s", $cpf);
    $verifica->execute();
    $resultado = $verifica->get_result();

    if ($resultado->num_rows > 0) {
        // Paciente ja existe, pega id e login
        $paciente = $resultado->fetch_assoc();
        $paciente_id = $paciente["id"];
        $login = $paciente["login"];
    } else {
        // Gerar login
        $resultCount = $conn->query("SELECT COUNT(*) as total FROM pacientes");
        $count = $resultCount->fetch_assoc()['total'] + 1;
        $login = 'pac' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Senha
        $senha = substr(preg_replace('/\D/', '', $cpf), 0, 6); 

        // Inserir novo paciente com login e senha 
        $inserir_paciente = $conn->prepare("INSERT INTO pacientes (nome, email, telefone, cpf, login, senha) VALUES (?, ?, ?, ?, ?, ?)");
        $inserir_paciente->bind_param("ssssss", $nome, $email, $telefone, $cpf, $login, $senha);
        $inserir_paciente->execute();
        $paciente_id = $inserir_paciente->insert_id;
    }

    // Inserir consulta
    $status = "agendada";
    $consulta = $conn->prepare("INSERT INTO consultas (paciente_id, medico_id, data, hora, status) VALUES (?, ?, ?, ?, ?)");
    $consulta->bind_param("iisss", $paciente_id, $medico_id, $data, $hora, $status);
    $consulta->execute();

    // Salvar login e senha na sessão para exibir na pagina de confirmacao
    $_SESSION['login_paciente'] = $login;
    if (!isset($senha)) {
        $senhaQuery = $conn->prepare("SELECT senha FROM pacientes WHERE id = ?");
        $senhaQuery->bind_param("i", $paciente_id);
        $senhaQuery->execute();
        $senhaResult = $senhaQuery->get_result();
        $senhaRow = $senhaResult->fetch_assoc();
        $senha = $senhaRow['senha'];
    }
    $_SESSION['senha_paciente'] = $senha;

    header("Location: /sistema-consultas/public/agendar/confirm.php");
    exit();
}
?>




