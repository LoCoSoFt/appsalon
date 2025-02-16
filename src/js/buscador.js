document.addEventListener('DOMContentLoaded', ()=>{
    inciarApp();
})

function inciarApp(){
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', (e)=>{
        fechaSeleccionada = e.target.value;
        
        window.location = `?fecha=${fechaSeleccionada}`
    })
}