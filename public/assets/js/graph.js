var ALEX = ALEX || {};
ALEX.sendData = function (start, end) {
    $.ajax({
        method: "POST",
        url: "/api/v3/getdate",
        data: {startDate: start, endDate: end}
    })
        .done(function (msg) {
            ALEX.avgMinMax(msg.data.extr);
            setTimeout(function () {
                msg.data.x.unshift('x');
                msg.data.StreetTemp.unshift('Street Temp');
                msg.data.PondTemp.unshift('Pond Temp');
                msg.data.StreetHum.unshift('StreetHum');
                msg.data.ShedHum.unshift('ShedHum');
                msg.data.Pressure.unshift('Pressure');
                msg.data.shedTemp.unshift('Shed Temp');
                ALEX.temps.load({
                    columns: [
                        msg.data.x,
                        msg.data.StreetTemp,
                        msg.data.PondTemp
                    ],
                    length: 0,
                    duration: 8500
                });
                ALEX.humid.load({
                    columns: [
                        msg.data.x,
                        msg.data.StreetHum,
                        msg.data.ShedHum
                    ],
                    length: 0,
                    duration: 8500
                });
                ALEX.press.load({
                    columns: [
                        msg.data.x,
                        msg.data.Pressure
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
    bindto: '#temp',
    data: {
        x: 'x',
        columns: [
            ['x', []],
            ['Street Temp', []],
            ['Pond Temp', []],
            ['Shed Temp', []]
        ]
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    }
});
ALEX.humid = c3.generate({
    bindto: '#humid',
    data: {
        x: 'x',
        columns: [
            ['x', []],
            ['StreetHum', []],
            ['ShedHum', []]
        ]
    },
    axis: {
        x: {
            type: 'category'
        }
    }
});

ALEX.press = c3.generate({
    bindto: '#press',
    data: {
        x: 'x',
        columns: [
            ['x', []],
            ['Pressure', []],
        ]
    },
    axis: {
        x: {
            type: 'category'
        }
    }
});
ALEX.avgMinMax = function (data) {
    var ids = [
        "curstr",
        "curpnd",
        "maxstr",
        "minstr",
        "maxpnd",
        "minpnd",
        "avgstr",
        "avgpnd"];

    for(var i in ids){
        id = ids[i];
        $('#'+id).text(' '+ data[id]);
    }
}

$(document).ready(function () {
    ALEX.sendData(null, null);
    $('#daterange').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        dateLimit: {
            "days": 60
        },
        timePickerIncrement: 20,
        locale: {
            format: 'DD/MM/YYYY H:mm'
        }
    }, function (start, end, label) {
        ALEX.sendData(start._d, end._d);
    });
});