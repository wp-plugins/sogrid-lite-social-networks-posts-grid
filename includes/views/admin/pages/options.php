<?php
if(!defined('ABSPATH'))die('');
?>
<?php if(isset($ok_msg)){?>
	<div class="updated">
				<p>
				<?php echo $ok_msg;?>
				</p>
			</div>
<?php }?>
<?php if(isset($error_msg)){?>
	<div class="error">
				<p>
				<?php echo $error_msg;?>
				</p>
			</div>
<?php }?>
<div class="wrap imapper-admin-wrapper">
	<h2 class="imapper-backend-header">
	
	<?php echo __("SoGrid Options","wp_my_sogrid_domain");?>
	</h2>
	<form method="post">
		<?php $my_nonce_str='my_save_options_'.get_current_user_id();?>
		<input type="hidden" name="my_nonce" value="<?php echo wp_create_nonce($my_nonce_str);?>"/> 
	<div class="my-option-items">
		<!--  <h2 class="imapper-sort-header-my" style=""><?php echo __("Options","wp_my_sogrid_domain");?></h2>-->
			<div class="my_padding_10" id="my_social_options">
			<div class="my_width_100">
 				<div class="dummy-adapter-new" style="display:block;">
 				<?php /*
 				<p>
 				<?php echo __("To enable Youtube like / dislike video option you neeed a client id and cilent secret from ","wp_my_sogrid_domain").'<a href="https://console.developers.google.com">console.developers.google.com</a>';?>
 				<br/>
 				<?php echo __("In redirect url please add this url :","wp_my_sogrid_domain");?>&nbsp;<strong><?php echo MY_WP_SOGRID_SOCIAL_MODULES_URL.'youtube/my_like_dislike.php';?></strong>
 				</p>
 				*/ ?>
				<div style="padding-top:10px;padding-bottom:10px">
					<input name="my-submit" value="<?php echo __("Update Options","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:10px 15px" type="submit" />
						
				</div>
				<ul style="display:block;" class="imapper-sortable" id="imapper-sortable-dummy-my">
	
			
				<?php 
				if(!empty($plugin_options)){
					foreach($plugin_options as $key=>$val){
						$name=$key;
						$field=$val;
						$field['id']=$name.'_id';
						?>
						<li style="width:50%" class="my_general_options_li">
							<label for="<?php echo $field['id'];?>"><?php echo $field['title'];?></label>
							<?php if(!empty($field['tooltip'])){?>
								<div class="my_tooltip_form">
									<div class="my_tooltip_content">
									<?php echo $field['tooltip'];?>
									</div>
								</div>	
							<?php }?>
							<br/><br/>
						
							<?php Class_My_Module_Form_Static::render_element($name, $field);?>
						</li>
						<?php 
					}
				}	
				?>
				</ul>
				<div style="padding-top:10px;padding-bottom:10px">
					<input name="my-submit" value="<?php echo __("Update Options","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:10px 15px" type="submit" />
						
				</div>
				
			</div><!-- dummy-adapter-new -->
		</div><!-- social options -->
	</div><!-- options form -->			
	</div>
	</form>			
</div>		