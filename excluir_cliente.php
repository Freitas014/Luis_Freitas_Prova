<?php
session_start();
require 'conexao.php';

// Verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1){
    echo"<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
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


// Inicializa variavel por armazenas clientes.
$cliente = [];

// Busca todos os usuarios cadastrados em ordem alfabetica.
$sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Se um id for passado via GET excluir o cliente
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_cliente = $_GET['id'];

    // Excluir o cliente do banco de dados.
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Cliente excluído com sucesso!');window.location.href='excluir_cliente.php'</script>";
    } else {
        echo "<script>alert('Erro ao excluir o Cliente!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Clientes</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="styles.css">
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
        <center><h2>Excluir Clientes</h2></center>
        <?php if(!empty($clientes)): ?>
            <center>
                <table class="table" border="2">
                    <tr class="tr">
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Endereço</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                <?php foreach($clientes as $cliente): ?>
                    <tr>
                        <td><center><?= htmlspecialchars($cliente['id_cliente'])?></center></td>
                        <td><?= htmlspecialchars($cliente['nome_cliente'])?></td>
                        <td><?= htmlspecialchars($cliente['email'])?></td>
                        <td><?= htmlspecialchars($cliente['endereco'])?></td>
                        <td><?= htmlspecialchars($cliente['telefone'])?></td>
                        <td>
                            <a href="excluir_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" 
                            onclick="return confirm('Tem certeza que deseja excluir este cliente?')"><center><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#a90000" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
</svg></i></a></center>
                        </td>
                    </tr>
                <?php endforeach; ?>    
                </table>
            </center>
        <?php else: ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif ?>
        <br>
        <center><a href="principal.php">Voltar</a></center>
</body>
</html>