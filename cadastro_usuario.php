<?php 
    session_start();
    require_once('conexao.php');

    //Verifica se o usuário tem permissão 
    //Supondo que o perfil 1 seja o Administrador
    if($_SESSION['perfil']!=1){
        echo "Acesso Negado!";
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
        $id_perfil = $_POST['id_perfil'];

        $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) 
        VALUES(:nome, :email, :senha, :id_perfil)"; // -> : <- para proteção evitar SQL INJECTION
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':id_perfil', $id_perfil);
        
        if($stmt->execute()){
            echo "<script>alert('Usuário Cadastrado com Sucesso!');</script>";
        }
        else{
            echo "<script>alert('Erro ao Cadastrar Usuário!');</script>";
        }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Cadastrar Usuário</h2>
    <form action="cadastro_usuario.php" method="POST"> <!-- Pq não "#" o objetivo é que busque no arquivo Back-End.(aqui está no mesmo pq nos é vagabundo e faz tudo no mesmo,mas o back-end deve estar separado do Front-End--> 
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="id_perfil">Perfil:</label>
        <select id="id_perfil" name="id_perfil">
            <option value="1">Administrador(a)</option>
            <option value="2">Secretário(a)</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <a href="principal.php">Voltar</a>
</body>
</html>