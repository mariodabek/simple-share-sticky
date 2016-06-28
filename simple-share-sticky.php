<?php

// Plugin Name: Flowpress Simple Share Sticky 
// Plugin URI: http://flowpress.ca/
// Description: A simpler way to share your wordpress pages / posts / and custom post type posts.
// Version: 0.0.1
// Author: Mario Dabek, Flowpress
// Requires at least: 3.5
// Tested up to: 4.5.3
// Stable tag: 4.5.3
// License: GPLv2 or later
// License URI: http://www.gnu.org/licenses/gpl-2.0.html

class simple_share_sticky{

    function enqueue_scripts(){
        wp_enqueue_script('simple_share_sticky',plugins_url( '/simple-share-sticky.js' , __FILE__ ) ,array( 'jquery' ), '0.0.1');
        wp_enqueue_style( 'simple_share_sticky', plugins_url( '/simple-share-sticky.css', __FILE__ ), array(), '0.0.1' );
    }

    function setup_menu(){
        add_options_page( 'Simple Share Sticky', 'Share Sticky', 'manage_options', __FILE__, array($this,'simple_share_sticky_config') );
    }

    function simple_share_sticky_config($ajax=false){

        if (isset($_POST['options'])) { 
            update_option('simple_share_sticky_fb', $_POST['share_on_facebook']); 
            update_option('simple_share_sticky_tw', $_POST['share_on_twitter']); 
            update_option('simple_share_sticky_g', $_POST['share_on_google']); 
            update_option('simple_share_sticky_li', $_POST['share_on_linkedin']); 
            update_option('simple_share_sticky_pt', $_POST['share_on_pinterest']); 
            update_option('simple_share_sticky_su', $_POST['share_on_stumbleupon']); 
            update_option('simple_share_sticky_posts', $_POST['share_on_posts']); 
            update_option('simple_share_sticky_pages', $_POST['share_on_pages']); 
            update_option('simple_share_sticky_custom', $_POST['share_on_custom_post_types']); 
            update_option('simple_share_sticky_archive', $_POST['share_on_archive']); 
            update_option('simple_share_sticky_home', $_POST['share_on_home']); 
        }

        $fb_ck = get_option('simple_share_sticky_fb') ? 'checked' : '';
        $tw_ck = get_option('simple_share_sticky_tw') ? 'checked' : '';
        $g_ck = get_option('simple_share_sticky_g') ? 'checked' : '';
        $li_ck = get_option('simple_share_sticky_li') ? 'checked' : '';
        $pt_ck = get_option('simple_share_sticky_pt') ? 'checked' : '';
        $su_ck = get_option('simple_share_sticky_su') ? 'checked' : '';
        $posts_ck = get_option('simple_share_sticky_posts') ? 'checked' : '';
        $pages_ck = get_option('simple_share_sticky_pages') ? 'checked' : '';
        $custom_ck = get_option('simple_share_sticky_custom') ? 'checked' : '';
        $archive_ck = get_option('simple_share_sticky_archive') ? 'checked' : '';
        $home_ck = get_option('simple_share_sticky_home') ? 'checked' : '';

        echo '<div id="ss_settings">';
        echo '<h1><div class="title">Simple Share Sticky Options</div></div></h1>';
        echo "
                <form method='post' id='simple_share_sticky' class='admin'>
                    <input name='options' type='hidden' value='1'>
                    <h2>Select Networks</h2>
                    <label class='icon icon-facebook'>Facebook</label><input type='checkbox' $fb_ck name='share_on_facebook'>
                    <label class='icon icon-twitter'>Twitter</label><input type='checkbox' $tw_ck name='share_on_twitter'>
                    <label class='icon icon-google'>Google</label><input type='checkbox' $g_ck name='share_on_google'>
                    <label class='icon icon-linkedin'>LinkedIn</label><input type='checkbox' $li_ck name='share_on_linkedin'>
                    <label class='icon icon-pinterest'>Pinterest</label><input type='checkbox' $pt_ck name='share_on_pinterest'>
                    <label class='icon icon-stumbleupon'>Stumbleupon</label><input type='checkbox' $su_ck name='share_on_stumbleupon'>
                    <h2>Enable on the following</h2>
                    <label>Posts</label><input type='checkbox' $posts_ck name='share_on_posts'>
                    <label>Pages</label><input type='checkbox' $pages_ck name='share_on_pages'>
                    <label>Custom Post Types</label><input type='checkbox' $custom_ck name='share_on_custom_post_types'>
                    <label>Archive Pages</label><input type='checkbox' $archive_ck name='share_on_archive'>
                    <label>Home Page / Front Page</label><input type='checkbox' $home_ck name='share_on_home'>
                    <input type='submit' value='Save' />
                </form>

        ";
        echo '</div>';    

    }

    function front_end() {
        if (get_option('simple_share_sticky_posts')) if (is_singular('post')) $this->share_buttons();
        if (get_option('simple_share_sticky_pages')) if (is_singular('page')) $this->share_buttons();
        if (get_option('simple_share_sticky_custom')) if (is_singular() && !is_singular('post') && !is_singular('page')) $this->share_buttons();
        if (get_option('simple_share_sticky_archive')) if (is_archive()) $this->share_buttons();
        if (get_option('simple_share_sticky_home')) if (is_home() || is_front_page()) $this->share_buttons();
    }

   function share_buttons() {

        global $post;
        $share_url = get_permalink();
        $title = get_the_title();
        if (has_post_thumbnail( $post->ID ) ):
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); 
            $img = $image[0];
        endif;

        echo '<div id="simple_share_sticky">';
        echo '<div class="heading">Share</div>';
        if (get_option('simple_share_sticky_fb')) $this->show_button('facebook', 'http://www.facebook.com/sharer.php?u='.$share_url, $title, $img);
        if (get_option('simple_share_sticky_tw')) $this->show_button('twitter', 'http://twitter.com/intent/tweet?text='.$title.' - '.$share_url);
        if (get_option('simple_share_sticky_g')) $this->show_button('google', 'https://plus.google.com/share?url='.$share_url);
        if (get_option('simple_share_sticky_li')) $this->show_button('linkedin', 'http://www.linkedin.com/shareArticle?url='.$share_url);
        if (get_option('simple_share_sticky_su')) $this->show_button('stumbleupon', 'http://www.stumbleupon.com/submit?url='.$share_url);
        if (get_option('simple_share_sticky_pt')) $this->show_button('pinterest', 'http://pinterest.com/pin/create/button/?url='.$share_url.'&media='.$img.'&description='.$title);
        echo '</div>';

    }

    function show_button($brand,$share_url) {
        echo "<a title='Share on ".ucfirst($brand)."'' class='social icon-$brand rel='nofollow' href='$share_url'></a>";
    }
    
}

$simple_share_sticky = new simple_share_sticky;

add_action('init', array(&$simple_share_sticky,'enqueue_scripts'), 0);
add_action('admin_menu',array(&$simple_share_sticky,'setup_menu'));
add_action('wp_head',array(&$simple_share_sticky,'front_end'));