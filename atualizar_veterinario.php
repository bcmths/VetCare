<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha os dados do formulário
    $veterinario_id = $_POST['id']; // Usando o campo "id" para o ID do veterinário
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];

    // Atualize os dados do veterinário no banco de dados
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