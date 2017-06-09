ALEX.sendData = function (start, end) {
    $.ajax({
        method: "POST",
        url: "/api/v3/getfilesstats",
        data: {startDate: start, endDate: end}
    })
        .done(function (msg) {
            ALEX.avgMinMax(msg.data.extr);
            setTimeout(function () {
                msg.data.x.unshift('x');
                msg.data.mamacam.unshift('Mama Cam');
                msg.data.koridor.unshift('Koridor');
                msg.data.pond.unshift('Pond');

                ALEX.temps.load({
                    columns: [
                        msg.data.x,
                        msg.data.mamcam,
                        msg.data.koridor,
                        msg.data.pond
                    ],
                    length: 0,
                    duration: 8500
                });
            });
        })
        .error(function (msg, status) {
            alert(msg.statusText);
        });
};
ALEX.temps = c3.generate({
    bindto: '#qty',
    data: {
        x: 'x',
        columns: [
            ['x', []],
            ['Mama Cam', []],
            ['Koridor', []],
            ['Pond', []]
        ]
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    }
});


$(document).ready(function () {
    ALEX.sendData(null, null);
});