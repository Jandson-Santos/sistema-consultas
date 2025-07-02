<?php
require_once("../config/db.php");

$nome = "João da Silva";
$cpf = "12345678900";
$telefone = "11999999999";
$email = "joao@teste.com";
$senha = "123456"; // senha simples para teste
$senha_hash = password_hash($senha, PASSWORD_DEFAULT); // criptografada

$stmt = $conn->prepare("INSERT INTO pacientes (nome, cpf, telefone, email, senha) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nome, $cpf, $telefone, $email, $senha_hash);

if ($stmt->execute()) {
    echo "Paciente criado com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}
