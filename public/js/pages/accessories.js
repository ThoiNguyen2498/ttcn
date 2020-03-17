$(document).ready(function(){var page=1;var isEnd=false;var load_image=$('#load_image').val();$(window).scroll(function(){if($(window).scrollTop()+$(window).height()==$(document).height()){
    var pageNumber = parseInt($("#page").val())+1;
    var link =location.href;
    // var pageNumber = 1;
    $.get("http://localhost/tmobile/phu-kien/loadListSP",{page:pageNumber,link:link},function (data) {
        if (data.length == 115){
            $("#ketQua").remove();
        }
        $(".product-list").append(data);
        $("#page").val(pageNumber);
    });
}});$('.viewmore .more').click(function(){page++;if(isEnd==false){mycontent(page);}})
function mycontent(page){$.ajax({type:'GET',url:'/ajax'+window.location.pathname+'?page='+page,beforeSend:function(){$('.view-ajax-more').css('display','block');},success:function(result){$('.view-ajax-more').css('display','none');if(result[0]==0){$('.alert_message').empty().append("Phụ kiện cho di động trên website đã hết! Không thể tải thêm").css('display','block');setInterval(function(){$('.alert_message').fadeOut(1000);},5000);$('.viewmore .more').css('display','none');isEnd=true;}
$.each(result[1],function(key,value){$('.product-list').append(value);});$(function(){$("img[src='"+load_image+"']").lazyload({effect:"fadeIn",failure_limit:10,});});}})}});