<?php
/*
Plugin Name: Gr80 JWPlayer Plugin Helper Panel
Version: 0.2
Plugin URI: http://blog.gr80.net
Description: JWPlayer Panel registers a meta box in your Post Writing Panel, where you enter your video title/url/thumbnail, and get those displayed automatically before the post content using the official JWPlayer ( Must be installed prior to using this plugin ).
Author: Shady Sharaf <shady@gr80.net>
Author URI: http://blog.gr80.net

Copyright   (email: shady@gr80.net)

/** TODO - Add Multiple Videos
/** TODO - Provide Options page to modify default options / Choose from JWPlayer setups
/** TODO - Automatic Thumbnail Creation

*/

class GR80_JWPH
{

	function __construct() 
	{
	if(is_admin()):
		add_action('admin_menu', array($this, 'register'));
		add_action('save_post', array($this, 'metasave'));
	else:
		add_action('the_content', array($this, 'display'));
	endif;
	}

	function register()
	{
    add_meta_box('jwph', 'JWPlayer Video Details', array($this, 'metabox'), 'post', 'normal', 'high');
    add_meta_box('jwph', 'JWPlayer Video Details', array($this, 'metabox'), 'page', 'normal', 'high');
	}	
	
	function metabox() 
	{
	  global $post;
	  $jwph = get_post_meta($post->ID, 'jwph_video', true);
	  $jwph = explode('|', $jwph);
	?>
		<div class="box-state">
		
			<p>Title</p>
			<input type="text" name="jwph_video_title" id="jwph_video_title" class="widefat" value="<?php echo $jwph[0] ?>" />
			
			<p>URL</p>
			<input type="text" name="jwph_video_url" id="jwph_video_url" class="widefat" value="<?php echo $jwph[1] ?>" />
			
			<p>Thumb URL</p>
			<input type="text" name="jwph_video_thumb" id="jwph_video_thumb" class="widefat" value="<?php echo $jwph[2] ?>" />
			
		</div>
	<?php
	}
	
	function metasave($post_id) 
	{
	  global $post;
	  $jwph = array_intersect_key($_POST, array_flip(array('jwph_video_title', 'jwph_video_url', 'jwph_video_thumb')));
	  $jwph = implode('|', $jwph);
		update_post_meta($post_id, 'jwph_video', $jwph);
	}
	
	function display($content)
	{
		global $post;
		$jwph = get_post_meta($post->ID, 'jwph_video', true);
		list($title, $url, $thumb) = explode('|',$jwph);
		$obj = jwplayer_tag_callback("[jwplayer file=\"$url\" image=\"$thumb\"]");
		$content = $obj.$content;
		return $content;
	}
}	

$gr80_jwph = new GR80_JWPH;
