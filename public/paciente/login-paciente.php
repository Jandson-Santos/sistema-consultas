<?php
session_start();
require_once("../../config/db.php");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"]);
    $senha = trim($_POST["senha"]);

    // Buscar paciente pelo login
    $stmt = $conn->prepare("SELECT id, nome, senha FROM pacientes WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $paciente = $resultado->fetch_assoc();

        // Verifica senha 
        if ($senha === $paciente["senha"]) {
            $_SESSION["paciente_id"] = $paciente["id"];
            $_SESSION["paciente_nome"] = $paciente["nome"];
            header("Location: perfil-paciente.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Login não encontrado.";
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Paciente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f5f7fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
      }
      .card-login {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        max-width: 400px;
        width: 100%;
      }
      h1 {
        font-weight: 600;
        color: #0071bc;
        margin-bottom: 1.5rem;
        text-align: center;
      }
      label {
        font-weight: 600;
        color: #0071bc;
      }
      .btn-primary {
        background-color: #0071bc;
        border-color: #0071bc;
      }
      .btn-primary:hover {
        background-color: #005a8f;
        border-color: #005a8f;
      }
      .mensagem-erro {
        background-color: #ffe5e5;
        color: #c30000;
        padding: 0.75rem 1rem;
        border-left: 5px solid #c30000;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
      }
      a {
        display: block;
        margin-top: 1rem;
        color: #0071bc;
        text-decoration: none;
        text-align: center;
      }
      a:hover {
        text-decoration: underline;
      }
    </style>
</head>
<body>
  <div class="card-login">
    <h1>Login do Paciente</h1>

    <?php if ($erro): ?>
        <div class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form method="POST" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="login" class="form-label">Login</label>
        <input type="text" id="login" name="login" class="form-control" required>
        <div class="invalid-feedback">Por favor, informe seu login.</div>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" required>
        <div class="invalid-feedback">Por favor, informe sua senha.</div>
      </div>

      <button type="submit" class="btn btn-primary w-100 rounded-pill">Entrar</button>
    </form>

    <a href="../../index.php">← Voltar para a página inicial</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validacao bootstrap
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
</body>
</html>


