<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

$errors = []; // Para armazenar mensagens de erro

// Verificar se foi fornecido um ID de usuário válido na URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user_query = "SELECT * FROM tb_usuario WHERE id = :id";
    $stmt = $pdo->prepare($user_query);
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Se não houver ID válido, redirecione para a página de gerenciamento de usuários
    header("Location: usuario.php");
    exit;
}

// Processamento do formulário de edição de usuário
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
        // Execute a consulta SQL para atualizar os dados do usuário
        $update_query = "UPDATE tb_usuario SET tx_usuario = :username, vet_id = :vet_id, tx_senha = :password WHERE id = :id";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute(['username' => $username, 'vet_id' => $vet_id, 'password' => $password, 'id' => $user_id]);

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
    <title>Editar Usuário</title>
</head>
<body>
    <h2>Editar Usuário</h2>

    <?php if (!empty($errors)) { ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form method="post" action="editar_usuario.php?id=<?php echo $user['id']; ?>">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label for="username">Nome de Usuário:</label>
        <input type="text" name="username" value="<?php echo $user['tx_usuario']; ?>" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" required>

        <label for="vet_id">Veterinário:</label>
        <select name="vet_id">
            <option value="">Nenhum</option>
            <?php while ($vet = $vet_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $vet['id']; ?>" <?php if ($vet['id'] == $user['vet_id']) echo "selected"; ?>>
                    <?php echo $vet['tx_nome']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Salvar Alterações">
        <input type="button" value="Cancelar" onclick="window.location.href='usuario.php';">
    </form>

    <a href="usuario.php">Voltar para a lista de usuários</a>
</body>
</html>