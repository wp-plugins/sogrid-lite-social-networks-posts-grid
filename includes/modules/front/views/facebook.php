<?php
if(!defined('ABSPATH'))die('');
/*
 * changes 1.22.2015.
* change dynamic sogrid show all sections
* of the post
* data-my-has-thumb
*/
$dynamic_sogrid=$my_so_grid_options['general_options']['dynamic'];
if($dynamic_sogrid)$thumb=1;
else $thumb=$my_so_grid_options[$social_type]['thumb'];
if($thumb==1){
	$att_html=$obj->getImage();
	if(!empty($att_html))
		$has_thumb=true;
	else $has_thumb=false;
}else $has_thumb=false;

$subtype=$obj->getProperty('subtype');
if($dynamic_sogrid)$show_share=1;
else $show_share=$my_so_grid_options[$social_type]['share'];
?>
<li id="<?php echo $my_li_id;?>" data-my-show="<?php echo $my_data_show;?>" class="my_social_item my_social_<?php echo $social_type;?> <?php if($dynamic_sogrid) echo 'my_sogrid_dynamic_grid'?> <?php if($show_share==0)echo 'my_social_item_no_share';?> <?php if($has_thumb)echo 'my_social_item_has_thumb';else echo 'my_social_item_has_no_thumb';?> <?php if(isset($my_ajax_columns)){if($my_ajax_columns==2)echo 'my_sogrid_50';else if($my_ajax_columns==1)echo 'my_no_padding_right my_sogrid_100';}?>" data-my-has-thumb="<?php if($has_thumb)echo '1';else echo '0';?>" data-my-id="<?php echo $obj->getProperty("id");?>" data-published="<?php echo $obj->getProperty('published');?>" data-my-type="<?php echo $social_type;?>">
	<div class="my_social_inner my_social_<?php echo $social_type;?>_inner">
	<?php 
	//echo wp_my_sogrid_format_k_number(1650000);
	$published=$obj->getProperty('published');
	if($has_thumb){
		
		$image=$obj->getProperty('image');
		$style="background-image:url('".$image."');background-size:cover;";
		/*$my_id_123455=$obj->getProperty('id');
		if($my_id_123455!='not-set'){
			if(strpos($my_id_123455,'_')!==false){
					$arr=explode("_",$my_id_123455);
					$my_id_123455=$arr[0];
				}
			$image='https://graph.facebook.com/'.$my_id_123455.'/picture?type=normal';
		}*/
		$image=wp_my_sogrid_format_facebook_image($image);
		?>
		<div class="my_sogrid_thumb my_so_grid_<?php echo $social_type;?>_thumb" style="<?php //echo $style;?>">
			<img src="<?php echo $image;?>"/>
			<?php 
			unset($image);
			unset($style);
			//echo $att_html;
			
			?>
		</div>
		<?php 
	}
	?>
	<div class="my_social_item_inner my_social_item_inner_<?php echo $social_type;?>">
	<?php
	if($dynamic_sogrid)$intro=1;
	else $intro=$my_so_grid_options[$social_type]['intro'];
	if($intro==1){		
		?>
		<div class="my_social_item_intro my_social_item_intro_<?php echo $social_type;?>">
			<div class="my_social_item_icon my_social_item_icon_<?php echo $social_type;?>">
			<?php $item_link=$obj->getProperty('url');?>
			<a href="<?php echo $item_link;?>"><i class="fa fa-facebook my_social_item_icon_font_facebook"></i></a>
			</div>
			<div class="my_social_item_date my_social_item_date_<?php echo $social_type;?>"><?php echo date("F d,Y",$obj->getProperty("published"));?></div>
			<div class="my_clear"></div>
		</div>
		<?php 
	}
	$my_show_div_12=false;
	if($subtype=='gall'){
		if($dynamic_sogrid)$show_text=1;
		else $show_text=$my_so_grid_options[$social_type]['text'];	
		if($show_text)$my_show_div_12=true;
	}else {
		if($dynamic_sogrid)$show_title=1;
		else $show_title=$my_so_grid_options[$social_type]['title'];
		if($dynamic_sogrid)$show_text=0;
		else $show_text=$my_so_grid_options[$social_type]['text'];
		if($show_text || $show_title){
			$my_show_div_12=true;
		}
	}
	?>
	<?php if($my_show_div_12){?>
	<div class="my_social_item_inner_text my_social_item_inner_text_<?php echo $social_type;?>">
	<?php 
	}?>
	<?php
	if($subtype=='gall'){
		if($dynamic_sogrid)$show_text=1;
		else $show_text=$my_so_grid_options[$social_type]['text'];
		$comments=$obj->getProperty('comments');
		if($dynamic_sogrid==1){
			$name=$obj->getProperty('gall_name');
			if(empty($name))$name=__("View item","wp_my_sogrid_domain");
			else $name=wp_my_sogrid_limit_title_text($name);
			?>
				<h4 class="my_social_item_title my_social_item_title_<?php echo $social_type ?>">
			<a href="<?php echo $obj->getProperty('url')?>"><?php echo $name;//$obj->getProperty('title');?></a></h4>
	
			<?php 
		}else { 
			$show_title=$my_so_grid_options[$social_type]['title'];
			$name=$obj->getProperty('gall_name');
			if($show_title==1){
			if(empty($name))$name=__("View item","wp_my_sogrid_domain");
			//else $name=wp_my_sogrid_limit_title_text($name);
			?>
							<h4 class="my_social_item_title my_social_item_title_<?php echo $social_type ?>">
						<a href="<?php echo $obj->getProperty('url')?>"><?php echo $name;//$obj->getProperty('title');?></a></h4>
			<?php }
			if(($show_text==1)&&!empty($comments)){
			?>
			<p class="my_social_item_text my_social_item_text_<?php echo $social_type;?>">
				
				<?php foreach($comments as $key_new=>$val_new){?>
				<div class="my_comment">
				<a href="https://www.facebook.com/<?php echo $val_new->id;?>"><?php echo $val_new->from->name?></a>
				<div class="my_social_comment_<?php echo $social_type;?>"><?php echo $val_new->message;?></div>
				</div>
				<?php }?>
				
			</p>
			<?php 
			}
		}	
	}else {
	if($dynamic_sogrid)$show_title=1;
	else $show_title=$my_so_grid_options[$social_type]['title'];
	$title=$obj->getProperty('title');
	if($dynamic_sogrid==1){
		$title=$obj->getProperty('content');
		$title=wp_my_sogrid_limit_title_text($title);
	}
	if(($show_title==1)&&(!empty($title))){
		if(!$dynamic_sogrid){
			$title=wp_my_sogrid_limit_text($title);
		}
		?>
		<h4 class="my_social_item_title my_social_item_title_<?php echo $social_type ?>">
		<a href="<?php echo $obj->getProperty('url')?>"><?php echo $title;//$obj->getProperty('title');?></a></h4>
		<?php 
	}
	if($dynamic_sogrid)$show_text=0;
	else $show_text=$my_so_grid_options[$social_type]['text'];
	$text=$obj->getProperty('content');
	if(($show_text==1)&&!empty($text)){
		$text=wp_my_sogrid_linkify_text($text);	
		?>
		<p class="my_social_item_text my_social_item_text_<?php echo $social_type;?>">
		<?php echo $text;?>
		</p>
		<?php 
	}
	}
	/*
	 * end
	 */
	?>
	<?php if($my_show_div_12){?>
	</div>
	<?php }?>
	<?php
	/*
	 * 
	 */ 
	//unset($show_share);
	unset($has_thumb);
	unset($att_html);
	unset($title);
	unset($show_text);
	unset($show_title);
	unset($text);
	unset($intro);
	unset($item_link);
	unset($published);
	unset($thumb);	
	?>
	</div>
	<?php 
	//see with them to icnclude share link to facebook 
	if($show_share==1){
		$like_url=$obj->getLikeUrl();
		$share_url=$obj->getShareUrl();
		$comments_url=$obj->getCommentsUrl();
		$likes_count=$obj->getTotallikes();
		if($likes_count!==false){
			$post_id=$obj->getProperty('id');
			$my_has_cache_obj=wp_my_sogrid_get_post_cached_shares($social_type,$post_id);
			if($my_has_cache_obj!==false)$likes_count=$my_has_cache_obj;
		}
		/*
		 * 1.18.2015.
		* changes to a links added .my_social_share_link
		*/
		?>
		<div class="my_social_item_share my_social_item_share_<?php echo $social_type;?>">
			<?php /*
			<div class="my_float_left my_margin_right_10">
				<?php 
				/*
				 * changes 1.20.2015.
				 * change facebook like
				 */
				/*
				?>
				<a class="my_social_share_link_<?php echo $social_type;?>" href="<?php echo $like_url;?>">
				<?php echo __("LIKE","wp_my_sogrid_domain");?>
				</a>
				*/ 
				?>
				<?php /*
				<div class="my_facebook_like_tweek <?php if($likes_count!==false)echo 'my_facebook_tweek_has_n'?>">
					<?php /*
					<div class="my_facebook_tweek">
						<a class="my_social_share_link_<?php echo $social_type;?> my_facebook_new_like_a" data-c="<?php if($likes_count!==false)echo $likes_count;?>" data-url="<?php echo $obj->getProperty('url');?>" href="<?php echo $like_url;?>">
							<?php echo __("LIKE","wp_my_sogrid_domain");if($likes_count!==false){$my_f=wp_my_sogrid_format_k_number($likes_count);echo ' (<span>'.$my_f.'</span>)';};?>
						</a>
					
					</div>
					*/ ?>
					<?php /*
					<div class="fb-like" data-href="<?php echo $obj->getProperty('url');?>" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
					*/ ?>
					<?php /*
					<div class="fb-like-box" data-width="70" data-height="24" data-href="<?php echo $obj->getProperty('url');?>" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
				
				</div>
				*/  
				/*
				 *end changes 
				 *
				
			</div>
			
			<div class="my_float_left my_margin_right_10"><span class="my_social_share_link_<?php echo $social_type;?>">|</span></div>
			*/ ?>
			<div class="my_float_left my_margin_right_10">
				<a class="my_social_share_link my_social_share_link_<?php echo $social_type;?>" href="<?php echo $share_url;?>">
				<?php echo __("SHARE","wp_my_sogrid_domain");?>
				</a>
			</div>
			<div class="my_float_left my_margin_right_10"><span class="my_social_share_link_<?php echo $social_type;?>">|</span></div>
			<div class="my_float_left my_margin_right_10">
				<a class="my_social_share_link my_social_share_link_<?php echo $social_type;?> my_social_share_facebook_comments" href="<?php echo $comments_url;?>">
				<?php echo __("COMMENTS","wp_my_sogrid_domain");?>
				</a>
			</div>
			<div class="my_clear"></div>
		</div>	
		<?php 
	unset($likes_count);
	unset($my_f);	
	unset($share_url);
	unset($like_url);
	unset($comments_url);
	}
	unset($show_share);
	?>
</div>
</li>
	