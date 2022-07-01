<?php
get_header();
//var_dump(get_the_terms( get_the_ID(), 'estate_type'));
?>


<main class="mt-4">
    <div class="container">
        <div class="col-sm-12">
            <div class="row">
                    <?php echo get_the_post_thumbnail(get_the_ID()); ?>
                    <div class="card-body">
                        <?php
                            $post = get_post();
                            if ($post->post_parent != 0)  echo '<div><a href="'.get_permalink($post->post_parent).'">'.get_the_title( $post->post_parent ).'</a></div>';
                            if (!empty(get_field( "coast" ))) echo '<div>Стоимость: '.get_field( "coast" ).' руб.</div>';
                            if (!empty(get_field( "square" ))) echo '<div>Площадь (м2): '.get_field( "square" ).'</div>';
                            if (!empty(get_field( "living_square" ))) echo '<div>Жилая площадь (м2)'.get_field( "living_square" ).'</div>';
                            if (!empty(get_field( "floor" ))) echo '<div>Этаж: '.get_field( "floor" ).'</div>';
                            if (!empty(get_field( "address" ))) echo '<div>Адрес: '.get_field( "address" ).'</div>';
                        ?>
                        <div class="card-title"><?php the_title(); ?></div>
                        <p class="card-text"><?php  the_content();  ?></p>
                    </div>

                </div>
        </div>
    </div>
</main>

<?php
get_footer();
?>
