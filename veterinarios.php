<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'conexao.php';

function adicionarVeterinario($pdo, $nome, $genero)
{
    $insert_query = "INSERT INTO tb_vet (tx_nome, tx_genero)
                    VALUES (:nome, :genero)";
    $stmt = $pdo->prepare($insert_query);
    return $stmt->execute([
        'nome' => $nome,
        'genero' => $genero
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_veterinario'])) {
        $nome = $_POST['tx_nome'] ?? '';
        $genero = $_POST['tx_genero'] ?? '';

        if (!empty($nome) && !empty($genero)) {
            if (adicionarVeterinario($pdo, $nome, $genero)) {

                header("Location: veterinarios.php");
                exit;
            } else {
                echo "Falha ao adicionar veterinário.";
            }
        } else {
            echo "Por favor, preencha todos os campos obrigatórios.";
        }
    }
}

$veterinarios_query = "SELECT id, tx_nome, tx_genero FROM tb_vet";
$veterinarios_result = $pdo->query($veterinarios_query);

$veterinarios_data = [];

while ($row = $veterinarios_result->fetch(PDO::FETCH_ASSOC)) {
    if ($row !== false) {
        $veterinarios_data[] = $row;
    }
}

$usuario_id = $_SESSION['user_id'];
$vet_query = "SELECT tx_nome, tx_genero FROM tb_vet WHERE id = :usuario_id";
$stmt = $pdo->prepare($vet_query);
$stmt->execute(['usuario_id' => $usuario_id]);

$vet = $stmt->fetch();

if ($vet !== false) {

    echo '<script>var veterinarioData = ' . json_encode([
        'nome' => $vet['tx_nome'],
        'genero' => $vet['tx_genero']
    ]) . ';</script>';
}

echo '<script>var veterinariosData = ' . json_encode($veterinarios_data) . ';</script>';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>VetCare - Veterinários</title>

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
                <a class="nav-link" href="">
                    <i class="fa fa-file-medical"></i>
                    <span>Prontuários</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link usuarios-link" href="#" onclick="#modalSenhaMaster">
                    <i class="fa fa-users"></i>
                    <span>Usuários</span>
                </a>

            </li>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
            $(document).ready(function() {
                $(".usuarios-link").click(function() {
                    $("#modalSenhaMaster").modal("show");
                });
            });
            </script>

            <script>
            function verificarSenhaMaster() {

                $("#modalSenhaMaster").modal("show");

                var senhaMasterDigitada = document.getElementById("senhaMasterInput").value;

                $.ajax({
                    type: 'POST',
                    url: 'verificar_senha_master.php',
                    data: {
                        verificar_senha_master: true,
                        senha_master: senhaMasterDigitada
                    },
                    success: function(data) {
                        if (data === 'success') {

                            window.location.href = 'usuarios.php';
                        } else {

                            alert("Senha Master incorreta. Tente novamente.");
                        }
                    },
                    error: function() {
                        console.error('Erro na solicitação AJAX.');
                    }
                });
            }
            </script>

            <div class="modal fade" id="modalSenhaMaster" tabindex="-1" role="dialog"
                aria-labelledby="modalSenhaMasterLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalSenhaMasterLabel">Digite a senha master</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="password" id="senhaMasterInput" class="form-control"
                                placeholder="Senha master">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"
                                onclick="verificarSenhaMaster()">Acessar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
                            options);

                        dateElement.textContent = `Hoje é ${formattedDate}.`;
                    }

                    updateDate();
                    setInterval(updateDate, 1000);
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
                                Veterinários
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Gênero</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($veterinarios_data as $veterinario): ?>
                                        <tr>
                                            <td contenteditable="true" class="editable-cell" data-field="tx_nome">
                                                <?php echo $veterinario['tx_nome']; ?>
                                            </td>
                                            <td contenteditable="true" class="editable-cell" data-field="tx_genero">
                                                <?php echo $veterinario['tx_genero']; ?>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary save-btn"
                                                    data-veterinario-id="<?php echo $veterinario['id']; ?>">
                                                    Salvar
                                                </button>
                                                <button class="btn btn-danger delete-btn"
                                                    data-veterinario-id="<?php echo $veterinario['id']; ?>">
                                                    Excluir
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalAddVeterinario">
                                    Adicionar Veterinário(a)
                                </button>

                                <div class="modal fade" id="modalAddVeterinario" tabindex="-1" role="dialog"
                                    aria-labelledby="modalAddVeterinarioLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h5 class="modal-title" i d="modalAddVeterinarioLabel">Adicionar
                                                    Veterinário</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" id="addVeterinarioForm">
                                                    <div class="form-group">
                                                        <label for="nome">Nome:</label>
                                                        <input type="text" name="tx_nome" id="tx_nome"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="genero">Gênero:</label>
                                                        <select name="tx_genero" id="tx_genero" class="form-control"
                                                            required>
                                                            <option value="Masculino">Masculino</option>
                                                            <option value="Feminino">Feminino</option>

                                                        </select>

                                                    </div>

                                                    <!-- Adicione mais campos conforme necessário para veterinários -->

                                                    <button type="submit" class="btn btn-primary" name="add_veterinario"
                                                        id="addVeterinarioButton">Adicionar Veterinário</button>
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

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
    $(document).ready(function() {

        $('.delete-btn').click(function() {
            const veterinario_id = $(this).data('veterinario-id');

            if (confirm('Tem certeza de que deseja excluir este veterinário?')) {

                $.ajax({
                    type: 'POST',
                    url: 'excluir_veterinario.php',
                    data: {
                        id: veterinario_id
                    },
                    success: function(data) {

                        if (data === 'success') {

                            console.log('Veterinário excluído com sucesso.');

                            location.reload();
                        } else {

                            console.error('Falha ao excluir veterinário.');
                        }
                    }
                });
            }
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        $('.save-btn').click(function() {
            var veterinarioId = $(this).data('veterinario-id');
            var row = $(this).closest('tr');
            var nome = row.find('[data-field="tx_nome"]').text().trim();
            var genero = row.find('[data-field="tx_genero"]').text().trim();

            $.ajax({
                type: 'POST',
                url: 'atualizar_veterinario.php',
                data: {
                    id: veterinarioId,
                    nome: nome,
                    genero: genero
                },
                success: function(response) {
                    if (response === 'success') {

                        console.log('Veterinário atualizado com sucesso.');
                    } else {

                        console.error('Falha na atualização do veterinário.');
                    }
                },
                error: function() {
                    console.error('Erro na solicitação AJAX.');
                }
            });
        });
    });
    </script>

    <style>
    .table-responsive {
        overflow-x: hidden;
    }
    </style>

</body>

</html>