<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php
 foreach($formatVersions as $formatVersion){
?>
<option value="<?php echo $formatVersion->getId()?>"><?php echo $formatVersion->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>