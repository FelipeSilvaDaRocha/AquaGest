<?php
    session_start();
    
    $erro = isset($_GET['erro']) ? $_GET['erro']: 0;

    unset($_SESSION['id_membro_adm']);
    unset($_SESSION['funcao']);
    unset($_SESSION['registro_acesso']);
    unset($_SESSION['senha']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segunda Associação dos Moradores de Lagoa do Poço</title>
    <link rel="icon" type="image/x-icon" href="image/flaticon.ico">
    <link rel="stylesheet" href="style.css">
</head>
<!--Janela de login do sistema -->
<body>
    <div id="principal">
        <div id="secaoLogo">
            <div id="logo">
                <img src="image/logo.png" width="140">
            </div>
            <br><br>
            <h2 class="textoLogo">II</h2>
            <h2 class="textoLogo">Associação dos Moradores de Lagoa do Poço</h2>
            <div id="logocidade">
                <img src="image/logocidade.png" width="170">
            </div>
        </div>
        <form method="post" action="validar_acesso.php" id="secaoFormulario">
            <h2 id="tituloForm">INFORME SEU RA E SENHA</h2>
            <input type="number" name="ra" placeholder="Registro de Acesso" minlength="7" required>
            <br>
            <input type="password" name="senha" placeholder="Senha" maxlength="20" required>
            <a id="textoSenha" href="https://www.google.com/" target="_blank">Esqueci minha senha</a>
            <footer>
                <input type="submit" value="Entrar" name="submit">
            </footer>

            <?php
                if($erro == 1){
                    echo 'Registro de acesso ou senha inválido(s)';
                }
                if($erro == 2){
                    echo 'Entre com Registro de acesso e senha';
                }
            ?>
        </form>
    </div>
    <div class="clear"></div>
    <footer id="rodape">Todos os direitos reservados</footer>
</body>
</html> 