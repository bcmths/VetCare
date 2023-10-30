<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

$errors = []; // Para armazenar mensagens de erro

// Verificar se foi fornecido um ID de sinal clínico válido na URL
if (isset($_GET['id'])) {
    $sinal_id = $_GET['id'];
    $sinal_query = "SELECT * FROM tb_sinaisclinicos WHERE id = :id";
    $stmt = $pdo->prepare($sinal_query);
    $stmt->execute(['id' => $sinal_id]);
    $sinal = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Se não houver ID válido, redirecione para a página de gerenciamento de sinais clínicos
    header("Location: sinaisclinicos.php");
    exit;
}

// Processamento do formulário de edição de sinal clínico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $paciente_id = $_POST['paciente_id'];

    // Validação: descrição é obrigatória
    if (empty($descricao)) {
        $errors[] = "Descrição é obrigatória.";
    }

    if (empty($errors)) {
        // Execute a consulta SQL para atualizar os dados do sinal clínico
        $update_query = "UPDATE tb_sinaisclinicos SET tx_descricao = :descricao, paciente_id = :paciente_id WHERE id = :id";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute(['descricao' => $descricao, 'paciente_id' => $paciente_id, 'id' => $sinal_id]);

        // Redirecionar de volta para a página de gerenciamento de sinais clínicos
        header("Location: sinaisclinicos.php");
        exit;
    }
}

// Consulta para recuperar informações de todos os pacientes
$pacientes_query = "SELECT id, tx_nome FROM tb_paciente";
$pacientes_result = $pdo->query($pacientes_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Sinal Clínico</title>
</head>
<body>
    <h2>Editar Sinal Clínico</h2>

    <?php if (!empty($errors)) { ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form method="post" action="editar_sinalclinico.php?id=<?php echo $sinal['id']; ?>">
        <input type="hidden" name="id" value="<?php echo $sinal['id']; ?>">

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" value="<?php echo $sinal['tx_descricao']; ?>" required>

        <label for="paciente_id">Animal:</label>
        <select name="paciente_id">
            <option value="">Nenhum</option>
            <?php while ($paciente = $pacientes_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $paciente['id']; ?>" <?php if ($paciente['id'] == $sinal['paciente_id']) echo "selected"; ?>>
                    <?php echo $paciente['tx_nome']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Salvar Alterações">
        <input type="button" value="Cancelar" onclick="window.location.href='sinaisclinicos.php';">
    </form>

    <a href="sinaisclinicos.php">Voltar para a lista de sinais clínicos</a>
</body>
</html>
