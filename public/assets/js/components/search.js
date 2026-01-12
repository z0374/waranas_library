function pesquisar(data,tipo) {
  const input = document.getElementById("busca").value.toLowerCase(); // Termo de pesquisa
  const result = document.querySelector("main"); // Onde os resultados serão exibidos
  result.innerHTML = ""; // Limpa os resultados anteriores

  // Função de comparação para verificar se o termo está na string de tags
  function isEqual(tags, searchTerm) {
    // Converte a string de tags em um array e verifica se o termo de pesquisa está presente
    const tagArray = tags.toLowerCase().split(","); // Converte para minúsculas e divide as tags
    return tagArray.some(tag => tag.includes(searchTerm)); // Verifica se o termo de pesquisa está em alguma das tags
  }

  // Itera sobre o array de dados
  data.forEach(item => {
    // Verificando se o termo de pesquisa está nas tags do item
    if (isEqual(item["tags"], input)) {
	    if(tipo == "text"){
		      const li = document.createElement("li");
		        li.setAttribute("class","itms");
		      const h1 = document.createElement("h1");
		      h1.textContent = item["ttl"];
		      const h2 = document.createElement("h2");
		      h2.textContent = "("+item["lgd"]+")";
		      const a = document.createElement("a");
		      a.textContent = item["cnt"]; a.href=item["anc"];
		      li.append(h1);
		      li.append(h2);
		      li.append(a);
		      result.appendChild(li); // Exibe o item no resultado
		      	}
	if(tipo == "custom"){
 		result.appendChild(item["ttl"]);
	}
       
    }
  });if(result.innerHTML==""){result.style.padding="30% 0";result.innerHTML="<li class=itms><h1>ELEMENTO LEXICAL INESISTENTE !</h1></li>";return;}
  	let btReturn = document.createElement("button");
   	btReturn.click = location.reload();
    	btReturn.textContent = "Voltar";
     	result.appendChild(btReturn);
}