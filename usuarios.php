<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir o arquivo de conexão com o banco de dados
require_once 'conexao.php';

function adicionarUsuario($pdo, $usuario, $senha, $vet_id)
{
    $insert_query = "INSERT INTO tb_usuario (tx_usuario, tx_senha, vet_id)
                    VALUES (:usuario, :senha, :vet_id)";
    $stmt = $pdo->prepare($insert_query);
    return $stmt->execute([
        'usuario' => $usuario,
        'senha' => $senha,
        'vet_id' => $vet_id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_usuario'])) {
        $usuario = $_POST['tx_usuario'] ?? '';
        $senha = $_POST['tx_senha'] ?? '';
        $vet_id = $_POST['vet_id'] ?? '';

        if (!empty($usuario) && !empty($senha) && !empty($vet_id)) {
            if (adicionarUsuario($pdo, $usuario, $senha, $vet_id)) {
                // Redirecionar de volta para a página de gerenciamento de usuários após a adição
                header("Location: usuarios.php");
                exit;
            } else {
                echo "Falha ao adicionar usuário.";
            }
        } else {
            echo "Por favor, preencha todos os campos obrigatórios.";
        }
    }
}

// Consulta para recuperar informações de usuários
// Obtendo dados dos veterinários
$veterinarios_query = "SELECT id, tx_nome FROM tb_vet";
$veterinarios_result = $pdo->query($veterinarios_query);

// Obtendo dados dos usuários
$usuarios_query = "SELECT id, tx_usuario, tx_senha, vet_id FROM tb_usuario";
$usuarios_result = $pdo->query($usuarios_query);

// Construindo array de veterinários
$veterinarios_data = [];
while ($row = $veterinarios_result->fetch(PDO::FETCH_ASSOC)) {
    $veterinarios_data[] = $row;
}

// Construindo array de usuários
$usuarios_data = [];
while ($row = $usuarios_result->fetch(PDO::FETCH_ASSOC)) {
    $usuarios_data[] = $row;
}

// Consulta para recuperar informações do usuário logado
$usuario_id = $_SESSION['user_id'];
$usuario_query = "SELECT tx_usuario, tx_senha, vet_id FROM tb_usuario WHERE id = :usuario_id";
$stmt = $pdo->prepare($usuario_query);
$stmt->execute(['usuario_id' => $usuario_id]);
$usuario = $stmt->fetch();


// Inserir os dados do usuário no HTML como um objeto JavaScript
echo '<script>var usuariosData = ' . json_encode($usuarios_data) . ';</script>';
echo '<script>var usuarioData = ' . json_encode([
    'nome' => $usuario['tx_usuario'],
    'senha' => $usuario['tx_senha'],
    'vet_id' => $usuario['vet_id']
]) . ';</script>';
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>VetCare - Usuários</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa fa-dog"></i>
                </div>
                <div class="sidebar-brand-text mx-3">VetCare</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">Categorias</div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="veterinarios.php">
                    <i class="fa fa-user-md"></i>
                    <span>Veterinários</span></a>
            </li>

            <!-- Nav Item - Tables -->

            <li class="nav-item">
                <a class="nav-link" href="tutores.php">
                    <i class="fa fa-paw"></i>
                    <span>Tutores</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="pacientes.php">
                    <i class="fa fa-dog"></i>
                    <span>Pacientes</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="sinais.php">
                    <i class="fa fa-list"></i>
                    <span>Sinais Clínicos</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="prontuarios.php">
                    <i class="fa fa-file-medical"></i>
                    <span>Prontuários</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#senhaMasterModal">
                    <i class="fa fa-users"></i>
                    <span>Usuários</span>
                </a>
            </li>


            <script>
                function verificarSenhaMaster() {
                    var senhaMasterDigitada = document.getElementById("senhaMasterInput").value;
                    var senhaMasterCorreta = "sisvet";

                    // Enviar a senha master para verificar no lado do servidor
                    $.ajax({
                        type: 'POST',
                        url: 'verificar_senha_master.php',
                        data: {
                            verificar_senha_master: true,
                            senha_master: senhaMasterDigitada
                        },
                        success: function (data) {
                            if (data === 'success') {
                                // Senha master verificada com sucesso, recarregar a página de usuários
                                window.location.href = 'usuarios.php';
                            } else {
                                // Senha master incorreta, exibir uma mensagem de erro
                                alert("Senha Master incorreta. Tente novamente.");
                            }
                        },
                        error: function () {
                            console.error('Erro na solicitação AJAX.');
                        }
                    });
                }
            </script>

            <script>
                $(document).ready(function () {
                    // Inicializar o modal
                    $('#senhaMasterModal').modal({
                        backdrop: 'static', // Evitar fechar clicando fora do modal
                        keyboard: false // Evitar fechar pressionando a tecla Esc
                    });

                    // Adicionar evento de clique ao link de navegação
                    $('li.nav-item a.nav-link[href="#"]').on('click', function (e) {
                        e.preventDefault();
                        // Exibir o modal
                        $('#senhaMasterModal').modal('show');
                    });
                });
            </script>



            <!-- Modal para a senha master -->
            <div class="modal fade" id="senhaMasterModal" tabindex="-1" role="dialog"
                aria-labelledby="senhaMasterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="senhaMasterModalLabel">Digite a Senha Master</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="senhaMasterForm">
                                <div class="form-group">
                                    <label for="senhaMaster">Senha Master:</label>
                                    <input type="password" class="form-control" id="senhaMaster" required>
                                </div>
                                <button type="button" class="btn btn-primary"
                                    onclick="verificarSenhaMaster()">Confirmar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <div id="dateDisplay"></div>

                    <script>
                        function updateDate() {
                            const dateElement = document.getElementById('dateDisplay');
                            const currentDate = new Date();
                            const options = {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            const formattedDate = currentDate.toLocaleDateString('pt-BR',
                                options); // Altere 'pt-BR' para o código de idioma desejado

                            dateElement.textContent = `Hoje é ${formattedDate}.`;
                        }

                        // Atualize a data automaticamente a cada segundo (ou conforme necessário)
                        updateDate(); // Chama a função para exibir a data inicial
                        setInterval(updateDate, 1000); // Atualiza a data a cada segundo
                    </script>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $usuario_id = $_SESSION['user_id'];
                                $vet_query = "SELECT tb_vet.tx_nome, tb_vet.tx_genero FROM tb_vet
                                            JOIN tb_usuario ON tb_vet.id = tb_usuario.vet_id
                                            WHERE tb_usuario.id = :usuario_id";
                                $stmt = $pdo->prepare($vet_query);
                                $stmt->execute(['usuario_id' => $usuario_id]);
                                $vet = $stmt->fetch();

                                if ($vet['tx_genero'] === 'Masculino') {
                                    $prefixo = 'Dr.';
                                } elseif ($vet['tx_genero'] === 'Feminino') {
                                    $prefixo = 'Dra.';
                                } else {
                                    $prefixo = '';
                                }
                                ?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $prefixo . " " . $vet['tx_nome']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" />
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="perfil.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold text-primary">
                                Usuários
                            </h3>
                        </div>


                        <!-- Usuário.php -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome de Usuário</th>
                                            <th>Senha</th>
                                            <th>Veterinário</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios_data as $usuario): ?>
                                            <tr>
                                                <td contenteditable="true" class="editable-cell" data-field="tx_usuario">
                                                    <?php echo $usuario['tx_usuario']; ?>
                                                </td>
                                                <td contenteditable="true" class="editable-cell" data-field="tx_senha">
                                                    <?php echo $usuario['tx_senha']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $nomeDoVet = "";
                                                    foreach ($veterinarios_data as $veterinario) {
                                                        if ($veterinario['id'] == $usuario['vet_id']) {
                                                            $nomeDoVet = $veterinario['tx_nome'];
                                                            break;
                                                        }
                                                    }
                                                    echo $nomeDoVet;
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary save-btn"
                                                        data-usuario-id="<?php echo $usuario['id']; ?>">
                                                        Salvar
                                                    </button>
                                                    <button class="btn btn-danger delete-btn"
                                                        data-usuario-id="<?php echo $usuario['id']; ?>">
                                                        Excluir
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Botão para abrir o modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalAddUsuario">
                                    Adicionar Usuário
                                </button>

                                <!-- Modal para adicionar usuário -->
                                <div class="modal fade" id="modalAddUsuario" tabindex="-1" role="dialog"
                                    aria-labelledby="modalAddUsuarioLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalAddUsuarioLabel">Adicionar Usuário</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" id="addUsuarioForm"
                                                    onsubmit="return validarFormulario()">
                                                    <div class="form-group">
                                                        <label for="tx_usuario">Nome de Usuário:</label>
                                                        <input type="text" name="tx_usuario" id="tx_usuario"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tx_senha">Senha:</label>
                                                        <input type="password" name="tx_senha" id="tx_senha"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="tx_confirmar_senha">Confirmar Senha:</label>
                                                        <input type="password" name="tx_confirmar_senha"
                                                            id="tx_confirmar_senha" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vet_id">Veterinário:</label>
                                                        <select name="vet_id" id="vet_id" class="form-control" required>
                                                            <?php foreach ($veterinarios_data as $veterinario): ?>
                                                                <option value="<?php echo $veterinario['id']; ?>">
                                                                    <?php echo $veterinario['tx_nome']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <!-- Adicione mais campos conforme necessário para usuários -->
                                                    <button type="submit" class="btn btn-primary" name="add_usuario"
                                                        id="addUsuarioButton">
                                                        Adicionar Usuário
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; VetCare 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quer mesmo sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Clique em "Sair" se deseja encerrar sua sessão.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        Cancelar
                    </button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function () {
            $('.save-btn').click(function () {
                var usuario_id = $(this).data('usuario-id');
                var row = $(this).closest('tr');
                var usuario = row.find('[data-field="tx_usuario"]').text().trim();
                var senha = row.find('[data-field="tx_senha"]').text().trim();

                $.ajax({
                    type: 'POST',
                    url: 'atualizar_usuario.php',
                    data: {
                        id: usuario_id,
                        usuario: usuario,
                        senha: senha,

                    },
                    success: function (response) {
                        if (response === 'success') {
                            // Atualização bem-sucedida
                            console.log('Usuário atualizado com sucesso.');
                        } else {
                            // Atualização falhou
                            console.error('Falha na atualização do usuário.');
                        }
                    },
                    error: function () {
                        console.error('Erro na solicitação AJAX.');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Adicione um evento de clique aos botões "Excluir"
            $('.delete-btn').click(function () {
                const usuario_id = $(this).data('usuario-id');

                // Confirmar com o usuário antes de excluir
                if (confirm('Tem certeza de que deseja excluir este usuário?')) {
                    // Realizar uma solicitação AJAX para excluir o usuário
                    $.ajax({
                        type: 'POST',
                        url: 'excluir_usuario.php', // Crie um arquivo para a exclusão dos usuários
                        data: {
                            id: usuario_id
                        },
                        success: function (data) {
                            // Verificar a resposta do servidor
                            if (data === 'success') {
                                // Exclusão bem-sucedida
                                console.log('Usuário excluído com sucesso.');
                                // Recarregue a página ou atualize a tabela para refletir a exclusão
                                location.reload();
                            } else {
                                // Exibir uma mensagem de erro se a exclusão falhar
                                console.error('Falha ao excluir usuário.');
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        function verificarSenhaMaster() {
            // Substitua 'suaSenhaMaster' pela senha master real
            var senhaMasterDigitada = document.getElementById("senhaMaster").value;
            var senhaMasterCorreta = "sisvet";

            if (senhaMasterDigitada === senhaMasterCorreta) {
                // Senha correta, redirecionar para a página de usuários
                window.location.href = "usuarios.php";
            } else {
                // Senha incorreta, limpar campo e exibir mensagem de erro
                document.getElementById("senhaMaster").value = "";
                alert("Senha Master incorreta. Tente novamente.");
            }
        }
    </script>

    <script>
        function validarFormulario() {
            var senha = document.getElementById('tx_senha').value;
            var confirmarSenha = document.getElementById('tx_confirmar_senha').value;

            if (senha !== confirmarSenha) {
                alert('As senhas não coincidem. Por favor, verifique.');
                return false; // Impede o envio do formulário se as senhas não coincidirem
            }

            return true; // Permite o envio do formulário se as senhas coincidirem
        }
    </script>


    <style>
        .table-responsive {
            overflow-x: hidden;
        }
    </style>

</body>

</html>