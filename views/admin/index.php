<h1 class="nombre-pagina">Panel de administración</h1>
<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<?php include_once __DIR__ . '/../templates/alertas.php';?>


<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="<?=$fecha;?>">
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0){
        echo "<h2>No hay citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
            $idCita = 0;
            foreach ($citas as $key => $cita): 
                if($idCita !== $cita->id):
                    
                    $total = 0;
                    $idCita = $cita->id;?>

                    <li>
                        <p>ID: <span><?=$cita->id;?></span></p>
                        <p>Hora: <span><?=$cita->hora;?></span></p>
                        <p>Cliente: <span><?=$cita->cliente;?></span></p>
                        <p>Email: <span><?=$cita->email;?></span></p>
                        <p>Telefono: <span><?=$cita->telefono;?></span></p>
                    </li>
                    <h3>Servicios</h3>
                <?php endif; ?>
                <?php $total += $cita->precio; ?>
                <p class="servicio"><?=$cita->servicio . " " . $cita->precio;?></p>

                <?php 
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;?>
                <?php if(esUltimo($actual, $proximo)):?>
                    <p class="total">Total S/. <span><?=$total;?></span></p>
                    <form action="/api/eliminar" method="post" id="formularioAcciones">
                        <input type="hidden" name="id" id="id" value="<?=$cita->id;?>" >
                        <input type="submit" value="Eliminar" class="boton-eliminar" onclick="return swalConfirmar(event, '#formularioAcciones', {mensaje: '¿Desea eliminar esta cita?', texto: 'No podrá deshacer esto', botonConfirmacion: 'Si, eliminar', mensajeConfirmacion: 'Eliminando...'})">
                    </form>
                <?php endif;?>
            <?php endforeach;?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>
    <script src='build/js/swalAlertas.js'></script>"
?>
