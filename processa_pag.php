<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])) {
        header('Location: index.php?erro=2');
        exit;
    }
    
    require_once('bd.class.php');

    $valor_pago = isset($_POST['valorPagamento']) ? floatval($_POST['valorPagamento']) : 0;
    $mes_apuracao = isset($_POST['mes']) ? $_POST['mes'] : 0;
    $data_atual = date("Y-m-d");
    $id_membro = $_SESSION['id_membro'];

    $objDb = new db();
    $link = $objDb->conecta_mysql();
    
    if($mes_apuracao != 0){
        
        $sql = "UPDATE pagamentos SET $mes_apuracao = 'Liquidado' WHERE id_pag = '$id_membro' ";
        mysqli_query($link, $sql);

        header('Location: pagar.php?id='.$id_membro.'&warning=1');
    

        //Introduz no banco de dados os membros para corte e com pagamento em atraso
        $sql = " SELECT Janeiro, Fevereiro, Marco, Abril, Maio, Junho, Julho, Agosto, Setembro, Outubro, Novembro, Dezembro FROM pagamentos WHERE id_membro = $id_membro ";

        if($resultadoSelec = mysqli_query($link, $sql)){
            $dados2_pagamento = mysqli_fetch_array($resultadoSelec, MYSQLI_NUM);

            //Conta os meses em atraso
            $mes = date('m');
            $cont = false;

            for($i=0; $i < ($mes - 1); $i++){
                if($dados2_pagamento[$i] == 'A pagar'){
                    $cont = $cont + 1;
                }
            }

            $sql = " UPDATE pagamentos SET num_debitos = $cont WHERE id_membro = $id_membro ";
            if(mysqli_query($link, $sql)){
            }else{
                echo 'Erro ao tentar atualizar número de débitos do membro';
            }
        }
    }

    //Introduz no banco de dados os pagamentos feitos pelos clientes
    if($valor_pago != 0){
        $sqlReceita = "INSERT INTO receita (id_membro, data_pag, valor) VALUES ($id_membro, '$data_atual', $valor_pago)";
        if(mysqli_query($link, $sqlReceita)){
            header('Location: pagar.php?id='. $id_membro .'&warning=2');
        }else{
            echo "Erro ao inserir valor do pagamento no banco de dados!";
        }
    }

    mysqli_close($link);
?>