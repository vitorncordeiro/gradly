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
        console.log(resposta.alunos);
        let linhas = "";
        resposta.alunos.forEach(aluno => {
            linhas += `
                <tr>
                    <td>${aluno.nome}</td>
                    <td>${aluno.email}</td>
                    <td>${aluno.curso}</td>
                </tr>
            `;
        });
        document.getElementById("alunos-table-body").innerHTML = linhas;
    }else{
        alert("Erro! " + resposta.message);
    }
}   