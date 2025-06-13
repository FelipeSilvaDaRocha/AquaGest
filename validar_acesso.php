<?php
    session_start();

    require_once('bd.class.php');

    $ra = $_POST['ra'];
    $senha = $_POST['senha'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();

    $sql = " SELECT * FROM dados_login_adm WHERE registro_acesso = '$ra' AND senha = '$senha' ";

    $resultado = mysqli_query($link, $sql);

    if($resultado){
        $dados_adm = mysqli_fetch_array($resultado);

        if(isset($dados_adm['registro_acesso'])){
            $_SESSION['id_membro_adm'] = $dados_adm['id_membro'];
            $_SESSION['funcao'] = $dados_adm['funcao'];
            $_SESSION['registro_acesso'] = $dados_adm['registro_acesso'];
            $_SESSION['senha'] = $dados_adm['senha'];

            header('Location: home.php');
        }else{
            //Correção a tentativa de entrar deslogado
            header('Location: index.php?erro=1');
        }

    }else{
        echo 'Erro na execussão da consulta. Por favor, entre em contato com o adim do site.';
    }

?>