/**
 * Dashboard class
 * 
 * @returns {Dashboard}
 */
function Dashboard() {

    var selfObj = this;
    var baseUrl = null;

    /**
     * Set the base url.
     * @param {string} base_url
     * 
     */
    this.setBaseUrl = function (base_url) {
        baseUrl = base_url;
    }

    /**
     * 
     * @returns {undefined}
     */
    this.bindAll = function () {
        selfObj.onChangeProjects();
    }
    /**
     * 
     * @returns {undefined}
     */
    this.onChangeProjects = function () {
        $('#projects').change(function () {
            var selectedProject = $(this).val();
            if (selectedProject) {
                url = baseUrl + 'getFormatCount/' + selectedProject;
//                formats = [["1 Inch Open Reel Audio",4],["1 Inch Open Reel Video",2],["1\/2 Inch Open Reel Audio",4],["1\/2 Inch Open Reel Audio - Digital",4],["1\/2 Inch Open Reel Video",1],["1\/4 Inch Open Reel Audio",29],["1\/4 Inch Open Reel Video",2],["1610\/1630 (U-matic)",2],["16mm",5],["17mm",1],["2 Inch Open Reel Audio",3],["2 Inch Open Reel Video",2],["35mm",1],["45 RPM Disc",2],["70mm",3],["78 RPM Disc",3],["8-Track",3],["8mm",11],["9.5mm",1],["ADAT (VHS)",8]];
////                chart = new Highcharts.Chart({
//                $('#formatCount').highcharts({
//                    chart: {
////                        renderTo: 'formatCount',
//                        type: 'column'
//                    },
//                    title: {
//                        text: ''
//                    },
//                    subtitle: {
//                        text: ''
//                    },
//                    credits: {
//                        enabled: false
//                    },
//                    xAxis: {
//                        type: 'category',
//                        labels: {
//                            rotation: -90,
//                            style: {
//                                fontSize: '13px',
//                                fontFamily: 'Verdana, sans-serif'
//                            }
//                        }
//                    },
//                    yAxis: {
//                        title: {
//                            text: 'Total count per format'
//                        }
//                    },
//                    legend: {
//                        enabled: false
//                    },
//                    plotOptions: {
//                        series: {
//                            borderWidth: 0,
//                            dataLabels: {
//                                enabled: true,
//                                format: '{point.y}'
//                            }
//                        }
//                    },
//                    tooltip: {
//                        headerFormat: '<span><b>{series.name}: </b></span>',
//                        pointFormat: '<span style="color:{point.color}">{point.name}</span><br/><b>Total:</b>{point.y}<br/>'
//                    },
//                    series: [{
//                            name: 'Format',
//                            colorByPoint: true,
//                            data: formats
//                        }]
//                });

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        if (response != "") {   
                            console.log(response);
                            $('#formatCount').highcharts({
                                chart: {
                                    renderTo: 'formatCount',
                                    type: 'column'
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
                                        name: 'Format',
                                        colorByPoint: true,
                                        data: response
                                    }]
                            });
                        }
                    }

                }); // Ajax Call 
            }
        }).change();
    }
}