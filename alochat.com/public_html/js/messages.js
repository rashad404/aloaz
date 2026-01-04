/**
 * Created by Yusif Nesibli on 17.03.2016.
 */


function getMessages() {
    $.ajax({
        type: "GET",
        url: '/messages/get-new-messages/'
    }).done(function (data) {

        var allMessageCount = 0;
        for (var i = 0; i < data.conversationMessages.length; i++) {

            var cid = parseInt(data.conversationMessages[i].conversation_id);
            var count = parseInt(data.conversationMessages[i].count);
            var conversationItem = $("#" + cid);
            var newMsgIndicator1 = $("#new-message-count-" + cid);
            if (conversationItem) {

                var dataCounter = parseInt(conversationItem.attr('data-counter'));


                if (count > dataCounter) {
                    conversationItem.attr('data-counter', count);
                    conversationItem.addClass('unread is_new');
                    newMsgIndicator1.attr('data-val', count);
                    newMsgIndicator1.html("+" + count);
                    newMsgIndicator1.show();
                }
            }
        }


    });
}



// Set interval
setInterval('getMessages()', 2000);