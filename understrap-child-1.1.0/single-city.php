<?php
get_header();
//$estates = get_posts(array( 'post_type'=>'estate', 'post_parent'=>get_the_ID(), 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));
//var_dump($estates);
?>

<?php
$paged = 0;
query_posts(array('post_type' => 'estate', 'posts_per_page' => '10', 'paged' => $paged, 'meta_query'=> array(
    array(
        'key' => 'parent_city',
        'compare' => '=',
        'value' => get_the_ID(),
        //'type' => 'numeric',
    )
) )); //первая загрузка страницы
?>

    <main class="mt-4">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                        <?php echo get_the_post_thumbnail(get_the_ID()); ?>
                        <div class="card-body">
                            <div class="card-title">Город: <?php the_title(); ?></div>
                            <p class="card-text"><?php  the_content();  ?></p>
                        </div>

                    </div>
            </div>
            <hr>

            <section>
                <?php
                $count=0;
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        if ($count % 3 == 0 && $count >= 0) echo '<div class="row mb-3">';
                        ?>
                        <div class="col-sm-4 p-2">
                            <div class="card">
                                <?php the_post_thumbnail();?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php the_title(); ?></h5>
                                    <p class="card-text"><?php the_content(); ?>
                                        <?php if (isset(get_the_terms( get_the_ID(), 'estate_type' )[0]->name)) { ?> <span class="badge"><?php echo get_the_terms( get_the_ID(), 'estate_type' )[0]->name; } ?></span>
                                    </p>
                                    <?php
                                    if (!empty(get_field( "coast" ))) echo '<div>Цена: '.get_field( "coast" ).'</div>';
                                    if (!empty(get_field( "square" ))) echo '<div>Площадь (м2): '.get_field( "square" ).'</div>';
                                    if (!empty(get_field( "living_square" ))) echo '<div>Жидая площадь (м2): '.get_field( "living_square" ).'</div>';
                                    if (!empty(get_field( "floor" ))) echo '<div>Этаж: '.get_field( "floor" ).'</div>';
                                    if (!empty(get_field( "address" ))) echo '<div>Адрес: '.get_field( "address" ).'</div>';
                                    ?>
                                    <a class="" class="btn btn-primary" href="<?php the_permalink() ?>">Подробнее</a>
                                </div>
                            </div>
                        </div>
                        <?php
                        $count++;
                        if ($count % 3 == 0 && $count >= 3) echo '</div>';
                    }
                }
                ?>
            </section>
        </div>
    </main>
<?php
get_footer();
?>