<?php $view->extend('FOSUserBundle::layout.html.php') ?>
<?php $view['slots']->start('body') ?>

<div class="grid fluid">
    <h1>
        <a href="<?php echo $view['router']->generate('record_list') ?>"><i class="icon-arrow-left-3 smaller"></i> </a> Record Detail

    </h1>
    <?php
    if ($entity->getMediaType()->getId() == 1):
        $id = $entity->getAudioRecord()->getId();
        $rout = 'record_edit';
    elseif ($entity->getMediaType()->getId() == 3):
        $id = $entity->getVideoRecord()->getId();
        $rout = 'record_video_edit';
    else:
        $id = $entity->getFilmRecord()->getId();
        $rout = 'record_film_edit';
    endif;
    ?>
    <?php if ($view['security']->isGranted('ROLE_CATALOGER')): ?>
        <a href="<?php echo $view['router']->generate($rout, array('id' => $id)) ?>" class="button primary">Edit</a>
    <?php endif; ?>
    <?php if ($uploadImages) { ?>
        <a href="<?php echo $view['router']->generate('record_photo', array('id' => $entity->getId())) ?>" class="button primary">Add Images</a>
    <?php } ?>
    <div class="clearfix"></div> 

    <div class="grid">
        <div class="row">
            <div class="span4">Created by: <?php echo ($entity->getUser()) ? $entity->getUser()->getName() : ""; ?></div>
            <?php if ($entity->getEditor()) : ?>
                <div class="span4">Last Modified by: <?php echo $entity->getEditor()->getName(); ?></div>
            <?php endif;
            ?>
        </div>
        <div class="row">
            <div class="span4">Created at: <?php echo $entity->getCreatedOn()->format('Y-m-d H:i:s'); ?></div>
            <?php if ($entity->getUpdatedOn()): ?>
                <div class="span4">Last Modified at:  <?php echo $entity->getUpdatedOn()->format('Y-m-d H:i:s'); ?>
                </div>
                <?php
            endif;
            ?>
        </div>

    </div>
    <div class="row">
        <div class="span6">
            <table class="table">
                <tbody>            
                    <?php $type = strtolower($entity->getMediaType()); ?>
                    <?php foreach ($fieldSettings[$type] as $typeField): ?>
                        <?php
                        if ($typeField["title"] == "Show Images") {
                            $hide_image = $typeField['hidden'];
                            continue;
                        }
                        $field = explode('.', $typeField['field']);
                        $arrayIndex = (count($field) == 2) ? $field[1] : $field[0];
                        if ($entityArray[$arrayIndex]) {
                            ?>
                            <tr style="<?php echo ($typeField['hidden']) ? 'display:none;' : ''; ?>">
                                <th class="text-right" width="20%">
                                    <p class="label_class" data-toggle="popover" data-placement="bottom" data-content="<?php echo isset($tooltip[$arrayIndex]) ? $tooltip[$arrayIndex] : ''; ?>">
                                        <?php echo $typeField['title']; ?>
                                    </p>
                                </th>
                                <td width="80%"><?php echo $entityArray[$arrayIndex] ?></td>
                            </tr> 
                        <?php } ?>  
                    <?php endforeach; ?>                          
                </tbody>
            </table>
        </div>
        <style>
            .lSSlideOuter .lSPager.lSGallery li.active{
                border: solid 3px black;
            }
            .viewer {
                width: 100%;
                height: 303px;
                position: relative;
            }
            .lSSlideOuter .lSPager.lSGallery li{
                width: 90.556px ! important;
            }  
            .lSSlideOuter .lSPager.lSGallery{
                width: 1200px ! important;
            }
            .wrapper {
                overflow: hidden;
            }
            .iviewer_zoom_zero ,.iviewer_zoom_status,.iviewer_zoom_fit,.iviewer_rotate_left,.iviewer_rotate_right,.iviewer_zoom_in ,.iviewer_zoom_out {
                display: none;
            }
            #actions {
                background-color: black;
                text-align: center;
            }
            #actions a{
                color: white
            }
        </style>

        <?php if ($uploadImages && $hide_image == 0) { ?>
            <div class="span6">

                <ul id="lightSlider" class="hide">
                    <?php
                    foreach ($images as $key => $image) {
                        $path = $image->getAwsPath();
                        ?>   
                        <li data-thumb="<?php echo $path ?>" id="image_<?php echo $image->getId(); ?>">
                            <img src="<?php echo $path ?>" />
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <script>
                var dpath = "<?php echo $view['router']->generate('record_delete_image'); ?>";
                $(function () {

                    $('#lightSlider').lightSlider({
                        gallery: true,
                        item: 1,
                        slideMove: 1,
                        loop: false,
                        enableDrag: false,
                        slideMargin: 0,
                        thumbItem: 9,
                        currentPagerPosition: 'middle',
                        onSliderLoad: function (el) {
                            var src = "";
                            var id = "";
                            el.find('li').each(function () {
                                if ($(this).hasClass("active")) {
                                    src = $(this).data("thumb");
                                    id = $(this).attr("id").replace(/image_/gi, '');
                                }
                            });
                            var html = '<div id="actions">' +
                                    '<a href="javascript://" id="in" title="Zoom in"><i class="icon-zoom-in"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript://" id="out" title="Zoom out"><i class="icon-zoom-out"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript://" id="original" title="Original"><i class="icon-fullscreen-alt"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript://" id="rotate" title="Rotate"><i class="icon-loop"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript://" id="delete" data-img="" title="Delete"><i class="icon-remove"></i></a>&nbsp;&nbsp;' +
                                    '</div>' + '<div id="viewer" class="viewer"></div>';

                            $(".lSSlideWrapper").prepend(html);
                            $("#viewer").iviewer({
                                src: src,
                                onFinishLoad: function () {
                                    $("#viewer").iviewer('zoom_by', 0.5);
                                }
                            });
    //                            $("#viewer").iviewer('zoom_by', 1);
                            $("#in").click(function () {
                                $("#viewer").iviewer('zoom_by', 1);
                            });
                            $("#out").click(function () {
                                $("#viewer").iviewer('zoom_by', -1);
                            });
                            $("#original").click(function () {
                                $("#viewer").iviewer('fit');
                            });
                            $("#rotate").click(function () {
                                $("#viewer").iviewer('angle', 90);
                            });
                            $("#delete").attr("data-img", id);
                            $("#delete").click(function () {
                                var res = confirm('Are you sure you want to delete this image?');
                                if (res) {
                                    var id = $("#delete").data("img");
                                    $.ajax({
                                        type: 'POST',
                                        url: dpath,
                                        data: {
                                            image_id: id
                                        },
                                        dataType: 'json',
                                        success: function (response) {
                                            window.location.reload()
                                        }
                                    });
                                }
                            });
                        },
                        onAfterSlide: function (el) {
                            el.find('li').each(function () {
                                if ($(this).hasClass("active")) {
                                    var src = $(this).data("thumb");
                                    var id = $(this).attr("id").replace(/image_/gi, '');
                                    $("#viewer").iviewer('loadImage', src);
                                    $("#delete").attr("data-img", id);
                                }
                            });
                        }
                    });
                });
            </script>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $(function () {
            $('[data-toggle="popover"]').popover();
        });
    });

</script>
<?php
$view['slots']->stop();
