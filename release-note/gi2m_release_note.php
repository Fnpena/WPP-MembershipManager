<h1><?php echo get_admin_page_title(); ?></h1>
<?php if(current_user_can('manage_options')){ ?>
<div class="wrap">
    <p>
    HOLA MUNDO
    </p>
    <?php }else{ ?>
    <p>
    Acceso denegado
    </p>
</div>
<?php } ?>
