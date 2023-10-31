<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha os dados do formulário
    $tutor_id = $_POST['id']; // Usando o campo "id" para o ID do tutor
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Atualize os dados do tutor no banco de dados
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
            // Corrigir aqui, usar 'tutor_id' em vez de 'id'
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