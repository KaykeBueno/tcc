// Arquivo mínimo para evitar 404 quando páginas esperam por ele.
// Pode conter inicializações comuns (menu, handlers)
console.log('inicial.js loaded');

// Hambúrguer simples para abrir/fechar sidebar
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.hamburger-btn, .hamburger-btn-header').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.getElementById('sidebar').classList.toggle('open');
        });
    });
});
