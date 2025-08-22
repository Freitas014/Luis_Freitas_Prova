<?php
    session_start();
    require_once 'conexao.php';

    //Verifica se o usuário tem permissão de ADM.
    if($_SESSION['perfil'] !=1){
        echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
        exit();
    }
    // Obtendo o nome do perfil do usuario logado
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil ->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// Definição das permissoes por perfil

$permissoes = [
    1 => ["Cadastrar" =>["cadastro_usuario.php", "cadastro_perfil.php", "cadastro_cliente.php",
        "cadastro_fornecedor.php", "cadastro_produto.php", "cadastro_funcionario.php"],

        "Buscar" =>["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php",
        
        "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        
        "Alterar" =>["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php",
        "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],

        "Excluir" =>["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php",
        "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"],],

    2 => ["Cadastrar" =>["cadastro_cliente.php"],

        "Buscar" =>["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],

        "Alterar" =>["alterar_produto.php", "alterar_fornecedor"],

        "Excluir" =>["excluir_produto.php"],],

    3 => ["Cadastrar" =>["cadastro_funcionario.php"],

        "Buscar" =>["buscar_cliente.php","buscar_fornecedor.php", "buscar_produto.php"],
          
        "Alterar" =>["alterar_fornecedor.php", "alterar_produto.php"],

        "Excluir" =>["excluir_produto.php"],],

    4 => ["Cadastrar" => ["cadastro_cliente.php"],

        "Buscar" =>["buscar_produto.php"],

        "Alterar" =>["alterar_cliente.php"],]
    ];

    //Obtendo as opçoes disponiveis para o perfil logado.
    $opcoes_menu = $permissoes[$id_perfil];

    //inicializa variaveis.
    $usuario = null;
    if($_SERVER["REQUEST_METHOD"]== "POST"){
        if(!empty($_POST['busca_usuario'])){
            $busca = trim($_POST['busca_usuario']);

            //Verifica se a busca e um numero (id) ou um nome.
            if(is_numeric($busca)){
                $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
            } else{
                $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
            }
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            //Se o usuario nao for encontrado, exibe um alerta.
            if(!$usuario){
                echo "<script>alert('Usuario não encontrado!');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <!-- Certifique-se que o JavaScript está sendo carregado corretamente. -->
</head>
<nav>
        <ul class="menu">
            <?php 
                foreach($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?=$categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?=$arquivo ?>"><?=ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
                            </li>   
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<body>
    <h2>Alterar Usuário</h2>
    <form action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o ID ou o nome do usuário:</label>
        <input type="text" class="form-control" placeholder="Insira ID ou Nome" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

        <!-- Div para exibir sugestoes de usuarios -->
         <div id="sugestoes">

         </div>
         <button type="submit" class="btn btn-outline-warning">Buscar</button>
    </form>
    <?php if($usuario): ?>
        <!-- Formulario para alterar o usuario -->
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario'])?>">

            <label for="nome">Nome:</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required>

            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?=htmlspecialchars($usuario['email']);?>" required>

            <label for="id_perfil">Perfil:</label>
            <select id="id_perfil" name="id_perfil">
                <option value="1" <?=$usuario['id_perfil'] == 1 ? 'select':''?>>Administrador</option>
                <option value="2" <?=$usuario['id_perfil'] == 2 ? 'select':''?>>Secretaria</option>
                <option value="3" <?=$usuario['id_perfil'] == 3 ? 'select':''?>>Almoxarife</option>
                <option value="4" <?=$usuario['id_perfil'] == 4 ? 'select':''?>>Cliente</option>
            </select>

            <!-- Se o usuario logado for adm, exibir opcao de alterar senha -->
            <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova Senha</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha">
            <?php endif; ?>
        
            <button type="submit" class="btn btn-outline-warning">Alterar</button>
            <button type="reset" class="btn btn-outline-warning">Cancelar</button>
        </form>
    <?php endif; ?>
    <a href="principal.php">Voltar</a>
         
</body>
</html>