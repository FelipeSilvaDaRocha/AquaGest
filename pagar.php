<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    require_once('bd.class.php');

    $alerta_mes_alterado = isset($_GET['warning']) ? $_GET['warning'] : 0;

    $id_membro_adm = $_SESSION['id_membro_adm'];
    $registro_acesso = $_SESSION['registro_acesso'];
    $funcao = $_SESSION['funcao'];

    $id_membro = $_GET['id'];
    $_SESSION['id_membro'] = $_GET['id'];
    
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

    //Listar dados da tabela
    $sql = " SELECT * FROM pagamentos WHERE id_membro = '$id_membro' ";
    if($resultadoPag = mysqli_query($link, $sql)){
        $dados_pagamento = mysqli_fetch_array($resultadoPag);

        $janeiro = $dados_pagamento['Janeiro'];
        $fevereiro = $dados_pagamento['Fevereiro'];
        $marco = $dados_pagamento['Marco'];
        $abril = $dados_pagamento['Abril'];
        $maio = $dados_pagamento['Maio'];
        $junho = $dados_pagamento['Junho'];
        $julho = $dados_pagamento['Julho'];
        $agosto = $dados_pagamento['Agosto'];
        $setembro = $dados_pagamento['Setembro'];
        $outubro = $dados_pagamento['Outubro'];
        $novembro = $dados_pagamento['Novembro'];
        $dezembro = $dados_pagamento['Dezembro'];

    }else{
        echo 'Erro ao tentar localizar dados de pagamento';
    } 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segunda Associação dos Moradores de Lagoa do Poço</title>
    <link rel="icon" type="image/x-icon" href="image/flaticon.ico">
    <link rel="stylesheet" href="style2.css?v=<?= filemtime('style2.css') ?>">
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <!--A imagem deve ser do usuário que acessa-->
        <div id="imagemusuario">
            <img src="image/logo2.png" width="100">
        </div>
        <div id="textocabecalho">
            <h3><?php echo $nome_adm ?></h3>
            <ul id="dadosUsuario">
                <li>RA: <?php echo $registro_acesso?></li>
                <li><?php echo $funcao?></li>
            </ul>
        </div>
        <div id="botaoConfiguracao">
            <a href="configuracao.php"><i class="fa-solid fa-gear"></i></a>
        </div>
        <div class="clear"></div>
    </header>
    <nav>
        <a href="nova-rua.php"><i class="fa-solid fa-plus"></i>Nova Rua</a>
        <a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>
        <a href="balanco.php"><i class="fa-solid fa-chart-line"></i>Balanço</a>
        <a href="membros.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
        <?php
            if($alerta_mes_alterado == 1){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-user-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Mês liquidado com sucesso!</h3>
                      </div>';
            }
            if($alerta_mes_alterado == 2){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-user-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Pagamento efetuado com sucesso!</h3>
                      </div>';
            }
        ?>
    </nav>

    <main>
        <section >
            <div>
                <h2>Apuração de Pagamentos</h2>
                <table id="tabelaPagamentos">
                    <tr>
                        <th>Período de Apuração</th>
                        <th>Situação</th>
                        <th>Data de Vencimento</th>
                    </tr>
                    <tr>
                        <td>Janeiro</td>
                        <?php 
                            if($janeiro === 'Liquidado'){
                                echo '<td style="color: green">'.$janeiro.'</td>'; 
                            }else{
                                echo '<td>'.$janeiro.'</td>'; 
                            }
                        
                        ?>
                        <td>30/01/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Fevereiro</td>
                        <?php 
                            if($fevereiro === 'Liquidado'){
                                echo '<td style="color: green">'.$fevereiro.'</td>'; 
                            }else{
                                echo '<td>'.$fevereiro.'</td>'; 
                            }
                        
                        ?>
                        <td>28/02/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Março</td>
                        <?php 
                            if($marco === 'Liquidado'){
                                echo '<td style="color: green">'.$marco.'</td>'; 
                            }else{
                                echo '<td>'.$marco.'</td>'; 
                            }
                        
                        ?>
                        <td>30/03/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Abril</td>
                        <?php 
                            if($abril === 'Liquidado'){
                                echo '<td style="color: green">'.$abril.'</td>'; 
                            }else{
                                echo '<td>'.$abril.'</td>'; 
                            }
                        
                        ?>
                        <td>30/04/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Maio</td>
                        <?php 
                            if($maio === 'Liquidado'){
                                echo '<td style="color: green">'.$maio.'</td>'; 
                            }else{
                                echo '<td>'.$maio.'</td>'; 
                            }
                        
                        ?>
                        <td>30/05/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Junho</td>
                        <?php 
                            if($junho === 'Liquidado'){
                                echo '<td style="color: green">'.$junho.'</td>'; 
                            }else{
                                echo '<td>'.$junho.'</td>'; 
                            }
                        
                        ?>
                        <td>30/06/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Julho</td>
                        <?php 
                            if($julho === 'Liquidado'){
                                echo '<td style="color: green">'.$julho.'</td>'; 
                            }else{
                                echo '<td>'.$julho.'</td>'; 
                            }
                        
                        ?>
                        <td>30/07/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Agosto</td>
                        <?php 
                            if($agosto === 'Liquidado'){
                                echo '<td style="color: green">'.$agosto.'</td>'; 
                            }else{
                                echo '<td>'.$agosto.'</td>'; 
                            }
                        
                        ?>
                        <td>30/08/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Setembro</td>
                        <?php 
                            if($setembro === 'Liquidado'){
                                echo '<td style="color: green">'.$setembro.'</td>'; 
                            }else{
                                echo '<td>'.$setembro.'</td>'; 
                            }
                        
                        ?>
                        <td>30/09/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Outubro</td>
                        <?php 
                            if($outubro === 'Liquidado'){
                                echo '<td style="color: green">'.$outubro.'</td>'; 
                            }else{
                                echo '<td>'.$outubro.'</td>'; 
                            }
                        
                        ?>
                        <td>30/10/<?php echo date("Y"); ?> </td>
                    </tr>
                    <tr>
                        <td>Novembro</td>
                        <?php 
                            if($novembro === 'Liquidado'){
                                echo '<td style="color: green">'.$novembro.'</td>'; 
                            }else{
                                echo '<td>'.$novembro.'</td>'; 
                            }
                        
                        ?>
                        <td>30/11/<?php echo date("Y"); ?></td>
                    </tr>
                    <tr>
                        <td>Dezembro</td>
                        <?php 
                            if($dezembro === 'Liquidado'){
                                echo '<td style="color: green">'.$dezembro.'</td>'; 
                            }else{
                                echo '<td>'.$dezembro.'</td>'; 
                            }
                        
                        ?>
                        <td>30/12/<?php echo date("Y"); ?></td>
                    </tr>
                </table>
                <div id="confirmarPagamento">
                    <div id="valor-pag-box">
                        <form method="post" action="processa_pag.php">
                            <label>Informe o valor a ser pago:</label>
                            <input type="number" name="valorPagamento" require>
                            <input type="submit" value="Pagar">
                        </form>
                    </div>
                    <div id="mes-pag-box">
                        <form method="post" action="processa_pag.php">
                            <div>
                                <label>Informe o mês para o pagamento:</label>
                                <select name="mes">
                                    <option>Janeiro</option>
                                    <option>Fevereiro</option>
                                    <option>Marco</option>
                                    <option>Abril</option>
                                    <option>Maio</option>
                                    <option>Junho</option>
                                    <option>Julho</option>
                                    <option>Agosto</option>
                                    <option>Setembro</option>
                                    <option>Outubro</option>
                                    <option>Novembro</option>
                                    <option>Dezembro</option>
                                </select>
                            </div>
                            <div id="input-apurar-mes">
                                <input type="submit" value="Apurar mês">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>