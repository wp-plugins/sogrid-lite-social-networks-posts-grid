<?php
require '../../../../../../../wp-blog-header.php';
if(!defined('ABSPATH'))die('');
if(!is_user_logged_in())die('');
if(!current_user_can('manage_options'))die('');
$id=@$_GET['id'];
$id=(int)$id;
$has_mapper=wp_my_sogrid_is_exist_object($id);
if($has_mapper){
	$my_sogrid_title=wp_my_sogrid_get_object_title($id);
}else $my_sogrid_title='';
	?>
<!DOCTYPE html>
	<title><?php echo __("Preview - ","wp_my_sogrid_domain").$my_sogrid_title;?></title>
	<head>
	<?php 
	
	wp_head();
	?>
	<style type="text/css">
		.my_api_errors_12{
			background-color:white;
			font-size:12px;
			text-align:left;
			color:black;
			width:100%;
			border-bottom:1px solid black;
			margin-bottom:10px;
			padding-bottom:10px;
		}
		.my_api_errors_12 h2{
			color:blue;
			font-size:14px;
		}
		.my_api_errors_12 h4{
			font-size:12px;
			color:black;
		}
		.my_api_errors_12 label{
			color:red;
			font-size:12px;
		}
		.my_sogrid_container{
			width:100% !important;
		}
		
			</style>
	</head>
<body style="width:100%;margin:auto;">
<?php 
if($has_mapper){
	echo do_shortcode('[sogrid id="'.$id.'"]');
}else echo __("SoGrid don't exists !","wp_my_sogrid_domain");
?>
</body>
</html>