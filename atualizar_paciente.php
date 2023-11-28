<?php

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pacienteId = $_POST['id'];
    $nome = $_POST['nome'];
    $animal = $_POST['animal'];
    $raca = $_POST['raca'];
    $tutorId = $_POST['tutor_id'];
    $vetId = $_POST['vet_id'];

    $update_query = "UPDATE tb_paciente
                    SET tx_nome = :nome, tx_animal = :animal, tx_raca = :raca, tutor_id = :tutor_id, vet_id = :vet_id
                    WHERE id = :id";

    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'nome' => $nome,
            'animal' => $animal,
            'raca' => $raca,
            'tutor_id' => $tutorId,
            'vet_id' => $vetId,
            'id' => $pacienteId,
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