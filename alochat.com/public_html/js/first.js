/**
 * Created by Elvin Valiev on 07.05.2015.
 */

function goToLink(element) {
    window.location = element.href
}

$(document).ready(function(){

    var scrollPointer = $("#scrollToBottom");

    if(scrollPointer.val()==1){
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    }

});