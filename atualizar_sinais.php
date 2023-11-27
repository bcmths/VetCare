<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sinaisId = $_POST['id'];
    $descricao = $_POST['descricao'];
    $pacienteId = $_POST['paciente_id'];

    $update_query = "UPDATE tb_sinaisclinicos
                    SET tx_descricao = :descricao, paciente_id = :paciente_id
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'descricao' => $descricao,
            'paciente_id' => $pacienteId,
            'id' => $sinaisId,
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