<?php
// Loop code
?>
<?php
/**
 * shortcode for listing Event posts
 * 
 */
add_shortcode('upcoming_event', 'upcoming_event');

function upcoming_event($atts, $content = null) {
    ob_start();
    $atts = shortcode_atts(
            array(
        'post_type' => 'post',
        'category_name' => '',
        'initial_posts' => '4',
        'loadmore_posts' => '4',
            ), $atts, $tag
    );
    $additonalArr = array();
    $additonalArr['appendBtn'] = true;
    $additonalArr["offset"] = 0;
    
    ?>
    <div class="AllPostsWrapper latest_posts_wrapper"> 
        <input type="hidden" name="PostType" value="<?= $atts['post_type'] ?>"/>
        <input type="hidden" name="categoryName" value="<?= $atts['category_name'] ?>"/>
        <input type="hidden" name="offset" value="<?= $atts['initial_posts'] - $atts['loadmore_posts'] ?>"/>
        <input type="hidden" name="loadMorePosts" value="<?= $atts['loadmore_posts'] ?>"/>

        <div class="Wrapper">
            <?php GetPostsFtn($atts, $additonalArr); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function GetPostsFtn($atts, $additonalArr = array()) {
    $today = date('Ymd');
    $args = array(
        'post_type' => $atts['post_type'],
        'posts_per_page' => $atts['initial_posts'],
        'offset' => $additonalArr["offset"],
        'category_name' => $atts['category_name'],
        'meta_query' => array(
            array(
                'key' => 'event_start_date',
                'compare' => '>=',
                'value' => $today,
            ),
        ),
        'orderby' => 'key',
        'order' => 'ASC',
    );
     
    $the_query = new WP_Query($args);
    $havePosts = true;
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
            <div class="loadMoreRepeat">
                <?php get_template_part('event/event_content', 'part'); ?>
            </div>
            <?php
        }
    } else {
        $havePosts = false;
    }
    wp_reset_postdata();
    if ($havePosts && $additonalArr['appendBtn']) {
        ?>
        <div class="btnLoadmoreWrapper">
            <a href="javascript:void(0);" class="btn btn-primary LoadMorePostsbtn">Load More</a>
        </div>

        <!-- loader for ajax -->
        <div class="LoaderImg" style="display: none;">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" style="
                 color: #ff7361;">
                <path fill="#ff7361" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
                </path>
            </svg>
        </div>

        <p class="noMorePostsFound" style="display: none;">No More Posts Found</p>
        <?php
    }
}
?>

<?php
add_action("wp_ajax_AjaxLoadMorePostsAjaxReq", "AjaxLoadMorePostsAjaxReq");
add_action("wp_ajax_nopriv_AjaxLoadMorePostsAjaxReq", "AjaxLoadMorePostsAjaxReq");

function AjaxLoadMorePostsAjaxReq() {


    extract($_POST);
    $additonalArr = array();
    $additonalArr['appendBtn'] = false;
    $additonalArr["offset"] = $offset;
    $atts["initial_posts"] = $loadMorePosts;
    $atts["post_type"] = $postType;
   $atts["category_name"] = $category_name;
//   var_dump($_POST);
//   var_dump($atts);
 

    GetPostsFtn($atts, $additonalArr);
    die();
}
