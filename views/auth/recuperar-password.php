<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'?>

<!-- para que no aparezca el formulario evaluamos la variable pasada por el controlador -->
 <?php
    if($error)
        return;
 ?>

<form method="post" class="formulario">
    <div class="campo">
        <label for="passowrd">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu nueva contraseña">
    </div>
    <input type="submit" value="Guardar nueva contraseña" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>
