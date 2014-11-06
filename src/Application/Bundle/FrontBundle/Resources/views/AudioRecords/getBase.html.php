<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php
 foreach($bases as $base){
?>
<option value="<?php echo $base->getId()?>"><?php echo $base->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>