<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

// Tratamento para exclusão de usuário
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    // Execute a consulta SQL para excluir o usuário com base no ID
    $delete_query = "DELETE FROM tb_usuario WHERE id = :id";
    $stmt = $pdo->prepare($delete_query);
    $stmt->execute(['id' => $user_id]);
}

// Consulta para recuperar informações da tabela tb_usuario, incluindo o veterinário associado
$usuarios_query = "SELECT tb_usuario.id, tb_usuario.tx_usuario, tb_vet.tx_nome AS tx_vet_nome
                  FROM tb_usuario
                  LEFT JOIN tb_vet ON tb_usuario.vet_id = tb_vet.id";
$usuarios_result = $pdo->query($usuarios_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Usuários</title>
    <style>
        /* Seus estilos CSS aqui */
    </style>
</head>
<body>
    <h2>Gerenciar Usuários</h2>

    <!-- Tabela para exibir os usuários -->
    <table>
        <thead>
            <tr>
                <th>Nome de Usuário</th>
                <th>Veterinário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $usuarios_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $usuario['tx_usuario']; ?></td>
                    <td><?php echo $usuario['tx_vet_nome'] ?? 'Nenhum'; ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>">Editar</a>
                        <a href="usuario.php?delete=<?php echo $usuario['id']; ?>">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>

    <!-- Botão para adicionar um novo usuário -->
    <a href="adicionar_usuario.php">Adicionar Usuário</a>
    <br>

    <!-- Botão para voltar ao dashboard -->
    <a href="../dashboard.php">Voltar para o Dashboard</a>
</body>
</html>
