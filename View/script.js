// http://localhost/CEP_API/View/index.html

// ─── GET: busca um CEP já salvo no banco ──────────────────────────────────────
async function buscarCep() {
    const cep = document.getElementById('cep').value;
    const btn = document.getElementById('btnBuscar');

    if (!cep) { mostrarMensagem('Digite um CEP', 'erro'); return; }

    btn.innerText = "Buscando...";
    btn.disabled  = true;

    try {
        const response = await fetch(`../Controller/CepAPI.php?cep=${cep}`);
        const data     = await response.json();

        if (data.erro) {
            mostrarMensagem(data.erro, 'erro');
            limparCampos();
        } else {
            preencherCampos(data);
            mostrarMensagem('CEP encontrado no banco!', 'ok');
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mostrarMensagem('Erro ao consultar o servidor.', 'erro');
    } finally {
        btn.innerText = "Buscar (GET)";
        btn.disabled  = false;
    }
}

// ─── POST: consulta ViaCEP e salva no banco ───────────────────────────────────
async function salvarCep() {
    const cep = document.getElementById('cep').value;
    const btn = document.getElementById('btnSalvar');

    if (!cep) { mostrarMensagem('Digite um CEP', 'erro'); return; }

    btn.innerText = "Salvando...";
    btn.disabled  = true;

    try {
        const response = await fetch('../Controller/CepAPI.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ cep: cep }),
        });
        const data = await response.json();

        if (data.erro) {
            mostrarMensagem(data.erro, 'erro');
        } else {
            preencherCampos(data.dados);
            mostrarMensagem(data.mensagem, 'ok');
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mostrarMensagem('Erro ao consultar o servidor.', 'erro');
    } finally {
        btn.innerText = "Salvar (POST)";
        btn.disabled  = false;
    }
}

// ─── PUT: atualiza dados de um CEP já salvo ───────────────────────────────────
async function atualizarCep() {
    const cep = document.getElementById('cep').value;
    const btn = document.getElementById('btnAtualizar');

    if (!cep) { mostrarMensagem('Digite o CEP que deseja atualizar', 'erro'); return; }

    const body = {
        logradouro: document.getElementById('logradouro').value,
        bairro:     document.getElementById('bairro').value,
        cidade:     document.getElementById('cidade').value,
        uf:         document.getElementById('uf').value,
    };

    // Verifica se todos os campos estão preenchidos
    for (const [campo, valor] of Object.entries(body)) {
        if (!valor.trim()) {
            mostrarMensagem(`Preencha o campo: ${campo}`, 'erro');
            return;
        }
    }

    btn.innerText = "Atualizando...";
    btn.disabled  = true;

    try {
        const response = await fetch(`../Controller/CepAPI.php?cep=${cep}`, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(body),
        });
        const data = await response.json();

        if (data.erro) {
            mostrarMensagem(data.erro, 'erro');
        } else {
            preencherCampos(data.dados);
            mostrarMensagem(data.mensagem, 'ok');
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mostrarMensagem('Erro ao consultar o servidor.', 'erro');
    } finally {
        btn.innerText = "Atualizar (PUT)";
        btn.disabled  = false;
    }
}

// ─── DELETE: remove um CEP do banco ──────────────────────────────────────────
async function deletarCep() {
    const cep = document.getElementById('cep').value;
    const btn = document.getElementById('btnDeletar');

    if (!cep) { mostrarMensagem('Digite um CEP', 'erro'); return; }

    btn.innerText = "Deletando...";
    btn.disabled  = true;

    try {
        const response = await fetch(`../Controller/CepAPI.php?cep=${cep}`, {
            method: 'DELETE',
        });
        const data = await response.json();

        if (data.erro) {
            mostrarMensagem(data.erro, 'erro');
        } else {
            mostrarMensagem(data.mensagem, 'ok');
            limparCampos();
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mostrarMensagem('Erro ao consultar o servidor.', 'erro');
    } finally {
        btn.innerText = "Deletar (DELETE)";
        btn.disabled  = false;
    }
}

// ─── Helpers ──────────────────────────────────────────────────────────────────
function preencherCampos(data) {
    document.getElementById('logradouro').value = data.logradouro  || '';
    document.getElementById('bairro').value     = data.bairro      || '';
    document.getElementById('cidade').value     = data.localidade  || data.cidade || '';
    document.getElementById('uf').value         = data.uf          || '';
}

function limparCampos() {
    ['logradouro', 'bairro', 'cidade', 'uf'].forEach(id => {
        document.getElementById(id).value = '';
    });
}

function mostrarMensagem(texto, tipo) {
    const el  = document.getElementById('mensagem');
    el.innerText   = texto;
    el.className   = tipo; // 'ok' ou 'erro'
}
