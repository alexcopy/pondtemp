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
                msg.data.mamakitchen.unshift('MamaKit');
                ALEX.drawTable(msg.data, '');

                ALEX.temps.load({
                    columns: [
                        msg.data.x,
                        msg.data.mamacam,
                        msg.data.koridor,
                        msg.data.pond,
                        msg.data.mamakitchen,
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
            ['Pond', []],
            ['MamaKit', []],
        ]
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    }
});
ALEX.drawTable = function (data) {

    table = '<thead><tr>';

    for (var hd in data.x) {
        table += '<th>' + data.x[hd] + '</th>';
    }
    table += '</tr></thead><tbody>';

    for (var rw in data) {
        table += '<tr>';
        if (rw !== 'x') {
            for (var td in data[rw]) {
                table += '<td class="text-nowrap">' + data[rw][td] + '</td>';
            }
        }
        table += '</tr>';
    }
    table += '</tbody>';
    $("#filestimeline").append(table);
};

$(document).ready(function () {
    ALEX.getFilesData(null, null);
});