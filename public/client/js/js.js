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
    uploadVideo : function (id) {
        var url = ROOT_URL + "/Video/addvideo";
        var data = {id: id};
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
    },
    searchVideo : function(el) {
        var value = el.value;
        if (value.trim() !== "") {
            var url = ROOT_URL + "/Video/searchvideo";
            var data = {value:value};
            $.ajax({
                url:url,
                data:data,
                success: function(data){
                    $('#box_search_video_list_value').html(data);
                },
                errror: function() {
                    alert('error')
                }
            });
        }
    },
    filterListVideo: function (fieldOrder) {
        var url = ROOT_URL + "/Index/listvideobymenu";
        var data = {value:fieldOrder};
        $.ajax({
            url:url,
            data:data,
            success: function(data){
                $('#box_content_home').html(data);
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
                window.location.href = "http://localhost:8000/watchDailyTV/public/index.php";
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
    saveVideo : function (id) {
        var $form = $('#formVideo');
        var url = ROOT_URL + "/Video/savevideo";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {id:id},            
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
    },
    likeVideo :function (idVideo, isLike,el) {
        var url = ROOT_URL + "/Video/likevideo";
        var strUrl = {            
            url: url,  
            data: {idVideo:idVideo,isLike:isLike},  
            dataType: "json",
            success: function(data) {
                if (data.intIsOk == 2) {
                    alert(data.message);
                } else {
                    if (el.id == 'iconTotalLike') {
                        if (((el.className).split(' '))[2] == "w3-text-white") {
                            $("#" + el.id).removeClass('w3-text-white');
                            $("#" + el.id).addClass('w3-text-blue');
                            $("#iconTotalDisLike").addClass('w3-text-white');
                            $("#iconTotalDisLike").removeClass('w3-text-blue');
                        } else {
                            $("#" + el.id).removeClass('w3-text-blue');
                            $("#iconTotalLike").addClass('w3-text-white');
                        }
                    } else {
                        if (((el.className).split(' '))[2] == "w3-text-white") {
                            $("#" + el.id).removeClass('w3-text-white');
                            $("#" + el.id).addClass('w3-text-blue');
                            $("#iconTotalLike").addClass('w3-text-white');
                            $("#iconTotalLike").removeClass('w3-text-blue');
                        } else {
                            $("#" + el.id).removeClass('w3-text-blue');
                            $("#" + el.id).addClass('w3-text-white');
                        }
                    }
                    $('#totalDisLike').html(data.totalDisLike);
                    $('#totalLike').html(data.totalLike);
                }
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    deleteVideo : function (idVideo) {
        if (confirm('Do you want detele this video?')) {
            var url = ROOT_URL + "/Video/deletevideo";
            var strUrl = {            
                url: url,  
                data: {idVideo:idVideo},  
                success: function(data) {
                    location.reload();
                },            
                error: function() {                
                    alert('error');
                }
            }
            $.ajax(strUrl);
        }
    }
};
var VideoList = {
    listVideoAdd : [],
    addListVideo :function (idList) {
        var url = ROOT_URL + "/Videolist/add";
        var strUrl = {            
            url: url,  
            data: {idList:idList},            
            success: function(data) {
                $("#box-popup").html(data);
                $('#box-popup').css('display','block');
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl); 
    },
    saveListVideo : function (idList) {
        var $form = $('#formAddList');
        var url = ROOT_URL + "/Videolist/save";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {idList:idList, listVideoAdd:VideoList.listVideoAdd},            
            success: function(data) {
                if(data.intIsOk == 1){
                    VideoList.loadListVideoByUser();
                    alert(data.message);
                    Default.closePopup();
                } else {
                    alert(data.message);
                }
            },            
            error: function() {                
                alert('error');
            }
        }
        $form.ajaxForm(strUrl);
    },
    addVideoTolist :function (id,boxRemove) {
        var url = ROOT_URL + "/Video/addvideotolist";
        var strUrl = {            
            url: url,                        
            data: {id:id, idList:$('#id_list_video').val()}, 
            type: 'post',
            dataType: "json",
            success: function(data) {
                if (data.isOk == 1) {
                    $('#' + boxRemove).remove();
                    VideoList.loadVideosNotInList($('#id_list_video').val());
                    VideoList.loadVideosInList($('#id_list_video').val());
                }else {
                    alert("error");
                }
                
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    removeVideoTolist :function (id,boxRemove) {
        var url = ROOT_URL + "/Video/removevideotolist";
        var strUrl = {            
            url: url,                        
            data: {id:id}, 
            type: 'post',
            dataType: "json",
            success: function(data) {
                if (data.isOk == 1) {
                    $('#' + boxRemove).remove();
                    VideoList.loadVideosNotInList($('#id_list_video').val());
                    VideoList.loadVideosInList($('#id_list_video').val());
                }else {
                    alert("error");
                }
                
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    loadVideosNotInList : function (idList) {
        var url = ROOT_URL + "/Video/loadvideosnotinlist";
        var strUrl = {            
            url: url,                        
            data: {idList:idList},            
            success: function(data) {
                    $('#box_video_add_list').html(data);
                
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    loadVideosInList : function (idList) {
        var url = ROOT_URL + "/Video/loadvideosinlist";
        var strUrl = {            
            url: url,                        
            data: {idList:idList},            
            success: function(data) {
                    $('#box_video_in_list').html(data);

                
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    loadVideosByList :function (idList) {
        var url = ROOT_URL + "/Video/loadvideobylist";
        var strUrl = {            
            url: url,                        
            data: {video_list_code:idList},            
            success: function(data) {
                $("#list_video").html(data);
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    loadListVideoByUser : function(){
        var url = ROOT_URL + "/Videolist/loadlistvideobyuser";
        var strUrl = {            
            url: url,                        
            data: {},            
            success: function(data) {
                $("#box_list_video").html(data);
            },            
            error: function() {                
                alert('error');
            }
        }
        $.ajax(strUrl);
    },
    deleteList :function (idList) {
        if (confirm('Do you want detele this list video?')) {
            var url = ROOT_URL + "/Videolist/deletelistvideo";
            var strUrl = {            
                url: url,  
                data: {idList:idList},  
                success: function(data) {
                    VideoList.loadListVideoByUser();
                    Default.closePopup();
                },            
                error: function() {                
                    alert('error');
                }
            }
            $.ajax(strUrl);
        }
    }
};

//auto load js


