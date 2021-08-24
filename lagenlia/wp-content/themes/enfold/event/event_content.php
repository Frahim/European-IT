<?php
//Event Content
?>
<article id="post-id-<?php the_id(); ?>">

    <div class="evleftPart">
        <?php
        $date_string = strtotime(get_field('event_start_date'));
        echo '<span class="day">' . date_i18n('d ', $date_string) . '</span><span class="daymy">' . date_i18n('M', $date_string) . '</br>' . date_i18n('Y', $date_string) . '</span>';
        ?>

        <span class="title"> <?php the_title(); ?><spn class="cat_name"><?php $categories = get_the_category();
        echo $categories[0]->name; ?></spn></span>
        <div>
            <a class="ancor" href="<?php echo the_permalink(); ?>">Visit</a><br/>
            <a class="ancor" href="/lagenlia/contact/?event=<?php the_title(); ?>">Book</a>
        </div>
    </div> 
</article>