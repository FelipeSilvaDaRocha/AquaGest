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
    
    // Recupera o valor da receita total do ano
    $sqlReceita = "SELECT SUM(valor) AS receita_total FROM receita";
    $resultReceita = mysqli_query($link, $sqlReceita);
    $totalReceita = $resultReceita->fetch_assoc();

    // Recupera o valor da despesa total do ano
    $sqlDespesa = "SELECT SUM(valor) AS despesa_total FROM despesas";
    $resultDespesa = mysqli_query($link, $sqlDespesa);
    $totalDespesa = $resultDespesa->fetch_assoc();

    // Calcula saldo total anual
    $saldo = $totalReceita['receita_total'] - $totalDespesa['despesa_total'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Segunda Associação dos Moradores de Lagoa do Poço</title>
    <link rel="icon" type="image/x-icon" href="image/flaticonnovo.png">
    <link rel="stylesheet" href="style2.css?v=<?= filemtime('style2.css') ?>">
    <!-- <link rel="stylesheet" href="style2.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
        <a href="home.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
    </nav>
    <main>
        <h2>Balanço Financeiro</h2>
        
        <!--Indicadores de receita, despesa e lucro totais-->
        <div id="incicadores-container">
            <div class="indicador-box">
                <div class="icon-circle verde">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <div class="text-container">
                    <span class="label">RECEITAS</span>
                    <?php
                        echo "<span class='value verde-text'>R$ ".$totalReceita['receita_total']."</span>";
                    ?>
                </div>
            </div>
            <div class="indicador-box">
                <div class="icon-circle vermelho">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </div>
                <div class="text-container">
                    <span class="label">DESPESAS</span>
                    <?php
                        echo "<span class='value vermelho-text'>R$ ".$totalDespesa['despesa_total']."</span>";
                    ?>
                </div>
            </div>
            <div class="indicador-box">
                <div class="icon-circle azul">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="text-container">
                    <span class="label">SALDO ATUAL</span>
                    <?php
                        echo "<span class='value azul-text'>R$ ".$saldo."</span>"
                    ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        
        <!--Grágico de colunas receita vs despesas-->
        <div class="grafico-container">
            <div id="graficoMeses" style="width: 100%; height: 400px;"></div>
        </div>

        <!--Gráfico de barras (Charts) com valor gasto com cada tipode despesa-->
        <div class="grafico-container">
            <div id="graficoCategorias" style="width: 100%; height: 300px;"></div>
        </div>
        
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; <?php echo date("Y");?> Segunda Associação - Todos os direitos reservados.</p>
    </footer>

    <script type="text/javascript">
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(desenharGraficos);

        function desenharGraficos() {
            fetch('dados-grafico.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`Erro ao carregar dados: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Dados recebidos:', data);

                    // Gráfico de colunas - Receita vs Despesa
                    const tableMensal = new google.visualization.DataTable();
                    tableMensal.addColumn('string', 'Mês');
                    tableMensal.addColumn('number', 'Receita');
                    tableMensal.addColumn('number', 'Despesa');
                    data.mensal.forEach(mes => {
                        tableMensal.addRow([mes.mes, mes.receita, mes.despesa]);
                    });
                    const chart1 = new google.visualization.ColumnChart(document.getElementById('graficoMeses'));
                    chart1.draw(tableMensal, {
                        title: 'Receita vs Despesa',
                        colors: ['#0e68f0', '#F44336'],
                        height: 400,
                        legend: { position: 'top' },
                        //chartArea: { width: '80%' }
                    });

                    // Gráfico de pizza - Despesas por categoria
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
                })
                .catch(error => {
                    console.error('Erro ao carregar gráficos:', error);
                });
        }

        // Atualiza os gráficos automaticamente a cada 10 segundos
        setInterval(desenharGraficos, 10000);
    </script>
</body>
</html>