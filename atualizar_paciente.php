<?php
// Inclua seu arquivo de conexão com o banco de dados aqui
require_once 'conexao.php';

// Verifique se a solicitação é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha os dados do formulário
    $pacienteId = $_POST['id'];
    $nome = $_POST['nome'];
    $animal = $_POST['animal'];
    $raca = $_POST['raca'];
    $tutorId = $_POST['tutor_id'];
    $vetId = $_POST['vet_id'];

    // Atualize os dados do paciente no banco de dados
    $update_query = "UPDATE tb_paciente
                    SET tx_nome = :nome, tx_animal = :animal, tx_raca = :raca, tutor_id = :tutor_id, vet_id = :vet_id
                    WHERE id = :id";

    $stmt = $pdo->prepare($update_query);

    // Execute a atualização
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
        // Atualização bem-sucedida
        echo 'success';
    } else {
        // Falha na atualização
        echo 'error';
    }
} else {
    // Responda a outros tipos de solicitações, se necessário
    http_response_code(405); // Método não permitido
    echo 'Method Not Allowed';
}
?>