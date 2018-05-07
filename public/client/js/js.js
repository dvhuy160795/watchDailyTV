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
    },
    loadListSelected : function (addressGet,addressFill){
        var url = ROOT_URL + "/Index/loadlistselect";
        var code = $('#'+addressGet).val();
        var data = {code:code,address:addressGet};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                $('#'+addressFill).removeClass('display-none');
                $('#lb_'+addressFill).removeClass('display-none');
                $('#'+addressFill).html(data);
            },
            errror: function() {
                alert('error')
            }
        });
    },
    setValueChangeSelect : function (e, idInputCode, idInputValue) {
        if (idInputCode !== "") {
            $("#input_" + idInputCode).val(e.selectedIndex);
        }
        
        if (idInputValue !== "") {
            $("#input_" + idInputValue).val(e.options[e.selectedIndex].text);
        }
    },
    uploadVideo : function (videoCode) {
        var url = ROOT_URL + "/Video/addvideo";
        var data = {videoCode: videoCode};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                $('#boxContentVideo').html(data);
            },
            errror: function() {
                alert('error')
            }
        });
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
    loadImg : function (input, autoImg, itemSetvalue = ""){
        var $avatarUser = $('#'+autoImg);
        var reader = new FileReader();

        User.fileSize = input.files[0].size;
        User.fileType = input.files[0].type;

        reader.onload = function (e) {
            $avatarUser.attr('src', e.target.result);
            $("#" + itemSetvalue).val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    },
    autoLoadEl : function (el, elDisplay, isLoading, elLoading, itemValue = ""){
        var $avatarUser = $('#'+elDisplay);
        var reader = new FileReader();

        User.fileSize = el.files[0].size;
        User.fileType = el.files[0].type;
        if (isLoading == 1) {
            $("#" + elLoading).show();
        }      
        reader.onload = function (e) {
            $avatarUser.attr('src', e.target.result);
            if (isLoading == 1) {
                $("#" + elLoading).hide();
                $("#" + itemValue).val(e.target.result);
            }
        };
        reader.readAsDataURL(el.files[0]);
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
                        location.reload();
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
    },

    saveUser : function () {
        var $form = $('#formEdituser');
        var url = ROOT_URL + "/User/saveedituser";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {},            
            success: function(data) {
                if (data.intIsOk == 1) {
                    alert("Edit success!!");
                    location.reload();
                } 
            },            
            error: function() {                
                alert('error');
            }
        }
        $form.ajaxForm(strUrl); 
    },
    
    logoutUser : function () {
        var url = ROOT_URL + "/User/logout";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {},            
            success: function(data) {
                location.reload();
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    }, 
    
    loadPagination : function(controller, action, page, blockLoadlist, typeCode = "") {
        var url = ROOT_URL + "/" + controller + "/" + action;
        var strUrl = {            
            url: url,                        
            data: {p:page, typeCode: typeCode},            
            success: function(data) {
                $("#" + blockLoadlist).html(data);
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
};

var Video = {
    showOvlAddVideo : function (id){
        var $form = $('#formEdituser');
        var url = ROOT_URL + "/User/saveedituser";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {},            
            success: function(data) {
                if (data.intIsOk == 1) {
                    alert("Edit success!!");
                    location.reload();
                } 
            },            
            error: function() {                
                alert('error');
            }
        }
        $form.ajaxForm(strUrl);
    },
    saveVideo : function () {
        var $form = $('#formVideo');
        var url = ROOT_URL + "/Video/savevideo";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {},            
            success: function(data) {
                if(data.intIsOk == 1){
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
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


