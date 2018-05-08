$(document).keypress(function(e) {
    if (e.keyCode == 13) {
        viewVideo.sendComment();
    }
});
var viewVideo = {
    sendComment : function () {
        var comment = $("#box_comment").val();
        var videoCode = $("#video_code_hidden").val();
        var url = ROOT_URL + "/Video/sendcomment";
        var data = {comment:comment, videoCode:videoCode};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                
            },
            errror: function() {
                alert('error')
            }
        });
    },
};