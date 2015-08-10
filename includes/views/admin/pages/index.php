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
<?php 
/*
 * removed action now they are in conteroller
 */
?>
<div class="wrap imapper-admin-wrapper">
	<h2 class="imapper-backend-header">
	
	<?php echo __("SoGrid","wp_my_sogrid_domain")?>
			<a href="<?php echo admin_url( "admin.php?page=my-sogrid-edit" ); ?>" class="add-new-h2"><?php echo __("Add New","wp_my_sogrid_domain")?></a>
	</h2>
	<ul class="imapper-backend-ul">
		<li>
		<?php 
			echo $table_html;
		?>
		</li>
	</ul>	
<div style="margin-top:20px;">

<h2 class="imapper-backend-header"><?php echo __("Step by step instructions","wp_my_sogrid_domain");?>:</h2>
<ul class="imapper-backend-ul">
	<li><h3><?php echo __("1. Click the ","wp_my_sogrid_domain");?><span class="emphasize"><?php echo __('"Add New"',"wp_my_sogrid_domain");?></span><?php echo __("button","wp_my_sogrid_domain");?></h3></li>
	<li><h3><?php echo __("2. Name your sogrid and click","wp_my_sogrid_domain");?> <span class="emphasize"><?php echo __('"Social Options"',"wp_my_sogrid_domain");?></span> <?php echo __("to enable some social networks.","wp_my_sogrid_domain");?></h3></li>
	<li><h3><?php echo __("3. Add options to each social network","wp_my_sogrid_domain");?> </h3></li>
	<li><h3><?php echo __("4. Save your soGrid","wp_my_sogrid_domain");?> <span class="emphasize"><?php echo __("\"Save SoGrid\"","wp_my_sogrid_domain");?></span><?php //echo __(" to save your SoGrid","wp_my_sogrid_domain");?></h3></li>
	<li><h3><?php echo __("4. Enjoy","wp_my_sogrid_domain");?></h3></li>
</ul>
</div>
</div>	