<?php
/**
 * Envolve um array de conteúdo em uma tag <section> com uma classe específica.
 * (Este bloco é um "DocBlock", um tipo de comentário especial que documenta a função,
 * explicando o que ela faz de forma geral.)
 */

// Define uma função chamada 'section' que aceita dois parâmetros:
// $id: Será usado como o nome da classe CSS para a tag <section>.
// $sectionContent: Um array onde cada item é um pedaço de conteúdo HTML (uma string).
function section($id, $sectionContent) {
    
    // Retorna uma única string HTML. Vamos quebrar o que acontece aqui:
    // 1. "<section class='{$id}'>": Inicia a string criando a tag de abertura <section>. A variável $id é injetada diretamente dentro da string para definir a classe.
    // 2. . (ponto): Este é o operador de concatenação em PHP. Ele une a string da esquerda com o resultado da expressão da direita.
    // 3. implode('', $sectionContent): Esta função junta todos os elementos do array '$sectionContent' em uma única string. O primeiro argumento ('') define que não haverá nenhum separador entre os elementos.
    // 4. . '</section>': Concatena a tag de fechamento da seção ao final de tudo.
    return "<section class='{$id}'>" . implode('', $sectionContent) . '</section>';
}