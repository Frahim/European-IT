<?php

//Event Shortcode

function recent_posts_function($atts) {
    extract(shortcode_atts(array(
        'posts' => 4,
                    ), $atts));

    $return_string = '<hr class="rcp_img_bg"/><div id="galleries-list"><ul>';
    $posts = query_posts(array('post_type' => 'post', 'orderby' => 'date', 'order' => 'ASC', 'showposts' => $posts));
    global $avia_config, $post;
    if (have_posts()) :
        while (have_posts()) : the_post();
            $image = get_field('evicon');
            $year = strtotime(get_field('event_start_date'));

            $return_string .= '<li><div class="slideContent"><div class="ev_wrap"><div class="evicon"><img src="' . $image . '" alt="' . get_the_title() . '" /></div><h3>' . get_the_title() . '</h3><p>' . date_i18n("Y", $year) . '</p></div></div></li>';
        endwhile;
    endif;
    $return_string .= '</ul></div>';
    $return_string .= '<ul id="indicators">';
    if (have_posts()) :
        $item_count = 0; // Start up a count
        foreach ($posts as $post) { // Loop through the array            
            $return_string .= ' <li data-target="#galleries-list" data-slide-to="' . $item_count . ' "><span class="dot"></span> </li>';
            $item_count++;
        }
    endif;
    $return_string .= '</ul>';
    wp_reset_query();
    return $return_string;
}

function register_shortcodes() {
    add_shortcode('recent-posts', 'recent_posts_function');
}

add_action('init', 'register_shortcodes');

// Filter Shortcode
function event_filter_shortcode() {
    ob_start();
    ?>    
    <div class="filter-wrap">
        <div class="category">
            <div class="field-title">Category</div>
    <?php $get_categories = get_categories(array('hide_empty' => 0)); ?>
            <select class="js-category">
                <option value="all">Select Category</option>
            <?php
            if ($get_categories) :
                foreach ($get_categories as $cat) :
                    ?>
                        <option value="<?php echo $cat->term_id; ?>">
                        <?php echo $cat->name; ?>
                        </option>
                            <?php
                        endforeach;
                    endif;
                    ?>
            </select>
        </div>
        <div class="date">
            <div class="field-title">Select Month</div>
            <select class="js-date" id="month" name="month" autocomplete='off'>
                <option value="all">Select Month</option>
    <?php
    // Create a dropdown for months using a php foreach.
    $monthArray = range(1, 12);
    foreach ($monthArray as $month):
        $monthCode = str_pad($month, 2, "0", STR_PAD_LEFT);
        $selected = ($monthCode === date('m')) ? 'selected' : '';
//All months start with 1
        $date = new DateTime(date("Y-") . $monthCode . "-01");
        print '<option ' . $selected . ' value="' . $monthCode . '">' . $date->format('F') . '</option>';
    endforeach;
    wp_reset_postdata();
    ?>
            </select>

        </div>
        <div class="location">
    <?php
    global $avia_config, $post;
    $args = array(
        'post_type' => 'post',
        'meta_key' => 'event_location',
        'order' => 'ASC',
    );
    $the_query = new WP_Query($args);
    ?>
            <div class="field-title">Venue:</div> 
            <select class="js-location">
                <option>Select Location</option>
    <?php
    while ($the_query->have_posts()) : $the_query->the_post();
        $city = get_field('event_location');
        // only create option if city hasn't been added yet
        if (!in_array($city, $unique_cities)) :
            // add city to array so it doesn't repeat
            $unique_cities[] = $city;
            ?>
                        <option><?php echo $city; ?></option>
                        <?php
                    endif;
                endwhile;
                ?>
            </select>
        </div>

    </div>
    <div class="filtered-posts"></div>

    <?php
    // Reset query to prevent conflicts


    $output = ob_get_clean();
    return $output;
}

add_shortcode('event_filter', 'event_filter_shortcode');

// Ajax filter

function ajax_filterposts_handler() {
    $category = esc_attr($_POST['category']);
    $location = esc_attr($_POST['location']);
    $date = date("Y") . $_POST['date'];


    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'event_start_date',
                'compare' => 'REGEXP',
                'value' => '^' . $date . '[0-9]{2,4}$',
            ),
            array(
                'key' => 'event_location',
                'compare' => 'REGEXP',
                'value' => $location,
            ),
        )
    );

    if ($category != 'all')
        $args['cat'] = $category;

    $posts = 'No Event found.';
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
        ob_start();

        while ($the_query->have_posts()) : $the_query->the_post();
            get_template_part('event/event_content', 'part');
        endwhile;

        $posts = ob_get_clean();
    endif;

    $return = array(
        'posts' => $posts
    );
    wp_send_json($return);
}

add_action('wp_ajax_filterposts', 'ajax_filterposts_handler');
add_action('wp_ajax_nopriv_filterposts', 'ajax_filterposts_handler');


