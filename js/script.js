//-----------------USER ACTIONS------------------

$("#login").on("click", (e)=>{
    e.preventDefault();
    let inputObject = {
        "email": $("#email").val(),
        "pw": $("#pw").val(),
    };
    if(!validateEmpty(inputObject)){
        alertDiv("danger", "Täida kõik väljad!");
    }else{
        inputObject.remember = $("input[name=remember]:checked").val();
        inputObject.action = "login";
        ajaxGet("api/userActions.php", inputObject, (data) => {
            if(data.status === "success"){
                window.location.href = "main.php";
            }else{
                alertDiv(data[0].type, data[0].text);
            }

        });
    }
})

$("#register").on("click", (e)=>{
    e.preventDefault();
    let inputObject = {"firstname": $("#firstname").val(),
        "lastname": $("#lastname").val(),
        "email": $("#email").val(),
        "pw": $("#pw").val(),
        "pwrepeat": $("#pwrepeat").val(),
        "gender": $('input[name=gender]:checked').val()
    };
    if(!validateEmpty(inputObject)){
        alertDiv("danger", "Täida kõik väljad!");
    }
    else if (inputObject.pw !== inputObject.pwrepeat){
        alertDiv("danger", "Paroolid ei kattu!");
    }
    else if (!validateEmail(inputObject.email)){
        alertDiv("danger", "Sisesta korrektne email!");
    }else{
        inputObject.action = 'register';
        ajaxGet("api/userActions.php", inputObject, (data)=>{
            alertDiv(data[0].type, data[0].text);
        });
    }
})

$("#logout").on("click", (e) => {
    e.preventDefault();
    let inputObject = {};
    inputObject.action = 'logout';
    ajaxGet("api/userActions.php", inputObject, (data)=>{
        if(data.status === "success"){
            window.location.href = "index.php";
        }
    });
})


//---------------POST ACTIONS-----------------

$("#post_text").on("input", ()=>{
    let text = $("#post_text").val();
    $("#post_text").removeClass("form-error");
    if(text){
        $("#post").prop("disabled", false);
    }else{
        $("#post").prop("disabled", true);
    }
});

$("#post").on("click", (e)=>{
    e.preventDefault();
    let inputObject = {
        "post_text": $("#post_text").val()
    }

    if(!inputObject.post_text){
        alertDiv("danger", "Postitus ei saa olla tühi!");
        $("#post_text").addClass("form-error");
    }else{
        inputObject.action = "addPost";
        ajaxGet("api/postActions.php", inputObject, (data)=>{
            console.log(data.status);
            if(data.status === "success"){
                $("#post_text").val('');
            }else{
                $("#post_text").addClass("form-error");
            }
        });
    }


});

let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
$(document).on("click", "#delete-post", function(e){
    e.preventDefault();
    let id = $(this).data("id")
    $("#delete-post-confirm").attr("data-id", ''+id);
    confirmModal.show()
});

$(document).on("click", "#delete-post-confirm", function(e){
    e.preventDefault();
    let id = $("#delete-post-confirm").attr("data-id")
    let inputObject = {"action": "delete_post", "post_id": id}
    ajaxGet("api/postActions.php", inputObject, (data)=>{
        confirmModal.hide();
    });
});

$(document).on("click", "#like-counter", function(e){
    e.preventDefault();
    let id = $(this).attr("data-id")
    let inputObject = {"action": "like_post", "post_id": id}
    ajaxGet("api/postActions.php", inputObject, (data)=>{
        let htmlOutput = data.htmlOutput;
        $(this).html(htmlOutput);
    });
});

$(document).on("click", "#comment-counter", function(e){
    e.preventDefault();
    let id = $(this).attr("data-id")
    $("#display-comments-"+id).slideToggle();
});

$(document).on("input", ".comment-text", function(e){
    let text = $(this).val();
    let id = $(this).attr("data-id")
    $(this).removeClass("form-error");
    if(text){
        $("#new-comment-post-"+id).prop("disabled", false);
    }else{
        $("#new-comment-post-"+id).prop("disabled", true);
    }
});

$(document).on("click", "[id^=new-comment-post-]", function(e){
    e.preventDefault();
    let id = $(this).attr("data-id")
    let inputObject = {
        "action": "new_comment",
        "post_id": id,
        "text": $("#comment-text-"+id).val()
    }

    if(!inputObject.text){
        alertDiv("danger", "Postitus ei saa olla tühi!");
        $("#comment-text-"+id).addClass("form-error");
    }else{
        ajaxGet("api/postActions.php", inputObject, (data)=>{
            console.log(data.status);
            if(data.status === "success"){
                $("#comment-text-"+id).val('');
            }else{
                $("#comment-text-"+id).addClass("form-error");
            }
        });
    }
});

$(document).on("click", "#close-confirm-modal", function(e){
    confirmModal.hide()
});

//----------------------TIMELINE-------------------

//Web Sockets

var apiKey = 'Xuec59yH4uLbQYrkAQJ8iOzzJONWMc4Pvuf0sD6E';
var channelId = 1;
var piesocket = new WebSocket(`wss://free3.piesocket.com/v3/${channelId}?api_key=${apiKey}&notify_self`);

piesocket.onmessage = (message) => {
    let dataObject = JSON.parse(message.data);
    if(dataObject.type === "new_post"){
        loadPostId(dataObject.post_id);
    }else if(dataObject.type === "add_comment"){
        loadComments(dataObject.post_id);
    }else{
        removePostFromDOM(dataObject.post_id);
    }

}

$(()=>{
    loadPosts();
});


$(document).on("mouseenter", "#like-counter", function(){
    $(this).removeClass("text-muted");
    $(this).css('cursor','pointer');
})

$(document).on("mouseleave", "#like-counter", function(){
    $(this).addClass("text-muted");
    $(this).css('cursor','default');
})

$(document).on("mouseenter", "#comment-counter", function(){
    $(this).removeClass("text-muted");
    $(this).css('cursor','pointer');
})

$(document).on("mouseleave", "#comment-counter", function(){
    $(this).addClass("text-muted");
    $(this).css('cursor','default');
})

$("#share-counter").on("mouseenter", ()=>{
    $("#share-counter").removeClass("text-muted");
    $('#share-counter').css('cursor','pointer');
})

$("#share-counter").on("mouseleave", ()=>{
    $("#share-counter").addClass("text-muted");
    $('#share-counter').css('cursor','default');
})



function showDelete(id){
    $("#close-post-"+id).show();
}

function hideDelete(id){
    $("#close-post-"+id).hide();
}

function deletePost(id){
    console.log(id);
}

//-------------------------------------------------------//

function loadPosts(){
    let action = {"action": "load_posts"};
    ajaxGet("api/timeline.php", action, (data)=>{
        $("#content-wrapper").append(data.html);
    });
}

function loadComments(post_id){
    let action = {"action": "load_comments", "post_id": post_id};
    ajaxGet("api/timeline.php", action, (data)=>{
        $("#display-comments-"+post_id).html(data.html);
        $('#comment-counter[data-id="'+post_id+'"]').html(data.comments)
    });
}

function loadPostId(id){
    let action = {"action": "load_posts", "post_id": id};
    ajaxGet("api/timeline.php", action, (data)=>{
        $("#content-wrapper").append(data.html);
    });
}

function removePostFromDOM(id){
    $("#post-id-"+id).remove();
}

function validateEmail(email){
    var regex = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if(!regex.test(email)){
        return false;
    }
    return true;
}

function validateEmpty(object){
    let empty = true;
    $.each(object, (key, value) => {
        if(!value){
            empty = false;
        }
    })

    return empty;

}

function alertDiv(type, text){
    $("#alert-div").html("<div class='alert alert-"+type+"' role='alert'>"+text+"</div>");
}

function ajaxGet(url, data, onSuccess){
    $.ajax({
        url: url,
        type: "GET",
        dataType: 'json',
        data: {
            data: JSON.stringify(data)
        },
        success: (data) => {
            onSuccess(data);
        }
    })
}