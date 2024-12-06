<?php
/*
Plugin Name: Copy Move posts
Author: Ujjaval Jani
Version: 1.6
Description: You can Copy or Move posts from one Post type to another Post type with custom field,thumbnail and meta information.
License: GPLv3
*/
ini_set('max_execution_time', 0);
function CPMV_my_admin_menu() {
	add_menu_page( 'Copy Move Posts', 'Move or Copy', 'manage_options', 'copy-move-posts/copy-move-posts-admin-page.php', '', 'dashicons-tickets','61' );
}
add_action( 'admin_menu', 'CPMV_my_admin_menu' );

function CPMV_get_posts_types()
{
	$get_cpt_args = array(
		'public'   => true,
	);
	$post_types=get_post_types($get_cpt_args,'object');
	unset($post_types['attachment']);
	return $post_types;
}
add_filter('CPMV_get_posts_types','CPMV_get_posts_types');

function CPMV_convert_post()
{
	
	if(isset($_POST['change_post']) && filter_var($_POST['change_post'], FILTER_SANITIZE_STRING)=='Complete')
	{	unset($_POST['change_post']);
		if(isset($_POST['need_count']))
		{
			if($_POST['need_count']=='' || $_POST['need_count']<'-1')
			{
				$pagination=-1;
			}
			else
			{
				$pagination=$_POST['need_count'];
			}
		}
		else
		{
			$pagination=-1;
		}
		if(filter_var($_POST['cmp'], FILTER_SANITIZE_STRING)==1) ///Copy posts
		{	
			global $wpdb;
			$args = array('post_type'=> filter_var($_POST['post_from'],FILTER_SANITIZE_STRING),'posts_per_page'=> filter_var($pagination,FILTER_SANITIZE_STRING));
			$the_query = new WP_Query( $args );
			
			foreach ($the_query->posts as $posts) {

				$post_meta=get_post_meta( $posts->ID, $key = '', $single = false );
				unset($post_meta['_edit_lock']);
				unset($post_meta['_edit_last']);
				//exit();

				//print_r($post_meta);
				//$attach_id=$post_meta['_thumbnail_id'];
				//print_r($post_meta);
				
				//echo get_bloginfo('version');
		        //add_post_meta($post_id,'Archived','testsst');
				if(get_bloginfo('version')>='4.4.0')
				{	 
					$meta_info=array();
				 	foreach ($post_meta as $key => $value) {
				 		//echo $key.'='.$value[0].',';
				 		$meta_info[$key]=$value[0];
				 	}
					$my_post = array(
					    'post_title'    		=> $posts->post_title,
					    'post_content'  		=> $posts->post_content,
					    'post_excerpt'  		=> $posts->post_excerpt,
					    'post_status'   		=> $posts->post_status,
					    'post_author'   		=> get_current_user_id(),
					    'comment_status'   		=> $posts->comment_status,
					    'post_type' 			=> filter_var($_POST['post_to'],FILTER_SANITIZE_STRING),
					    'meta_input'			=>$meta_info,
					);
					//print_r($my_post);
					//exit();
					$post_id=wp_insert_post( $my_post , true);	
				}
				else
				{
					$my_post = array(
					    'post_title'    		=> $posts->post_title,
					    'post_content'  		=> $posts->post_content,
					    'post_excerpt'  		=> $posts->post_excerpt,
					    'post_status'   		=> $posts->post_status,
					    'post_author'   		=> get_current_user_id(),
					    'comment_status'   		=> $posts->comment_status,
					    'post_type' 			=> filter_var($_POST['post_to'],FILTER_SANITIZE_STRING),
					);
					$post_id=wp_insert_post( $my_post , true);	

					foreach ($post_meta as $key => $value) {
						add_post_meta($post_id,$key,$value[0]);
					}
					
				}
			}
		}
		elseif(filter_var($_POST['cmp'], FILTER_SANITIZE_STRING)==2) ///Move posts
		{
			global $wpdb;
			$args = array('post_type'=> filter_var($_POST['post_from'],FILTER_SANITIZE_STRING),'posts_per_page'=> filter_var($pagination,FILTER_SANITIZE_STRING));
			$the_query = new WP_Query( $args );
			//echo "<pre>";
			//print_r($the_query);
			foreach ($the_query->posts as $posts) 
			{
				//print_r($posts);
					$my_post = array(
					    'ID'    				=> $posts->ID,
					    'post_type' 			=> filter_var($_POST['post_to'],FILTER_SANITIZE_STRING),
					);
					$post_id=wp_update_post( $my_post , true);	
			}

		}
	}
}
add_action('init', 'CPMV_convert_post');
