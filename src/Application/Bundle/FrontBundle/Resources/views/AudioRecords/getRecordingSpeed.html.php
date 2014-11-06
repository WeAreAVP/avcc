<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php
 foreach($speeds as $speeds){
?>
<option value="<?php echo $speeds->getId()?>"><?php echo $speeds->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>