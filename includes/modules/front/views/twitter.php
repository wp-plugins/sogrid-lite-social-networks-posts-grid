<?php
if(!defined('ABSPATH'))die('');
/*
 * changes 1.22.2015.
 * if is dynamic grid show all
 * sections of posts
 * add data-my-has-thumb to li add id to li
 * add id to li
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
/*
 * changes 1.22.2015.
 */
if($dynamic_sogrid)$show_share=1;
else $show_share=$my_so_grid_options[$social_type]['share'];
/*
 * end
 */
?>
<li id="<?php echo $my_li_id;?>" data-my-show="<?php echo $my_data_show;?>" class="my_social_item my_social_<?php echo $social_type;?> <?php if($show_share==0)echo 'my_social_item_no_share';?> <?php if($has_thumb)echo 'my_social_item_has_thumb';else echo 'my_social_item_has_no_thumb';?> <?php if($dynamic_sogrid) echo 'my_sogrid_dynamic_grid'?> <?php if(isset($my_ajax_columns)){if($my_ajax_columns==2)echo 'my_sogrid_50';else if($my_ajax_columns==1)echo 'my_no_padding_right my_sogrid_100';}?>" data-my-has-thumb="<?php if($has_thumb)echo '1';else echo '0';?>" data-my-id="<?php echo $obj->getProperty("id");?>" data-published="<?php echo $obj->getProperty('published');?>" data-my-type="<?php echo $social_type;?>">
	<div class="my_social_inner my_social_<?php echo $social_type;?>_inner">
	<?php 
	//echo wp_my_sogrid_format_k_number(1650000);
	$published=$obj->getProperty('published');
	if($has_thumb){
		
		$my_att=$obj->getProperty('attachments');
		//print_r($my_att)
		$image=$my_att[0]['src'];
		$style="background-image:url('".$image."');background-size:cover;"
		?>
		<div class="my_sogrid_thumb my_so_grid_<?php echo $social_type;?>_thumb" style="<?php //echo $style;?>">
			<?php //echo $att_html;?>
			<img src="<?php echo $image;?>"/>
		</div>
		<?php
		unset($my_att);
		unset($image);
		unset($style); 
		
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
			<a href="<?php echo $item_link;?>"><i class="fa fa-twitter my_social_item_icon_font_twitter"></i></a>
			</div>
			<div class="my_social_item_date my_social_item_date_<?php echo $social_type;?>"><?php echo date("F d,Y",$obj->getProperty("published"));?></div>
			<div class="my_clear"></div>
		</div>
		<?php 
			}
		?>
	<?php 
	if($dynamic_sogrid)$show_text=1;
	else $show_text=$my_so_grid_options[$social_type]['text'];
	/*
	 * changes_1_16_2015 added div to hold text
	 */
	if($show_text){
	?>
	<div class="my_social_item_inner_text my_social_item_inner_text_<?php echo $social_type;?>">
	<?php }?>
	<?php
	/*
	 * end
	 */
	?>		
	<?php 
	if($dynamic_sogrid)$show_text=1;
	else $show_text=$my_so_grid_options[$social_type]['text'];
	$text=$obj->getProperty('content');
	if(($show_text==1)&&!empty($text)){
		//echo $text;
		if($dynamic_sogrid!=1)$text=$obj->format_text();
		else $text=wp_my_sogrid_limit_title_text($text);
		?>
			<p class="my_social_item_text my_social_item_text_<?php echo $social_type;?>">
			<?php if($dynamic_sogrid==1){?>
			<a href="<?php echo $obj->getProperty('url');?>">
			<?php }?>
			<?php echo $text;//echo wp_my_sogrid_linkify_text($text);?>
			<?php if($dynamic_sogrid==1){?>
			</a>
			<?php }?>
			</p>
			<?php 
		}
	?>	
	<?php 
	unset($att_html);
	unset($has_thumb);
	unset($intro);
	unset($item_link);
	unset($published);
	//unset($show_text);
	unset($text);
	unset($thumb);
	?>
	<?php if($show_text){?>
	</div>
	<?php }
	unset($show_text);
	?>
	<?php 
	?>
	</div>
	<?php 
	if($show_share==1){
		$share_url=$obj->getShareReplyUrl();
		$retweet_url=$obj->getRetweetUrl();
		$fav_url=$obj->getFavoriteUrl();
		$post_id=$obj->getProperty('id');
		$my_has_cache_obj=wp_my_sogrid_get_post_cached_shares($social_type,$post_id);
		//print_r($my_has_cache_obj);
		if(empty($my_has_cache_obj)){
			$my_retweeted_count=$obj->getProperty('retweet_count');
			$my_favourites_count=$obj->getProperty('favourites_count');
		}else {
			$my_retweeted_count=$my_has_cache_obj['retweeted'];
			$my_favourites_count=$my_has_cache_obj['favourites_count'];
		}
		
		?>
	<div class="my_social_item_share my_social_item_share_<?php echo $social_type;?>">
		<div class="my_float_left my_margin_right_10">
			<a class="my_social_share_link my_social_share_link_<?php echo $social_type;?>" href="<?php echo $share_url;?>">
			<i class="fa fa-reply my_social_icon_share_<?php echo $social_type;?>"></i>
			</a>
		</div>
		<div class="my_float_left my_margin_right_10">
			<a class="my_social_share_link my_social_share_link_<?php echo $social_type;?>" data-t="retweeted" href="<?php echo $retweet_url;?>" data-id="<?php echo $obj->getProperty("id");?>" data-c="<?php echo $my_retweeted_count;?>">
			<i class="fa fa-retweet my_social_icon_share_<?php echo $social_type;?>"></i>
			</a>
			<span class="my_social_share_span_<?php echo $social_type?>"><?php echo wp_my_sogrid_format_k_number($my_retweeted_count);?></span>
		</div>
		<div class="my_float_left">
			<a class="my_social_share_link my_social_share_link_<?php echo $social_type;?>" data-t="favourites_count" href="<?php echo $fav_url;?>" data-id="<?php echo $obj->getProperty("id");;?>" data-c="<?php echo $my_favourites_count;?>">
			<i class="fa fa-star my_social_icon_share_<?php echo $social_type;?>"></i>
			</a>
				<span class="my_social_share_span_<?php echo $social_type?>"><?php echo wp_my_sogrid_format_k_number($my_favourites_count);?></span>
		
		</div>
		<div class="my_clear"></div>
		
	</div>	
		<?php 
	}
	
	?>
</li>