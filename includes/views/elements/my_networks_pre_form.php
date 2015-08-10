<?php
if(!defined('ABSPATH'))die('');
/**
 * Active social networks
 */
if(isset($my_saved_values['active_social'])){
	$active_social=$my_saved_values['active_social'];
}
?>
<div class="my_social_networks_init" style="display:none">
	<?php foreach($social_modules as $k=>$v){
		
		/*
		 * Dont include active networks
		 */
		if(in_array($k,$active_social))continue;
		$network=$k;
		$values=$v;
		$file=$template_dir.'elements/my_network.php';
		require $file;
		?>
		
	<?php }?>
</div> 