var ALEX = ALEX || {};
ALEX.camdelete = function (url) {
    if (location.protocol == 'http:') {
        url = url.replace('http', 'https');
    }
    var parts = url.split('/');
    var id = parts[parts.length - 1];
    $.ajax(
        {
            url: url,
            type: 'DELETE',
            dataType: "JSON",
            data: {
                "id": id,
                "_method": 'DELETE'
            },
            success: function () {
                console.log("it Work");
                $("#camrow_" + id).remove();
            }
        });

    console.log("It failed");
}