<?php
session_start();        // Inicia a sessão atual
session_unset();        // Remove todas as variáveis da sessão
session_destroy();      // Destroi a sessão completamente

// Após encerrar a sessão, redireciona para a página de login do paciente
header("Location: login-paciente.php");
exit();

