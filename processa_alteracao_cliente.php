<?php 
    session_start();
    require 'conexao.php';

    if($_SESSION['perfil'] !=1){
        echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST");
    $id_cliente = $_POST['id_cliente'];
    $nome_cliente = $_POST['nome_cliente'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    // Atualiza os dados do usuario 
    if($id_cliente && $nome_cliente && $email && $endereco && $telefone){
        $sql = "UPDATE usuario SET nome_cliente = :nome_cliente, email = :email, endereco = :endereco, telefone = :telefone WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':telefone', $telefone);
    } else{
        $sql = "UPDATE usuario SET nome_cliente = :nome_cliente, email = :email, endereco = :endereco, telefone = :telefone WHERE id_cliente = :id";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindParam(':nome_cliente', $nome_cliente);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->bindParam(':id', $id_usuario);

    if($stmt->execute()) {
        echo "<script>alert('Cliente atualizado com sucesso!');window.location.href='buscar_usuario.php';</script>";
    } else{
        echo "<script>alert('Erro ao atualizar usuario!');window.location.href='alterar_usuario.php?=$id_usuario';</script>";
    }
?>