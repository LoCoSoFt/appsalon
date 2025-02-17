<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . '/../templates/alertas.php'?>

<form action="/crear-cuenta" class="formulario" method="post">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value=<?=s($usuario->nombre) ?>>
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Tu apellido" value="<?=$usuario->apellido ?>">
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Tu telefono" value="<?= $usuario->telefono ?>">
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Tu email" value="<?= $usuario->email ?>">
    </div>
    <div class="campo">
        <label for="password">Tu password</label>
        <input type="password" id="password" name="password" placeholder="Tu password">
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        <a href="/olvide">Olvidé mi password</a>
</div>