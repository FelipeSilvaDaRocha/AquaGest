<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    $alerta = isset($_GET['warning']) ? $_GET['warning'] : 0;

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
        echo 'Erro ao tentar localizar membro administrador';
    }

    //Recupera os dados do membro do banco de dados
    if(!empty($_GET['id'])){
        $id_membro = $_GET['id'];

        $sql2 = " SELECT * FROM ruas_cadastradas LEFT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua WHERE id_membro = '$id_membro' ";
        
        if($resultado2 = mysqli_query($link, $sql2)){
            while($dados_membro2 = mysqli_fetch_assoc($resultado2)){
                $nome = $dados_membro2['nome'];
                $apelido = $dados_membro2['apelido'];
                $data_nascimento = $dados_membro2['data_nascimento'];
                $celular = $dados_membro2['celular'];
                $nome_rua = $dados_membro2['nome_rua'];
                $numero_casa = $dados_membro2['numero'];
                $referencia = $dados_membro2['referencia'];
                $status = $dados_membro2['status_m'];
            } 
        }else{
            echo 'Erro ao consultar dados do membro';
        }
    }

    //Recupera os nomes das ruas e exibe na tabela
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
        <a href="nova-rua.php"><i class="fa-solid fa-plus"></i>Nova Rua</a>
        <!--<a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>-->
        <a href="membros.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>

        <?php
            if($alerta){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-user-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Alteração concluída com sucesso!</h3>
                      </div>';
            }
        ?>
    </nav>
    <main>
        <h2>Informações de Membro</h2>
        <div id="cadastroNovoMembro">
            <form action="processa_cadastro.php" method="get">
                <!-- Campo de ID removido porque será gerado automaticamente -->
    
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $nome ?>" disabled>
    
                <label for="apelido">Apelido (opcional):</label>
                <input type="text" id="apelido" name="apelido" value="<?php echo $apelido ?>" disabled>
    
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $data_nascimento ?>" disabled>

                <label for="celular">celular:</label>
                <input type="tel" id="celular" name="celular" value="<?php echo $celular ?>" disabled>
        
                <label for="rua">Nome da Rua:</label>
                <input type="text" id="rua" name="rua" value="<?php echo $nome_rua ?>" disabled>
    
                <label for="numero">Número da Casa:</label>
                <input type="number" id="numero" name="numero" value="<?php echo $numero_casa ?>" disabled>
    
                <label for="referencia">Ponto de Referência (opcional):</label>
                <input type="text" id="referencia" name="referencia" value="<?php echo $referencia ?>" disabled>

                <label for="status">Status:</label>
                <?php
                    if($status == 'Ativo'){
                        echo "<input type='text' name='status' value='$status' disabled>";
                    }else{
                        echo "<input type='text' name='status' value='$status' style='border-color: #F44336' disabled>";
                    }         
                ?>
            </form>
        </div>

    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>