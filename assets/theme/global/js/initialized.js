(function () {
    "use strict"

    const amountChart = document.querySelector("#subscription-chart");
    if (amountChart) {

        const chartData = JSON.parse(amountChart.getAttribute('data-chartData'));
        const toolTipTheme = amountChart.getAttribute('data-tool-tip-theme');
        const legendTheme = amountChart.getAttribute('data-legend-theme');
        const days = chartData.dates;
        const newUsersData = chartData.newUsers;
        const subscriptionsData = chartData.subscriptions;
        const options = {
            series: [{
                name: 'New Users',
                data: newUsersData,
                
            }, {
                name: 'Subscriptions',
                data: subscriptionsData
            }],
            legend: {
                labels: {
                    useSeriesColors: true
                },
              
            },
            chart: {
                height: 320,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: [
                "var(--color-primary)",
                "var(--color-trinary)",
            ],
            fill: {
                type: "gradient",
                gradient: {
                    shade: 'white',
                    type: "horizontal",
                    shadeIntensity: 0.2,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.08,
                    opacityTo: 0.08,
                    stops: [0, 0, 100],
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: days,
                labels: {
                    style: {
                        colors: legendTheme, 
                        fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 400,
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        if (value % 100 === 0) {
                            return value.toString();
                        } else {
                            return '';
                        }
                    }
                },
                style: {
                    colors: legendTheme, 
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400,
                }
                
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy'
                },
                y: {
                    formatter: function(value) {
                        return value.toString(); 
                    }
                },
                theme: toolTipTheme
            },
        };

        // Render ApexCharts with options
        const chart = new ApexCharts(amountChart, options);
        chart.render();
    }
    
    const userChart = document.querySelector("#application_usage");
    if (userChart) {
        var sms_heading = $("#application_usage").data('sms-heading');
        var sms_count = $("#application_usage").data('sms');
        var sms_color = $("#application_usage").data('sms-color');
        var whatsapp_heading = $("#application_usage").data('whatsapp-heading');
        var whatsapp_count = $("#application_usage").data('whatsapp');
        var whatsapp_color = $("#application_usage").data('whatsapp-color');
        var email_heading = $("#application_usage").data('email-heading');
        var email_count = $("#application_usage").data('email');
        var email_color = $("#application_usage").data('email-color');

        var options = {
            series: [sms_count, whatsapp_count, email_count],
            chart: {
                type: 'donut',
                width: '100%',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: [sms_color, whatsapp_color, email_color], 
            labels: [sms_heading, whatsapp_heading, email_heading],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return val.toFixed(1) + "%";
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 1,
                    opacity: 0.45
                },
                style: {
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'bold',
                    colors: [sms_color, whatsapp_color, email_color]
                },
                background: {
                    enabled: true,
                    foreColor: '#fff',
                    padding: 4,
                    borderRadius: 2,
                    borderWidth: 1,
                    borderColor: '#fff',
                    opacity: 0.9,
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                fontSize: '14px',
                fontFamily: 'Helvetica, Arial',
                fontWeight: 400,
                formatter: function(seriesName, opts) {
                    return seriesName + ": " + opts.w.globals.series[opts.seriesIndex] + "";
                },
                labels: {
                    colors: [sms_color, whatsapp_color, email_color],
                    useSeriesColors: true
                },
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    radius: 12,
                    offsetX: 0,
                    offsetY: 0
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                },
                onItemClick: {
                    toggleDataSeries: true
                },
                onItemHover: {
                    highlightDataSeries: true
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: true,
                    customScale: 1,
                    offsetX: 0,
                    offsetY: 0,
                    dataLabels: {
                        offset: -5,
                        minAngleToShowLabel: 10
                    },
                    donut: {
                        size: '70%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '22px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 600,
                                color: undefined,
                                offsetY: -5,
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                color: "var(--text-primary)",
                                offsetY: 16,
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                fontSize: '22px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 600,
                                color: "var(--text-primary)",
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => {
                                        return a+b;
                                    }, 0);
                                }
                            }
                        }
                    },
                    borderRadius: 10 // Add this line to round the edges
                }
            },
            stroke: {
                show: false,
                width: 2,
                colors: ['#fff'],
            },
            tooltip: {
                enabled: false,
                shared: true,
                followCursor: true,
                intersect: false,
                inverseOrder: false,
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    return '<div class="arrow_box">' +
                        '<span>' + w.globals.labels[seriesIndex] + ': ' + series[seriesIndex] + '%</span>' +
                        '</div>';
                },
                style: {
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif'
                },
                onDatasetHover: {
                    highlightDataSeries: true,
                },
                theme: 'dark',
                x: {
                    show: true,
                    format: 'dd MMM',
                    formatter: undefined,
                },
                y: {
                    formatter: function(value) {
                        return value + "%";
                    },
                    title: {
                        formatter: function(seriesName) {
                            return seriesName;
                        }
                    },
                },
                z: {
                    formatter: undefined,
                    title: 'Size: '
                },
                marker: {
                    show: true,
                },
                fixed: {
                    enabled: false,
                    position: 'topRight',
                    offsetX: 0,
                    offsetY: 0,
                }
            },
            responsive: [{
                breakpoint: 1200,
                options: {
                    chart: {
                        width: 500
                    },
                    legend: {
                        position: 'right'
                    }
                }
            },{
                breakpoint: 1025,
                options: {
                    chart: {
                        width: 500
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }, {
                breakpoint: 620,
                options: {
                    chart: {
                        width: 470
                    },
                    legend: {
                        position: 'right'
                    }
                }
            },{
                breakpoint: 570,
                options: {
                    chart: {
                        width: 410
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }, {
                breakpoint: 480,
                options: {
                    chart: {
                        width: 390
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }, {
                breakpoint: 450,
                options: {
                    chart: {
                        width: 330
                    },
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    name: {
                                        fontSize: '15px',
                                        fontWeight: 300,
                                    },
                                    value: {
                                        fontSize: '12px',
                                        fontWeight: 200,
                                    },
                                    total: {
                                        fontSize: '15px',
                                        fontWeight: 300,
                                        style: {
                                            letterSpacing: '0'
                                        },
                                    }
                                }
                            }
                        }
                    }
                }
            }, {
                breakpoint: 380,
                options: {
                    chart: {
                        width: 310
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            },{
                breakpoint: 330,
                options: {
                    chart: {
                        width: 280
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }]
        };

        var chart = new ApexCharts(userChart, options);
        chart.render();
    }

    const mySwiper = document.querySelector('.mySwiper');
    if (mySwiper) {
        new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            }
        });
    }

    // Flatpickr Initialized
    const singleDate = document.querySelectorAll(".singleDatePicker");
    if (singleDate) {
        singleDate.forEach(data => {
            flatpickr(data, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        })
    }

    const singleDateTime = document.querySelectorAll(".singleDateTimePicker");
    if (singleDateTime) {
        singleDateTime.forEach(data => {
            flatpickr(data, {
                altInput: true,
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        })
    }

    // Tooltip Initialized
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    if (tooltips) {
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip, { boundary: document.body })
        })
    }

}())