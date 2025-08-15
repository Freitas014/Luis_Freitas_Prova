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
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Se um id for passado via GET excluir o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])){
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
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuários</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
        <center><h2>Excluir Usuários</h2></center>
        <?php if(!empty($usuarios)): ?>
            <center>
                <table class="table" border="2">
                    <tr class="tr">
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Ações</th>
                    </tr>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><center><?= htmlspecialchars($usuario['id_usuario'])?></center></td>
                        <td><?= htmlspecialchars($usuario['nome'])?></td>
                        <td><?= htmlspecialchars($usuario['email'])?></td>
                        <td><center><?= htmlspecialchars($usuario['id_perfil'])?></center></td>
                        <td>
                            <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>" 
                            onclick="return confirm('Tem certeza que deseja excluir este usuário?')"><center><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#a90000" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
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