<?php
if(!defined('ABSPATH'))die('');
?>
<input <?php if(isset($required)&&($required))echo 'data-my-required="true"';?> style="<?php if(isset($styles)){foreach($styles as $k=>$v)echo $k.':'.$v.';';}?>" type="password" name="<?php echo $name;?>" id="<?php echo $id;?>" value="<?php echo esc_attr($value); ?>"/>
