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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
        <a href="membros.php"><i class="fa-solid fa-user-group"></i>Membros</a>
        <a href="despesas.php"><i class="fa-solid fa-file-invoice-dollar"></i>Despesas</a>
        <a href="home.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
    </nav>
    <main>
        <h2>Balanço Financeiro</h2>   
        
        <!--Grágico de colunas receita vs despesas-->
        <div class="estilo-graficos">
            <div id="graficoMeses" style="width: auto;"></div>
        </div>

        <!--Gráfico de barras (Charts) com valor gasto com cada tipode despesa-->
        <div>
            <div id="graficoCategorias"></div>
        </div>

        <!--Tabela mostrando o balanço de cada mês-->
        <div>
            <table>

            </table>
        </div>

        <!--Indicadores de receita, despesa e Lucro totais-->
        <div class="indicador-box">
            <div class="icon-circle verde">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div class="text-container">
                <span class="label">RECEITAS</span>
                <span class="value verde-text">R$ 5.505,50</span>
            </div>
        </div>
        <div class="indicador-box">
            <div class="icon-circle vermelho">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div class="text-container">
                <span class="label">DESPESAS</span>
                <span class="value vermelho-text">R$ 5.505,50</span>
            </div>
        </div>
        <div class="indicador-box">
            <div class="icon-circle azul">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <div class="text-container">
                <span class="label">SALDO ATUAL</span>
                <span class="value azul-text">R$ 5.505,50</span>
            </div>
        </div>
        
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>

    <script type="text/javascript">
        google.charts.load('current', { packages:['corechart'] });
        google.charts.setOnLoadCallback(desenharGraficos);

        function desenharGraficos() {
            fetch('dados-grafico.php') // Obtendo os dados do PHP
                .then(response => response.json())
                .then(data => {
                    // Gráfico 1: Colunas mês  a mês                    
                    const tableMensal = new google.visualization.DataTable();
                    tableMensal.addColumn('string', 'Mês');
                    tableMensal.addColumn('number', 'Receita');
                    tableMensal.addColumn('number', 'Despesa');
                    data.mensal.forEach(mes => {
                        tableMensal.addRow([`${mes.mes}`, mes.receita, mes.despesa]);
                    });
                    const chart1 = new google.visualization.ColumnChart(document.getElementById('graficoMeses'));
                    chart1.draw(tableMensal, {
                        title: 'Receita, Despesa',
                        colors: ['#0e68f0', '#F44336'],
                        height: 400
                    });

                    // Gráfico 2: Pizza por categoria
                    const tableCat = new google.visualization.DataTable();
                    tableCat.addColumn('string', 'Categoria');
                    tableCat.addColumn('number', 'Total Gasto');
                    data.categorias.forEach(cat => {
                        tableCat.addRow([cat.categoria, cat.total]);
                    });
                    const chart2 = new google.visualization.PieChart(document.getElementById('graficoCategorias'));
                    chart2.draw(tableCat, {
                        title: 'Distribuição de Despesas por Categoria',
                        pieHole: 0.4,
                        height: 300
                    });
                });
        }

        // Atualizar o gráfico a cada 10 segundos automaticamente
        setInterval(carregarDados, 10000);
    </script>
</body>
</html>