<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }

    $alerta_alteracao = isset($_GET['warning']) ? $_GET['warning'] : 0;

    require_once('bd.class.php');

    $id_membro_adm = $_SESSION['id_membro_adm'];
    $registro_acesso = $_SESSION['registro_acesso'];
    $funcao = $_SESSION['funcao'];
    $erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
    
    $objDb = new db();
    $link = $objDb->conecta_mysql();

    //Recupera o nome do membro adm
    $sql = " SELECT * FROM ruas_cadastradas LEFT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua WHERE id_membro = '$id_membro_adm' ";

    if($resultado = mysqli_query($link, $sql)){
        while($dados_membro = mysqli_fetch_assoc($resultado)){
            $nome = $dados_membro['nome'];
            $data_nascimento = $dados_membro['data_nascimento'];
            $celular = $dados_membro['celular'];
            $nome_rua = $dados_membro['nome_rua'];
            $numero_casa = $dados_membro['numero'];
            $referencia = $dados_membro['referencia'];
        }
    }else{
        echo 'Erro ao tentar localizar membro';
    }

    $sql = " SELECT * FROM dados_login_adm WHERE id_membro = '$id_membro_adm' ";
    if($resultado = mysqli_query($link, $sql)){
        while($dados_membro = mysqli_fetch_assoc($resultado)){
            $email = $dados_membro['email'];
            $senha = $dados_membro['senha'];
        }
    }else{
        echo 'Erro ao tentar localizar dados de administrador';
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
        <!--A imagem deve ser do usuário que acessa-->
        <div id="imagemusuario">
            <img src="image/logo2.png" width="100">
        </div>
        <div id="textocabecalho">
            <h3><?php echo $nome ?></h3>
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
        <a href="membros.php"><i class="fa-solid fa-user-group"></i>Membros</a>
        <a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>
        <a href="balanco.php"><i class="fa-solid fa-chart-line"></i>Balanço</a>
        <a href="home.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
        <?php
            if($alerta_alteracao){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-user-check" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Alteração concluída com sucesso!</h3>
                      </div>';
            }
        ?>
    </nav>
    <main>
        <h2>Configurações</h2>
        <div class="container">
            <div class="section" style="margin-top: 0;">
                <h3 style="margin-top: 0;">Perfil</h3>
                
                <form method="post" action="processa_perfil_adm.php">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" value="<?php echo $nome?>" disabled>

                    <label for="name">Função:</label>
                    <input type="text" id="funcaoadm" name="funcaoadm" value="<?php echo $funcao?>">

                    <button type="update">Atualizar função</button>
                </form>
            </div>
        
            <div class="section">
                <form method="post" action="processa_perfil_adm.php">
                    <h3 class="indice">Atualizar Senha</h3>
                    <input type="password" name="senha" placeholder="Digite a nova senha">
                    <input type="password" name="senhaConfirmacao" placeholder="Digite novamente a senha">
                    <input type="hidden" name="id" value="<?php echo $id_membro_adm ?>">
                    <?php
                        if($erro){
                            echo '<h5 style="color: #F44336; margin: auto">Senhas não correspondem</h5>';
                        }
                    ?>
                    <button type="update">Atualizar Senha</button>
                </form>
            </div>
        
            <div class="section">
                <form method="post" action="processa_perfil_adm.php">
                    <h3 class="indice">Telefones e E-mails</h3>
                    
                    <label for="email">Email pessoal:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email?>">
                    
                    <label for="celular">Celular Pessoal:</label>
                    <input type="text" id="celular" name="celular" value="<?php echo $celular?>">
                    <input type="hidden" name="id" value="<?php echo $id_membro_adm ?>">
                    <button type="update">Atualizar Telefones e E-mails</button>
                </form>
            </div>
        
            <div class="section">
                <form method="post" action="processa_perfil_adm.php">
                    <h3 class="indice">Endereço</h3>

                    <label for="rua">Nome da rua:</label>
                    <input type="text" name="rua" value="<?php echo $nome_rua?>">
                    <label for="numero">Número:</label>
                    <input type="number" name="numero" value="<?php echo $numero_casa?>">
                    <label for="referencia">Ponto de referência:</label>
                    <input type="text" name="referencia" value="<?php echo $referencia?>">
                    <input type="hidden" name="id" value="<?php echo $id_membro_adm ?>">
                
                    <button type="update">Atualizar Endereço</button>
                </form>
            </div>
        </div>
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
</html>