<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once('../conexao.php');

$errors = []; // Para armazenar mensagens de erro

// Processamento do formulário de adição de sinal clínico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $paciente_id = $_POST['paciente_id'];

    // Validação: descrição é obrigatória
    if (empty($descricao)) {
        $errors[] = "Descrição é obrigatória.";
    }

    if (empty($errors)) {
        // Execute a consulta SQL para adicionar um novo sinal clínico
        $insert_query = "INSERT INTO tb_sinaisclinicos (tx_descricao, paciente_id) VALUES (:descricao, :paciente_id)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute(['descricao' => $descricao, 'paciente_id' => $paciente_id]);

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
    <title>Adicionar Sinal Clínico</title>
    <script>
        function checkSubmit() {
            var select = document.querySelector("select[name='paciente_id']");
            if (select.value === "") {
                alert("Selecione um animal antes de enviar o formulário.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Adicionar Sinal Clínico</h2>

    <?php if (!empty($errors)) { ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form method="post" action="adicionar_sinalclinico.php" onsubmit="return checkSubmit();">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" required>

        <label for="paciente_id">Animal:</label>
        <select name="paciente_id">
            <option value="">Nenhum</option>
            <?php while ($paciente = $pacientes_result->fetch(PDO::FETCH_ASSOC)) { ?>
                <option value="<?php echo $paciente['id']; ?>">
                    <?php echo $paciente['tx_nome']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Adicionar">
    </form>

    <a href="sinaisclinicos.php">Voltar para a lista de sinais clínicos</a>
</body>
</html>
