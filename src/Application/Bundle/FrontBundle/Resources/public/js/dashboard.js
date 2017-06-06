/**
 * Dashboard class
 * 
 * @returns {Dashboard}
 */
function Dashboard() {

    var selfObj = this;
    var baseUrl = null;
    var formatData = null;
    /**
     * Set the base url.
     * @param {string} base_url
     * 
     */
    this.setBaseUrl = function (base_url) {
        baseUrl = base_url;
    };

    /**
     * Set the formats for hight charts.
     * @param {array} format_data
     * 
     */
    this.setFormats = function (format_data) {
        formatData = format_data;
    };

    /**
     * 
     * @returns {undefined}
     */
    this.bindAll = function () {
        if (formatData) {
            selfObj.charts('all', formatData);
        }
        selfObj.onChangeProjects();
    };
    /**
     * 
     * @returns {undefined}
     */
    this.onChangeProjects = function () {
        formatData = "";
        $('#projects').change(function () {
            var selectedProject = $(this).val();
            if (selectedProject) {
                selfObj.charts(selectedProject, formatData);
            }
        });
    };

    this.charts = function (selectedProject, formatData) {
        formatUrl = baseUrl + 'getFormatCount/' + selectedProject;
        cuUrl = baseUrl + 'getCommercialUniqueCount/' + selectedProject;
        totalRecordsUrl = baseUrl + 'getTotalRecords/' + selectedProject;
        ////// ajax call to get total records, linear feet for media types.
        selfObj.getOverview(totalRecordsUrl, 1, ""); //get digitized
        selfObj.getOverview(totalRecordsUrl, 2, "nd"); //get not digitized
        $('#formatCount').highcharts({
            chart: {
                type: 'column',
                width: 1500,
                height: 400
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -90,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Total count per format'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            tooltip: {
                useHTML: true,
                headerFormat: '<span><b>{series.name}: </b></span>',
                pointFormat: '<a href="javascript://" onclick="setFacets(\'{point.name}\')"><span style="color:{point.color}">{point.name}</span><br/><b>Total:</b>{point.y}<br/>'
            },
            series: [{
                    name: 'Format',
                    colorByPoint: true,
                    data: (function () {
                        var data;
                        if (formatData) {
                            data = formatData;
                        } else {
                            $.blockUI({
                                message: 'Please wait...',
                                css: {
                                    border: 'none',
                                    padding: '15px',
                                    backgroundColor: '#000',
                                    '-webkit-border-radius': '10px',
                                    '-moz-border-radius': '10px',
                                    opacity: .5,
                                    color: '#fff',
                                    zIndex: 999999
                                }
                            });
                            $.ajax({
                                url: formatUrl,
                                async: false,
                                dataType: "json",
                                type: "GET",
                                success: function (response) {
                                    data = response;
                                    $.unblockUI();
                                }
                            });
                        }
                        return data;
                    })()
                }]
        });

        $('#commercialUnique').highcharts({
            chart: {
                type: 'column',
                width: 455,
                height: 400
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -90,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Total count per format'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span><b>{series.name}: </b></span>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span><br/><b>Total:</b>{point.y}<br/>'
            },
            series: [{
                    name: 'Commercial/Unique',
                    colorByPoint: true,
                    data: (function () {
                        var data;
                        $.ajax({
                            url: cuUrl,
                            async: false,
                            dataType: "json",
                            type: "GET",
                            success: function (response) {
                                data = response;
                            }
                        });
                        return data;
                    })()
                }]
        });

    }

    this.getOverview = function (totalRecordsUrl, digitized, prefix) {
        $.ajax({
            type: "GET",
            url: totalRecordsUrl + "/" + digitized,
            dataType: 'json',
            success: function (response) {
                if (response) {
                    $.each(response, function (index, element) {
                        $('#' + prefix + 'audioTotal').html(0);
                        $('#' + prefix + 'audiolinear').html(0.00);
                        $('#' + prefix + 'audiofile').html(0.00);
                        $('#' + prefix + 'audiodigitized').html(0);
                        $('#' + prefix + 'audiocontentDur').html(0.00);
                        $('#' + prefix + 'videoTotal').html(0);
                        $('#' + prefix + 'videolinear').html(0.00);
                        $('#' + prefix + 'videofile').html(0.00);
                        $('#' + prefix + 'videodigitized').html(0);
                        $('#' + prefix + 'videocontentDur').html(0.00);
                        $('#' + prefix + 'filmTotal').html(0);
                        $('#' + prefix + 'filmlinear').html(0.00);
                        $('#' + prefix + 'filmfile').html(0.00);
                        $('#' + prefix + 'filmdigitized').html(0);
                        $('#' + prefix + 'filmcontentDur').html(0.00);
                        $('#' + prefix + 'total').html(0);
                        $('#' + prefix + 'dtotal').html(0);
                        $('#' + prefix + 'durTotal').html(0);
                        $('#' + prefix + 'fileTotal').html(0);
                        var total, dtotal, durTotal, fileTotal;
                        total = dtotal = durTotal = fileTotal = 0;
                        //  non digitized
                        if (typeof element !== "undefined" && typeof element[0] !== "undefined" && element[0].Audio) {
                            var audio = element[0].Audio;
                            total += audio.totalRecords;
                            dtotal += audio.dRecords;
                            durTotal += audio.sum_content_duration;
                            fileTotal += audio.fileSize;
                            $('#' + prefix + 'audioTotal').html($.number(audio.totalRecords));
                            $('#' + prefix + 'audiolinear').html($.number(audio.linearFeet,2));
                            $('#' + prefix + 'audiofile').html($.number(audio.fileSize,2));
                            $('#' + prefix + 'audiodigitized').html($.number(audio.dRecords));
                            $('#' + prefix + 'audiocontentDur').html($.number(audio.sum_content_duration,2));

                        }
                        if (typeof element !== "undefined" && typeof element[1] !== "undefined" && element[1].Video) {
                            var video = element[1].Video;
                            total += video.totalRecords;
                            dtotal += video.dRecords;
                            durTotal += video.sum_content_duration;
                            fileTotal += video.fileSize;
                            $('#' + prefix + 'videoTotal').html($.number(video.totalRecords));
                            $('#' + prefix + 'videolinear').html($.number(video.linearFeet,2));
                            $('#' + prefix + 'videofile').html($.number(video.fileSize,2));
                            $('#' + prefix + 'videodigitized').html($.number(video.dRecords));
                            $('#' + prefix + 'videocontentDur').html($.number(video.sum_content_duration,2));
                        }
                        if (typeof element !== "undefined" && typeof element[2] !== "undefined" && element[2].Film) {
                            var film = element[2].Film;
                            total += film.totalRecords;
                            dtotal += film.dRecords;
                            durTotal += film.sum_content_duration;
                            fileTotal += film.fileSize;
                            $('#' + prefix + 'filmTotal').html($.number(film.totalRecords));
                            $('#' + prefix + 'filmlinear').html($.number(film.linearFeet,2));
                            $('#' + prefix + 'filmfile').html($.number(film.fileSize,2));
                            $('#' + prefix + 'filmdigitized').html($.number(film.dRecords));
                            $('#' + prefix + 'filmcontentDur').html($.number(film.sum_content_duration,2));
                        }
                        $('#' + prefix + 'total').html($.number(total));
                        $('#' + prefix + 'dtotal').html($.number(dtotal));
                        $('#' + prefix + 'durTotal').html($.number(durTotal,2));
                        $('#' + prefix + 'fileTotal').html($.number(fileTotal,2));
                    });

                }

            }

        }); // Ajax Call 
    }
}