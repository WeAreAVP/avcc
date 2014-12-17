<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php if ($reeldiameters): ?>
<option value=""></option>
<?php
 foreach ($reeldiameters as $reeldiameter) {
?>
<option value="<?php echo $reeldiameter->getId()?>" <?php echo ($reeldiameter->getId() == $selectedRD) ? 'selected="selected"' : '';?>><?php echo $reeldiameter->getName()?></option>
<?php
 }
?>
<?php endif;?>
<?php
$view['slots']->stop();
?>
