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
    </nav>
    <main>
        <!--Inserção de despesas-->
        <section> 
            <h2>Controle de Despesas</h2>
            <div class="controleDespesa">
                <form id="formDespesa">
                    <h3>Adicionar Despesa</h3>
                        
                    <div id="blocoCateg">
                        <label for="categoria">Categoria:</label><br>
                        <input list="categoria" name="categoria" id="" required>
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
                        <input type="date" id="data" required>
                    </div>
                    <div class="clear"></div>

                    <label for="valor">Valor da Despesa:</label><br>
                    <input type="number" id="valor" placeholder="Valor em reais" required>
                    <br>
                    <label for="descricao">Descrição:</label><br>
                    <input type="text" id="descricao" required>
                    <br>
                    <input type="submit" value="Adicionar Despesa">
                </form>
            </div>
        </section>

        <!--Filtros para consultas-->
        <section id="consultarDespesas">
            <h3>Consultar Despesas</h3>
            <form>
                <label for="meses">Selecione o mês desejado:</label>
                <br>
                <select id="meses" name="meses">
                    <option value="Janeiro">Janeiro</option>
                    <option value="Fevereiro">Fevereiro</option>
                    <option value="Março">Março</option>
                    <option value="Abril">Abril</option>
                    <option value="Maio">Maio</option>
                    <option value="Junho">Junho</option>
                    <option value="Julho">Julho</option>
                    <option value="Agosto">Agosto</option>
                    <option value="Setembro">Setembro</option>
                    <option value="Outubro">Outubro</option>
                    <option value="Novembro">Novembro</option>
                    <option value="Dezembro">Dezembro</option>
                </select>
                <input type="submit" value="Consultar">
            </form>
        
        <!--Exibe as despesas do período selecionado-->
            <label>Despesas do mês TAL</label>
            <!--Caixa externa para delimitar o espaço onde as informações serão exibidas-->
            <div> 
                <!--Contém as informações de cada despesa/ Usar PHP para iterar as despesas do banco de dados-->
                <div id="exibeDespesas"> 
                    <h4>Categoria</h4>
                    <h4>Data</h4>
                    <h4>Valor</h4>
                    <p>Descrição</p>
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