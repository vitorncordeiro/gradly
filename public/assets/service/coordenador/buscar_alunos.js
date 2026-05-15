document.addEventListener('DOMContentLoaded', (event) => {
    buscarAlunos();
});

async function buscarAlunos(){
    const fd = new FormData();
    fd.append('acao', 'buscarAlunos');

    const retorno = await fetch("/gradly/app/controllers/coordenador_controller.php",{
        method: "POST",
        body: fd
    });

    const resposta = await retorno.json();

    if(resposta.success){
        let linhas = "";
        resposta.alunos.forEach(aluno => {
            if(!aluno.titulo_projeto){
                aluno.titulo_projeto = 'Não possui projeto'
            }
            linhas += `
                <tr>
                    <td>${aluno.nome}</td>
                    <td>${aluno.email}</td>
                    <td>${aluno.titulo_projeto}</td>
                    <td>
                        <button 
                            class="btn btn-primary btn-sm"
                            onclick="abrirProjeto(${aluno.grupo_id})"
                        >
                            Ver Projeto
                        </button>
                    </td>
                </tr>
            `;
        });
        document.getElementById("alunos-table-body").innerHTML = linhas;
    }else{
        alert("Erro! " + resposta.message);
        console.log(resposta.error)
    }
}

function abrirProjeto(grupoId){
    window.location.href =
        `/gradly/public/views/coordenador/projeto.php?grupo_id=${grupoId}`;
}