/**
 * Created by Elvin Valiev on 01.05.2015.
 */

$(document).ready(function () {
    scrollChatArea();
    $('#message-text').keydown(function (event) {
        if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {

            $(this).val($(this).val() + "\n");
            return true;
        } else if (event.keyCode == 13) {

            sendMessage();
            $(this).val('').focus();
            return false;
        }
    });

    $('#control-wink').click(function () {

        collapseWink();
    });

    getMessages();
    scrollChatArea();
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
function sendSmile(elem) {

    var messageInput = $('#message-text');
    var ic = elem.getAttribute('rel');

    var message = "<div class='wink wink-" + ic + "'></div>";

    messageInput.val(message);

    var data = $("#message-form").serialize();
    sendMessageAjax(data, message);
    messageInput.val('').focus();
    closeWink();

}
function getMessages() {
    $.ajax({
        type: "GET",
        url: '/messages/get-new/?id=' + globalCid
    }).done(function (data) {

        var message;
        for (var i = 0; i < data.messages.length; i++) {
            var local = new Date();
            var newMessage = "<div class='chat-item' >" +
                "<div class='head'> <div class='name'>" + globalConversationUser + "</div>" +
                "<div class='when'>" + data.messages[i].time + "</div> </div>" +
                "<div class='message'> <div class='text'>" + data.messages[i].reply + "</div> </div></div>";

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
            display: 'none',
            top: '0',
            left: '0px',
            opacity: "0"
        });
        $winkBox.removeClass("show-wink-box");
    }
}
function collapseWink() {

    var $winkBox = $("#wink-box");

    if ($winkBox.hasClass("show-wink-box")) {

        $winkBox.css({
            display: 'none',
            top: '0',
            right: '0px',
            opacity: "0"
        });
        $winkBox.removeClass("show-wink-box");
    }
    else {
        $winkBox.css({
            display: 'block',
            top: '-211px',
            left: '0px',
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
                "<div class='when'>" + local.getHours() + ":" + local.getMinutes() + "</div> </div>" +
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


function scrollChatArea() {
    var el = $("#activity");
    var height = el[0].scrollHeight;
    el.scrollTop(height);
}

// Set interval
setInterval('getMessages()', 1000);