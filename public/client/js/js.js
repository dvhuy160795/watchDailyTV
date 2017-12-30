var ROOT_URL = "http://localhost/watchDailyTV/public/";
var User = {
    showPopupLogin : function () {
        var url = ROOT_URL + "User/login";
        var data = {};
        $.ajax({
            url:url,
            data:data,
            success: function(data){alert(data)},
            errror: function() {alert(2)}
        });
        document.getElementById('id01').style.display='block'
    },
};


