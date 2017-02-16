<div>Hi <b><?php echo $user->getName(); ?></b>,</div>
<br/>
<?php if (isset($fieldErrors)) { ?>
    <div>Following issues found while importing records.</div>
    <?php
    foreach ($fieldErrors as $key => $value) {
        echo '<p><b>' . str_replace('_', ' ', ucfirst($key)) . '</b><br />';
        echo implode('<br />', $value);
        echo '</p>';
    }
    ?>
    <br/>
    <?php
} else if (isset($numberOfRecords)) {
    echo "$numberOfRecords records imported successfully.";
} else if (isset($organization)) {
    echo "No. of record exceeds $plan_limit limit for organization $organization. Please upgrade your account by contacting $contact_person";
} else {
    ?>
    <div>Following issues found while importing records.</div>
    <?php
    foreach ($errors as $key => $value) {
        $format = explode(" | ", $value);
        echo '<p>No format ' . $format[1] . ' found for media type ' . $format[0] . '<br />';
        echo '</p>';
    }
}
?>
<br/><br/>
<div>AVCC-team</div>
