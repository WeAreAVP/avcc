<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a>
        New Record
    </h1>

    <div>
        <div style="" class="col-lg-6" id="mediaType_lbl">
            <label  class="label_class" data-toggle="popover" data-placement="bottom" data-content="Automatically entered based on template selection. Used for reporting purposes and to help differentiate formats like VHS and A-DAT that can use the same physical media but be totally different recording types. This field is required." style="width: 200px">Media Type&nbsp;<span>*</span></label>
            <div class="input-control new" data-role="input-control">
                <select id="mediaType" class="size4">
                    <option value=""></option>        
                    <option value="1">Audio</option>
                    <option value="3">Video</option>
                    <option value="2">Film</option></select>                        
                <span class="has-error text-danger"></span>
            </div>
        </div>
    </div>
    <br />
    <a href="<?php echo $view['router']->generate('record_list') ?>" name="cancle" class="button">Cancel</a>&nbsp;

</div>
</div>
<script src="<?php echo $view['assets']->getUrl('js/manage.records.js') ?>"></script>
<script type="text/javascript">
    var changes = false;
    var baseUrl = '<?php echo $view['router']->generate('record') ?>';
    var selectedFormat = '';
    var selectedMediaType = 0;
    var selectedFormatVersion = '';
    var selectedRS = '';
    var selectedRD = '';
    var selectedProject = '';
    var viewUrl = '';
    var projectId = 0;
    var bulk = false;
    $(document).ready(function () {
        initialize_records_form();
        $(function () {
            $('[data-toggle="popover"]').popover();
        });
    });
</script>
<?php
$view['slots']->stop();
?>
