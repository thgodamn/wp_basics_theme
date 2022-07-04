<?php
/*
Template Name: Шаблон (Недвижимость + Города)
*/
get_header();
?>

<main class="mt-4">
    <div class="container">
        <!--Grid row-->
                <?php
                include( 'archive-city.php' );
                include( 'archive-estate.php' );
                ?>
                <!--Section: Reply-->
                <section>
                    <p class="text-center"><strong>Отправить недвижимость</strong></p>
                    <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="estate_add">
                                <input class="form-outline mb-4 form-control" name="coast" value="" placeholder="coast">
                                <input class="form-outline mb-4 form-control" name="square" value="" placeholder="square">
                                <input class="form-outline mb-4 form-control" name="address" value="" placeholder="address">
                                <input class="form-outline mb-4 form-control" name="living_square" value="" placeholder="living_square">
                                <input class="form-outline mb-4 form-control" name="floor" value="" placeholder="floor">
                                <input class="form-outline mb-4 form-control" name="image" type="file">
                                <button class="btn btn-primary btn-block mb-4" type="submit">Применить фильтры</button>
                                <input type="hidden" name="action" value="estate_add">
                                <?php wp_nonce_field( 'estate_form_nonce', 'form_nonce' ); ?>
                                <div class="form-result" style="display: none;"></div>
                    </form>

                </section>
    </div>
</main>

<script>
    (function($){

        $('#estate_add').submit(function(e){
            e.preventDefault();
            var filter = $(this);

            var form_data = new FormData($(this)[0]);
            form_data.append('file', filter.find('input[name=image]').prop('files')[0]);

            $.ajax({
                url:$(this).attr('action'),
                //data:$(this).serialize(),
                data: form_data,
                processData: false,
                contentType: false,
                type:$(this).attr('method'), // POST
                beforeSend:function(xhr){
                    filter.find('button').text('Загрузка...'); // changing the button label
                },
                success:function(data){
                    //console.log(data);
                    if (data=='success') {
                        $('.form-result').attr('style','display: block; color: green;').html('Отправлено!');
                        filter.find('button').text('Применить фильтры');
                    } else if (data=='denied') {
                        filter.find('button').text('Применить фильтры'); // changing the button label back
                        $('.form-result').attr('style','display: block; color: red;').html('Данные введены не корректно, заполните поля.');
                    }
                }
            });
            return false;
        });
    })(jQuery);
</script>
<?php get_footer(); ?>
