<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8"/>
    <title>Clinica Pombos</title>

    <link rel="stylesheet" href="public/css/style.css" />
    <style>
        /* Estilo simples para posicionar o botão no header */
        header {
            position: relative;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }

        /* Botão no topo direito */
        .login-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        /* Ajuste para o h1 e p no header para não encobrir o botão */
        header h1, header p {
            margin: 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>Clinica Pombos</h1>
        <p>Cuidando da sua saúde com responsabilidade</p>
        <a href="public/paciente/login-paciente.php" class="login-btn">Login</a>
    </header>
    

    <main style="text-align: center; padding: 60px;">
        <section>
            <h2>Informações de Contato</h2>
            <p><strong>Endereço:</strong> Rua dos Pombos - Centro, Pombos - PE</p>
            <p><strong>Telefone:</strong> (81) 12345-6789</p>
            <p><strong>Email:</strong> contato@clinicapombos.com.br</p>
            <p><strong>Horario de atendimento:</strong> Segunda a Sexta, das 08h as 18h</p>
        </section>

        <section>
            <h2>Nossos Medicos</h2>

            <div class="medico">
                <h3>Dr. Joao Silva</h3>
                <p><strong>Especialidade:</strong> Clinico Geral</p>
                <p>sla oq 15 anos de experiencia kskfskfskfskffakifnafna kifanfklanf kifanfklanffa</p>
                <p><strong>Atendimento:</strong> Terça e Quinta - das 08 as 12h</p>
            </div>

            <div class="medico">
                <h3>Dra. Carla Santos</h3>
                <p><strong>Especialidade:</strong> Cardiologista</p>
                <p>sla oq 15 anos de experiencia kskfskfskfskffakifnafna kifanfklanf kifanfklanffa</p>
                <p><strong>Atendimento:</strong> Segunda e Quarta - das 14 as 18h</p>
            </div>
        </section>

        <section style="margin-top: 40px;">
            <a href="public/agendar.php">
                <button style="padding: 12px 24px; font-size: 16px; margin-top: 20px;">
                Clique aqui para agendar uma consulta
                </button>
            </a>
        </section>  
    </main>
</body>
</html>
