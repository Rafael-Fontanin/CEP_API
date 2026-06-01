<?php
require_once '../Model/Endereco.php';
// Chama o método estático - retorna array com todos os registros
$enderecos = Endereco::listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Endereços Salvos</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #0f0f0f;
            --surface: #181818;
            --border:  #2a2a2a;
            --accent:  #c8f060;
            --text:    #f0f0f0;
            --muted:   #555;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Mono', monospace;
            padding: 2rem;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: .3rem;
        }

        h1 span { color: var(--accent); }

        a {
            color: var(--accent);
            font-size: .8rem;
            display: inline-block;
            margin: 1rem 0 2rem;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .85rem;
            border-radius: 10px;
            overflow: hidden;
            background: var(--surface);
        }

        thead { background: #111; }

        th {
            padding: .8rem 1rem;
            color: var(--accent);
            font-size: .7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: .7rem 1rem;
            color: #ccc;
            border-bottom: 1px solid var(--border);
        }

        tbody tr { transition: background .2s; }
        tbody tr:nth-child(even)  { background: #141414; }
        tbody tr:hover            { background: #1f1f1f; }

        .vazio {
            color: var(--muted);
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Endereços <span>Salvos</span></h1>
    <a href="index.html">← Voltar</a>

    <table>
        <thead>
            <tr>
                <th>CEP</th>
                <th>Logradouro</th>
                <th>Bairro</th>
                <th>Cidade</th>
                <th>UF</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($enderecos)): ?>
                <tr><td colspan="5" class="vazio">Nenhum endereço cadastrado.</td></tr>
            <?php else: ?>
                <?php foreach ($enderecos as $row): ?>
                <tr>
                    <td><?= htmlspecialchars(substr($row['cep'], 0, 5) . '-' . substr($row['cep'], 5, 3)) ?></td>
                    <td><?= htmlspecialchars($row['logradouro']) ?></td>
                    <td><?= htmlspecialchars($row['bairro'])     ?></td>
                    <td><?= htmlspecialchars($row['cidade'])     ?></td>
                    <td><?= htmlspecialchars($row['uf'])         ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
