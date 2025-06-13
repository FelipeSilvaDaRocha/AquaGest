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
        $dados_membro_adm = mysqli_fetch_array($resultado);

        $nome_adm = $dados_membro_adm['nome'];
    }else{
        echo 'Erro ao tentar localizar membro';
    }

    //Recupera os nomes das ruas para exibir
    $sqlr = " SELECT nome_rua FROM ruas_cadastradas ORDER BY id_rua ASC ";
    $resultado_ruas = mysqli_query($link, $sqlr);

    //Aplica a pesquisa
    if(!empty($_GET['search'])){
        $key_pesquisa = $_GET['search'];
        
        $sql2 = " SELECT * FROM membros WHERE id_membro LIKE '%$key_pesquisa%' OR nome LIKE '%$key_pesquisa%' OR apelido LIKE '%$key_pesquisa%' OR referencia LIKE '%$key_pesquisa%' ORDER BY nome ASC ";
    
        $resultado2 = mysqli_query($link, $sql2);
    }else{
        /*Recupera o nome, id e status do membro
        $sql2 = " SELECT * FROM membros ";*/

        //Aplica filtros
        $name_rua = isset($_GET['nome_rua']) ? $_GET['nome_rua'] : 'Todas';
        $tipo_membro = isset($_GET['tipo_membro']) ? $_GET['tipo_membro'] : 'Ativos';

        if($tipo_membro == 'Ativos'){

            //Verifica qual rua foi selecionada
            if($name_rua == 'Todas'){
                $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua  WHERE status_m = 'Ativo' ";
            }else{
                $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua WHERE status_m = 'Ativo' AND nome_rua = '$name_rua'";
            }
        }else{
            if($tipo_membro == 'Inativos'){
            
                //Verifica qual rua foi selecionada
                if($name_rua == 'Todas'){
                    $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua  WHERE status_m = 'Inativo' ";
                }else{
                    $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua WHERE status_m = 'Inativo' AND nome_rua = '$name_rua'";
                }
        
            }else{
                //Verifica qual rua foi selecionada
                if($name_rua == 'Todas'){
                    $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua ";
                }else{
                    $sql = "SELECT * FROM ruas_cadastradas RIGHT JOIN membros ON ruas_cadastradas.id_rua = membros.id_rua WHERE nome_rua = '$name_rua'";
                }
            }
        }
        $resultado2 = mysqli_query($link, $sql);
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
        <a href="home.php"><i class="fa-solid fa-circle-arrow-left"></i>Voltar</a>
    </nav>
    <main>
        <section>
            <div class="tituloMain">
                <h2>Gerenciamento de Membros</h2>
            </div>
            <div id="filtros">
                <div id="selecionarFiltro">
                    <h4>Filtros:</h4>
                    <form id="selecionarTipoRua">
                        <label>Tipo de membro:</label>
                        <br>
                        <select name="tipo_membro">
                            <option selected>Ativos</option>
                            <option>Inativos</option>
                            <option>Todos</option>
                        </select>
                        <br>
                    <!-- class="selecionarRua" -->
                        <label>Selecione a rua:</label>
                        <br>
                        <select name="nome_rua">
                            <?php
                                echo '<option selected>Todas</option>';
                                while($dados_rua = mysqli_fetch_assoc($resultado_ruas)){
                                    echo '<option>'.$dados_rua['nome_rua'].'</option>';
                                }
                            ?>
                        </select>
                        <input type="submit" value="Filtrar" style="background-color: #2E5DA3; color: #fff; border-color: #ccc; border-radius: 4px; padding: 2px 4px">
                    </form>
                </div>
                <div id="inserirMembro">
                    <a href="novo-membro.php"><i class="fa-solid fa-plus"></i>Novo</a>
                </div>
                <!--<div class="clear"></div>-->
                <div >
                    <form id="pesquisar">
                        <input type="search" id="pesquisa" name="search" placeholder="Digite aqui para fazer uma busca">
                        <input type="submit" onclick="searchData()" value="Pesquisar" >
                    </form>
                </div>
                <div class="clear"></div>
            </div>
            <form>
                <table id="tableMembros">
                    <tr>
                        <!--<th></th>-->
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Apelido</th>
                        <th colspan="6"> Ações</th>
                    </tr>
                    <?php
                        //Exibe uma tabela com os nomes dos membros e fucionalidades do sistema
                        while($dados2_membro = mysqli_fetch_assoc($resultado2)){
                            $id = $dados2_membro['id_membro'];
                            $status = $dados2_membro['status_m'];

                            echo '<tr>';
                            //echo '<td class="selecionar"><input type="checkbox" nome="selecionaMembro"></td>';
                            
                            //Verifica se o membro está inativo e muda a cor da fonte para vermelho
                            if($status == 'Inativo'){
                                echo "<td style='color: #F44336'>".$dados2_membro['id_membro']."</td>";
                                echo "<td class='nomeMembro' style='color: #F44336'>".$dados2_membro['nome']."</td>";
                                echo "<td class='nomeMembro' style='color: #F44336'>".$dados2_membro['apelido']."</td>";
                            }else{
                                echo '<td>'.$dados2_membro['id_membro'].'</td>';
                                echo '<td class="nomeMembro">'.$dados2_membro['nome'].'</td>';
                                echo '<td class="nomeMembro">'.$dados2_membro['apelido'].'</td>';
                            }
                            
                            echo "<td class='ver'><a href='ver.php?id=$id'><i class='fa-solid fa-magnifying-glass'></i>Ver</a></td>";
                            echo "<td class='alterar'>
                                    <a href='alterar.php?id=$id'>
                                        <i class='fa-solid fa-pen-to-square'></i>Alterar
                                    </a>
                                </td>";
                            echo "<td class='pagar'><a href='pagar.php?id=$id'><i class='fa-solid fa-dollar-sign'></i>Pagar</a></td>";
                            //echo '<td class="ativar"><a href=""><i class="fa-solid fa-check"></i>'.$botao_status.'</a></td>';
                            //echo '<td class="excluir"><a href=""><i class="fa-solid fa-xmark"></i>Excluir</a></td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </form>
        </section>
    </main>
    <div class="clear"></div>
    <footer>
        <p>&copy; 2024 Segunda Associação - Todos os direitos reservados.</p>
    </footer>
</body>
<script >
    var search = document.getElementById('pesquisa');

    search.addEventListener("keydown", function(event){
        if(event.key === "Enter"){
            searchData();
        }
    });

    function searchData(){
        window.location = 'membros.php?search='+search.nodeValue;
    }
</script>
</html>