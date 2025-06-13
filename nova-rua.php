<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    require_once('bd.class.php');

    $erro_nova_rua = isset($_GET['erro-rua']) ? $_GET['erro-rua'] : 0;
    $alerta = isset($_GET['warning']) ? $_GET['warning'] : 0;

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

    //Recupera os nomes das ruas para exibir na tabela
    $sqlr = " SELECT nome_rua FROM ruas_cadastradas ORDER BY id_rua ASC ";
    $resultado2 = mysqli_query($link, $sqlr);
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
        <a href="membros.php"><i class="fa-solid fa-user-group"></i>Membros</a>
        <!--<a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>-->
        <a href="home.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
        <?php
            if($alerta){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Rua cadastrada com sucesso!</h3>
                      </div>';
            }
        ?>
    </nav>
    <main>
        <section id="cadastroRua">
            <div class="tituloMain">
                <h2>Cadastrar Nova Rua</h2>
            </div>
            <form method="get" action="registra-rua.php" >
                <label>Insira o nome:</label>
                <br>
                <input type="text" name="novaRua" required>
                <br>
                <input type="submit" name="inserirRua">
                <?php
                    if($erro_nova_rua == 1){
                        echo '<font style="color: #F44336">Rua já cadastrada</font>';
                    }
                ?>
                <div class="clear"></div>
            </form>
        </section>

        <section>
            <div class="tituloMain">
                <h2>Ruas Cadastradas</h2>
            </div>
            <table class="nomeRuas">
                <?php
                    while($dados_rua = mysqli_fetch_assoc($resultado2)){
                        echo '<tr>';
                        echo '<td><a href="">'.$dados_rua['nome_rua'].'</a></td>';
                        echo '<td class="renomear"><a href=""><i class="fa-solid fa-pen-to-square"></i>Renomear</a></td>';
                    }
                ?>
            </table>
        </section>
            
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>