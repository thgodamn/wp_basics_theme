<?php
echo '<h2>Города</h2>';
$paged = 0;
query_posts(array('post_type' => 'city', 'posts_per_page' => '10', 'paged' => $paged )); //первая загрузка страницы
?>

<section>
<?php
$count = 0;
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
                    <p class="card-text"><?php the_excerpt(); ?>
                        <?php if (isset(get_the_terms( get_the_ID(), 'estate_type' )[0]->name)) { ?> <span class="badge"><?php echo get_the_terms( get_the_ID(), 'estate_type' )[0]->name; } ?></span>
                    </p>
                    <!--<a class="" class="btn btn-primary" href="<?php the_permalink() ?>">
                        Подробнее
                    </a>-->
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
