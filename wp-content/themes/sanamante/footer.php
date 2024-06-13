<footer id="mf-footer">
    <div class="container">
        <?php dynamic_sidebar('footer') ?>
        <p class="text-center">
            Copyright © <?= date('Y') ?> <?= get_bloginfo( 'name' ) ?> - Todos los Derechos Reservados
        </p>
    </div>
</footer>
<?php wp_footer(); ?>
<script>
  function onClick(e) {
    e.preventDefault();
    grecaptcha.enterprise.ready(async () => {
      const token = await grecaptcha.enterprise.execute('6LdqnvYpAAAAAFNzU-1vdRtVc_eGdtjO6As1Z-8J', {action: 'LOGIN'});
    });
  }
</script>
</body>
</html>