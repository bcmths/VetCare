<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prontuario_id = $_POST['id'];
    $obs = $_POST['obs'];
    $paciente_id = $_POST['paciente_id'];

    $update_query = "UPDATE tb_prontuario
                     SET tx_obs = :obs, paciente_id = :paciente_id
                     WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'obs' => $obs,
            'paciente_id' => $paciente_id,
            'id' => $prontuario_id,
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