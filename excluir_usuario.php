<?php
session_start();
require 'conexao.php';

// Verifica se o usuarioo tem permissao de adm
if($_SESSION['perfil'] !=1){
    echo"<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
    exit();
}

// Inicializa variavel por armazenas usuarios.
$usuario = [];

// Busca todos os usuarios cadastrados em ordem alfabetica.
$sql = "SELECT * FROM usuarios ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Se um id for passado via GET excluir o usuario
if(isset($_GET['id']) && is_numeric($_GET['id']));
    $id_usuario = $_GET['id'];

    // Excluir o usuario do banco de dados.
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Usuário excluído com sucesso!');window.location.href='excluir_usuario.php'</script>";
    } else {
        echo "<script>alert('Erro ao excluir o usuario!');</script>";
    }