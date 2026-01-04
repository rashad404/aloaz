/**
 * Created by Elvin Valiev  on 05.05.2015.
 */

$(document).ready(function () {
    $("#discoveryfilterform-agerange").slider({
        tooltip: 'always'
    });
    var nextBtn = $("#discoveryNextBtn");

    nextBtn.on('click', function () {
        discoveryNext(this.getAttribute('data-id'));
    });

    var prevBtn = $("#discoveryPreviousBtn");

    prevBtn.on('click', function () {

        discoveryPrevious(this.getAttribute('data-id'));
    });

});
/* Document ready*/

// Get next user
function discoveryNext(id) {

    showInvisibleElements();

    clearOtherImagesBlock();

    id = parseInt(id);

    $.ajax({
        url: '/site/discovery-next-user/?cid=' + id + "&direction=right",
        method: 'get'

    }).done(function (data) {

        var currentUser = data.currentUser;
        var nextUser = data.nextUser;
        if (currentUser) {

            $("#discoveryPreviousBtn").attr("data-id", $("#currentUserName").attr("data-id"));
            $("#prevUserImg").attr("src", $("#avatar").attr("src"));

            $("#prevName").html($("#currentUserName").html());

            $("#prevMeta").html('');

            $("#prevMeta").append($("#currentUserMeta").html());

            setCurrentUserDetails(currentUser);

            if (nextUser) {

                $("#discoveryNextBtn").attr("data-id", nextUser.id);
                if(nextUser.profile_photo!=null){
                    $("#nextUserImg").attr("src", nextUser.profile_photo);
                } else{
                    $("#nextUserImg").attr("src", '/images/icons/male_0.png');
                }
                $("#nextName").html(nextUser.full_name);


                var meta = "<span>" + nextUser.age + " " + lblYears + "</span>";

                if (nextUser.city_name!=null) {
                    meta = meta + "<span>, " + nextUser.city_name + "</span>";
                }

                $("#nextMeta").html(meta);
            }
            else {
                $("#previewNext").css("display", 'none');
                $("#discoveryNextBtn").css("display", 'none');

                $("#nextName").css("display", 'none');
                $("#nextMeta").css("display", 'none');
            }
        }
    });
}

// Get previous user
function discoveryPrevious(id) {

    showInvisibleElements();
    clearOtherImagesBlock();

    id = parseInt(id);

    $.ajax({
        url: '/site/discovery-next-user/?cid=' + id + "&direction=left",
        method: 'get'

    }).done(function (data) {

        var currentUser = data.currentUser;
        var previousUser = data.nextUser;

        if (currentUser) {

            $("#discoveryNextBtn").attr("data-id", $("#currentUserName").attr("data-id"));
            $("#nextUserImg").attr("src", $("#avatar").attr("src"));

            $("#nextName").html($("#currentUserName").html());

            $("#nextMeta").html('');
            $("#nextMeta").append($("#currentUserMeta").html());

            setCurrentUserDetails(currentUser);

            if (previousUser) {
                $("#discoveryPreviousBtn").attr("data-id", previousUser.id);
                if(previousUser.profile_photo!=null){
                    $("#prevUserImg").attr("src", previousUser.profile_photo);
                }else {
                    $("#prevUserImg").attr("src", '/images/icons/male_0.png');
                }

                $("#prevName").html(previousUser.full_name);

                var meta = "<span>" + previousUser.age + " " + lblYears + "</span>";

                if (previousUser.city_name!==null) {
                    meta = meta + "<span>, " + previousUser.city_name + "</span>";
                }
                $("#prevMeta").html(meta);

            }
            else {
                $("#previewPrevious").css("display", 'none');
                $("#discoveryPreviousBtn").css("display", 'none');
                $("#prevName").css("display", 'none');
                $("#prevMeta").css("display", 'none');
            }
        }

    });
}

function clearOtherImagesBlock() {

    var userOtherImages = $("#userOtherImages");

    userOtherImages.html('');

    var otherImagesCount = $("#otherImagesCount");

    otherImagesCount.html('');
}
function showInvisibleElements() {

    $("#previewNext").css("display", 'block');
    $("#discoveryNextBtn").css("display", 'block');
    $(".discoveryNextBtn").css("display", 'block');
    $("#previewPrevious").css("display", 'block');
    $("#discoveryPreviousBtn").css("display", 'block');
    $(".discoveryPreviousBtn").css("display", 'block');
    $("#nextName").css("display", 'block');
    $("#nextMeta").css("display", 'block');

    $("#prevName").css("display", 'block');
    $("#prevMeta").css("display", 'block');
}
function setCurrentUserDetails(currentUser) {


    var userOtherImages = $("#userOtherImages");
    var otherImagesCount = $("#otherImagesCount");

    // set current user for cookie
    $.ajax({
        url: '/site/set-current-user/?cid=' + currentUser.id,
        method: 'get'
    });
    if(currentUser.main_photo!=null){
        $("#avatar").attr("src",currentUser.main_photo);
    }else{
        $("#avatar").attr("src",'/images/icons/male_0.png');
    }

     $("#userAge").html(currentUser.age);

    if (currentUser.city_name !== null) {
        $("#userCity").html(", " + currentUser.city_name);
    } else {
        $("#userCity").html("");
    }
    $("#currentUserName").html(currentUser.full_name);
    $("#currentUserName").attr("data-id", currentUser.id);
    $(".currentUserLink").attr("href", "/u/" + currentUser.id)

    if (currentUser.images) {
        for (var i = 0; i < currentUser.images.length; i++) {

            var itemA = document.createElement("a");
            itemA.setAttribute("target", "_blank");
            itemA.setAttribute("href", "/u/" + currentUser.id);
            var itemLi = document.createElement("li");
            var itemImage = document.createElement("img");
            itemImage.setAttribute('src', currentUser.images[i]);
            itemA.appendChild(itemImage);
            itemLi.appendChild(itemA);
            userOtherImages.append(itemLi);
        }
    }
    if (currentUser.remainingImageCount) {
        otherImagesCount.html('+' + currentUser.remainingImageCount + " " + lblImages);
    }
}

