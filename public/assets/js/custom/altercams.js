var ALEX = ALEX || {};

ALEX.camdelete = function (id) {
    var url = window.location.toString();
    url=url.replace(/\/index|\/show/i, '');
    var token =
        $.ajax(
            {
                url: url + '/' + id,
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_method": 'DELETE'
                },
                success: function () {
                    console.log("it Work");
                    $("#camrow_"+id).remove();
                }
            });

    console.log("It failed");
}
ALEX.camedit = function (id) {

}
ALEX.camshow = function (id) {

}