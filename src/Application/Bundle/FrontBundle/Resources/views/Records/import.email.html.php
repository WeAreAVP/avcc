<div>Hi <b><?php echo $user->getName(); ?></b>,</div>
<br/>
<?php if (isset($fieldErrors)) { ?>
    <div>Following issues found while importing records.</div>
    <?php
    foreach ($validation as $key => $value) {
        echo '<p><b>' . str_replace('_', ' ', ucfirst($key)) . '</b><br />';
        echo implode('<br />', $value);
        echo '</p>';
    }
    ?>
    <br/>
<?php } else {
    echo "$numberOfRecords records imported successfully.";
}
?>
<br/><br/>
<div>AVCC-team</div>
