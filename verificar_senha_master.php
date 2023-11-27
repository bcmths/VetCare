<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar_senha_master'])) {
    $senhaMasterDigitada = $_POST['senha_master'] ?? '';
    $senhaMasterCorreta = '';

    if ($senhaMasterDigitada === $senhaMasterCorreta) {
        // Senha correta, defina a variável de sessão
        $_SESSION['senha_master_verificada'] = true;
        header('usuarios.php');
        echo 'success';
    } else {
        // Senha incorreta
        echo 'error';
    }
} else {
    http_response_code(405); // Método não permitido
    echo 'Method Not Allowed';
}
?>