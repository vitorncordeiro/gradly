document.addEventListener("DOMContentLoaded", () => {
  buscarProjeto();
});

async function buscarProjeto() {
  const container = document.getElementById("projectContainer");
  if (!container) {
    return;
  }

  container.innerHTML = "";

  try {
    const retorno = await fetch(
      "/gradly/app/controllers/projeto_controller.php?acao=buscar",
      { method: "GET" },
    );

    const resposta = await retorno.json();

    if (!resposta.success || !resposta.data) {
      renderEmptyState(container, resposta.message);
      return;
    }

    renderProjeto(container, resposta.data);
  } catch (error) {
    renderEmptyState(container, "Não foi possível carregar o projeto.");
  }
}

function renderProjeto(container, projeto) {
  const card = document.createElement("div");
  card.className = "proj-card";

  const header = document.createElement("div");
  header.className = "proj-header";

  const title = document.createElement("h3");
  title.className = "proj-card-title";
  title.textContent = "Resumo do Projeto";

  const status = document.createElement("span");
  status.className = `sbadge ${getStatusClass(projeto.estado)}`;
  status.textContent = projeto.estado || "Sem status";

  header.appendChild(title);
  header.appendChild(status);

  const meta = document.createElement("div");
  meta.className = "proj-meta";

  const metaInfo = document.createElement("div");
  const name = document.createElement("p");
  name.className = "proj-name";
  name.textContent = projeto.titulo || "Projeto sem título";

  const desc = document.createElement("p");
  desc.className = "proj-desc";
  desc.textContent = projeto.descricao || "Sem descrição cadastrada";

  metaInfo.appendChild(name);
  metaInfo.appendChild(desc);

  const orientador = document.createElement("div");
  const orientadorLabel = document.createElement("p");
  orientadorLabel.className = "proj-desc";
  orientadorLabel.textContent = "Orientador responsável";

  const orientadorName = document.createElement("p");
  orientadorName.className = "proj-name";
  orientadorName.textContent = projeto.orientador_nome || "Não definido";

  orientador.appendChild(orientadorLabel);
  orientador.appendChild(orientadorName);

  meta.appendChild(metaInfo);
  meta.appendChild(orientador);

  const grid = document.createElement("div");
  grid.className = "info-grid";

  const items = [
    { label: "Objetivo", value: projeto.objetivo },
    { label: "Temas", value: projeto.temas },
    { label: "Áreas", value: projeto.areas },
    { label: "Estado", value: projeto.estado },
  ];

  items.forEach((item) => {
    const cell = document.createElement("div");
    cell.className = "info-item";

    const label = document.createElement("div");
    label.className = "info-label";
    label.textContent = item.label;

    const value = document.createElement("div");
    value.className = "info-value";
    value.textContent = item.value || "Não informado";

    cell.appendChild(label);
    cell.appendChild(value);
    grid.appendChild(cell);
  });

  card.appendChild(header);
  card.appendChild(meta);
  card.appendChild(grid);
  container.appendChild(card);
}

function renderEmptyState(container, message) {
  const empty = document.createElement("div");
  empty.className = "empty-state";

  const title = document.createElement("p");
  title.style.fontWeight = "600";
  title.style.marginBottom = "6px";
  title.textContent = "Nenhum projeto encontrado";

  const subtitle = document.createElement("p");
  subtitle.style.marginBottom = "12px";
  subtitle.textContent = message || "Cadastre um projeto para visualizar os detalhes.";

  const link = document.createElement("a");
  link.className = "btn-primary";
  link.href = "cadastro_projeto.php";
  link.textContent = "Criar projeto";

  empty.appendChild(title);
  empty.appendChild(subtitle);
  empty.appendChild(link);
  container.appendChild(empty);
}

function getStatusClass(status) {
  if (!status) {
    return "pending";
  }

  const normalized = status.toLowerCase();

  if (normalized.includes("aprov") || normalized.includes("concl")) {
    return "approved";
  }

  if (normalized.includes("reje") || normalized.includes("erro")) {
    return "error";
  }

  return "pending";
}
