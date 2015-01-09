<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>
<div class="grid">
    <div class="row">
        <h2>Update Score</h2>
        <form action="<?php echo $view['router']->generate('record_savescore') ?>" method="post" enctype="multipart/form-data" name="frmUploadScore">
            <div id="beforeExportMerge">
                <p><span style="font-size:13px;"></span></p>
                <div class="pull-right">
                    <input type="file" name="uploadfile" class="required" required="required" /><br /><br />
                </div>
                <input type="submit" name="submit" class="button" value="Update Score" />
            </div>
        </form>
        <p>
            <?php
              if (isset($updated)) {
                  echo implode("<br />", $updated);
              }
            ?>
        </p>
    </div>
</div>

<?php
$view['slots']->stop();
?>
