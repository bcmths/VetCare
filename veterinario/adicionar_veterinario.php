<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

// Processamento do formulário de adição de veterinário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $genero = $_POST['genero']; // Adicione a linha para pegar o gênero do formulário

    // Execute a consulta SQL para adicionar um novo veterinário
    $insert_query = "INSERT INTO tb_vet (tx_nome, tx_genero) VALUES (:nome, :genero)"; // Inclua o campo tx_genero
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute(['nome' => $nome, 'genero' => $genero]); // Inclua o valor do gênero

    // Redirecionar de volta para a página de gerenciamento de veterinários
    header("Location: veterinario.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Veterinário</title>
</head>
<body>
    <h2>Adicionar Veterinário</h2>

    <form method="post" action="adicionar_veterinario.php">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required>
        
        <!-- Adicione um campo de seleção para o gênero -->
        <label for="genero">Gênero:</label>
        <select name="genero">
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
        </select>
        
        <input type="submit" value="Adicionar">
    </form>

    <a href="veterinario.php">Voltar para a lista de veterinários</a>
</body>
</html>
