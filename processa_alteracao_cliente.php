<?php 
    session_start();
    require 'conexao.php';

    if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=4){
        echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST");
    $id = $_POST['id_cliente'];
    $nome = $_POST['nome_cliente'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    // $novos_dados = $_POST['nome_cliente'] &&  $_POST['email']  &&  $_POST['endereco'] &&  $_POST['telefone'];

    // Atualiza os dados do cliente 
    if($nome_cliente && $email && $endereco && $telefone){
        $sql = "UPDATE cliente SET nome_cliente = :nome_cliente, email = :email, endereco = :endereco, telefone = :telefone WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_cliente', $nome_cliente && ':email', $email && ":endereco", $endereco && ':telefone', $telefone);
    } else{
        $sql = "UPDATE cliente SET nome_cliente = :nome_cliente, email = :email, endereco = :endereco, telefone = :telefone WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindParam(':nome_cliente', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':endereco', $endereco) ;
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        echo "<script>alert('Cliente atualizado com sucesso!');window.location.href='buscar_cliente.php';</script>";
    } else{
        echo "<script>alert('Erro ao atualizar cliente!');window.location.href='alterar_cliente.php?=$id_cliente';</script>";
    }
?>