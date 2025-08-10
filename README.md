# Projeto PHP Refatorado

Este projeto foi reestruturado para seguir as melhores práticas de desenvolvimento, separando o código em diretórios lógicos para facilitar a manutenção, escalabilidade e segurança.

## Estrutura de Diretórios

-   **/public**: O único diretório que deve ser acessível pela web.
    -   `index.php`: Ponto de entrada principal da aplicação. É aqui que você monta suas páginas.
    -   `/assets`: Contém todos os arquivos estáticos (CSS, JavaScript, imagens).
-   **/src**: Contém todo o código-fonte PHP da sua aplicação.
    -   `/components`: Cada arquivo aqui é uma função que gera um componente HTML (ex: `menu.php`, `slideshow.php`).
    -   `/core`: Contém o núcleo da aplicação (bootstrap, renderizador, cache, banco de dados).
    -   `/helpers`: Funções utilitárias (formatação, validação, etc.).
    -   `globals.php`: Onde as variáveis globais (`$style`, `$script`, `$main`, etc.) são inicializadas.
    -   `svg.php`: Onde os ícones SVG (`$lupa`, `$envelope`) são definidos.
-   **/cache**: Diretório onde os arquivos de cache das páginas são salvos.

## Estrutura Detalhada de Arquivos

Para uma visualização completa e detalhada de todos os arquivos e pastas do projeto, consulte o arquivo **`structure.txt`**, localizado na raiz do projeto. Ele fornece um mapa visual que ajuda a navegar pela base de código.

## Como Usar

1.  **Configure seu servidor web** (Apache, Nginx) para que a raiz do seu site aponte para o diretório `/public`. Isso é crucial para a segurança, pois impede que os usuários acessem diretamente os arquivos em `/src`.
2.  **Abra o arquivo `public/index.php`**. Este é o seu principal local de trabalho.
3.  **Use as funções de componentes** (como `menu()`, `slideshow()`, `grid()`) para construir o conteúdo da sua página, atribuindo o resultado às variáveis globais (ex: `$header[] = menu(...)`).
4.  **Chame a função `html()`** no final para renderizar a página completa.

Toda a lógica de carregamento de arquivos é gerenciada automaticamente pelo `src/core/bootstrap.php`. Você só precisa focar em construir sua página no `index.php`.