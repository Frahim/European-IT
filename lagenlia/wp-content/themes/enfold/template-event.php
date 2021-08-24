<?php
/*
  Template Name: Event Page
 */
if (!defined('ABSPATH')) {
    die();
}

global $avia_config, $post;


/*
 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
 */
get_header();

echo '<h2>' . the_title() . '</h2>';
?>



<div class="contaner">
    <?php 
    
//    function dump_extracted_post() {
//    extract($_POST);
//    var_dump(get_defined_vars());
//  }
//  dump_extracted_post();

    ?>

    </div>

<?php
get_footer();
