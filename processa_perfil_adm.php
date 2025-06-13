<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    $senha = isset($_POST['senha']) ? $_POST['senha'] : 0;
    $senhaConfirmacao = isset($_POST['senhaConfirmacao']) ? $_POST['senhaConfirmacao'] : 0;
    $funcao = isset($_POST['funcaoadm']) ? $_POST['funcaoadm'] : 0;
    $email = isset($_POST['email']) ? $_POST['email'] : 0;
    $celular = isset($_POST['celular']) ? $_POST['celular'] : 0;
    $rua = isset($_POST['rua']) ? $_POST['rua'] : 0;
    $numero = isset($_POST['numero']) ? $_POST['numero'] : 0;
    $referencia = isset($_POST['referencia']) ? $_POST['referencia'] : 0;
    $id_membro = $_SESSION['id_membro_adm'];

    require_once('bd.class.php');

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

    //Efetua atualização da senha do adm
    if($senha === $senhaConfirmacao){
        $sqlUpdateS = " UPDATE dados_login_adm SET senha = '$senha' WHERE id_adm = '$id_membro' ";

        if(mysqli_query($link, $sqlUpdateS)){
            header('Location: configuracao.php?warning=2');
        }else{
            echo "Erro ao atualizar senha";
        }
        die();
    }else{
        header('Location: configuracao.php?erro=3');
    }

    //Efetua a atualização da função
    if($funcao != 0){
        $sqlUpdateF = "UPDATE dados_login_adm SET funcao = '$funcao' WHERE id_adm = '$id_membro' ";
        
        if(mysqli_query($link, $sqlUpdateF)){
            header('Location: configuracao.php?warning=3');
        }else{
            echo "Erro ao atualizar funcao";
        }
        
    }

    //Efetua a atualização do telefone e email
    if($celular != 0 || $email != 0){
        $sqlUpdateEmail = "UPDATE dados_login_adm SET email = '$email' WHERE id_adm = '$id_membro' ";

        if(mysqli_query($link, $sqlUpdateEmail)){
            header('Location: configuracao.php?warning=4');
        }else{
            echo "Erro ao atualizar telefone ou email";
        }

        $sqlUpdateC = "UPDATE membros SET celular = '$celular' WHERE id_adm = '$id_membro' ";

        if(mysqli_query($link, $sqlUpdateC)){
            header('Location: configuracao.php?warning=4');
        }else{
            echo "Erro ao atualizar telefone ou email";
        }
        die();
    }

    //Efetua a atualização dos dados de endereço
    if($rua != 0){
        $sqlUpdate = "UPDATE membros SET id_rua = '$id_rua', numero = '$numero', referencia = '$referencia' WHERE id_adm = '$id_membro' ";

        if(mysqli_query($link, $sqlUpdate)){
            header('Location: configuracao.php?warning=5');
        }else{
            echo "Erro ao atualizar dados de endereco";
        }
        die();
    }

?>