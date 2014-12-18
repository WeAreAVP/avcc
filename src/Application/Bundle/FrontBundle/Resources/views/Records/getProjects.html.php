<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php if ($projects): ?>
    <option value=""></option>
    <?php
    foreach ($projects as $project) {
        ?>
        <option value="<?php echo $project->getId() ?>" <?php echo ($project->getId() == $selectedProjectId) ? "selected = 'selected'" : ""; ?>><?php echo $project->getName() ?></option>
        <?php
    }
    ?>
    <?php
endif;
?>
<?php
$view['slots']->stop();
?>
