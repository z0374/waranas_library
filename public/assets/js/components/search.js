function pesquisar(data, tipo, htmlResult) {
  const input = document.getElementById("busca").value.toLowerCase().trim();
  const result = document.createElement("ul");
  const htmlRes = document.querySelector(htmlResult);
  result.className = "waranasSearchItems";
  htmlRes.innerHTML = "";

  if (!input) return;

  const searchTerms = input.split(",").map(t => t.trim()).filter(t => t);

  // Função de pontuação fuzzy
  function scoreField(field, term) {
    field = field.toLowerCase();
    if (field === term) return 5;            // correspondência exata máxima
    if (field.includes(term)) return 3;      // substring
    // correspondência parcial (simples)
    let common = 0;
    for (let i = 0; i < Math.min(field.length, term.length); i++) {
      if (field[i] === term[i]) common++;
    }
    return common / term.length;             // ponto proporcional
  }

  // Calcula relevância total do item
  function relevancia(item) {
    let score = 0;
    searchTerms.forEach(term => {
      score += scoreField(item.ttl, term) * 5;   // TTL mais importante
      score += scoreField(item.lgd, term) * 3;   // LGD
      score += scoreField(item.cnt, term) * 2;   // CNT
      const tagsArray = item.tags.toLowerCase().split(",").map(t => t.trim());
      tagsArray.forEach(tag => {
        score += scoreField(tag, term);          // tags
      });
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
    return;
  }

  // Função para destacar termos encontrados
  function highlight(text) {
    let highlighted = text;
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
      h1.innerHTML = highlight(item.ttl);

      const h2 = document.createElement("h2");
      h2.innerHTML = `(${highlight(item.lgd)})`;

      const a = document.createElement("a");
      a.innerHTML = highlight(item.cnt);
	  a.innerHTML += " <svg class='icon arrowClick' style='width: 1em !important; height:1em !important;'><use href='#arrow'></use></svg>";
      a.href = item.anc;

      li.append(h1, h2, a);
      result.appendChild(li);
    } else if (tipo === "custom") {
      result.appendChild(document.createTextNode(item.ttl));
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
