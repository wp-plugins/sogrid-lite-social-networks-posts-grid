<?php
if(!defined('ABSPATH'))die('');

$my_action=@$_POST['my_action'];
//print_r($_POST);
if(!empty($my_action)){
	$network=@$_POST['my_network'];
	if(!empty($network)){
		switch ($network){
			
			
			case 'twitter':
				$my_is_twitter=1;
				$my_name=@$_POST['my_name'];
				if(empty($my_name)){
					$error_msg=__("Please add twiiter screename","wp_my_sogrid_domain");
				}else {
					$obj=wp_my_sogrid_twitter_get_list_id($my_name);
					if(is_array($obj)){
					//print_r($obj);
						$ok_msg=__("Result is found","wp_my_sogrid_domain");
					}else $error_msg=$obj;
				}
			break;	
			case 'facebook':
				$my_is_facebook=1;
				$my_name=@$_POST['my_name'];
				if($my_action=='get_albums'){
					if(empty($my_name)){
						$error_msg=__("Please add facebook user ID","wp_my_sogrid_domain");
					}else {
						$ret=wp_my_sogrid_get_facebook_albumns($my_name);
						if(!is_array($ret)){
							$error_msg=$ret;
						}else {
							$ok_msg=__("Result is found","wp_my_sogrid_domain");
							
						}
					}
				}else {
				if(empty($my_name)){
					$error_msg=__("Please add facebook name","wp_my_sogrid_domain");
						
				}else {
					$obj=wp_my_sogrid_get_facebook_id($my_name);
					//print_r($obj);
					if($obj===false){
						$error_msg=__("No result","wp_my_sogrid_domain");
					}
					if(isset($obj->error)){
						$error_msg=$obj->error->message;
					}else {
						$id=$obj->id;
						if(isset($obj->about)){
							$about=$obj->about;
						}
						if(isset($obj->description)){
							$description=$obj->description;
						}
						if(isset($obj->gender)){
							$gender=$obj->gender;
						}
						if(isset($obj->first_name)){
							$first_name=$obj->first_name;
						}
						if(isset($obj->last_name)){
							$last_name=$obj->last_name;
						}
						$my_fields=array(
							'id'=>__("ID","wp_my_sogrid_domain"),
							'about'=>__("About","wp_my_sogrid_domain"),
							'description'=>__("Description","wp_my_sogrid_domain"),
							'gender'=>__("Gender","wp_my_sogrid_domain"),
							'first_name'=>__("First Name","wp_my_sogrid_domain"),
							'last_name'=>__("Last Name","wp_my_sogrid_domain")
						);
						$ok_msg=__("Result is found","wp_my_sogrid_domain");
					}
				}}
			break;
				
			
		}
	}
}

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
	<div class="postbox">
					<h2 class='imapper-backend-header' style="cursor:auto"><span><?php echo __("Facebook","wp_my_sogrid_domain");?></span></h2>
					<div class="inside">
						<div style="padding:5px">
						<?php 
						if(isset($my_is_facebook)&&$my_action=='get_id'){
							if(!isset($error_msg)){
							?>
							<ul>
								<?php 
								foreach($my_fields as $key=>$val){
									//$val_1=$$k;
									if(isset($$key)){
									?>
									<li><label><?php echo $val;?> <b><?php echo $$key?></b></label></li>
									<?php 
									}				
								}
									
								?>
							</ul>
							<?php 
							}
						}else if(isset($my_is_facebook)&&$my_action=='get_albums'){
							$check_fileds=array(
								'name'=>__("Name","wp_my_sogrid_domain"),
								'id'=>__("ID","wp_my_sogrid_domain"),
								'link'=>__("Link","wp_my_sogrid_domain"),
								'count'=>__("Count","wp_my_sogrid_domain"),
								//'url'=>__("URL","wp_my_sogrid_domain"),
							);
							if(!isset($error_msg)){
								if(!empty($ret['data'])){
									$obj=$ret['data'];
									$c=count($obj);
									echo '<h4/>'.__("Total results ","wp_my_sogrid_domain").' : '.$c.'</h4>';
									foreach($obj as $k=>$v){
										?>
											<ul style="min-height:200px;float:left;width:300px;margin-bottom:10px;margin-right:10px;;border-right:1px solid black;">
										<?php 
										foreach($check_fileds as $key=>$val){
											if(isset($v->$key)){
											?>
											<li><label><?php echo $val.' : ';?><?php if($key=='link')echo '<a href="'.$v->$key.'">'.__("Album link","wp_my_sogrid_domain").'</a>';else echo $v->$key;?></label></li>
											<?php 
											}
										}
										?>
										</ul>
										<?php 				
									}
									?>
									<div class="clear"></div>
									<?php 
								}
							}
						}
						?>
		<h2><?php echo __("Get facebook user ID","wp_my_sogrid_domain");?></h2>	
		<form method="post">
			<input type="hidden" name="my_action" value="get_id"/>
			<input type="hidden" name="my_network" value="facebook"/>
			<ul>
			<li><label for="my_name_id"><?php echo __("Facebook name","wp_my_sogrid_domain");?></label>
			<input type="text" name="my_name" id="my_name_id" value="" />
			</li>
			<li>
			<input name="preview-timeline" id="preview-timeline" value="<?php echo __("Get Ids","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:3px 25px" type="submit" />
			</li>
			</ul>
		</form>
		<h2><?php echo __("Get user albums","wp_my_sogrid_domain");?></h2>	
		
		<form method="post">
			<input type="hidden" name="my_action" value="get_albums"/>
			<input type="hidden" name="my_network" value="facebook"/>
			<ul>
			<li><label for="my_name_id"><?php echo __("Facebook user id","wp_my_sogrid_domain");?></label>
			<input type="text" name="my_name" id="my_name_id" value="" />
			</li>
			<li>
			<input name="preview-timeline" id="preview-timeline" value="<?php echo __("Get Ids","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:3px 25px" type="submit" />
			</li>
			</ul>
		</form>
	</div>
	</div>
	</div>
	<div class="clear"></div>
	
	<div class="postbox">
					<h2 class='imapper-backend-header' style="cursor:auto"><span><?php echo __("Twitter","wp_my_sogrid_domain");?></span></h2>
					<div class="inside">
						<div style="padding:5px">
						<?php 
						if(isset($my_is_twitter)){
						$check_fileds=array(
							'name'=>__("Name","wp_my_sogrid_domain"),
							'id'=>__("ID","wp_my_sogrid_domain"),
							'mode'=>__("Mode","wp_my_sogrid_domain"),
							'slug'=>__("Slug","wp_my_sogrid_domain"),
							'subscriber_count'=>__("Subscribers Count","wp_my_sogrid_domain"),
							'member_count'=>__("Members Count","wp_my_sogrid_domain"),			
						);
						if(!empty($obj)){
							if(!isset($error_msg)){
							?>
								<?php
								$c=count($obj);
								echo '<h4/>'.__("Total results ","wp_my_sogrid_domain").' : '.$c.'</h4>';
								
								//if(!empty($obj->items)){ 
								foreach($obj as $k=>$v){
									?>
									<ul style="min-height:200px;float:left;width:300px;margin-bottom:10px;margin-right:10px;;border-right:1px solid black;">
									<?php if(!empty($v->uri)){?>
									<li><a href="https://twitter.com/<?php echo $v->uri?>"><?php echo $v->name;?></a>
									<?php }?>
									<?php if(!empty($v->profile_image_url)){?>
									<li><img src="<?php echo $v->profile_image_url;?>"/></li>
									<?php }?>
									<?php foreach($check_fileds as $key=>$val){?>
										<?php if(!empty($v->$key)){?>
										<li><label><?php echo $val;?> <b><?php echo $v->$key;?></b></label></li>
										<?php }?>
									<?php }?>
									</ul>
									<?php 
								//$c=count($obj->items);
								/*foreach($obj->items as $key=>$val){
									if(!empty($val->image->url)){
										$image=$val->image->url;
									}else {
										$image='';
									}				
									?>
									<ul style="float:left;width:300px;margin-bottom:10px;margin-right:10px;;border-right:1px solid black;">
									<?php 
									foreach($check_fileds as $k1=>$v1){
										if($k1=='images'&&empty($image))continue;
										if($k1=='image'){
											?>
											<li><img src="<?php echo $image; ?>"/></li>
											<?php 
										}
										else if(isset($val->$k1)){
											?>
											<li><label><?php echo $v1;?> <b><?php if($k1=='url')echo '<a href="'.$val->$k1.'">'.$val->$k1.'</a>';else echo $val->$k1?></b></label></li>
											<?php 
										}
									}
									?>
									</ul>
									<?php 
													
								}*/
								}
								?>
								<div class="clear"></div>
							<?php 
							}
						}
						}
						?>
		<h4><?php echo __("Get user lists","wp_my_sogrid_domain");?></h4>	
		<form method="post">
			<input type="hidden" name="my_action" value="get_id"/>
			<input type="hidden" name="my_network" value="twitter"/>
			<ul>
			<li><label for="my_name_id"><?php echo __("Twitter user screen name","wp_my_sogrid_domain");?></label>
			<input type="text" name="my_name" id="my_name_id" value="" />
			</li>
			<li>
			<input name="preview-timeline" id="preview-timeline" value="<?php echo __("Get Ids","wp_my_sogrid_domain");?>" class="add-new-h2" style="padding:3px 25px" type="submit" />
			</li>
			</ul>
		</form>
	</div>
	</div>
	</div>
	
	
	<div class="clear"></div>
</div>	
<div class="clear"></div>