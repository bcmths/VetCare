<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verificar_senha_master'])) {
    $senhaMasterDigitada = $_POST['senha_master'] ?? '';
    $senhaMasterCorreta = 'sisvet';

    if ($senhaMasterDigitada === $senhaMasterCorreta) {

        $_SESSION['senha_master_verificada'] = true;
        header('usuarios.php');
        echo 'success';
    } else {

        echo 'error';
    }
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}
?>