<?php
    require_once('bd.class.php');

    $nome = $_GET['novaRua'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();

    $rua_existe = false;

    //Verifica se a rua já existe
    $sql = " SELECT * FROM ruas_cadastradas WHERE nome_rua = '$nome' ";

    if($resultado = mysqli_query($link, $sql)){
        $dados_rua = mysqli_fetch_array($resultado);

        if($dados_rua['nome_rua']){
            $rua_existe = true;
        }

    }else{
        echo 'Erro ao tentar localizar rua';
    }

    if($rua_existe){
        $retorno_get = '';
        $retorno_get.= 'erro-rua=1';

        header('Location: nova-rua.php?'.$retorno_get);
        die();
    }

    $sql = " INSERT INTO ruas_cadastradas(nome_rua) VALUES ('$nome') ";
    
    //executa a query
    if(mysqli_query($link, $sql)){
        header('Location: nova-rua.php?warning=1');
    }else{
        echo "Erro ao registrar nova rua!";
    }

?>