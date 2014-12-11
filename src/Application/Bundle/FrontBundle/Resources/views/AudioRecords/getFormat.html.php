<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<option value=""></option>
<?php
 foreach ($formats as $format) {
?>
<option value="<?php echo $format->getId()?>" <?php echo ($format->getId() == $formatId) ? 'selected=selected' : '';?>><?php echo $format->getName()?></option>
<?php
 }
?>
<?php
$view['slots']->stop();
?>
