<?php
if(!defined('ABSPATH'))die('');
if($network=='google'){
	$my_saved_google_api_key=wp_my_sogrid_get_option_by_key('google_api_key');
	
}else if($network=='youtube'){
	$my_saved_google_api_key=wp_my_sogrid_get_option_by_key('google_api_key');
	
}
?>
<div class="my_social_pre_form_<?php echo $network;?>">
	<h2 class="imapper-sort-header-my" my_id="pin"><?php echo $values['title'];?></h2>
		<div class="my_padding_10" id="my_social_<?php echo $network;?>">
			<div class="my_width_100">
				<?php if($network=='instagram'){
					$app_id_12=wp_my_sogrid_get_option_by_key('instagram_client_id');
					
					$my_url_12='https://instagram.com/oauth/authorize/?client_id='.$app_id_12;
					$red_url_12=wp_my_sogrid_get_site_url().'?my_instagram_12_12_get_code=1';
					$my_url_12.='&redirect_uri='.urlencode($red_url_12).'&response_type=token';
					$has_token=wp_my_sogrid_check_instagram_access_token();		
					
				?>
				
				<div class="my_get_access_token" title="<?php echo __("Get Instagram Access Token","wp_my_sogrid_domain");?>">
				
					
					<?php if($has_token===false){?>
						<h4 style="color:red"><?php echo __("You don't have instagram access token please click link below.","wp_my_sogrid_domain");?></h4>
						<p>
					<?php echo __("To get access token automaticaly please add to your instagram client ","wp_my_sogrid_domain");?>:
					<ul>
						<li><label><?php echo __("Redirect Uri","wp_my_sogrid_domain");?>-<b><?php echo $red_url_12;?></b></label></li>
						<li><label><?php echo __("Uncheck option in edit client ","wp_my_sogrid_domain");?>-<b>Disable Implicit oAuth</b></label></li>
					</ul>
					</p>
						<h4><?php echo __("Get Instagram Access Token","wp_my_sogrid_domain");?></h4>
						<a href="<?php echo $my_url_12;?>" target="_blank"><?php echo __("Get Access Token","wp_my_sogrid_domain");?></a>
				
					<?php }else {?>
							<h4 style="color:blue"><?php echo __("Instagram Access code is valid.","wp_my_sogrid_domain");?></h4>
					
					<?php }?>
					
				</div>
				<?php }else if($network=='vkontakte'){
					$vk_a=get_option("_my_sogrid_vkontakte_access_token");
				//	echo 'Access token '.$vk_a;
								$vkontakte_client_id=wp_my_sogrid_get_option_by_key('vkontakte_client_id');
							$red_url_12=wp_my_sogrid_get_site_url().'?my_vkontakte_12_12_get_code=1';
							$my_url_12='https://oauth.vk.com/authorize?client_id='.$vkontakte_client_id;
							$my_url_12.='&scope=offline&redirect_uri='.urlencode($red_url_12);
							$my_url_12.='&response_type=code&v='.urlencode('5.33');
							$object=wp_my_sogrid_get_vkontakte_users('microsoft');
							$has_token=true;
							if(isset($object->error)){
								$has_token=false;
							}
					?>
				
				<div class="my_get_access_token" title="<?php echo __("Get VKontakte access token","wp_my_sogrid_domain");?>">
					<?php if($has_token===false){?>
						<h4 style="color:red"><?php echo __("You don't have VKontakte access token please click link below.","wp_my_sogrid_domain");?></h4>
						<p>
					<?php //echo __("To get access token automaticaly please add to your instagram client ","wp_my_sogrid_domain");?>:
					<?php /*
					<ul>
						<li><label><?php echo __("Redirect Uri","wp_my_sogrid_domain");?>-<b><?php echo $red_url_12;?></b></label></li>
						<li><label><?php echo __("Uncheck option in edit client ","wp_my_sogrid_domain");?>-<b>Disable Implicit oAuth</b></label></li>
					</ul>
					*/  ?>
					</p>
						<h4><?php echo __("Get VKontakte Access Token","wp_my_sogrid_domain");?></h4>
						<a href="<?php echo $my_url_12;?>" target="_blank"><?php echo __("Get Access Token","wp_my_sogrid_domain");?></a>
				
					<?php }else {?>
							<h4 style="color:blue"><?php echo __("VKontakte Access code is valid.","wp_my_sogrid_domain");?></h4>
					
					<?php }?>
				</div>
				
				
				<?php }?>
				
 				<div class="dummy-adapter <?php //if($id_pin!=1)echo 'closed'?>" style="display:block;<? //if($id_pin!=1)echo 'display: none;';else echo 'display:block';?>">
				<ul style="display:block;" class="imapper-sortable" id="imapper-sortable-dummy-my">
	
				<?php $options=$social[$network]['options'];
				  $new_items=$social[$network]['item'];
				  $tooltips=$social[$network]['tooltips'];
				  foreach($options as $k=>$v){
				  	if(isset($general_options[$v])){
				  		$field_pre=$general_options[$v];
				  		$name=$network.'_'.$v;
				  		$field_pre['id']=$network.'_'.$v.'_id';
				  		if(isset($my_values[$v])){
				  			$field_pre['value']=$my_values[$v];
				  		}
				  		?>
				  		<li>
						<label for="<?php echo $network.'_'.$v.'_id';?>"><?php echo $field_pre['title'];?></label>
						<?php if(isset($tooltips[$v])){?>
							<div class="my_tooltip_form"><div class="my_tooltip_content">
							<?php echo $tooltips[$v];?>
							</div>
						</div>
						
						<?php }?>
						<br/><br/>
						<?php Class_My_Module_Form_Static::render_element($name,$field_pre);?>
						
						</li>
				  		<?php 	
				  	}
				  }	//foreach options
				  if(!empty($new_items)){
				  	foreach($new_items as $k=>$v){
				  		$field_pre=$v;
				  		$name=$network.'_'.$k;
				  		$field_pre['id']=$network.'_'.$k.'_id';
				  		if($network=='google'){
							if($k=='api_key'){
								if(!empty($my_saved_google_api_key)){
									$field_pre['value']=$my_saved_google_api_key;
								}
							}
						}else if($network=='youtube'){
							if($k=='api_key'){
							if(!empty($my_saved_google_api_key)){
								$field_pre['value']=$my_saved_google_api_key;
							}
							if($k=='max'){
								
								$field_pre['default']=50;
							}
						}
						}
				  		if(isset($my_values[$k])){
				  			$field_pre['value']=$my_values[$k];
				  		}
				  		?>
				  		<li>
						<label for="<?php echo $network.'_'.$k.'_id';?>"><?php echo $field_pre['title'];?></label>
						<?php if(isset($field_pre['tooltip'])){?>
							<div class="my_tooltip_form"><div class="my_tooltip_content">
							<?php echo $field_pre['tooltip'];?>
							</div>
						</div>
						
						<?php }?>
						<br/><br/>
						<?php Class_My_Module_Form_Static::render_element($name,$field_pre);?>
						
						</li>
				  		<?php 
				  		
				  	}
				  }
				?>
				</ul>
				</div>
			</div>	
		</div>
</div>	