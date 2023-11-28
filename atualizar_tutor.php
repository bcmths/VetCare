<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tutor_id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $update_query = "UPDATE tb_tutor
                    SET tx_nome = :nome, tx_email = :email, nb_telefone = :telefone, tx_endereco = :endereco
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'id' => $tutor_id,
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