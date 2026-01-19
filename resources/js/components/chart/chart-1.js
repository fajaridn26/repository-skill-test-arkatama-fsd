export const initChartOne = () => {
    const chartElement = document.querySelector("#chartOne");
    if (!chartElement) return;

    function formatRupiah(value) {
        return new Intl.NumberFormat("id-ID", {
            style: "decimal",
            // currency: "IDR",
            minimumFractionDigits: 0,
        }).format(value);
    }

    window.chartOne = new ApexCharts(chartElement, {
        series: [
            {
                name: "Pendapatan",
                data: [],
            },
        ],
        colors: ["#fa891a"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "bar",
            height: 300,
            width: 1160,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "39%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 4,
            colors: ["transparent"],
        },
        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "left",
            fontFamily: "Outfit",
            markers: {
                radius: 99,
            },
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return formatRupiah(val);
                },
            },
        },
        grid: {
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        fill: {
            opacity: 1,
        },

        tooltip: {
            x: {
                show: false,
            },
            y: {
                formatter: function (val) {
                    return formatRupiah(val);
                },
            },
        },
    });

    window.chartOne.render();
};

export default initChartOne;
