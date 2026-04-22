function sendMSG(textoDigitado) {
    const htmlUsuario = `
        <div class="chat-bubble user">
            <span class="bubble-text">${textoDigitado}</span>
        </div>`;
    atualizarQuadro(htmlUsuario);

    console.log("Enviando para o servidor: ", textoDigitado);
}