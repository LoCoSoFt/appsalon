let paso = 1;
const pasoInicial = 1, pasoFinal = 3;
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
};

document.addEventListener('DOMContentLoaded', iniciarApp);

function iniciarApp() {
    mostrarSeccion();
    tabs(); //cambia entre secciones
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();

    nombreCliente();
    idCliente();
    seleccionarFecha();
    seleccionarHora();

    // mostrarResumen();
}

function mostrarSeccion() {
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior)
        seccionAnterior.classList.remove('mostrar');

    const pasoSelector = document.querySelector(`#paso-${paso}`);
    pasoSelector.classList.add('mostrar')

    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior)
        tabAnterior.classList.remove('actual');

    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual')
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    
    botones.forEach(boton => {
        boton.addEventListener('click', e=>{
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        })
        
    });
}

function botonesPaginador() {
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');
    
    switch (paso) {
        case 1:
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
            break;
        case 3:
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
            if(paso === 3) mostrarResumen();
            break;
        default:
            paginaSiguiente.classList.remove('ocultar');
            paginaAnterior.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', ()=>{
        if(paso <= pasoInicial) return
        
        paso--;
        botonesPaginador();
        mostrarSeccion();
    })
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', ()=>{
        if(paso >= pasoFinal) return;
        
        paso++;
        botonesPaginador();
        mostrarSeccion();
    })
}

async function consultarAPI() {
    
    try {
        // const url = `${location.origin}/api/servicios`;
        const url = `/api/servicios`; //usalo así cuando los archivos están en el mismo dominio
/*         await fetch($url)
            .then( respuesta => respuesta.json() )
            .then( resultado => console.log(resultado)) */

        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
    } catch (error) {
        console.log(error);
    }
    
}

function mostrarServicios(servicios) {
    const contenedorServicios = document.querySelector('#servicios');
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P'); 
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `S/. ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        servicioDiv.onclick = ()=> seleccionarServicio(servicio);

        contenedorServicios.appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;

    const existe = servicios.some( agregado => agregado.id === id);
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    if(!existe){
        cita.servicios = [...servicios, servicio];
    
        divServicio.classList.add('seleccionado');
    }else{
        const nuevoServicios = servicios.filter( servicio => servicio.id !== id)
        cita.servicios = nuevoServicios;
        
        divServicio.classList.remove('seleccionado');
    }
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function idCliente() {
    cita.id = parseInt(document.querySelector('#id').value);
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', (e)=>{
        const dia = new Date(e.target.value).getUTCDay();
        
        if ([0, 6].includes(dia)){
            mostrarAlerta('Sabado y Domingos no hay atención', 'error', '.formulario')
            e.target.value = "";
        }else{
            cita.fecha = e.target.value;
        }
    })
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', (e)=>{
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];
        if(hora > 18 || hora < 10){
            mostrarAlerta('Hora no puede ser mayor a 18:00 ni menor a 10:00', 'error', '.formulario');
            e.target.value='';
        }else{
            cita.hora = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    const alertaExiste = document.querySelector('.alerta');
    if (alertaExiste) {
        alertaExiste.remove();
    };

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta', tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    
    if(desaparece){
        setTimeout(()=>{
            alerta.remove();
        }, 3000)
    }
    
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if(!cita.servicios.length){
        mostrarAlerta('Agregue algunos servicios', 'error', '.contenido-resumen', false);
        return;
    }

    //obtiene solo los valores de las citas
    if(Object.values(cita).includes('')){
        mostrarAlerta('Hacen falta datos. Ingrese Fecha y Hora', 'error', '.contenido-resumen', false);
        return;
    }

    //formatear contenido resumen
    const { nombre, fecha,  hora, servicios } = cita;

    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de servicios'
    resumen.appendChild(headingServicios)

    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> S/.${precio}`

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);
    });

    //heading de cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen cita'
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //==============================
    //formatear al fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2; //compensar desface
    const year = fechaObj.getFullYear();

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    const fechaFormateada = fechaUTC.toLocaleDateString('es-PE', opciones)
    //==============================
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

    //crear un botón para reservar
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente)
    resumen.appendChild(fechaCita)
    resumen.appendChild(horaCita)
    resumen.appendChild(botonReservar)
}

async function reservarCita() {

    const { nombre, id, fecha, hora, servicios } = cita;
    
    const idServicios = servicios.map((servicio)=>servicio.id);

    const datos = new FormData();

    datos.append('usuarioId', id)
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('servicios', idServicios)

    const url = '/api/citas'

    //peticion a la API
    try {
        // console.log([...datos]);
        
        const respuesta = await fetch(url, { 
                        method: 'POST',
                        body: datos
                    });
        const resultado = await respuesta.json();
        if(resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                // footer: '<a href="#">Why do I have this issue?</a>'
              }).then (()=>{
                setTimeout(()=>{
                    window.location.reload();
                }, 3000)
              })
        }else{
            throw new Error("No se creó la cita, servidor retorna error");
        }
    } catch (error) {
        console.log(error);
        
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita",
            // footer: '<a href="#">Why do I have this issue?</a>'
          });
    }

}