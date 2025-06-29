<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "banco_dados_associacao");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Função para converter "YYYY-MM" em nome do mês
function nomeMes($data) {
    $meses = [
        '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
        '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
        '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
    ];
    $partes = explode('-', $data);
    return $meses[$partes[1]]; //. ' ' . $partes[0]; // Exemplo: "Janeiro 2025"
}

// Consulta para obter receita total por mês
$sqlReceita = "SELECT DATE_FORMAT(data_pag, '%Y-%m') AS mes, SUM(valor) AS receita_total FROM receita GROUP BY mes";
$resultReceita = $conn->query($sqlReceita);

$mensal = [];
while ($row = $resultReceita->fetch_assoc()) {
    $mensal[$row['mes']] = [
        'mes' => nomeMes($row['mes']), 
        'receita' => (float)$row['receita_total'], 
        'despesa' => 0
    ];
}

// Consulta para obter despesa total por mês
$sqlDespesas = "SELECT DATE_FORMAT(data_registro, '%Y-%m') AS mes, SUM(valor) AS despesa_total FROM despesas GROUP BY mes";
$resultDespesas = $conn->query($sqlDespesas);

while ($row = $resultDespesas->fetch_assoc()) {
    $mes = $row['mes'];
    $valor = (float)$row['despesa_total'];
    if (isset($mensal[$mes])) {
        $mensal[$mes]['despesa'] = $valor;
    } else {
        $mensal[$mes] = [
            'mes' => nomeMes($mes),
            'receita' => 0,
            'despesa' => $valor
        ];
    }
}

// Consulta 2: Despesas por categoria
$sqlCategorias = "SELECT categoria, SUM(valor) AS total FROM despesas GROUP BY categoria";
$resultCategorias = $conn->query($sqlCategorias);

$por_categoria = [];
while ($row = $resultCategorias->fetch_assoc()) {
    $por_categoria[] = [
        'categoria' => $row['categoria'],
        'total' => (float)$row['total']
    ];
}

// Saída combinada
echo json_encode([
    'mensal' => array_values($mensal),
    'categorias' => $por_categoria
], JSON_UNESCAPED_UNICODE);

$conn->close();
?>