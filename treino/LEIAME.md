# 🏋️ Treino para a prova: simulados

A prova do SAEP provavelmente vai pedir **um CRUD de outro assunto** (não de produtos) e vai exigir que você
**troque colunas, adicione campos e mude regras**. Estes simulados foram feitos para você treinar exatamente isso,
com o modelo deste projeto.

| # | Simulado | Nível | Tempo | O que você treina |
|---|----------|-------|-------|-------------------|
| 1 | [Biblioteca escolar](simulado-1-biblioteca.md) | ⭐ fácil | 60 min | trocar tabela e colunas, **remover** uma coluna, adaptar o alerta de estoque, campo novo |
| 2 | [Clínica: agenda de consultas](simulado-2-clinica.md) | ⭐⭐ médio | 75 min | chave primária com **outro nome**, select, data, hora, textarea, **remover** o alerta, regra de negócio |
| 3 | [Escola: boletim de alunos](simulado-3-escola.md) | ⭐⭐⭐ desafio | 90 min | coluna **calculada** (média), situação por regra, **reaproveitar** o alerta, e-mail único, validações |

Cada simulado tem **duas fases**, como na prova de verdade:

- **Fase 1:** construir o sistema a partir do enunciado.
- **Fase 2:** o professor "muda de ideia" e pede um campo novo e uma regra nova. Treina `ALTER TABLE` e a adaptação de um sistema que **já está funcionando**.

---

## Como treinar (passo a passo)

1. **Faça uma cópia** da pasta do projeto, por exemplo `saep-treino` (dentro do `htdocs`). **Não treine em cima do modelo original.**
2. Abra o simulado, **ligue um cronômetro** e leia o enunciado inteiro antes de mexer em qualquer arquivo.
3. Siga a ordem de sempre (a mesma do vídeo):
   1. `banco.sql` → executar no Workbench
   2. `conexao.php` → nome do banco
   3. Localizar e substituir (`Ctrl+H`) → tabela e colunas nos 4 arquivos `.php`
   4. `index.php` → `<th>`, `<td>`, `colspan`, busca e ordem
   5. `form.php` → `$registro` e os campos
   6. `salvar.php` → variáveis, validações, `INSERT` e `UPDATE`
   7. **Testar tudo**: cadastrar, editar, excluir, buscar e tentar dados inválidos
4. Termine a **Fase 1**, teste, e só então abra a **Fase 2** (está escondida em uma caixinha 🔒 no enunciado).
5. Use a tabela de **critérios** no fim do enunciado para se dar nota.
6. Só depois, confira o gabarito (pasta `gabaritos/`): tem o passo a passo e os arquivos completos.

> 💡 **Não abra a pasta `gabaritos/` antes de terminar!** O treino só vale se você tentar sozinho(a) primeiro.

### Comparar o seu com o gabarito
No VS Code, clique com o botão direito num arquivo seu → **Select for Compare**, depois no arquivo do gabarito → **Compare with Selected**.
As diferenças aparecem coloridas. (Nomes de variáveis diferentes **não são erro**: o que vale é funcionar.)

### Quer repetir?
Apague a sua cópia e copie o modelo de novo. Para recriar o banco, é só executar o `banco.sql` outra vez.

---

## Dicas que valem em qualquer simulado (e na prova)

- 🔎 `Ctrl+F` por **`ADAPTAR`**: cada ponto a mudar está marcado nos arquivos, com exemplo.
- 🔁 `Ctrl+H` com **palavra inteira** ligada (botão `ab`): troca `nome` sem estragar outras palavras.
- ✅ Depois de **cada** arquivo, abra o navegador e teste. Erro pequeno achado cedo custa 1 minuto; achado no fim custa 20.
- 🧪 Teste **o caminho errado**: campo vazio, e-mail inválido, número negativo. A prova costuma avaliar isso.
- 🧱 Campo novo = **4 arquivos**: `banco.sql` (ou `ALTER TABLE`), `index.php`, `form.php`, `salvar.php`.
- 🗑️ Remover campo = apagar nos mesmos lugares. Esquecer um deles é o erro mais comum (aparece `Undefined array key` ou erro de SQL).
- 🧾 Em dúvida do que o enunciado quer dizer, faça o **mínimo que ele pede** primeiro e só depois capriche.
