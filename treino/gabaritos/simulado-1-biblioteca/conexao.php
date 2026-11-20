<?php
// =====================================================
// CONEXÃO COM O BANCO (PDO)
// Na prova, só troque os valores abaixo.
// Todos os outros arquivos fazem require 'conexao.php', então a conexão
// só precisa ser configurada AQUI (uma vez).
//
// >>> ADAPTAR — O QUE CONFERIR/MUDAR (é a 1ª coisa a fazer na prova):
//     $host    → quase sempre 'localhost' (só mude se a prova informar outro servidor/IP)
//     $banco   → nome do banco da prova; tem que ser IGUAL ao do CREATE DATABASE / USE
//                do banco.sql (ou ao banco que a prova entregar pronto).
//                Ex.: prova pede o banco "estoque_saep" → $banco = 'estoque_saep';
//     $usuario → geralmente 'root'
//     $senha   → a senha do MySQL. Depende de QUAL MySQL o computador usa:
//                • MySQL do XAMPP (botão "MySQL" no painel do XAMPP): senha VAZIA  → ''
//                • MySQL Server instalado à parte (o que vem junto com o Workbench):
//                  é a senha criada na instalação. Ex.: '123456' ou 'root'.
//                Dica: é a MESMA senha que você digita no Workbench para abrir a conexão.
//                Se a prova informar uma senha, use a da prova.
//
//     Erros comuns ao abrir a página (a mensagem aparece em cima, na tela branca):
//       "Access denied for user"   → usuário ou senha errados (teste '' ou a senha do Workbench)
//       "Unknown database"         → nome do banco errado, ou o banco.sql ainda não foi executado
//       "Connection refused" /
//       "No connection could be made" → o MySQL está desligado (ligue no XAMPP ou no
//                                    Workbench/serviço do Windows)
// =====================================================
$host    = 'localhost';
$banco   = 'biblioteca_db';
$usuario = 'root';
$senha   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erro ao conectar no banco: ' . $e->getMessage());
}

// Função auxiliar para escapar saída em HTML (evita XSS).
// Não precisa mudar. Use e(...) sempre que imprimir TEXTO vindo do banco ou do usuário.
// Ex.: no index.php →  e($r['titulo'])  dentro do echo curto do PHP.
function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
