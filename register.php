<?php
session_start();

require_once('conexao.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $vet_id = $_POST['vet_id'];

    if (empty($username)) {
        $errors[] = "Nome de usuário é obrigatório.";
    }
    if (empty($password)) {
        $errors[] = "Senha é obrigatória.";
    }

    if (empty($vet_id)) {
        $errors[] = "Somente veterinários podem ter acesso ao sistema.";
    }

    $check_username_query = "SELECT COUNT(*) FROM tb_usuario WHERE tx_usuario = :username";
    $check_stmt = $pdo->prepare($check_username_query);
    $check_stmt->execute(['username' => $username]);
    $count = $check_stmt->fetchColumn();

    if ($count > 0) {
        $errors[] = "Nome de usuário já existe. Escolha outro.";
    }

    if (empty($errors)) {

        $insert_query = "INSERT INTO tb_usuario (tx_usuario, vet_id, tx_senha) VALUES (:username, :vet_id, :password)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->execute(['username' => $username, 'vet_id' => $vet_id, 'password' => $password]);

        header("Location: login.php");
        exit;
    }
}

$vet_query = "SELECT id, tx_nome FROM tb_vet";
$vet_result = $pdo->query($vet_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https:
        rel=" stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crie uma conta!</h1>
                            </div>
                            <form method="post" action="register.php">
                                <div class="form-group">
                                    <label for="username">Nome de Usuário:</label>
                                    <input type="text" class="form-control form-control-user" name="username"
                                        id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Usuário"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Senha:</label>
                                    <input type="password" class="form-control form-control-user" name="password"
                                        id="exampleInputPassword" placeholder="Senha" required>
                                </div>

                                <div class="form-group">
                                    <label for="vet_id">Veterinário:</label>
                                    <select name="vet_id">
                                        <option value="">Nenhum</option>
                                        <?php while ($vet = $vet_result->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $vet['id']; ?>">
                                                <?php echo $vet['tx_nome']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <?php if (!empty($errors)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php foreach ($errors as $error) { ?>
                                            <?php echo $error; ?><br>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <button type="submit" class="btn btn-primary btn-user btn-block">Register
                                    Account</button>
                            </form>
                            <hr />
                            <div class="text-center">
                                <a class="small" href="login.php">Já tem uma conta? Faça login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>