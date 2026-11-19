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
//     $senha   → a senha do MySQL do computador da prova. No XAMPP/WAMP padrão ela costuma
//                ser vazia: ''  (se a prova informar uma senha, coloque aqui).
//
//     Erros comuns ao abrir a página:
//       "Access denied for user"  → usuário ou senha errados
//       "Unknown database"        → nome do banco errado (ou banco.sql ainda não foi importado)
//       "Connection refused"      → o MySQL não está ligado no XAMPP/WAMP
// =====================================================
$host    = 'localhost';
$banco   = 'saep_db';
$usuario = 'root';
$senha   = '123456';

// =====================================================
// TEMA VISUAL (CSS)
//
// >>> ADAPTAR (VISUAL): é AQUI que você escolhe o visual do sistema inteiro.
//     Troque só o nome entre aspas na linha marcada "TROQUE AQUI" (logo abaixo desta explicação):
//
//       'oceano'      → vidro fosco (glassmorphism) azul/ciano, tema escuro
//       'neon'        → dark estilo cyberpunk, com brilho ciano / rosa / roxo
//       'por-do-sol'  → gradiente laranja → rosa → roxo com vidro claro
//       'floresta'    → verde limpo e suave, claro
//       'brutalista'  → amarelo e rosa, bordas pretas e sombras duras
//       ''            → visual padrão simples (só o style.css: azul e cinza)
//
//     O nome é o do arquivo dentro da pasta  temas/  (sem o .css).
//     Ex.: 'neon' usa o arquivo  temas/neon.css
//     Digitou o nome e a página ficou sem o visual do tema? Confira se está IGUAL ao nome do arquivo.
//
//     Quer um visual só seu? Copie um tema (ex.: temas/oceano.css → temas/meu.css),
//     troque as cores das variáveis no começo do arquivo e escreva 'meu' na linha "TROQUE AQUI".
//
//     A prova pediu um visual específico, ou não quer tema nenhum? Deixe as aspas VAZIAS ('')
//     na linha "TROQUE AQUI" e ajuste as cores no :root do style.css.
// =====================================================
$TEMA = 'oceano';   // <<< TROQUE AQUI: só o nome entre aspas (esta é a ÚNICA linha que muda o tema)

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
