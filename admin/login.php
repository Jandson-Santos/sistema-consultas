<?php
session_start();
require_once("../config/db.php");

$erro = "";

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        //buscar admin pelo email
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if($resultado->num_rows === 1){
            $admin = $resultado->fetch_assoc();

            if(password_verify($senha, $admin["senha"])){
                //login valido, iniciar sessao
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_nome"] = $admin["nome"];
                header("Location: dashboard.php");
                exit();
            } else{
                $erro = "Senha incorreta";
            }
        } else {
            $erro = "email não encontrado";
        }
    }

?>


<!DOCTYPE html>
<html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Login do adm</title>
        <link rel="stylesheet" href="../public/css/style.css" />
    </head>
    <body>
        <main>
            <h1>Painel do adm - Login</h1>

            <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro;?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="email">E-mail</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="senha">Senha</label><br>
                <input type="password" id="senha" name="senha" required><br><br>

                <button type="submit">Entrar</button>
            </form>
        </main>
    </body>


</html>