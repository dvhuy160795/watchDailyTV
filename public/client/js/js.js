var Default = {
    closePopup : function () {
        $("#box-popup").html('');
        $("#box-popup").css('display','none');
    },

    setDateTimeByYear : function (eYear){
        var year = $(eYear).val();
        var month = $("#date-month").val()
        var listOptionDay;
        listOptionDay = Default.builListDay(year,month);
        $("#date-day").html(listOptionDay);

    },
    setDateTimeByMonth : function (eMonth){
        var month = $(eMonth).val();
        var year = $("#date-year").val();
        var listOptionDay;
        listOptionDay = Default.builListDay(year,month);
        $("#date-day").html(listOptionDay);
    },
    setDateTimeByDate : function (){
        console.log(eDate.value);
    },
    showLoading : function (id, src) {
        $("#" +id).attr("src",src);
    },
    builListDay :function (year, month) {
        var listOptionDay = "";
        var countDay;
        var month1 = ['1','3','5','7','8','10','12'];
        var month2 = ['4','6','9','11'];
        var month3 = ['2'];
        if (((year%4 == 0) && (year % 100 != 0)) || (year %400 == 0) ) {
            if (month1.indexOf(month) >= 0) {
                countDay = 31;
            } else if (month2.indexOf(month) >= 0){
                countDay = 30;
            } else {
                countDay = 29;
            }
        } else {
            if (month1.indexOf(month) >= 0) {
                countDay = 31;
            } else if (month2.indexOf(month) >= 0){
                countDay = 30;
            } else {
                countDay = 28;
            }
        }
        for (var i = 1; i <= countDay; i++) {
            listOptionDay += "<option value='"+i+"'>"+i+"</option>";
        }
        return listOptionDay;
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
    showPopupCheckCodeSendedByEmail : function () {
        var url = ROOT_URL + "/User/showpopupcheckcode";
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
    showForgotPassword : function () {
        var url = ROOT_URL + "/User/showpopupforgotpassword";
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
    checkLoginAction :function () {
        var $form = $('#formLogin');
        var url = ROOT_URL + "/User/loginexistuser";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    //reset
                    if (data.intIsOk == -2) {
                        alert(data.message);
                    } else {
                        Default.closePopup();
                    }  
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
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
                    $("#imgLoading").css("display","inline-block");
                    $("#formRegister input").removeClass('bor-red');
                    $("#list_message_error").html("");
                    if (data.intIsOk == -2) {
                        $("#imgLoading").css("display","none");
                        $("#list_message_error").html(data.message);
                        $.each(data.arrItemError, function(key,value){
                            $("#" + value).addClass('bor-red');
                        });
                    } else {
                        User.showPopupCheckCodeSendedByEmail();
                    }  
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
    },

    checkRegisterCodeSendedByMail : function() {
        var $form = $('#formCheckCode');
        var url = ROOT_URL + "/User/checkregistercodesendedbymail";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    if (data.intIsOk == false) {
                        alert(data.message);
                        User.showPopupRegister();
                    } else{
                        User.showPopupLogin();
                    }
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
    },

    loginUserExistAction :function () {
        var url = ROOT_URL + "/User/loginexistuser";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    if (data.intIsOk == false) {
                        alert(data.message);

                    } else{
                        alert("login");
                    }
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
    },

    sendMailForgotPassword : function () {
        var $form = $('#formForgotPassword');
        var url = ROOT_URL + "/User/sendmailforgotpassword";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    if (data.intIsOk == false) {
                        alert(data.message);
                        //User.showPopupRegister();
                    } else{
                        //User.showPopupLogin();
                    }
                },            
                error: function() {                
                    alert('error');
                }
            }
        $form.ajaxForm(strUrl); 
    }

};
//auto load js


