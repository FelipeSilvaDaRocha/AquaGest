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
    
    // Consulta membros com seus débitos e nome da rua
    $sql = "
        SELECT 
            pagamentos.id_pag,
            membros.nome,
            pagamentos.num_debitos,
            ruas_cadastradas.nome_rua
        FROM pagamentos
        LEFT JOIN membros ON pagamentos.id_membro = membros.id_membro
        LEFT JOIN ruas_cadastradas ON membros.id_rua = ruas_cadastradas.id_rua
    ";

    $resultado_debitos = mysqli_query($link, $sql);

    $atraso = [];
    $corte = [];

    if ($resultado_debitos) {
        while ($linha = mysqli_fetch_assoc($resultado_debitos)) {
            $info = [
                'id_pag' => $linha['id_pag'],
                'nome'   => $linha['nome'],
                'rua'    => $linha['nome_rua']
            ];
            $debitos = (int)$linha['num_debitos'];

            if ($debitos === 2) {
                $atraso[] = $info;
            } elseif ($debitos > 2) {
                $corte[] = $info;
            }
        }
    } else {
        echo 'Erro ao consultar dados de membros e ruas.';
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
    <link rel="stylesheet" href="style2.css?v=<?= filemtime('style2.css') ?>">
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
            <table class="nomeRuas tabelasPrincipais">
                <?php while ($nome_ruas = mysqli_fetch_assoc($resultado2)): ?>
                    <tr>
                        <td><?= htmlspecialchars($nome_ruas['nome_rua']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
        <section>
            <h2>Membros com Débitos</h2>
            <!--Tabela listando os membros em atraso-->
            <div id="areaAtraso">
                <h3>Atraso</h3>
                <table id="tabelaAtraso" class="display tabelaAlerta">
                    <thead>
                        <tr><th>Nome</th><th>Rua</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atraso as $membro): ?>
                        <tr>
                            <td>
                                <a href="pagar.php?id=<?= urlencode($membro['id_pag']) ?>">
                                    <?= htmlspecialchars($membro['nome']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($membro['rua']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!--Tabela com membros na lista de corte-->
            <div id="areaCorte">
                <h3>Corte</h3>
                <table id="tabelaCorte" class="display tabelaAlerta">
                    <thead>
                        <tr><th>Nome</th><th>Rua</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($corte as $membro): ?>
                        <tr>
                            <td>
                                <a href="pagar.php?id=<?= urlencode($membro['id_pag']) ?>">
                                    <?= htmlspecialchars($membro['nome']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($membro['rua']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; <?php echo date("Y");?> Segunda Associação - Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaAtraso').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                paging: false,        // Remove a paginação
                info: false,          // Remove a contagem de registros ("Mostrando de 1 até...")
                lengthChange: false,  // Remove o seletor "Mostrar X registros"
                ordering: true,       // Mantém a ordenação de colunas, se quiser
                searching: true       // Mantém a busca                
            });
            $('#tabelaCorte').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                paging: false,        
                info: false,          
                lengthChange: false,  
                ordering: true,       
                searching: true       

            });
        });
    </script>

</body>
</html>