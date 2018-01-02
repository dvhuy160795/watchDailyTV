var Default = {
    closePopup : function () {
        $("#box-popup").html('');
        $("#box-popup").css('display','none');
    },

    setDateTimeByYear : function (eYear){
        console.log(eYear.value);
    },
    setDateTimeByMonth : function (eMonth){
        console.log(eMonth.value);
    },
    setDateTimeByDate : function (eDate){
        console.log(eDate.value);
    }
}
var User = {
    fileType :"",
    fileSize : 0,
    fileName : "",
    showPopupLogin : function () {
        var url = ROOT_URL + "/User/login";
        var data = {};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                $('#box-popup').html(data);
                $('#box-popup').css('display','block');
            },
            errror: function() {
                alert('error')
            }
        });
    },
    showPopupRegister : function () {
        var url = ROOT_URL + "/User/register";
        var data = {};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                $('#box-popup').html("");
                $('#box-popup').html(data);
                $('#box-popup').css('display','block');
            },
            errror: function() {
                alert('error')
            }
        });
    },
    loadImg : function (input){
        var $avatarUser = $('#avatarUser');
        var reader = new FileReader();

        User.fileSize = input.files[0].size;
        User.fileType = input.files[0].type;

        reader.onload = function (e) {
            $avatarUser.attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    },
    checkRegisterAction : function () {
        var $form = $('#formRegister');
        var url = ROOT_URL + "/User/checkexistuser";

        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    //reset
                    $("#formRegister input").removeClass('bor-red');
                    $("#list_message_error").html("");
                    if (data.intIsOk == -2) {
                        $("#list_message_error").html(data.message);
                        $.each(data.arrItemError, function(key,value){
                            $("#" + value).addClass('bor-red');
                        });
                    } else {
                        
                    }  
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
    },
    checkLoginAction :function () {
        var url = ROOT_URL + ""
    },

};
//auto load js



