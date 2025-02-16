<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php';?>

<form action="" class="formulario" method="post">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" placeholder="Tu email" name="email" id="email">
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" placeholder="Tu contraseña" id="password" name="password">
    </div>
    <input type="submit" value="Iniciar sesión" class="boton">

    <div class="acciones">
        <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
        <a href="/olvide">Olvidé mi password</a>
    </div>
</form>
