<?php
session_start();        // Inicia a sessao atual
session_unset();        // Remove todas as variaveis da sessao
session_destroy();      // Destroi a sessao 
header("Location: login-paciente.php");
exit();

