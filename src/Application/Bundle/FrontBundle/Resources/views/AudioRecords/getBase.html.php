<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php if ($bases): ?>
<option value=""></option>
<?php
foreach ($bases as $base) {
?>
<option value="<?php echo $base->getId() ?>" <?php echo ($base->getId() == $selectedBaseId) ? "selected = 'selected'" : ""; ?>><?php echo $base->getName() ?></option>
<?php
}
?>
<?php
endif;
?>
<?php
$view['slots']->stop();
?>
