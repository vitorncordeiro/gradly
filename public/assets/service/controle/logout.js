document.getElementById("logout").addEventListener("click", (e) =>{
    e.preventDefault();
    logout();
})

// aqui o logout ta redirecionando pro ../login.php, presumindo que a página atual tá numa subpasta de view(como view/aluno)
// o que é DIFERENTE DE ANTES, que tava tudo dentro de view. Caso alguma página esteja direto no view, tem q ou por numa subpasta
// ou criar uma validação aqui pra redirecionar certo.
async function logout() {
    await fetch("/gradly/app/controllers/login_controller.php", {
        method: "POST",
        body: new URLSearchParams({ acao: "logout" })
    });

    window.location.href = "/gradly/public/views/login.php";
}