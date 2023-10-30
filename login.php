<?php
session_start();

// Verificar se o usuário está logado
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir o arquivo de conexão com o banco de dados
    require_once 'conexao.php';

    // Receber os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar as credenciais do usuário
    $query = "SELECT id, tx_usuario, tx_senha FROM tb_usuario WHERE tx_usuario = :username AND tx_senha = :password";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username, 'password' => $password]);

    // Verificar se o login foi bem-sucedido
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $login_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Sistema Veterinário</h1>
                                    </div>
                                    <form class="user" method="post" action="login.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Usuário">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" id="exampleInputPassword" placeholder="Senha">
                                        </div>
                                        <?php if (isset($login_error) && $login_error): ?>
                                            <p class="text-danger">Usuário ou senha incorretos.</p>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Crie uma conta!</a>
                                    </div>
                                </div>
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