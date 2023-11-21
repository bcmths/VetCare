<?php
// Inclua o arquivo de conexão com o banco de dados
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha os dados do formulário
    $usuario_id = $_POST['id']; // Usando o campo "id" para o ID do usuário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $vet_id = $_POST['vet_id'];

    // Atualize os dados do usuário no banco de dados
    $update_query = "UPDATE tb_usuario
                    SET tx_usuario = :usuario, tx_senha = :senha, vet_id = :vet_id
                    WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    if (
        $stmt->execute([
            'usuario' => $usuario,
            'senha' => $senha,
            'vet_id' => $vet_id,
            'id' => $usuario_id,
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