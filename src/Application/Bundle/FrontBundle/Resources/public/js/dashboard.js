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
                formatUrl = baseUrl + 'getFormatCount/' + selectedProject;
                cuUrl = baseUrl + 'getCommercialUniqueCount/' + selectedProject;
                $('#formatCount').highcharts({
                    chart: {
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
                            data: (function () {
                                var data;
                                $.ajax({
                                    url: formatUrl,
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
                
                $('#commercialUnique').highcharts({
                    chart: {
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
        }).change();
    }
}