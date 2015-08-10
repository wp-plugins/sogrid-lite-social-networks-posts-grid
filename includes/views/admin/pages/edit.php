<?php
if(!defined('ABSPATH'))die('');
$pageName = __('Name your SoGrid',"wp_my_sogrid_domain");
$my_image_mapper_id=@$_GET['id'];
$my_saved_values=array();
if(!isset($my_image_mapper_id))$my_image_mapper_id='';
if(!empty($my_image_mapper_id)){
	$has_mapper=wp_my_sogrid_is_exist_object($my_image_mapper_id);
	$my_saved_values=array();
		if(empty($has_mapper)){
		?>
		<h4 style="color:red"><?php echo __("SoGrid with this id don't exist.","wp_my_sogrid_domain");?></h4>
		<?php 
		return;
		}
	$my_sogrid_title=wp_my_sogrid_get_object_title($my_image_mapper_id);	
	$my_saved_values=wp_my_sogrid_get_object_and_meta($my_image_mapper_id);
	
}
$file=$template_dir.'elements/my_networks_pre_form.php';
require $file; 
/*
 * changes 1.20.2015.
 */
//wp_my_sogrid_delete_cache_files(7);
/*
 * tested ok delete files
 */
//echo phpversion();
?>


<div class="wrap imapper-admin-wrapper">
	
	<div class="form_result"></div>
	<form name="post_form"  method="post" id="post_form"><!-- items in this form should be saved -->
		
		<input type="hidden" id="plugin-url" value="<?php echo $this->url; ?>" />
		
		<input type="hidden" name="my_save_nonce" id="my_save_nonce_id" value="<?php $str='my_sogrid_save_'.get_current_user_id();echo wp_create_nonce($str);?>"/>
		
		<input type="hidden" name="sogrid_id" id="sogrid_id" value="<?php echo $my_image_mapper_id; ?>" />
		<div class="imapper_items_options">
			
			<?php
			/**
			 * Add active networks
			 */
			if(isset($my_saved_values['active_social'])){
				foreach($my_saved_values['active_social'] as $k=>$v){
					?>
					<input type="hidden" value="<?php echo esc_attr($v);?>" name="my_active_networks[]">
				<?php 	
				}
			} 
			/**
			 * Set saved pins positions
			 */
			/*if(isset($pins)){
				if(!empty($pins)){
					foreach($pins as $k=>$v){
						$id_pin=$k+1;
					?>
					<input type="hidden" id="sort<?php echo $id_pin;?>-imapper-item-x" name="sort<?php echo $id_pin;?>-imapper-item-x" value="<?php echo $v['pos_x'];?>" />
					<input type="hidden" id="sort<?php echo $id_pin;?>-imapper-item-y" name="sort<?php echo $id_pin;?>-imapper-item-y" value="<?php echo $v['pos_y'];?>" />
			
					<?php 
					}
				}
			}*/
			
			?>
		</div>
		<div id="poststuf">
	
			<div id="post-body" class="metabox-holder columns-1" style="padding:0;">
				<div id="post-body-content">
				
				<div id="titlediv">
					<div id="titlewrap">
					<h2 class="imapper-backend-header"><?php echo $pageName; ?>
					<a href="<?php echo admin_url( "admin.php?page=my-sogrid-index" ); ?>" class="add-new-h2"><?php echo __("Cancel","wp_my_sogrid_domain");?></a>
				</h2>
						<label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php echo __("Enter title Here","wp_my_sogrid_domain");?></label>
						<input type="text" data-my-required="true" name="image_mapper_title" size="30" tabindex="1" value="<?php if(isset($my_sogrid_title))echo esc_attr($my_sogrid_title);//if(isset($title))echo $title; ?>" id="title" autocomplete="off" />
					</div>
				</div>
				
				
				<div class="clear"></div>
		
				
			
				<div class="my_general_social_options postbox" style="margin-right:300px">
				<h2 class="imapper-backend-header"><?php echo __("Social SoGrid Options","wp_my_sogrid_domain");; ?></h2>	
					<div class="inside">
					<ul class="my_ul_radio_list">
					<?php 
					
					foreach($social_modules as $k=>$v){
						$v['type']='on_off';
						$v['default']=0;
						/**
						 * Add values to gnerela
						 */
						if(isset($my_saved_values['active_social'])){
							if(in_array($k,$my_saved_values['active_social'])){
								$v['value']=1;
							}
							//$v['value']=$general[$k];
						}
						?>
						<li ><label for="<?php echo $k;?>"><?php echo $v['title'];?></label>
						<br/><br/>
						<div class="my_form_element">
						<?php Class_My_Module_Form_Static::render_element($k,$v);?>
						</div>
						</li>
						<?php 
						
					}
					?>
					</ul>
					<div class="my_img_12_12">
					<a href="http://codecanyon.net/item/sogrid-wordpress-grid-for-social-stream/10919286?utm_source=Lite2ProUpgrade&utm_medium=wpRepository&utm_campaign=SoGrid"><img src="<?php echo MY_WP_SOGRID_IMAGES_URL.'my_links.jpg'?>"/></a>
					</div>
					</div>
			<div class="my_save_preview_options">			
			<div class="postbox">
					<h2 class='imapper-backend-header' style="cursor:auto"><span><?php echo __("Publish SoGrid","wp_my_sogrid_domain");?></span></h2>
					<div class="inside">
						<div style="padding-top:30px">
						<div id="save-progress" class="waiting ajax-saved" style="background-image: url(<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>)" ></div>
						<input name="preview-timeline" id="preview-timeline" value="<?php echo __("Preview","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:3px 25px" type="submit" />
						<input name="save-timeline" id="save-timeline" value="<?php echo __("Save SoGrid","wp_my_sogrid_domain");?>" class="alignright add-new-h2" style="padding:3px 15px" type="submit" />
						<img id="save-loader" src="<?php echo MY_WP_SOGRID_IMAGES_URL;?>ajax-loader.gif" class="alignright" />
						<br class="clear" />		
						</div>
					</div>
			</div>
			<div class="postbox my_banner">
				<div class="inside">
				<a href="http://codecanyon.net/item/sogrid-wordpress-grid-for-social-stream/10919286?utm_source=Lite2ProUpgrade&utm_medium=wpRepository&utm_campaign=SoGrid"><img src="<?php echo MY_WP_SOGRID_IMAGES_URL.'my_baner.jpg'?>"/></a>
				</div>	
			</div>
			</div>
			
			</div><!-- imapper general options  -->
			<div class="clear"></div>
			
			<?php /*changes 1.20.2015. general options
				   */	
			?>
			<div class=" postbox" style="margin-right:300px">
					<h2 class='imapper-backend-header' style="cursor:auto"><span><?php echo __("General SoGrid Options","wp_my_sogrid_domain");?></span></h2>
					<div class="inside">
						<div style="padding-top:30px">
						<ul  class="my_ul_radio_list">
						<?php 
						global $wp_my_sogrid_genereal_sogrid_options;
						foreach($wp_my_sogrid_genereal_sogrid_options as $k=>$v){
								$my_item=$v;
								$my_item['id']='my_general_sogrid_'.$k;
								$my_name='my_general_option_'.$k;
								if(isset($my_saved_values['general_options'][$k])){
									$my_item['value']=$my_saved_values['general_options'][$k];
								}
							?>
							<li class="">
								<label for="<?php echo $my_item['id'] ?>"><?php echo $my_item['title'];?>
								<?php if(isset($my_item['tooltip'])){?>
									<div class="my_tooltip_form"><div class="my_tooltip_content">
									<?php echo $my_item['tooltip'];?>
									</div>
									</div>
								<?php }?>
								</label>
								<br/>
								<br/>
								<?php Class_My_Module_Form_Static::render_element($my_name, $my_item);?>
								
							</li>
							
							<?php 
						}
						?>
						</ul>
						</div>
					</div>	
			</div>
			<?php 
			/*
			 * end
			 */
			?>
			<div class="clear"></div>
			<?php /*
			<!-- Image part begins -->
				<div class="map-wrapper">
				<h2 class="imapper-backend-header" style="padding:0 0 10px 0;"><?php echo __("Woo Mapper Image","wp_my_sogrid_domain");?><a href="#" id="map-change" style="display:inline;" class="tsort-change add-new-h2"><?php echo __("Change Image","wp_my_sogrid_domain");?></a></h2>
					<div class="mapper-sort-image-wrapper">
					<div class="mapper-sort-image">
						<div class="my-mapper-sort-image">
						<img id="map-image" src="<?php if(isset($my_mapper_image_src))echo $my_mapper_image_src[0];else echo $this->url . 'images/no_image.jpg'; ?>" />
						<input id="map-input" name="map-image" type="hidden" value="<?php if(isset($my_mapper_image_src)) echo esc_attr($my_mapper_image_src[0]); ?>" />
						<input id="map-input-id" name="map-image-id" type="hidden" value="<?php if(isset($my_mapper_image_id))echo $my_mapper_image_id;?>" />
						
						<a href="#" id="map-image-remove" class="tsort-remove"><?php echo __("Remove","wp_my_sogrid_domain");?></a>
						</div>
					</div>
					<div style="clear:both;"></div>
					</div>
				</div>
				<!-- Image part ends, items begin -->
			<div class="clear"></div>
			*/ ?>
				<?php /*
				<div class="my_use_custom_pins">
					<h2 class="imapper-backend-header" style="padding:0 0 10px 0;"><?php echo __("Pin Icon","wp_my_sogrid_domain");?></h2>
					<div class="clear"></div>
					<ul id="imapper-sortable-items-new" class="imapper-sortable-new">
					<li class="imapper-sortableItem-new">
						<div class="dummy-adapter-new <?php //if($id_pin!=1)echo 'closed'?>" style="display:block;<? //if($id_pin!=1)echo 'display: none;';else echo 'display:block';?>">
					
							<a class="tsort-change add-new-h2" style="width:200px" id="icon-change" href="#"><?php echo __("Change pins Icons","wp_my_sogrid_domain");?></a>
							
						</div>
					</li>
					
					</ul>
				</div>
				*/   ?>`
			
				<div class="items">
					<h2 class="imapper-backend-header" style="padding:0 0 10px 0;"><?php echo __("Active Social Networks","wp_my_sogrid_domain");?></h2>
					<div class="clear"></div>
					<ul id="imapper-sortable-items" class="imapper-sortable">
					<?php
					if(!empty($my_saved_values['active_social'])){
						foreach($my_saved_values['active_social'] as $k=>$v){
							$my_values=$my_saved_values[$v];
							$network=$v;
							//print_r($my_saved_values[$v]);
							$values=$social_modules[$v];
							?>
							<li data-my-id="<?php echo $v;?>" id="my_social_network_li_<?php echo $v;?>" class="imapper-sortableItem">
							<?php 
								$file=MY_WP_SOGRID_VIEWS_DIRNAME.'elements/my_network.php';
								require $file;
								?>
							</li>
						<?php 		
						}
					} 
					/*if(isset($saved_social_networks)){
						$file=MY_WP_SOGRID_VIEWS_DIRNAME.'elements/my_network.php';
						require $file;
					}*/
					?>
					</ul>
				</div>
				<div class="clear"></div>
		</div>
		</div>
		<div class="clear"></div>
		</div>
		
		
	</form>
	<div class="my_inactive_so_networks" style="display:none">
		
	</div>
	<?php 
	/*
	 * changes add dialog window
	 */
	?>
	<div class="my_dialog_preview" title="<?php echo __("Preview SoGrid!","wp_my_sogrid_domain");?>">
		<div class="my_loading" style="width:16px;height:16px;margin:auto">
			<img id="my-save-loader" src="<?php echo MY_WP_SOGRID_IMAGES_URL;?>ajax-loader.gif" class="" />
						
		</div>
		<iframe src="" width="100%" height="450" style="margin:auto"></iframe>
	</div>
	<?php 
	/*
	 * end changes
	 */
	?>
</div>				
	
<?php /*	
<div id="poststuf">
	
		<div id="post-body" class="metabox-holder columns-2" style="margin-right:300px; padding:0;">
		
			<div id="post-body-content">
				
				<div id="titlediv">
					<div id="titlewrap">
					<h2 class="imapper-backend-header"><?php echo $pageName; ?>
		<a href="<?php echo admin_url( "admin.php?page=woo-imagemapper" ); ?>" class="add-new-h2"><?php echo __("Cancel","wp_my_sogrid_domain");?></a>
	</h2>
						<label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php echo __("Enter title Here","wp_my_sogrid_domain");?></label>
						<input type="text" name="image_mapper_title" size="30" tabindex="1" value="<?php echo $title; ?>" id="title" autocomplete="off" />
					</div>
				</div>
				
				
				<div class="clear"></div>
				<!-- Image part begins -->
				<div class="map-wrapper">
				<h2 class="imapper-backend-header" style="padding:0 0 10px 0;">Map <a href="#" id="map-change" style="display:inline;" class="tsort-change add-new-h2">Change</a></h2>
					<div class="mapper-sort-image-wrapper">
					<div class="mapper-sort-image">
						<img id="map-image" src="<?php if(isset($settings['map-image']))echo $settings['map-image'];else echo $this->url . 'images/no_image.jpg'; ?>" />
						<input id="map-input" name="map-image" type="hidden" value="<?php if(isset($settings['map-image'])) echo esc_attr($settings['map-image']); ?>" />
						<input id="map-input-id" name="map-image-id" type="hidden" value="<?php if(isset($settings['map-image-id']))echo $settings['map-image-id']; ?>" />
						
						<a href="#" id="map-image-remove" class="tsort-remove">Remove</a>
					</div>
					<div style="clear:both;"></div>
					</div>
				</div>
				<!-- Image part ends, items begin -->
				<div class="clear"></div>
				<div class="items">
					<h2 class="imapper-backend-header" style="padding:0 0 10px 0;">Active Pins</h2>
					<div class="clear"></div>
					<ul id="imapper-sortable-items" class="imapper-sortable">
					
					</ul>
				</div>
				<div class="clear"></div>
				
			</div>
		</div>
		<?php 
		$file=MY_WOO_IMAGE_MAPPER_VIEWS_DIRNAME.'elements/my_mapper_options.php';
		include_once $file;
		?>
	</div>
	</form>
</div>
*/ ?>						