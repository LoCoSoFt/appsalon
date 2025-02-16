<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="post" class="formulario" id="formulario">
    <?php
        include_once __DIR__ . '/formulario.php' 
    ?>
    
    <input 
        type="submit" 
        value="Actualizar servicio" 
        class="boton" 
        onclick="
            return swalConfirmar(
                event, 
                '#formulario', 
                {mensaje: '¿Desea guardas los cambios?', 
                texto: 'No podrá deshacer esto', 
                botonConfirmacion: 'Si, guardar', 
                mensajeConfirmacion: 'Guardando cambios'})">
</form>

<?php
    $script = "<script src='../build/js/swalAlertas.js'></script>"
?>