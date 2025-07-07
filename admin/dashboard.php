<?php
session_start();

// Verifica se esta logado
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$admin_nome = $_SESSION["admin_nome"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Painel Administrativo</title>
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
      .card-dashboard {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        max-width: 480px;
        width: 100%;
        text-align: center;
      }
      h1 {
        font-weight: 600;
        color: #0071bc;
        margin-bottom: 1rem;
      }
      p {
        margin-bottom: 2rem;
        color: #333;
      }
      .btn {
        margin: 0.4rem;
        min-width: 140px;
        font-weight: 600;
      }
      .btn-primary {
        background-color: #0071bc;
        border-color: #0071bc;
      }
      .btn-primary:hover {
        background-color: #005a8f;
        border-color: #005a8f;
      }
      .btn-logout {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        margin-top: 1.5rem;
      }
      .btn-logout:hover {
        background-color: #b02a37;
        border-color: #b02a37;
      }
    </style>
</head>
<body>
  <div class="card-dashboard">
    <h1>Bem-vindo, <?php echo htmlspecialchars($admin_nome); ?>!</h1>
    <p>O que você deseja gerenciar?</p>

    <div class="d-flex flex-wrap justify-content-center">
      <a href="medico/medicos.php" class="btn btn-primary rounded-pill">Médicos</a>
      <a href="medico/disponi-medico.php" class="btn btn-primary rounded-pill">Horários</a>
      <a href="pacientes.php" class="btn btn-primary rounded-pill">Pacientes</a>
      <a href="consultas/consultas.php" class="btn btn-primary rounded-pill">Consultas</a>
    </div>

    <a href="logout.php" class="btn btn-logout rounded-pill">Sair</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


