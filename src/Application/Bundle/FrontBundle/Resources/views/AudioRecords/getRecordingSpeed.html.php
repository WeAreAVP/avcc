<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php
 foreach ($speeds as $speed) {
?>
<option value="<?php echo $speed->getId()?>"><?php echo $speed->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>
