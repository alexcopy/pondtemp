var ALEX = ALEX || {};
ALEX.getFilesData = function () {
    $.ajax({
        method: "POST",
        url: "/api/v3/getfilesstats"

    })
        .done(function (msg) {

            setTimeout(function () {
                msg.data.x.unshift('x');
                msg.data.mamacam.unshift('Mama Cam');
                msg.data.koridor.unshift('Koridor');
                msg.data.pond.unshift('Pond');

                ALEX.temps.load({
                    columns: [
                        msg.data.x,
                        msg.data.mamacam,
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
    ALEX.getFilesData(null, null);
});