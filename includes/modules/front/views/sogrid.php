<?php
if(!defined('ABSPATH'))die('');
/*
 * changes 1.25.2015.
 * added sogrid id
 */
//$text='Excited to announce that the Windows Developer Program for IoT will deliver Windows 10 support for Raspberry...';
//echo strlen($text);

?>
<?php /*
<div style="display:none">
	<fb:login-button onLogin="my_facebook_login_<?php echo  $id;?>">
	</fb:login-button>
</div>
*/ 
global $my_sogrid_disable_borders_12345;
$dynamic_sogrid=$my_so_grid_options['general_options']['dynamic'];
$my_preview_12345_12=@$_GET['my_preview_sogrid'];
if($my_preview_12345_12==1){
	$my_has_api_errors_12=false;
	if(!empty($my_api_errors_12)){
		?>
		<div class="my_api_errors_12">
			<h2><?php echo __("Api errors","wp_my_sogrid_domain");?></h2>
			<ul>
		<?php 
		//print_r($my_api_errors_12);
		foreach($my_api_errors_12 as $key=>$val){
			if(!empty($val)){
			?>
			<li><h4><?php echo wp_my_sogrid_get_network_name_12($key)?></h4></li>
			<?php 
			foreach($val as $k=>$v){
			?>
			<li>
			<h4><?php echo __("Error for a id - ","wp_my_sogrid_domain");?><?php echo $k?></h4>
			<label><?php echo $v['msg'].' - '.$v['msg_1']?></label>
			</li>
			<?php 
			$my_has_api_errors_12=true;
			}
			}
		}
		?>
		</ul>
		<?php if(!$my_has_api_errors_12){?>
		<h2><?php echo __("There are no API errors !","wp_my_sogrid_domain");?></h2>
		<?php }?>
		</div>
		<?php 
	}
}
?>
<?php /*<a data-pin-do="embedPin" href="http://www.pinterest.com/pin/58265388906140233 /"></a>
<a data-pin-do="embedPin" href="http://www.pinterest.com/pin/99360735500167749/"></a>
*/ ?>
<div id="my_sogrid_id_<?php echo $sogrid_id?>" data-is-dynamic-loading="<?php echo $is_dynamic_loading;?>" <?php if($is_dynamic_loading){ echo 'data-pages="'.$pages.'" ';echo 'data-total="'.$total.'" ';echo 'data-cache-time="'.$cache_lifetime.'"';}?> class="my_sogrid_container <?php if($is_dynamic_loading_animation==1)echo 'my_sogrid_dynamic_loading_animation'?> <?php if(isset($my_sogrid_disable_borders_12345[$sogrid_id]))echo 'my_sogrid_disabled_borders';?>" data-my-id="<?php echo $sogrid_id;?>">
	<div class="my_sogrid_inner <?php if($dynamic_sogrid==1)echo ' my_sogrid_dynamic';else echo 'my_sogrid_not_dynamic'?>">
		<ul class="my_sogrid_itms" data-my-id="<?php echo $sogrid_id?>">
		<?php echo $so_grid_inner_html;?>
		</ul>
		<?php /*
		<div class="my_button_load_more">
				<a href="#javascript" class="my_load_more_link"><?php echo __("Load More","wp_my_sogrid_domain");?></a>
		</div>
		*/ ?>
		<div class="my_sogrid_loading">
			<h4><?php echo __("Loading more ...  ","wp_my_sogrid_domain");?><img src="<?php echo MY_WP_SOGRID_IMAGES_URL.'ajax-loader.gif'?>"/></h4>
		</div>
	</div>
	<div class="my_sogrid_dialog my_dialog_<?php echo $sogrid_id?>" title="<?php echo __("Video Preview","wp_my_sogrid_domain");?>" style="display: none">
				<div class="my_close_dialog_12_12">
					<i class="fa fa-close"></i>
				</div>	
				<h4><?php echo __("Loading ...  ","wp_my_sogrid_domain");?><img src="<?php echo MY_WP_SOGRID_IMAGES_URL.'ajax-loader.gif'?>"/></h4>
				<div id="my_video_id_12_12"></div>
	
	</div>
</div>