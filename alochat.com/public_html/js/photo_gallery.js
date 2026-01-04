// close photo preview block

// '/'
var csrfToken = $('meta[name="csrf-token"]').attr("content");

function closePhotoPreview() {
    $('#photo_preview').hide();
    $('#photo_preview .pleft').html('empty');
    $('#photo_preview .pright').html('empty');
};

// display photo preview block
function getPhotoPreviewAjx(id, data_sec_id) {
    $.get('/profile/photos-ajax/', {action: 'get_info', id: id, data_sec_id: data_sec_id},
        function (data) {
            $('#photo_preview .pleft').html(data.data1);
            $('#photo_preview .pright').html(data.data2);
            $('#photo_preview').show();
        }, "json"
    );
};

function askUploadPhoto(id) {

    var id = parseInt(id);

    if (id > 0) {

        $.get(
            '/profile/ask-upload-image/?id=' + id,
            function (data) {
                if (data['success']) {
                    $('#ask-upload-image').css('display', 'none');
                }
            }
        );
    }
}

function likeUser(id) {

    var id = parseInt(id);

    if (id > 0) {

        $.get(
            '/profile/like-user/?id=' + id,
            function (data) {
                if (data['success'] == 1) {

                    $('.liked').show();
                    $('.like').hide();
                }
                else if (data['success'] == 2) {
                    $('.like').show();
                    $('.liked').hide();
                }

                $(".likeCount").html(data["likeCount"]);

/*
                location.reload();
*/

            }
        );
    }
}

function blockUser1(id,text) {
    if (confirm(text)) {
        blockUser(id);
    }
}

function blockUser(id) {

        var id = parseInt(id);

        if(id > 0) {

            $.get(
                '/profile/block-user/?id=' + id,
                function (data) {
                    if (data['success'] == 1) {

                        $('#block-user i').addClass('text-danger');

                    } else if(data['success'] == 2){
                        $('#block-user i').removeClass('text-danger');
                    }
                    location.reload();
                }
            );

        }

}

function reportUser(id,text){
    if (confirm(text)) {
        reportUser1(id);
    }
}

function reportUser1(id) {

    var id = parseInt(id);

    if(id > 0) {

        $.get(
            '/profile/report-user/?id=' + id,
            function (data) {
                if (data['success'] == 1) {

                    $('#report-user i').addClass('text-danger');

                } else if(data['success'] == 2){
                    $('#report-user i').removeClass('text-danger');
                }
                location.reload();
            }
        );
    }
}

/*function likeShare(id){
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
}*/


function likeImage(id){
    var id = parseInt(id);
    if(id>0){
        $.get(
            '/profile/like-image/?id='+id,
            function(data){
                if (data['success'] == 1) {
                    if(data["likeCount"]>0){
                        $('#like_count_'+id).html(data["likeCount"]);
                    } else {
                        $('#like_count_'+id).html("");

                    }
                    $("#image-img-"+id).attr("src", '/images/icons/share/liked.png');

                } else if(data['success'] == 2){
                    if(data["likeCount"]>0){
                        $('#like_count_'+id).html(data["likeCount"]);
                    }else {
                        $('#like_count_'+id).html("");

                    }
                    $("#image-img-"+id).attr("src", '/images/icons/share/like.png');
                }
                //location.reload();
            }
        );
    }
}



function addFriend(id,text){
    if (confirm(text)) {
        addFriend1(id);
    }
}

function resetFriend(id,text){
    if (confirm(text)) {
        addFriend1(id);
    }
}

function confirmFriend(id,text){
    if(confirm(text)) {
        confirmFriend1(id);
    }
}


function addFriend1(id){
    var id = parseInt(id);

    if(id > 0){

        $.get(
            '/profile/add-friend/' + id,
            function (data){
                /*if (data['success'] == 1) {

                    $('#add-friend i').addClass('text-danger');

                } else if(data['success'] == 2){
                    $('#add-friend i').removeClass('text-danger');
                }*/
                location.reload();
            }
        );
    }
}

function confirmFriend1(id){
    var id = parseInt(id);

    if(id>0) {
        $.get(
            '/profile/confirm-friend/' + id,
            function (data){
                /*if (data['success'] == 1) {

                 $('#add-friend i').addClass('text-danger');

                 } else if(data['success'] == 2){
                 $('#add-friend i').removeClass('text-danger');
                 }*/
                location.reload();
            }
        );
    }
}

// submit comment
function submitComment(id) {

    var sText = $('#acceptcommentform-text').val();

    if (sText) {

        $.post('/profile/accept-comment/', {text: sText, photo_id: id},
            function (data) {

                if (data['success']) {

                    $('#comments_warning2').fadeIn(1, function () {
                        $(this).html(data['success']);
                        $(this).addClass('text-success');
                        $(this).fadeOut(5000);
                    });

                } else if (data['error']) {
                    $('#comments_warning2').fadeIn(1, function () {
                        $(this).html(data['error']['text']);
                        $(this).addClass('text-danger');
                        $(this).fadeOut(5000);
                    });
                }

                $("#form-comment").css('display', 'none');
            }
        );
    }
};

function goToLink(element) {

    window.location = element.href

}
// init
$(document).ready(function () {

    // onclick event handlers
    $('#photo_preview .photo_wrp').click(function (event) {

        if ($(event.target).closest(".close").length > 0 || $(event.target).closest(".cancel").length > 0) {
            closePhotoPreview();
        } else {
            event.preventDefault();
            return false;
        }

    });
    $('#photo_preview').click(function (event) {
        closePhotoPreview();
    });
    $('.close-gallery').click(function (event) {
        closePhotoPreview();
    });

    // display photo preview ajaxy
    $('.container .photo img, .photo-prw img').click(function (event) {
        if (event.preventDefault) event.preventDefault();

        var id = parseInt($(this).attr('id'));
        var data_sec_id = parseInt($(this).attr('data-sec-id'));

        if (id > 0 && data_sec_id > 0) {
            getPhotoPreviewAjx(id, data_sec_id);
        }
    });


    $(".modal-footer .btn").on('click', function () {

        location.reload();
    });

})