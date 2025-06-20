<?php
    session_start();

    if(!isset($_SESSION['registro_acesso'])){
        header('Location: index.php?erro=2');
    }
    
    $alerta_despesa = isset($_GET['warning']) ? $_GET['warning'] : 0;
    $id_membro_adm = $_SESSION['id_membro_adm'];
    $registro_acesso = $_SESSION['registro_acesso'];
    $funcao = $_SESSION['funcao'];
    
    require_once('bd.class.php');

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

    // Recupera as consultas das despesas feita no banco de dados
    

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
        <a href="membros.php"><i class="fa-solid fa-user-group"></i>Membros</a>
        <a href="balanco.php"><i class="fa-solid fa-chart-line"></i>Balanço</a>
        <a href="index.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>

        <?php
            if($alerta_despesa){
                echo '<div style="border: 2px solid #003366; border-radius: 4px; margin-top: 30px">
                        <i class="fa-solid fa-file-invoice-dollar" style="color: #003366; width: 40px; margin-top: 10px; margin-left: 46%"></i>
                        <h3 style="color: #003366; margin: auto; padding: 10px; text-align: center">Despesa inserida com sucesso!</h3>
                      </div>';
            }
        ?>

    </nav>
    <main>
        <!--Inserir despesas-->
        <section> 
            <h2>Controle de Despesas</h2>
            <div class="controleDespesa">
                <form id="formDespesa" method="post" action="processa-despesas.php">

                    <h3>Adicionar Despesa</h3>
                    <div id="blocoCateg">
                        <label for="categoria">Categoria:</label><br>
                        <input list="categoria" name="categoria" required>
                        <datalist id="categoria">
                            <option value="Custos Operacionais">
                            <option value="Energia Elétrica">
                            <option value="Manutenção de Infraestrutura">
                            <option value="Salários e Benefícios">
                            <option value="Materiais e Insumos">
                            <option value="Transporte e Logística">
                            <option value="Seguros">
                            <option value="Impostos e Taxas">
                            <option value="Locação de Equipamentos e Estruturas">
                            <option value="Marketing e Comunicação">
                            <option value="Consultorias e Auditorias">
                            <option value="TI e Sistemas">
                            <option value="Gestão Ambiental">
                            <option value="Dívidas e Financiamentos">
                            <option value="Pesquisa e Desenvolvimento">
                        </datalist>
                    </div>
                    <div id="blocoData">
                        <label for="data">Data:</label>
                        <input type="date" name="data" required>
                    </div>
                    <div class="clear"></div>

                    <label for="valor">Valor da Despesa:</label><br>
                    <input type="number" name="valor" placeholder="Valor em reais" required>
                    <br>
                    <label for="descricao">Descrição:</label><br>
                    <input type="text" name="descricao" required>
                    <br>
                    <input type="submit" value="Adicionar Despesa">
                </form>
            </div>
        </section>

        <!--Consultar despesas-->
        <section id="consultarDespesas">
            <h3>Consultar Despesas</h3>
            <form method="get" action="processa-despesas.php">
                <label for="meses">Selecione o mês desejado:</label>
                <br>
                <select id="meses" name="meses">
                    <option value="01">Janeiro</option>
                    <option value="02">Fevereiro</option>
                    <option value="03">Março</option>
                    <option value="04">Abril</option>
                    <option value="05">Maio</option>
                    <option value="06">Junho</option>
                    <option value="07">Julho</option>
                    <option value="08">Agosto</option>
                    <option value="09">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>
                <input type="submit" value="Consultar">
            </form>
        
            <!--Exibe as despesas do período selecionado-->
            <!--Caixa externa para delimitar o espaço onde as informações serão exibidas-->
            <div id="exibeDespesas"> 
                <!--Contém as informações de cada despesa/ Usar PHP para iterar as despesas do banco de dados-->
                <div>
                    <?php
                        if (isset($_SESSION['despesas'])) {
                            foreach ($_SESSION['despesas'] as $despesa) {
                                echo "Categoria: " . $despesa['categoria'] . "<br>";
                                echo "Data: " . $despesa['data_registro'] . "<br>";
                                echo "Valor: R$" . $despesa['valor'] . "<br>";
                                echo "Descrição: " . $despesa['descricao'] . "<hr>";
                            }
                            unset($_SESSION['despesas']); // Limpa os dados após o uso
                        } else {
                            echo "Nenhuma despesa encontrada.";
                        }
                    ?> 
                </div>
            </div>
        </section>
    </main>
    <div class="clear"></div>
    
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>

    <script>
        // Variáveis para armazenar o total das despesas
        let despesas = [];
        const mesesTrimestre = {
            1: ['Janeiro', 'Fevereiro', 'Março'],
            2: ['Abril', 'Maio', 'Junho'],
            3: ['Julho', 'Agosto', 'Setembro'],
            4: ['Outubro', 'Novembro', 'Dezembro']
        };
        const mesesSemestre = {
            1: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
            2: ['Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
        };

        // Referência ao formulário e à tabela
        const formDespesa = document.getElementById('form-despesa');
        const totalTrimestreElement = document.getElementById('total-trimestre');
        const totalSemestreElement = document.getElementById('total-semestre');
        const selectTrimestre = document.getElementById('trimestre');
        const selectSemestre = document.getElementById('semestre');

        // Função para calcular o total por trimestre ou semestre
        function calcularTotalPorPeriodo(mesesPeriodo) {
            return despesas
                .filter(despesa => mesesPeriodo.includes(despesa.mes))
                .reduce((acc, despesa) => acc + despesa.valor, 0);
        }

        // Função para atualizar os totais conforme a seleção
        function atualizarTotais() {
            const trimestreSelecionado = parseInt(selectTrimestre.value);
            const semestreSelecionado = parseInt(selectSemestre.value);

            const totalTrimestre = calcularTotalPorPeriodo(mesesTrimestre[trimestreSelecionado]);
            const totalSemestre = calcularTotalPorPeriodo(mesesSemestre[semestreSelecionado]);

            totalTrimestreElement.textContent = totalTrimestre.toFixed(2);
            totalSemestreElement.textContent = totalSemestre.toFixed(2);
        }

        // Função para adicionar uma nova despesa
        formDespesa.addEventListener('submit', function (e) {
            e.preventDefault();
            const mes = document.getElementById('mes').value;
            const valor = parseFloat(document.getElementById('valor').value);

            // Adiciona a despesa ao array de despesas
            despesas.push({ mes, valor });

            // Atualiza os totais com base na seleção atual
            atualizarTotais();

            // Limpar o formulário
            formDespesa.reset();
        });

        // Atualizar os totais quando a seleção de trimestre ou semestre mudar
        selectTrimestre.addEventListener('change', atualizarTotais);
        selectSemestre.addEventListener('change', atualizarTotais);
    </script>

</body>
</html>