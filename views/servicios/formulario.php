<div class="campo">
    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" placeholder="Nombre de servicio" value="<?=$servicio->nombre ?? '';?>" >
</div>
<div class="campo">
    <label for="precio">Precio S/.</label>
    <input type="number" name="precio" id="precio" placeholder="Precio" value="<?=$servicio->precio ?? ''; ?>">
</div>