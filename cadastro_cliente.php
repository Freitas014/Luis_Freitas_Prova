<?php 
    session_start();
    require_once('conexao.php');

    //Verifica se o usuário tem permissão 
    //Supondo que o perfil 1 seja o Administrador
    if($_SESSION['perfil']!=1 && $_SESSION['perfil'] !=2 && $_SESSION['perfil'] !=4){
        echo "Acesso Negado!";
    }
    if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
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


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];

        $sql = "INSERT INTO cliente (nome_cliente, endereco, telefone, email) 
        VALUES(:nome, :endereco, :telefone, :email)"; // -> : <- para proteção evitar SQL INJECTION
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        
        if($stmt->execute()){
            echo "<script>alert('Cliente Cadastrado com Sucesso!');</script>";
        }
        else{
            echo "<script>alert('Erro ao Cadastrar Cliente!');</script>";
        }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
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
    <center><h2>Cadastrar Usuário</h2></center>
    <form action="cadastro_cliente.php" method="POST"> <!-- Pq não "#" o objetivo é que busque no arquivo Back-End.(aqui está no mesmo pq nos é vagabundo e faz tudo no mesmo,mas o back-end deve estar separado do Front-End--> 
        <label for="nome">Nome:</label>
        <input type="text" class="form-control" placeholder="Insira o Nome" id="nome" name="nome" required>
         
        <label for="email">Email:</label>
        <input type="email" class="form-control" placeholder="Insira o Email" id="email" name="email" required>

        <label for="endereco">endereco:</label>
        <input type="text" class="form-control" placeholder="Insira o Endereço" id="endereco" name="endereco" required>

        <label for="telefone">Telefone:</label>
        <input type="text" class="form-control" placeholder="Insira o Telefone" id="telefone" name="telefone" required>

        <button id="botao" class="btn btn-outline-warning" type="submit">Salvar</button>
        <button id="botao" class="btn btn-outline-warning" type="reset">Cancelar</button>
    </form>
    
    <center><a href="principal.php">Voltar</a></center>
    <br><br><address align="center">
        Luís Fernando / Estudante / Técnico Desenvolvimento de Sistemas
    </address>
</body>
</html>