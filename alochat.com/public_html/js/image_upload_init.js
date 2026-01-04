


$("#file-3").fileinput({
    uploadUrl: "/profile/image-upload/", // server upload action
    uploadAsync: true,
    showCaption: false,
    minFileCount: 1,
    maxFileCount: 10,
    overwriteInitial: false,
    language:'ru',
    allowedFileExtensions : ['jpg','jpeg', 'png','gif']
});
function getQueryVariable(variable)
{
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if(pair[0] == variable){return pair[1];}
    }
    return(false);
}


$("#file-4").fileinput({
    uploadUrl: "/messages/send-image/?id=" + getQueryVariable("id"), // server upload action
    uploadAsync: true,
    showCaption: false,
    minFileCount: 1,
    maxFileCount: 10,
    overwriteInitial: false,
    language:'ru',
    allowedFileExtensions : ['jpg','jpeg', 'png','gif']
});