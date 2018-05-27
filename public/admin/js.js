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
                    }  addadmin
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
                    console.log(data);
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
                    console.log(data);
                    $('#box_content_page_admin').html(data);
                },            
                error: function() {                
                    alert('error');
                }
            };
        $.ajax(strUrl);
    },
};
//auto load js


