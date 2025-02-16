<h1 class="nombre-pagina">Crear nueva cita</h1>
<p class="descripcion-pagina">Elige tus servicios a continuación</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<div id="app">
    <nav class="tabs ">
        <button class="actual" type="button" data-paso = "1">Servicios</button>
        <button type="button" data-paso = "2">Información cita</button>
        <button type="button" data-paso = "3">Resumen</button>
    </nav>
    <div class="seccion" id="paso-1">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div class="listado-servicios" id="servicios"></div>
    </div>
    <div class="seccion" id="paso-2">
        <h2>Tus datos y cita</h2>
        <p>Ingresa tus datos y fecha de cita</p>

        <form action="" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="" id="nombre" disabled placeholder="Tu nombre" value="<?= $nombre?>">
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" name="" id="fecha" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
            
            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" name="" id="hora">
            </div>
            <input type="hidden" name="id" id="id" value="<?= $id ?>">
        </form>
    </div>
    <div class="seccion contenido-resumen" id="paso-3">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button type="button" class="boton" id="anterior">&laquo; Anterior</button>
        <button type="button" class="boton" id="siguiente">Siguiente &raquo;</button>
    </div>
</div>

<?php
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";
?>