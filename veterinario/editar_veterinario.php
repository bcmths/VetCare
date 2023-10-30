<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vet_id = $_POST['id'];
    $nome = $_POST['nome'];
    $genero = $_POST['genero']; // Adicione a captura do gênero

    // Execute a consulta SQL para atualizar os dados do veterinário
    $update_query = "UPDATE tb_vet SET tx_nome = :nome, tx_genero = :genero WHERE id = :id";
    $stmt = $pdo->prepare($update_query);
    $stmt->execute(['nome' => $nome, 'genero' => $genero, 'id' => $vet_id]);

    // Redirecionar de volta para a página de gerenciamento de veterinários
    header("Location: veterinario.php");
    exit;
}

// Verificar se foi fornecido um ID de veterinário válido na URL
if (isset($_GET['id'])) {
    $vet_id = $_GET['id'];
    $vet_query = "SELECT * FROM tb_vet WHERE id = :id";
    $stmt = $pdo->prepare($vet_query);
    $stmt->execute(['id' => $vet_id]);
    $vet = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Se não houver ID válido, redirecione para a página de gerenciamento de veterinários
    header("Location: veterinario.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Veterinário</title>
</head>
<body>
    <h2>Editar Veterinário</h2>

    <form method="post" action="editar_veterinario.php">
        <input type="hidden" name="id" value="<?php echo $vet['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $vet['tx_nome']; ?>" required>
        
        <!-- Adicione um campo de seleção para o gênero -->
        <label for="genero">Gênero:</label>
        <select name="genero">
            <option value="Masculino" <?php if ($vet['tx_genero'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
            <option value="Feminino" <?php if ($vet['tx_genero'] === 'Feminino') echo 'selected'; ?>>Feminino</option>
            <option value="Outro" <?php if ($vet['tx_genero'] === 'Outro') echo 'selected'; ?>>Outro</option>
        </select>
        
        <input type="submit" value="Salvar Alterações">
    </form>

    <a href="veterinario.php">Voltar para a lista de veterinários</a>
</body>
</html>
