<?php
require_once 'Conexao.php';

class Endereco {
    private $cep;
    private $logradouro;
    private $bairro;
    private $cidade;
    private $uf;
    private $pais;

    // ─── Construtor: busca no ViaCEP e salva no banco ──────────────────────────
    public function __construct($cep) {
        $this->cep = preg_replace('/[^0-9]/', '', $cep);

        $jsonBruto = $this->buscarViaCep();
        $decode    = json_decode($jsonBruto, true);

        if ($decode && !isset($decode['erro'])) {
            $this->logradouro = $decode['logradouro'] ?? '';
            $this->bairro     = $decode['bairro']     ?? '';
            $this->cidade     = $decode['localidade'] ?? '';
            $this->uf         = $decode['uf']         ?? '';
            $this->pais       = 'Brasil';

            $this->insert();
        }
    }

    // ─── Consulta a API pública ViaCEP ────────────────────────────────────────
    public function buscarViaCep() {
        if (empty($this->cep))
            return json_encode(['erro' => 'CEP vazio']);

        $url = "https://viacep.com.br/ws/{$this->cep}/json/";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // ─── Insere no banco (chamado pelo construtor) ────────────────────────────
    private function insert() {
        try {
            $db  = (new Conexao())->conectar();
            $sql = "INSERT INTO endereco (cep, logradouro, bairro, cidade, uf, pais)
                    VALUES (:cep, :logradouro, :bairro, :cidade, :uf, :pais)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':cep'        => $this->cep,
                ':logradouro' => $this->logradouro,
                ':bairro'     => $this->bairro,
                ':cidade'     => $this->cidade,
                ':uf'         => $this->uf,
                ':pais'       => $this->pais,
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao inserir: " . $e->getMessage());
        }
    }

    // ─── Busca um CEP específico no banco ─────────────────────────────────────
    public static function buscar(string $cep): array|false {
        try {
            $db   = (new Conexao())->conectar();
            $stmt = $db->prepare("SELECT * FROM endereco WHERE REPLACE(cep, '-', '') = ?");
            $stmt->execute([$cep]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar: " . $e->getMessage());
            return false;
        }
    }

    // ─── Lista todos os endereços do banco ────────────────────────────────────
    public static function listar(): array {
        try {
            $db   = (new Conexao())->conectar();
            $stmt = $db->prepare("SELECT * FROM endereco ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao listar: " . $e->getMessage());
            return [];
        }
    }

    // ─── Remove um CEP do banco ───────────────────────────────────────────────
    public static function deletar(string $cep): bool {
        try {
            $db   = (new Conexao())->conectar();
            $stmt = $db->prepare("DELETE FROM endereco WHERE REPLACE(cep, '-', '') = ?");
            $stmt->execute([$cep]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar: " . $e->getMessage());
            return false;
        }
    }

    // ─── Serializa para JSON (usado pelo echo $model no controller) ───────────
    public function __toString() {
        return json_encode([
            'cep'        => $this->cep,
            'logradouro' => $this->logradouro,
            'bairro'     => $this->bairro,
            'localidade' => $this->cidade,
            'uf'         => $this->uf,
        ]);
    }
}
