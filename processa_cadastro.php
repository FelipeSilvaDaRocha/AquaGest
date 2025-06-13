<?php
    require_once('bd.class.php');

    $nome = $_GET['nome'];
    $apelido = $_GET['apelido'];
    $data_nascimento = $_GET['data_nascimento'];
    $celular = $_GET['celular'];
    $rua = $_GET['rua'];
    $numero = $_GET['numero'];
    $referencia = $_GET['referencia'];
    $status = $_GET['status'];
    $id_membro = $_GET['id'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();

    //Encontrar o id da rua pelo nome
    $sql = " SELECT id_rua FROM ruas_cadastradas WHERE nome_rua = '$rua' ";

    if($resultado = mysqli_query($link, $sql)){
       $dados_rua = mysqli_fetch_array($resultado);
       $id_rua = $dados_rua['id_rua'];
        
    }else{
        echo "Erro ao localizar resgistro no banco de dados";
    }

    //Alterando as informações do membro
    $sqlUpdate = "UPDATE membros SET nome = '$nome', apelido = '$apelido', data_nascimento = '$data_nascimento', celular = '$celular', id_rua = '$id_rua', numero = '$numero', referencia = '$referencia', status_m = '$status' WHERE id_membro = '$id_membro' ";

    if(mysqli_query($link, $sqlUpdate)){
        header('Location: alterar.php?id='.$id_membro.'&warning=1');
    }else{
        echo "Erro ao registrar o usuário!";
    }
    
?>