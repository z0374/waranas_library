function pesquisar(data, tipo, htmlResult) {
  const input = document.getElementById("busca").value.toLowerCase().trim();
  const result = document.createElement("ul");
  const htmlRes = document.querySelector(htmlResult);
  result.className = "waranasSearchItems";
  htmlRes.innerHTML = "";

  if (!input) return;

  const searchTerms = input.split(",").map(t => t.trim()).filter(t => t);

  // Função de pontuação fuzzy com proteção contra campos nulos/undefined
  function scoreField(field, term) {
    if (!field) return 0; // Se o campo for undefined ou null, ignora a pontuação
    
    const fieldStr = String(field).toLowerCase();
    if (fieldStr === term) return 5;            
    if (fieldStr.includes(term)) return 3;      
    
    let common = 0;
    for (let i = 0; i < Math.min(fieldStr.length, term.length); i++) {
      if (fieldStr[i] === term[i]) common++;
    }
    return common / term.length;             
  }

  // Calcula relevância total do item
  function relevancia(item) {
    let score = 0;
    searchTerms.forEach(term => {
      // Proteção com fallback para string vazia ""
      score += scoreField(item.titulo || "", term) * 5;   
      score += scoreField(item.legenda || "", term) * 3;  
      // Suporte para chaves 'texto' ou 'text' conforme seu dump
      score += scoreField(item.texto || item.text || "", term) * 2; 

      const tags = String(item.tags || "");
      if (tags) {
          const tagsArray = tags.toLowerCase().split(",").map(t => t.trim());
          tagsArray.forEach(tag => {
            score += scoreField(tag, term);
          });
      }
    });
    return score;
  }

  // Ordena os itens por relevância
  const filtered = data
    .map(item => ({ ...item, score: relevancia(item) }))
    .filter(item => item.score > 0)
    .sort((a, b) => b.score - a.score);

  if (filtered.length === 0) {
    result.style.padding = "30% 0";
    result.innerHTML = `<li class="waranasSearchItems"><h1>ELEMENTO LEXICAL INESISTENTE!</h1></li>`;
    htmlRes.append(result); // Garante que a mensagem apareça
    return;
  }

  // Função para destacar termos encontrados
  function highlight(text) {
    if (!text) return "";
    let highlighted = String(text);
    searchTerms.forEach(term => {
      const regex = new RegExp(`(${term})`, "gi");
      highlighted = highlighted.replace(regex, "<mark>$1</mark>");
    });
    return highlighted;
  }

  // Exibe resultados
  filtered.forEach(item => {
    if (tipo === "text") {
      const li = document.createElement("li");

      const h1 = document.createElement("h1");
      h1.innerHTML = highlight(item.titulo);

      const h2 = document.createElement("h2");
      h2.innerHTML = `(${highlight(item.legenda)})`;

      const a = document.createElement("a");
      // Mapeia para item.texto ou item.text para o conteúdo do link
      a.innerHTML = highlight(item.texto || item.text || "");
      a.innerHTML += " <svg class='icon arrowClick' style='width: 1em !important; height:1em !important;'><use href='#arrow'></use></svg>";
      
      // Mapeia para item.url ou item.anc para o endereço
      a.href = item.url || item.anc || "#";

      li.append(h1, h2, a);
      result.appendChild(li);
    } else if (tipo === "custom") {
      result.appendChild(document.createTextNode(item.titulo || ""));
    }
  });

  // Botão voltar
  const btReturn = document.createElement("button");
  btReturn.className = "waranasSearchItemsReturn";
  btReturn.textContent = "Voltar";
  btReturn.onclick = () => location.reload();
  result.appendChild(btReturn);

  htmlRes.append(result);
}