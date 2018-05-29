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
    
    setSelectedItemMenuLeft : function (el) {
        $.each($('ul#exampleAccordion li'),function(index,item){
            $(item).removeClass("selected");
        });
        $(el).addClass('selected');
    } ,
    
    setSelectedItemList : function (el, idBox) {
        $.each($('#'+idBox+' a'),function(index,item){
            $(item).removeClass("selected-list");
        });
        $(el).addClass('selected-list');
    } ,
    
    removeDisableDetailAdmin : function (idInput){
        $('#' + idInput).removeAttr("disabled");
        $('#box_btn_save').removeClass('display-none');
    }
}
var Admin = {
    login : function () {
        var $form = $('#loginAdmin');
        var url = ROOT_URL + "/Index/checklogin";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {},            
                success: function(data) {
                    //reset
                    if (data.intIsOk == 0) {
                        alert(data.message);
                    } else {
                        $(location).attr('href', 'index');
                    }
                },            
                error: function() {                
                    alert('error');
                }
            };
        $form.ajaxForm(strUrl);
    },
    logout : function () {
        var url = ROOT_URL + "/Index/logoutadmin";
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
            };
        $.ajax(strUrl);
    },
    showFormAddAdmin : function () {
        var url = ROOT_URL + "/Index/addadmin";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    showFormAddGroupPermission : function () {
        var url = ROOT_URL + "/Index/formaddgrouppermission";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    saveGroupPermission : function (idGroup) {
        var $form = $('#formAddGroupPermission');
        var url = ROOT_URL + "/Index/savegrouppermission";
        var strUrl = {            
                url: url,            
                type: 'post',
                dataType: "json",            
                data: {idGroup:idGroup},            
                success: function(data) {
                    //reset
                    if (data.intIsOk == 0) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        if (data.isLogout == true) {
                            Admin.logout();return;
                        }
                        Admin.loadListGroupPermission();
                    }
                },            
                error: function() {                
                    alert('error');
                }
            };
        $form.ajaxForm(strUrl);
    },
    
    loadListGroupPermission :function () {
        var url = ROOT_URL + "/Index/loadlistgrouppermission";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadListGroupPermissionOnlyListGroup :function () {
        var url = ROOT_URL + "/Index/loadlistgrouppermissiononlylistgroup";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#listPermissionGroup').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadGroupDetail :function (idGroup) {
        var url = ROOT_URL + "/Index/loadgroupdetail";
        var strUrl = {            
                url: url,    
                type: 'post',
                dataType: "json",  
                data: {idGroup:idGroup},            
                success: function(data) {
                    $('#box_group_detail').html(data.groupDetail);
                    $('#box_group_detail_list_member').html(data.listMember);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    saveAdmin : function (idAdmin) {
        var $form = $('#formAddAdmin');
        var url = ROOT_URL + "/Index/saveadmin";
        var strUrl = {            
            url: url,            
            type: 'post',
            dataType: "json",            
            data: {idAdmin:idAdmin},            
            success: function(data) {
                if(data.intIsOk == 1){
                    alert(data.message);
                    Admin.loadListMember();
                    Admin.loadAdminDetail(data.idAdmin);
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
    loadListMember :function () {
        var url = ROOT_URL + "/Index/loadlistmember";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadAdminDetail :function (idAdmin) {
        var url = ROOT_URL + "/Index/loadadmindetail";
        var strUrl = {            
                url: url,     
                data: {idAdmin:idAdmin},            
                success: function(data) {
                    $('#box_admin_detail').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    showUsersInListVideo :function () {
        var url = ROOT_URL + "/Index/showusersinlistvideo";
        var strUrl = {            
                url: url,                  
                data: {},            
                success: function(data) {
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadListVideoByUser: function (idUser) {
        var url = ROOT_URL + "/Index/loadlistvideobyuser";
        var strUrl = {            
                url: url,                  
                data: {idUser:idUser},            
                success: function(data) {
                    $('#box_list_by_user').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    searchUser: function () {
        var url = ROOT_URL + "/Index/loadlistuserbyadmin";
        var valueSearch = $('#valueSearchUser').val();
        var strUrl = {            
                url: url,                  
                data: {valueSearch:valueSearch},            
                success: function(data) {
                    $('#listUserInAdmin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadUserInfo: function (idUser) {
        var url = ROOT_URL + "/Index/loaduserinfo";
        var strUrl = {            
                url: url,                  
                data: {idUser:idUser},            
                success: function(data) {
                    $('#box_info_by_user').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
    
    loadVideoByList : function (idList) {
        var url = ROOT_URL + "/Index/loadvideobylist";
        var strUrl = {            
                url: url,                  
                data: {idList:idList},            
                success: function(data) {
                    $('#allItemVideo').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    }
};
//auto load js


