/**
 * Constrói e abre uma URL do WhatsApp para enviar uma mensagem pré-formatada.
 * @param {string} nameId - O ID do campo de input do nome.
 * @param {string} messageId - O ID do campo de textarea da mensagem.
 * @param {string} phone - O número de telefone de destino (com código do país).
 */
function sendwhats(nameId, messageId, phone) {
    const name = document.getElementById(nameId).value;
    const message = document.getElementById(messageId).value;

    // Codifica os componentes da URL para garantir que caracteres especiais funcionem
    const encodedName = encodeURIComponent(name);
    const encodedMessage = encodeURIComponent(message);

    const url = `https://api.whatsapp.com/send?phone=${phone}&text=Sou%20*${encodedName}*%0A%0A${encodedMessage}`;

    // Abre a URL em uma nova aba
    window.open(url, '_blank');
}