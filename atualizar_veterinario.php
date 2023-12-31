<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $veterinario_id = $_POST['id'];
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];

    $update_query = "UPDATE tb_vet
                    SET tx_nome = :nome, tx_genero = :genero
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'nome' => $nome,
            'genero' => $genero,
            'id' => $veterinario_id,
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