<?php
header('Content-Type: application/json');
require_once '../Model/Endereco.php';

$method = $_SERVER['REQUEST_METHOD'];

// Limpa o CEP recebido (aceita com ou sem máscara)
$cep = isset($_GET['cep']) ? preg_replace('/\D/', '', $_GET['cep']) : null;

// ─────────────────────────────────────────────────────────────────────────────
// GET  /CepAPI.php?cep=XXXXXXXX  → busca um CEP no banco de dados local
// GET  /CepAPI.php               → lista todos os CEPs salvos
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET' && !empty($cep)) {

    $resultado = Endereco::buscar($cep);

    if ($resultado) {
        echo json_encode($resultado);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado']);
    }

} elseif ($method === 'GET') {

    $resultado = Endereco::listar();

    if (!empty($resultado)) {
        echo json_encode($resultado);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'Nenhum endereço cadastrado']);
    }

// ─────────────────────────────────────────────────────────────────────────────
// POST  /CepAPI.php
// Body JSON: { "cep": "01310100" }
// Consulta o ViaCEP e persiste o endereço no banco
// ─────────────────────────────────────────────────────────────────────────────
} elseif ($method === 'POST') {

    $body    = json_decode(file_get_contents('php://input'), true);
    $cepPost = isset($body['cep']) ? preg_replace('/\D/', '', $body['cep']) : $cep;

    if (empty($cepPost) || strlen($cepPost) !== 8) {
        http_response_code(400);
        echo json_encode(['erro' => 'CEP inválido ou não informado']);
        exit;
    }

    // Verifica se já existe no banco para não duplicar
    if (Endereco::buscar($cepPost)) {
        http_response_code(409);
        echo json_encode(['erro' => 'CEP já cadastrado']);
        exit;
    }

    // O construtor já busca no ViaCEP e insere no banco
    $model = new Endereco($cepPost);
    $json  = json_decode((string) $model, true);

    if (!empty($json['logradouro']) || !empty($json['localidade'])) {
        http_response_code(201);
        echo json_encode([
            'status'  => 'ok',
            'mensagem' => 'Endereço cadastrado com sucesso',
            'dados'   => $json,
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado no ViaCEP']);
    }

    // ─────────────────────────────────────────────────────────────────────────────
// PUT  /CepAPI.php?cep=XXXXXXXX
// Body JSON: { "logradouro": "...", "bairro": "...", "cidade": "...", "uf": "..." }
// ─────────────────────────────────────────────────────────────────────────────
} elseif ($method === 'PUT') {

    if (empty($cep)) {
        http_response_code(400);
        echo json_encode(['erro' => 'CEP não informado na URL']);
        exit;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    // Verifica se os campos obrigatórios vieram no body
    $campos = ['logradouro', 'bairro', 'cidade', 'uf'];
    foreach ($campos as $campo) {
        if (!isset($body[$campo]) || trim($body[$campo]) === '') {
            http_response_code(400);
            echo json_encode(['erro' => "Campo obrigatório ausente: {$campo}"]);
            exit;
        }
    }

    // Verifica se o CEP existe antes de tentar atualizar
    if (!Endereco::buscar($cep)) {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado no banco']);
        exit;
    }

    $ok = Endereco::atualizar($cep, $body);

    if ($ok) {
        $atualizado = Endereco::buscar($cep);
        echo json_encode([
            'status'   => 'ok',
            'mensagem' => 'Endereço atualizado com sucesso',
            'dados'    => $atualizado,
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao atualizar no banco de dados']);
    }
// ─────────────────────────────────────────────────────────────────────────────
// DELETE  /CepAPI.php?cep=XXXXXXXX  → remove um CEP do banco
// ─────────────────────────────────────────────────────────────────────────────
} elseif ($method === 'DELETE') {

    if (empty($cep)) {
        http_response_code(400);
        echo json_encode(['erro' => 'CEP não informado']);
        exit;
    }

    $ok = Endereco::deletar($cep);

    if ($ok) {
        echo json_encode([
            'status'  => 'ok',
            'mensagem' => "CEP {$cep} removido com sucesso",
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['erro' => 'CEP não encontrado']);
    }

} else {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
}
