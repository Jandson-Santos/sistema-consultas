<?php

require_once("../config/db.php");

$nome = "Administrador";
$email = "adm@clinica.com";
$senha = "  "; //senha do adm
$senha_hash = password_hash($senha, PASSWORD_DEFAULT); //criptografar

$stmt = $conn->prepare("INSERT INTO admins (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha_hash);
$stmt->execute();

echo "adm criado";
?>