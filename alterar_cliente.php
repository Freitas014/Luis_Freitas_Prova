<?php
    session_start();
    require_once 'conexao.php';

    //Verifica se o usuário tem permissão de ADM.
    if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=4){
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
    $cliente = null;
    if($_SERVER["REQUEST_METHOD"]== "POST"){
        if(!empty($_POST['busca_cliente'])){
            $busca = trim($_POST['busca_cliente']);

            //Verifica se a busca e um numero (id) ou um nome.
            if(is_numeric($busca)){
                $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
            } else{
                $sql = "SELECT * FROM cliente WHERE nome LIKE :busca_nome";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
            }
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            //Se o cliente nao for encontrado, exibe um alerta.
            if(!$cliente){
                echo "<script>alert('cliente não encontrado!');</script>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
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
    <h2>Alterar Cliente</h2>
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Digite o ID ou o nome do usuário:</label>
        <input type="text" class="form-control" placeholder="Insira ID ou Nome" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()">

        <!-- Div para exibir sugestoes de cliente -->
         <div id="sugestoes">

         </div>
         <button type="submit" class="btn btn-outline-warning">Buscar</button>
    </form>
    <?php if($cliente): ?>
        <!-- Formulario para alterar o cliente -->
        <form action="processa_alteracao_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?=htmlspecialchars($cliente['id_cliente'])?>">

            <label for="nome_cliente">Nome:</label>
            <input type="text" class="form-control" name="nome_cliente" id="nome_cliente" palceholder="Alterar Nome" value="<?=htmlspecialchars($cliente['nome_cliente'])?>" required>

            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" palceholder="Alterar Email" value="<?=htmlspecialchars($cliente['email']);?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" id="endereco" name="endereco" palceholder="Alterar Endereço" value="<?=htmlspecialchars($cliente['endereco']);?>" required>

            <label for="telefone">telefone:</label>
            <input type="tel" pattern="\(d\{2}\)\d{4,5}-\d{4}" class="form-control" name="telefone" id="telefone" palceholder="Alterar Telefone" value="<?=htmlspecialchars($cliente['telefone']);?>" required> 
        
            <button type="submit" class="btn btn-outline-warning">Alterar</button>
            <button type="reset" class="btn btn-outline-warning">Cancelar</button>
        </form>
        <script>        
    //Mascara pro campo telefone
      telefone.addEventListener("input", function(){
          let valor = telefone.value.replace(/\D/g, "");
          if(valor.lenght >10){
              valor = valor.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3")
          } else if(valor.length > 5){
              valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3")
          }else{
              valor = valor.replace(/^(\d*)/, "($1")
          }
            
          telefone.value = valor;
    
        })
        </script> 
    <?php endif; ?>
    <a href="principal.php">Voltar</a>
         
</body>
</html>