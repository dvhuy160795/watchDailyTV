$(document).keypress(function(e) {
    if (e.keyCode == 13) {
        viewVideo.sendComment();
    }
});
var viewVideo = {
    sendComment : function () {
        var comment = $("textarea#box_comment").val();
        var videoCode = $("#video_code_hidden").val();
        var url = ROOT_URL + "/Video/sendcomment";
        var data = {comment:comment, videoCode:videoCode};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                if (data.intIsOk = 1) {
                    $("textarea#box_comment").val("");
                    viewVideo.loadListComment();
                }
            },
            errror: function() {
                alert('error')
            }
        });
    },
    
    loadListComment : function () {
        var url = ROOT_URL + "/Video/loadlistcomment";
        videoCode = $("#video_code_hidden").val();
        var data = {videoCode:videoCode};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
               $("#list_comment").html(data);
               $("#list_comment").scrollTop(1000000);
            },
            errror: function() {
                alert('error')
            }
        });
    }
};
