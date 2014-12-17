<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php if ($speeds): ?>
    <option value=""></option>
    <?php
    foreach ($speeds as $speed) {
        ?>
        <option value="<?php echo $speed->getId() ?>" <?php echo ($speed->getId() == $selectedrs) ? 'selected="selected"' : '';?>><?php echo $speed->getName() ?></option>
        <?php
    }
    ?>
    <?php
endif;
?>
<?php
$view['slots']->stop();
?>
