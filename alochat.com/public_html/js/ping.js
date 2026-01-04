function sendPing() {

    var newMsgIndicator;
    var newNotIndicator;
    var newNotMobIndicator;
    var newNotTextIndicator;
    newMsgIndicator = $(".new-message-count");
    newNotIndicator = $(".new-notification-count");
    newNotMobIndicator = $(".new-notification-count1");
    newNotTextIndicator = $("#notificationText");
    var msgCount = parseInt(newMsgIndicator.attr('data-val'));
    var notCount = parseInt(newNotIndicator.attr('data-val'));

    $.ajax({
        type: "GET",
        url: '/site/ping/'
    }).done(function (data) {

        var newMsgCount = parseInt(data.response.newMessageCount);

        if (newMsgCount != msgCount && newMsgCount > 0) {

            newMsgIndicator.css("display", "block");

            newMsgIndicator.attr('data-val', newMsgCount);

            newMsgIndicator.html(newMsgCount);
        }

        var newNotificationCount = parseInt(data.response.newNotificationCount);

         if(newNotificationCount != notCount && newNotificationCount > 0){
         newNotIndicator.css("display", "block");

         newNotIndicator.attr('data-val', newNotificationCount);

         newNotIndicator.html(newNotificationCount);

         newNotMobIndicator.html(newNotificationCount);

         newNotTextIndicator.html(data.response.newNotificationText);

         }
    });



}

function readNotification(){
    var newNotIndicator;
    var newNotTextIndicator;
     newNotIndicator = $(".new-notification-count");
     newNotTextIndicator = $("#notificationText");
     var notCount = parseInt(newNotIndicator.attr('data-val'));

    $.ajax({
        type: "GET",
        url: '/site/read-notification/'
    }).done(function (data) {

        var newNotificationCount = parseInt(data.response.newNotificationCount);

        if(newNotificationCount == 0){
            newNotIndicator.css("display", "none");

        }
    });
}
// Set interval
setInterval('sendPing()', 5 * 1000);

// Set event handler
$(document).ready(function () {

    sendPing();

    $(document).click(function (event) {

        var _opened = $("#profile-short-collapse").hasClass("out collapse in");
        if (_opened === true) {
            $("span.profile-short-menu").click();
        }
    });

    $('.messages .message').click(function (evt) {

        if ($(evt.target).closest(".close").length > 0) {

        } else {
            window.location.href = $(this).attr('data-href');
        }
    });

    $(".notification-id").click(function(){
        readNotification();


    });
});


function getNewMessages(){

}