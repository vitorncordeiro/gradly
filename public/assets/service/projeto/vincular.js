document.getElementById("vincular").addEventListener("click", function() {
  vincularProjeto();
});

async function vincularProjeto() {
  const projetoId = document.getElementById("selectProjetos").value;
  const orientadorId = document.getElementById("selectOrientadores").value;

    if (!projetoId || !orientadorId) {
        alert("Por favor, selecione um projeto e um orientador.");
        return;
    }

  const fd = new FormData();
  fd.append('acao', 'vincularProjeto');
  fd.append('projeto_id', projetoId);
  fd.append('orientador_id', orientadorId);

  const retorno = await fetch("/gradly/app/controllers/projeto_controller.php", {
    method: "POST",
    body: fd
  });

  const resposta = await retorno.json();

  if (resposta.success) {
    alert("Projeto vinculado com sucesso!");
    location.reload();
  } else {
    alert("Erro ao vincular projeto: " + resposta.message);
  }
}