<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    require_once('bd.class.php');

    $id_membro_adm = $_SESSION['id_membro_adm'];
    $registro_acesso = $_SESSION['registro_acesso'];
    $funcao = $_SESSION['funcao'];
    
    $objDb = new db();
    $link = $objDb->conecta_mysql();

    //Recupera o nome do membro adm
    $sql = " SELECT nome FROM membros WHERE id_membro = '$id_membro_adm' ";

    if($resultado = mysqli_query($link, $sql)){
        $dados_membro = mysqli_fetch_array($resultado);
        
        $nome_adm = $dados_membro['nome'];
    }else{
        echo 'Erro ao tentar localizar membro';
    }
    
    //Recupera os nomes das ruas e exibe na tabela
    $sqlr = " SELECT nome_rua FROM ruas_cadastradas ORDER BY id_rua ASC ";
    $resultado2 = mysqli_query($link, $sqlr);
    
    //Acessa lista de débitos dos membros
    $sql = " SELECT nome, num_debitos FROM pagamentos LEFT JOIN membros ON pagamentos.id_membro = membros.id_membro ";

    if($resultado_deb = mysqli_query($link, $sql)){
        
        $iterador = 0;
        while($dados_membro_deb = mysqli_fetch_assoc($resultado_deb)){
                                
            if($dados_membro_deb['num_debitos'] == 2){
                $atraso[$iterador] = $dados_membro_deb['nome'];
            }
                                
            if($dados_membro_deb['num_debitos'] > 2){
                $corte[$iterador] = $dados_membro_deb['nome'];
            }
            $iterador++;
        }
    }else{
        echo 'Erro ao tentar informações de nome e débitos de membros';
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segunda Associação dos Moradores de Lagoa do Poço</title>
    <link rel="icon" type="image/x-icon" href="image/flaticon.ico">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <!--A imagem deve ser a logo da Associação-->
        <div id="imagemusuario">
            <a href="home.php" title="II Associação Comunitária"><img src="image/logowhitecs.png" width="120"></a>
        </div>
        <div id="textocabecalho">
            <h1>II Associação Comunitária</h1>
            <ul id="dadosUsuario">
                <li>Usuário: <?php echo $nome_adm ?></li>
                <li>Função: <?php echo $funcao?></li>
                <li>RA: <?php echo $registro_acesso?></li>
            </ul>
        </div>
        <div id="botaoConfiguracao">
            <a href="configuracao.php" title="Configurações"><i class="fa-solid fa-gear"></i></a>
        </div>
        <div class="clear"></div>
    </header>
    <nav>
        <a href="nova-rua.php"><i class="fa-solid fa-plus"></i>Nova Rua</a>
        <a href="membros.php"><i class="fa-solid fa-user-group"></i>Membros</a>
        <a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>
        <a href="balanco.php"><i class="fa-solid fa-chart-line"></i>Balanço</a>
        <a href="index.php"><i class="fa-solid fa-right-to-bracket"></i>Sair</a>
    </nav>
    <main>
        <section>
            <div class="tituloMain">
                <h2>Ruas Atendidas</h2>
            </div>
            <table class="nomeRuas">
                <?php
                    while($dados_rua = mysqli_fetch_assoc($resultado2)){
                        echo '<tr>';
                        echo '<td style="padding: 10px 15px">'.$dados_rua['nome_rua'].'</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
        </section>
        <div>
            <!--Membros com atrazo no pagamento-->
            <div id="atrasoPagamento" class="table-wrapper">
                <table>
                    <tr>
                        <th>Pagamento em Atraso</th>
                    </tr>
                    <?php
                        foreach($atraso as $nome_atr){
                            echo '<tr>';
                            echo '<td>'.$nome_atr.'</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>

            <!--Membros que devem ter sua água cortada-->
            <div id="corteAgua" class="table-wrapper">
                <table>
                    <tr>
                        <th>Corte de Ligação</th>
                    </tr>
                    <?php
                        foreach($corte as $nome_cort){
                            echo '<tr>';
                            echo '<td>'.$nome_cort.'</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>