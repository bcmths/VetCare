<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $update_query = "UPDATE tb_usuario
                    SET tx_usuario = :usuario, tx_senha = :senha
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'usuario' => $usuario,
            'senha' => $senha,
            'id' => $usuario_id,
        ])
    ) {

        echo 'success';
    } else {

        echo 'error';
    }
} else {

    http_response_code(405);
    echo 'Method Not Allowed';
}
?>