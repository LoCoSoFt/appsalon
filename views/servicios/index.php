<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de servicios</p>


<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<ul class="servicios">
    <?php foreach ($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?=$servicio->nombre;?></span></p>
            <p>Precio: <span>S/.<?=$servicio->precio;?></span></p>

            <div class="acciones">
                <a href="/servicios/actualizar?id=<?=$servicio->id;?>" class="boton">Actualizar</a>
                <form action="/servicios/eliminar" method="post" id="formulario">
                    <input type="hidden" name="id" value="<?=$servicio->id;?>">
                    <input type="submit" value="Borrar" class="boton-eliminar" onclick="return swalConfirmar(event, '#formulario', {mensaje: '¿Desea eliminar este servicio?', texto: 'No podrá deshacer esto', botonConfirmacion: 'Si, eliminar', mensajeConfirmacion: 'Eliminando servicio...'})">
                </form>
            </div>
        </li>
    <?php endforeach;?>
</ul>

<?php
$script = "<script src='../build/js/swalAlertas.js'></script>"
?>