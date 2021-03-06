$(function () {
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        slideMove: 1,
        prevHtml : '<h1><span class="icon-arrow-left-3 smaller"></span></h1>',
        nextHtml : '<h1><span class="icon-arrow-right-3 smaller"></span></h1>',
//        loop: true,
        loop: false,
        enableDrag: false,
        slideMargin: 0,
        thumbItem: 6,
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
                zoom: 'fit',
                zoom_max: '200%',
                zoom_min: '50%',
                onFinishLoad: function () {
                    // $("#viewer img").attr('style', 'position: absolute; top: 0px;left: 0px; width: auto; height: 305px;')
                }
            });

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


