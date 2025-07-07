<?php
session_start();
session_unset(); // limpa todas as variaveis da sessão
session_destroy(); // desttoi a sessão

header("Location: login.php"); // redireciona para a tela de login
exit();