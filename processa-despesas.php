<?php
    session_start();
    
    $id_membro_adm = $_SESSION['id_membro_adm'];
    $categoria = $_POST['categoria'];
    $data = $_POST['data'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'];

    $mes = $_GET['meses'];

    require_once('bd.class.php');

    $objDb = new db();
    $link = $objDb->conecta_mysql();

    // Insere despesas no banco de dados
    $sql = "INSERT INTO despesas (id_adm, categoria, data_registro, descricao, valor) VALUES ('$id_membro_adm', '$categoria', '$data', '$descricao', '$valor')";
    
    if(mysqli_query($link, $sql)){
        header('Location: despesas.php?warning=1');
    }else{
        echo "Erro ao inserir despesas no banco de dados!";
    }

    // Consulta despesas cadastradas
    $sql = "SELECT categoria, data_registro, valor, descricao FROM despesas WHERE DATE_FORMAT(data_registro, '%m') = '$mes'";
    $result = mysqli_query($link, $sql);
    if ($result) {
        $despesas = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $_SESSION['despesas'] = $despesas; // armazena em sessão
        header("Location: despesas.php");
        exit;
    } else {
        echo "Erro ao consultar despesas no banco de dados!";
    }
    
?>