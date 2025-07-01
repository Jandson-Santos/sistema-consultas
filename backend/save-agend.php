<?php
    if ($_SERVER["REQUEST_METHOD"]=== "POST"){
            require_once("../config/db.php");

    //receber dados do formulario
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $medico_id = $_POST["medico"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];

    // verificar o cpf
    $verifica = $conn->prepare("SELECT id FROM pacientes WHERE cpf = ?");
    $verifica->bind_param("s", $cpf);
    $verifica->execute();
    $resultado = $verifica->get_result();


    if ($resultado->num_rows > 0){
        $paciente = $resultado->fetch_assoc();
        $paciente_id = $paciente["id"];
    } else {
        //inserir novo paciente
        $inserir_paciente = $conn->prepare("INSERT INTO pacientes (nome, email, telefone, cpf) VALUES (?, ?, ?, ?)");
        $inserir_paciente->bind_param("ssss", $nome, $email, $telefone, $cpf);
        $inserir_paciente->execute();
        $paciente_id = $inserir_paciente->insert_id;
    }

    //agendar consulta
    $status = "agendada";
    $consulta = $conn->prepare("INSERT INTO consultas (paciente_id, medico_id, data, hora, status) VALUES (?, ?, ?, ?, ?)");
    $consulta->bind_param("iisss", $paciente_id, $medico_id, $data, $hora, $status);
    $consulta-> execute();

    //pagina de confirmação
    header("Location: ../public/confirm.php");
    exit();
}
?>