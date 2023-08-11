<main class="contenedor seccion">
    <h1>Contacto</h1>
    <?php if($mensaje): ?>
        <p class="alerta exito eliminar-anuncio"><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <picture>
        <source srcset="build/img/destacada3.webp" type="image/webp">
        <source srcset="build/img/destacada3.jpg" type="image/jpeg">
        <img loading="lazy" src="build/img/destacada3.jpg" alt="Imagen Contacto">
    </picture>
    <h2>Llene el formulario de Contacto</h2>
    <form class="formulario" method="POST" action="/contacto">
        <fieldset>
            <legend>Informaci&oacute;n Personal</legend>
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Tu Nombre" id="nombre" name="contacto[nombre]" required>

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" name="contacto[mensaje]" required></textarea>
        </fieldset>

        <fieldset>
            <legend>Informaci&oacute;n sobre la propiedad</legend>
            <label for="opciones">Vende o Compra</label>
            <select id="opciones" name="contacto[tipo]" required>
                <option value="" disabled selected>-- Seleccione --</option>
                <option value="Compra">Compra</option>
                <option value="Venta">Venta</option>
            </select>

            <label for="presupuesto">Presupuesto</label>
            <input type="number" placeholder="Precio o Presupuesto" id="presupuesto" name="contacto[precio]" required>
        </fieldset>

        <fieldset>
            <legend>Informaci&oacute;n sobre la propiedad</legend>
            <p>Como desea ser contactado</p>
            <div class="forma-contacto">
                <label for="contactar-telefono">Tel&eacute;fono</label>
                <input type="radio"  value="telefono" id="contactar-telefono" name="contacto[contacto]" required>
                <label for="contactar-email">E-mail</label>
                <input type="radio"  value="email" id="contactar-email" name="contacto[contacto]" required>
            </div>
            
            <div id="contacto" class="transicion">

            </div>

        </fieldset>

        <input type="submit" value="Enviar" class="boton-verde">
    </form>
</main>