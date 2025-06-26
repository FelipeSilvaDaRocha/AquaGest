<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    $erro_membro = isset($_GET['erro-membro']) ? $_GET['erro-membro'] : 0;
    $erro_celular = isset($_GET['erro-celular']) ? $_GET['erro-celular'] : 0;
    $alerta_membro_cadastrado = isset($_GET['warning']) ? $_GET['warning'] : 0;

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

    //Recupera os nomes das ruas para exibir
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
        <a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>
        <a href="balanco.php"><i class="fa-solid fa-chart-line"></i>Balanço</a>
        <a href="membros.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
        <?php
            if($alerta_membro_cadastrado){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-user-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Membro cadastrado com sucesso!</h3>
                      </div>';
            }
        ?>
    </nav>
    <main>
        <h2>Cadastro de Novo Membro</h2>
        <div id="cadastroNovoMembro">
            <form action="registra_membro.php" method="post">
    
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <?php
                    if($erro_membro == 1){
                        echo '<font style="color: #F44336">Membro já existe</font>';
                    }
                ?>
    
                <label for="apelido">Apelido (opcional):</label>
                <input type="text" id="apelido" name="apelido">
    
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento">

                <label for="celular">Celular:</label>
                <input type="tel" id="celular" name="celular" required  placeholder="xx xxxxx-xxxx">
                <?php
                    if($erro_celular){
                        echo '<font style="color: #F44336">Celular já existe</font>';
                    }
                ?>
    
                <label>Nome da Rua:</label>
                <select id="selecionarRuaNovoMembro" name="rua" style="padding: 8px; margin: 8px 0; border-color: #ccc; width: 99.5%; border-radius: 4px">
                    <?php
                        while($dados_rua = mysqli_fetch_assoc($resultado2)){
                            echo '<option>'.$dados_rua['nome_rua'].'</option>';
                        }
                    ?>
                </select>
    
                <label for="numero">Número da Casa:</label>
                <input type="number" id="numero" name="numero" required>
    
                <label for="referencia">Ponto de Referência (opcional):</label>
                <input type="text" id="referencia" name="referencia">
    
                <button type="submit">Cadastrar</button>
            </form>
        </div>

    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>