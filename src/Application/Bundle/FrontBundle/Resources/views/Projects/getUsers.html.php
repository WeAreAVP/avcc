<?php $view->extend('FOSUserBundle::default.layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<?php if ($users): ?>
    <select id="application_bundle_frontbundle_projects_projectUsers" name="application_bundle_frontbundle_projects[projectUsers][]" class="size4" multiple="multiple">
        <option value=""></option>
        <?php
        foreach ($users as $user) {
            ?>
            <option value="<?php echo $user->getId() ?>" <?php echo (in_array($user->getId(), $selectedUserId)) ? "selected = 'selected'" : ""; ?>><?php echo $user->getName() ?></option>
            <?php
        }
        ?>
    </select>
    <?php
endif;
?>
<?php
$view['slots']->stop();
?>
