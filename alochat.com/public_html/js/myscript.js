/**
 * Created by HP on 7/14/2015.
 */
$(document).ready(function(){
    $("#user-filter-button").click(function(){
        /*$("#user-filter1").slideToggle('fast',function(){
        });*/
        $("#user-filter").slideToggle('fast',function(){
        });
    });
    $("#user-filter-button1").click(function(){
        /*$("#user-filter1").slideToggle('fast',function(){
        });*/
        $("#user-filter").slideToggle('fast',function(){
        });
    });


    $("#user-search-button").click(function(){
        $(".user-filter").slideToggle('fast',function(){
        });
    });

    //file upload icon

    $('#share-upload-photo').click(function(){
        $('#shareform-attach').click();
    });


    $('input[name="ShareForm[attach]"]').change(function(){
        var fileName = $(this).val();
        fileName = fileName.substr(0, 10);
        $("#share_filename").html('Image added');

    });

    //file upload icon


    $('.giftId').click(function(){
         $.post(
            "/profile/modal",
            {
                modalId: this.id,
                userId: this.getAttribute('data-uid')
            },
            onAjaxSuccess
        );
    });

    function onAjaxSuccess(data)
    {
        // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
        $("#modal_id").html(data);
     }



    $(".qp-ui-mask-modal").click(function(){
        $("#right-menu").hide('fast');
        $(".qp-ui-mask-modal").css('visibility','hidden');
        $("body").css({'overflow':'auto'});

    });

    $(".right-menu-back").click(function(){
        $("#right-menu").hide('fast');
        $(".qp-ui-mask-modal").css('visibility','hidden');
        $("body").css({'overflow':'auto'});

    });



    $(".dropdown-id").click(function() {
        $("#right-menu").show('fast');
         $(".qp-ui-mask-modal").css({'visibility':'visible','opacity':1});
        $("body").css({'overflow':'hidden'});
    });

    $('#control-smile').click(function () {
        collapseSmile();
    });

    $('html').click(function (e) {
        if (e.target.id == 'wink-box' || e.target.id == 'control-smile' || e.target.id == 'control-wink') {
        //
       } else {
            closeSmile();
        }
    });


});

function closeSmile() {

    var $winkBox = $("#wink-box");

    if ($winkBox.hasClass("show-wink-box")) {

        $winkBox.css({
            display: 'none'

        });
        $winkBox.removeClass("show-wink-box");
    }
}
function collapseSmile() {

    var $winkBox = $("#wink-box");

    if ($winkBox.hasClass("show-wink-box")) {

        $winkBox.css({
            display: 'none'
        });
        $winkBox.removeClass("show-wink-box");
    }
    else {
        $winkBox.css({
            display: 'block',
            opacity: "1"
        });
        $winkBox.addClass("show-wink-box");
    }
}

function addSmile(elem) {
    var messageInput = $('#share-text');
    var messageVal = messageInput.val();
    var ic = elem.getAttribute('rel');
    var message = ic;

    messageInput.val(messageVal+" "+" "+message);

    //var data = $("#message-form").serialize();
    //sendMessageAjax2(data, message);
    //messageInput.val('').focus();
    //closeWink();

}

function likeShare(id){
    var id = parseInt(id);
    if(id>0){
        $.get(
            '/profile/like-share/?id='+id,
            function(data){
                if (data['success'] == 1) {
                    if(data["likeCount"]>0){
                        $('#like_count_'+id).html(data["likeCount"]);
                    } else {
                        $('#like_count_'+id).html("");

                    }
                    $("#share-img-"+id).attr("src", '/images/icons/share/liked.png');

                } else if(data['success'] == 2){
                    if(data["likeCount"]>0){
                        $('#like_count_'+id).html(data["likeCount"]);
                    }else {
                        $('#like_count_'+id).html("");

                    }
                    $("#share-img-"+id).attr("src", '/images/icons/share/like.png');
                }
                //location.reload();
            }
        );
    }
}