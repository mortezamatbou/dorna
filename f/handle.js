/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var messageTimeOutObject = null;
var messageBoxLock = false;

function messageToUser(message, timer = 0) {
    alertBox = 
    '<div class="alert alert-success alert-dismissible fade in">' +
        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
        message +
    '</div>';

    messageBoxLock ? $("#message-to-user").append(alertBox) : $("#message-to-user").html(alertBox);
    
    if (timer != 0) {
        clearTimeout(messageTimeOutObject);
        messageTimeOutObject = setTimeout(function () {   //calls click event after a certain time
            $("#message-to-user").html("");
        }, timer);
    }
    
}

function openFile() {
    $('#uploadfile').trigger('click');
    $('#upload-form-content').show();
}

$("#upload-form").on('submit', (function (e) {
    e.preventDefault();
    if ($('#uploadfile').get(0).files.length === 0) {
        $("#upload-message").html('<b style="color: red;">Select file!!</b>');
        return;
    }
    $("#upload-message").html("<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i>");
    $("#upload-progress").show(50);
    messageBoxLock = true;
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = parseInt(evt.loaded / evt.total * 100);
                    // upload progress
                    $("#upload-progress-bar").css('width', percentComplete+"%");
                    $("#upload-progress-bar-d").html(percentComplete+"%");
                }
            }, false);

            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    // download progress
//                    $("#upload-message").html(percentComplete);
                }
            }, false);

            return xhr;
        },
        url: host + "api.php", // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        success: function (data) {  // A function to be called if request succeeds
            messageBoxLock = false;
            messageToUser(data + " uploaded successfully", 0);
            $("#upload-message").html("<i class='glyphicon glyphicon-ok'></i>");
            $("#upload-progress").hide(100);
            refereshTable("List updated!");
        }
    });
}));


function copyLink(id) {
    var $temp = $("<input>");
    $temp.css('display', 'hidden');
    $temp.css('width', '0px');
    $temp.css('height', '0px');
    $("body").append($temp);
    
    fileLinkCopy = baseUrl + routeW + $("#file_n_"+id).val();
    
    $temp.val(fileLinkCopy).select();
    document.execCommand("copy");
    $temp.remove();
    messageToUser("Copy Link", 3500);
}

function boxShow(which, id = 0) {
    switch (which) {
        case "folder":
            $("#box-add-folder").show(0);
            $("#folder-name").focus();
            break;
        case "rename":
            currentId = id;
            fitRenameBoxToItem(id);
            break;
    }
}

function fitRenameBoxToItem(id) {
    topp = $("#row_"+id).position();
    left = $("#items").position().left;
    lefttop = $("#items").position();
    width = $("#row_"+id).width();
    height = $("#row_"+id).height();
    
    cssAttr = {
        'top' : (topp.top+lefttop.top) + "px",
        'left' : left + "px",
        'width' : width + "px",
        'height' : height + "px"
    };
    $("#rename-file-name").val($("#file_n_"+id).val());
    $("#rename-to").val($("#file_n_"+id).val());

    $("#box-rename").css(cssAttr);
    $("#box-rename").show(100);
    
    $("#rename-to").focus();
    
}

function boxClose(which) {
    switch (which) {
        case "folder":
            $("#box-add-folder").hide(0);
            break;
        case "rename":
            $("#box-rename").hide(100);
            break;
    }
    
}

$("#add-folder-form").on('submit', (function (e) {
    e.preventDefault();
    if ($('#folder-name').val().length === 0) {
        $("#add-folder-message").html('<b style="color: red;">add folder name</b>');
        return;
    }
    $("#add-folder-message").html('<b style="color: red;">Loading...</b>');
    $.ajax({
        url: host + "api.php", // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        success: function (data) {  // A function to be called if request succeeds
            messageToUser(data, 5000);
            $("#add-folder-message").html("<i class='glyphicon glyphicon-ok'></i>");
            $("#box-add-folder").hide(0);
            refereshTable("List updated.");
        }
    });
}));

function deleteFile() {
    
    if (!confirm("Are you sure?")) {
        return;
    }
    
    checkbox = $('input[type="checkbox"][name="file_n"]:checked').map(function() { return this.value; }).get();
    a = '';
    
    for (i = 0; i < checkbox.length; i++) {
        file = $("#file_n_" + checkbox[i]).val();
        a += i == 0 ? file : "," + file;
    }
    
    files = JSON.stringify(a);
    
    url = host + "api.php";
    
    postData = {
        delete_files: a,
        token: token,
        level: 'delete',
        route: route
    };
    
    $.post(url, postData, function (data) {
        messageToUser(data, 5000);
        refereshTable("File deleted successfully");
    });
    
}

$("#rename-form").on('submit', (function (e) {
    e.preventDefault();
    
    if ($("#rename-file-name").val() == $("#rename-to").val()) {
        messageToUser('Rename file successfully', 5000);
        return;
    }
    
    $("#rename-button").val('Renaming...');
    $("#rename-button").toggleClass('disabled');
    $.ajax({
        url: host + "api.php", // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        success: function (data) {  // A function to be called if request succeeds
            messageToUser(data, 5000);
            $("#rename-button").val('Rename');
            $("#rename-button").toggleClass('disabled');
            $("#box-rename").hide(200);
            refereshTable("List updated!");
        }
    });
}));


function refereshTable(message = "") {
    
    url = host + "api.php";
    
    postData = {
        token: token,
        level: 'read',
        route: route
    };
    
    $.post(url, postData, function (data) {
        messageToUser(message, 5000);
        $("#files-list").html(data);
    });
}
