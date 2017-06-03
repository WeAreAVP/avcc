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
                            $('#' + prefix + 'audioTotal').html(audio.totalRecords);
                            $('#' + prefix + 'audiolinear').html(audio.linearFeet);
                            $('#' + prefix + 'audiofile').html(audio.fileSize);
                            $('#' + prefix + 'audiodigitized').html(audio.dRecords);
                            $('#' + prefix + 'audiocontentDur').html(audio.sum_content_duration);

                        }
                        if (typeof element !== "undefined" && typeof element[1] !== "undefined" && element[1].Video) {
                            var video = element[1].Video;
                            total += video.totalRecords;
                            dtotal += video.dRecords;
                            durTotal += video.sum_content_duration;
                            fileTotal += video.fileSize;
                            $('#' + prefix + 'videoTotal').html(video.totalRecords);
                            $('#' + prefix + 'videolinear').html(video.linearFeet);
                            $('#' + prefix + 'videofile').html(video.fileSize);
                            $('#' + prefix + 'videodigitized').html(video.dRecords);
                            $('#' + prefix + 'videocontentDur').html(video.sum_content_duration);
                        }
                        if (typeof element !== "undefined" && typeof element[2] !== "undefined" && element[2].Film) {
                            var film = element[2].Film;
                            total += film.totalRecords;
                            dtotal += film.dRecords;
                            durTotal += film.sum_content_duration;
                            fileTotal += film.fileSize;
                            $('#' + prefix + 'filmTotal').html(film.totalRecords);
                            $('#' + prefix + 'filmlinear').html(film.linearFeet);
                            $('#' + prefix + 'filmfile').html(film.fileSize);
                            $('#' + prefix + 'filmdigitized').html(film.dRecords);
                            $('#' + prefix + 'filmcontentDur').html(film.sum_content_duration);
                        }
                        $('#' + prefix + 'total').html(Math.round(total*100)/100);
                        $('#' + prefix + 'dtotal').html(Math.round(dtotal*100)/100);
                        $('#' + prefix + 'durTotal').html(Math.round(durTotal*100)/100);
                        $('#' + prefix + 'fileTotal').html(Math.round(fileTotal*100)/100);
                    });

                }

            }

        }); // Ajax Call 
    }
}