<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Clínica Pombos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f5f7fa;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header.topo {
      background-color: #0071bc;
      color: white;
      padding: 1.25rem 0;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    header.topo h1 {
      font-weight: 600;
      margin: 0;
    }
    header.topo nav a {
      color: white;
      font-weight: 600;
      margin-left: 1rem;
      text-decoration: none;
      transition: opacity 0.3s ease;
    }
    header.topo nav a:hover {
      opacity: 0.8;
    }
    section {
      padding: 4rem 1rem;
      background-color: white;
    }
    section:nth-of-type(even) {
      background-color: #f1f5f9;
    }
    section h2 {
      color: #0071bc;
      margin-bottom: 2rem;
      text-align: center;
      font-size: 2rem;
    }
    ul.lista-especialidades {
      max-width: 600px;
      margin: 0 auto;
      padding-left: 0;
      list-style: none;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1rem;
    }
    ul.lista-especialidades li {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      font-weight: 500;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      flex: 1 0 120px;
      text-align: center;
      user-select: none;
    }
    .grid-beneficios .col {
      border-left: 5px solid #00a859;
      background: white;
      padding: 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 1.5rem;
    }
    .grid-beneficios h3 {
      color: #00a859;
      margin-bottom: 0.75rem;
    }
    blockquote {
      font-style: italic;
      border-left: 4px solid #00a859;
      background: white;
      padding: 1.25rem 1.5rem;
      border-radius: 0.5rem;
      max-width: 700px;
      margin: 1rem auto;
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
    footer.rodape {
      background-color: #004c85;
      color: white;
      text-align: center;
      padding: 2rem 1rem;
      font-size: 0.9rem;
      margin-top: auto;
    }
  </style>
  
</head>
<body>

  <header class="topo">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
      <h1 class="mb-0">Clínica Pombos</h1>
      <nav class="navegacao mt-2 mt-md-0">
        <a href="public/medicos.php">Nossos Médicos</a>
        <a href="public/paciente/login-paciente.php" class="ms-3">Login do Paciente</a>
        <a href="admin/login.php" class="ms-3">Login do Administrador</a>
      </nav>
    </div>
  </header>

  <section class="hero text-center">
    <div class="container">
      <h2>Sua saúde em boas mãos</h2>
      <p class="mx-auto w-75">Atendimento humanizado, com especialistas de confiança e agendamento facilitado.</p>
      <a href="public/agendar/agendar.php" class="btn btn-primary btn-lg rounded-pill mt-3 px-4 fw-bold">Agendar Consulta</a>
    </div>
  </section>

  <section class="sobre">
    <div class="container">
      <h2>Sobre a Clínica</h2>
      <p class="mx-auto w-75 text-center">Somos referência em atendimento médico de qualidade com preços acessíveis. Atuamos com diversas especialidades e uma equipe preparada para cuidar de você e sua família.</p>
    </div>
  </section>

  <section class="especialidades">
    <div class="container">
      <h2>Especialidades</h2>
      <ul class="lista-especialidades">
        <li>Clínico Geral</li>
        <li>Pediatria</li>
        <li>Ginecologia</li>
        <li>Cardiologia</li>
        <li>Ortopedia</li>
        <li>Dermatologia</li>
      </ul>
    </div>
  </section>

  <section class="beneficios">
    <div class="container">
      <h2>Por que escolher a Clínica Pombos?</h2>
      <div class="row grid-beneficios">
        <div class="col-md-4">
          <h3>Agendamento Online</h3>
          <p>Praticidade e rapidez para marcar sua consulta sem sair de casa.</p>
        </div>
        <div class="col-md-4">
          <h3>Preços Acessíveis</h3>
          <p>Consultas com valores justos, sem precisar de plano de saúde.</p>
        </div>
        <div class="col-md-4">
          <h3>Atendimento Humanizado</h3>
          <p>Profissionais capacitados e prontos para te ouvir e cuidar de você.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="depoimentos">
    <div class="container text-center">
      <h2>O que nossos pacientes dizem</h2>
      <blockquote>“Fui muito bem atendido! Marquei pelo site e deu tudo certo.” – João S.</blockquote>
      <blockquote>“A médica foi super atenciosa. Vou voltar com certeza.” – Carla M.</blockquote>
    </div>
  </section>

  <footer class="rodape">
    <div class="container text-center">
      <p>© <?php echo date("Y"); ?> Clínica Pombos. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



