<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

$errors = []; // Para armazenar mensagens de erro

// Processamento do formulário de adição de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $vet_id = $_POST['vet_id'];
    $password = $_POST['password']; // Adicione um campo para a senha

    // Validação: nome de usuário e senha são obrigatórios
    if (empty($username)) {
        $errors[] = "Nome de usuário é obrigatório.";
    }
    if (empty($password)) {
        $errors[] = "Senha é obrigatória.";
    }

    if (empty($errors)) {
        // Execute a consulta SQL para adicionar um novo usuário
        $insert_query = "INSERT INTO tb_usuario (tx_usuario, vet_id, tx_senha) VALUES (:username, :vet_id, :password)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute(['username' => $username, 'vet_id' => $vet_id, 'password' => $password]);

        // Redirecionar de volta para a página de gerenciamento de usuários
        header("Location: usuario.php");
        exit;
    }
}

// Consulta para recuperar informações de todos os veterinários
$vet_query = "SELECT id, tx_nome FROM tb_vet";
$vet_result = $pdo->query($vet_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Usuário</title>
</head>
<body>
    <h2>Adicionar Usuário</h2>

    <!-- Exibir mensagens de erro (se houver) -->
    <?php if (!empty($errors)) { ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form method="post" action="adicionar_usuario.php">
        <label for="username">Nome de Usuário:</label>
        <input type="text" name="username" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" required>

        <label for="vet_id">Veterinário:</label>
        <select name="vet_id">
            <option value="">Nenhum</option>
            <?php while ($vet = $vet_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $vet['id']; ?>"><?php echo $vet['tx_nome']; ?></option>
            <?php } ?>
        </select>

        <input type="submit" value="Adicionar">
    </form>

    <a href="usuario.php">Voltar para a lista de usuários</a>
</body>
</html>
