/**
 * Created by Elvin Valiev on 01.05.2015.
 */

$(document).ready(function () {
    //scrollChatArea();
    $('#message-text').keydown(function (event) {
        if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {

            $(this).val($(this).val() + "\n");
            return true;
        } else if (event.keyCode == 13) {

            sendMessage2();
            $(this).val('').focus();
            return false;
        }
    });


    $('#control-wink').click(function () {
         collapseWink();
    });

    getMessages();
    scrollChatArea();

});

function sendMessage() {

    var messageInput = $('#message-text');
    var message = messageInput.val().trim();
    if (message != '') {

        var data = $("#message-form").serialize();
        sendMessageAjax(data, message);

        messageInput.val('').focus();
    }

}

function sendMessage2() {

    var messageInput = $('#message-text');
    var message = messageInput.val().trim();
    if (message != '') {

        var data = $("#message-form").serialize();
        sendMessageAjax2(data, message);

        messageInput.val('').focus();
    }

}
function sendSmile(elem) {
    var messageInput = $('#message-text');
    var messageVal = messageInput.val();
    var ic = elem.getAttribute('rel');
       var message = ic;

    messageInput.val(messageVal+" "+" "+message);

    //var data = $("#message-form").serialize();
    //sendMessageAjax2(data, message);
    //messageInput.val('').focus();
    //closeWink();

}
function getMessages() {
    $.ajax({
        type: "GET",
        url: '/messages/get-new/?id=' + globalCid
    }).done(function (data) {

        var message;
        for (var i = 0; i < data.messages.length; i++) {
            var local = new Date();
            var newMessage = "<li class='other'>"+
                "<div class='avatar'><img src='" + globalProfilePhoto + "' draggable='false'/></div>"+
                "<div class='msg message'>"+
                "<p>"+ data.messages[i].reply +" <time style='margin-top: 3px;'>" + data.messages[i].time + "</time></p>"+
                "</div></li>";


            $("#activity").append(newMessage);
            scrollChatArea();
        }

        var allMessageCount = 0;
        for (var i = 0; i < data.conversationMessages.length; i++) {

            var cid = parseInt(data.conversationMessages[i].conversation_id);
            var count = parseInt(data.conversationMessages[i].count);
            allMessageCount+=count;
            var conversationItem = $("#conversation_" + cid);

            if (conversationItem) {

                var dataCounter = parseInt(conversationItem.attr('data-counter'));


                if (count > dataCounter) {
                    conversationItem.attr('data-counter', count);
                    conversationItem.addClass('is_new');
                }
            }
        }

        var newMsgCount = allMessageCount;

        var newMsgIndicator = $(".new-message-count");

        var msgCount = parseInt(newMsgIndicator.attr('data-val'));

        if (newMsgCount != msgCount && newMsgCount > 0) {

            newMsgIndicator.css("display", "block");
            newMsgIndicator.attr('data-val', newMsgCount);
            newMsgIndicator.html(newMsgCount);
        }
    });
}
function closeWink() {

    var $winkBox = $("#wink-box");

    if ($winkBox.hasClass("show-wink-box")) {

        $winkBox.css({
            display: 'none'

        });
        $winkBox.removeClass("show-wink-box");
    }
}
function collapseWink() {

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
function sendMessageAjax(data, message) {
     var local = new Date();
    $.post('/messages/send/', data, function (data) {

        if (parseInt(data.response) === 1) {

            var newMessage = "<div class='chat-item' >" +
                "<div class='head'> <div class='name'>" + labelMe + "</div>" +
                "<div class='when'>" + local.getHours() + ":" + ("0" + local.getMinutes()).substr(-2) + "</div> </div>" +
                "<div class='message'> <div class='text'>" + message + "</div> </div></div>";

            $("#activity").append(newMessage);

        }
        else {
            $('#error-message-label').fadeIn(1, function () {
                $(this).removeClass('hidden');
                $(this).html(data.response.message[0]);
                $(this).fadeOut(5000);
            });
        }

        scrollChatArea();
    });
}

function sendMessageAjax2(data, message) {
     var local = new Date();
    $.post('/messages/send/', data, function (data) {

        if (parseInt(data.response) === 1) {

            var newMessage = "<li class='self'>"+
            "<div class='avatar'><img src='"+userProfilePhoto +"' draggable='false'/></div>"+
            "<div class='msg message'>"+
            "<p style='padding: 0px 0px 6px;margin-right:0px;'>"+ data.reply +" <time class='message_time' style='margin-top: 3px;'>" + local.getHours() + ":" + local.getMinutes() + "</time></p>"+
            "</div></li>";


            $("#activity").append(newMessage);

        }
        else {
            $('#error-message-label').fadeIn(1, function () {
                $(this).removeClass('hidden');
                $(this).html(data.response.message[0]);
                $(this).fadeOut(5000);
            });
        }

        scrollChatArea2();
    });
}


function scrollChatArea() {
    $('html, body').animate({scrollTop:$(document).height()}, 0);

}

function scrollChatArea2() {
    $('html, body').animate({scrollTop:$(document).height()}, 1000);

}

// Set interval
setInterval('getMessages()', 1000);