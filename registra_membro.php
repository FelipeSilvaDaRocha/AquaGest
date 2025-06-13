<?php
    require_once('bd.class.php');

    $nome = $_POST['nome'];
    $apelido = $_POST['apelido'];
    $data_nascimento = $_POST['data_nascimento'];
    $celular = $_POST['celular'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $referencia = $_POST['referencia'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();

    $membro_existe = false;
    $celular_existe = false;

    //Verifica se o membro já foi cadastrado
    $sql = " SELECT * FROM membros WHERE nome = '$nome' ";

    if($resultado = mysqli_query($link, $sql)){
        $dados_membro = mysqli_fetch_array($resultado);

        if(isset($dados_membro['nome'])){
            $membro_existe = true;
        }
    }else{
        echo 'Erro ao tentar localizar membro';
    }

    //Verifica se o celular já foi cadastrado
    $sql = " SELECT * FROM membros WHERE celular = '$celular' ";

    if($resultado = mysqli_query($link, $sql)){
        $dados_membro = mysqli_fetch_array($resultado);

        if(isset($dados_membro['celular'])){
            $celular_existe = true;
        }
    }else{
        echo 'Erro ao tentar localizar o celular';
    }

    if($membro_existe || $celular_existe){
        $retorno_get = '';

        if($membro_existe){
            $retorno_get.= 'erro-membro=1&';
        }
        if($celular_existe){
            $retorno_get.= 'erro-celular=1&'; 
        }

        header('Location: novo-membro.php?'.$retorno_get);
        die();
    }

    //Encontrar o id da rua pelo nome
    $sql = " SELECT id_rua FROM ruas_cadastradas WHERE nome_rua = '$rua' ";

    if($resultado = mysqli_query($link, $sql)){
       $dados_rua = mysqli_fetch_array($resultado);
       $id_rua = $dados_rua['id_rua'];
        
    }else{
        echo "Erro ao localizar resgistro no banco de dados";
    }

    //query para consulta no banco de dados
    $sql = "INSERT INTO membros (nome, apelido, data_nascimento, celular, id_rua, numero, referencia) VALUES ('$nome', '$apelido', '$data_nascimento', '$celular', '$id_rua', '$numero', '$referencia')";
    
    //executar a query
    if(mysqli_query($link, $sql)){
        header('Location: novo-membro.php?warning=1');
    }else{
        echo "Erro ao registrar o usuário!";
    }
?>