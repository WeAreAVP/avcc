<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<option value=""></option>
<?php
 foreach ($reeldiameters as $reeldiameter) {
?>
<option value="<?php echo $reeldiameter->getId()?>"><?php echo $reeldiameter->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>
