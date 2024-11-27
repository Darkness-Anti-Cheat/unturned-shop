function drawChart(dashboard, data, dataName) {
    $("#" + dashboard).empty();
    $("#" + dashboard).append("<div id='chart'></div>");

    const chart = LightweightCharts.createChart(document.getElementById('chart'), 
    {
        width: document.getElementById(dashboard).offsetWidth,
        height: 400,
        borderVisible: true,
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Magnet,
            vertLine: {
                visible: true,
                labelVisible: true,
                width: 1,
                color: '#C3BCDB44',
                style: LightweightCharts.LineStyle.Solid,
                labelBackgroundColor: 'rgb(0, 0, 0)',
            },
            horzLine: {
                visible: true,
                labelVisible: true,
                color: 'rgb(55,65,146)',
                labelBackgroundColor: 'rgb(0, 0, 0)',
            },
        },
        borderColor: 'rgb(0, 0, 0)',
        layout: {
            background: {
                type: 'solid',
                color: '#fff'
            },
            textColor: 'rgb(0, 0, 0)'
        },
        grid: {
            vertLines: {
                color: 'rgba(0, 0, 0, 0)',
            },
            horzLines: {
                color: 'rgba(0, 0, 0, 0)',
            },
        },
        leftPriceScale: {
            visible: true,
            borderVisible: true,
        },
        rightPriceScale: {
            visible: true,
            borderVisible: true,
        },
        timeScale: {
            minBarSpacing: 0.1,
            rightOffset: 2,
            barSpacing: 0.5,
            fixLeftEdge: true,
            lockVisibleTimeRangeOnResize: true,
            borderVisible: true,
            timeVisible: true,
            secondsVisible: true,
        }
    });

    const series = chart.addAreaSeries({
        title: dataName,
        topColor: 'rgb(216, 11, 209)',
        bottomColor: 'rgba(216, 11, 209, 0.10)',
        lineColor: 'rgb(216, 11, 209)',
        lineWidth: 2,
        priceScaleId: 'left',
        visible: true
    });

    series.setData(data);

    chart.timeScale().fitContent();

    // Esto es un fix para que haga auto resize
    window.onresize = function() {
        chart.applyOptions({
            width: document.getElementById(dashboard).offsetWidth,
            height: 400
        });
        console.log("as")
    }

    chart.applyOptions({
        width: document.getElementById(dashboard).offsetWidth,
        height: 400
    });
}