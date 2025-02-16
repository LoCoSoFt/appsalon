<h1 class="nombre-pagina">Olvidé mi Password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'?>

<form action="/olvide" class="formulario" method="post">
    <div class="campo">
        <label for="email">Email</label>
    <input type="email" name="email" placeholder="@" id="email">
</div>
<input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>