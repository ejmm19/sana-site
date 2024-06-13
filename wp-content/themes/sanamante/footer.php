<footer id="mf-footer">
    <div class="container">
        <?php dynamic_sidebar('footer') ?>
        <p class="text-center">
            Copyright © <?= date('Y') ?> <?= get_bloginfo( 'name' ) ?> - Todos los Derechos Reservados
        </p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>