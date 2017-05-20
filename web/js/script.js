Dropzone.autoDiscover = false;
$(function(){
    $("#ddForm").dropzone({
        url: "/ajax/upload.php",
        acceptedFiles : 'application/pdf',
        success : function(file) {
            console.log('success');
            console.log(file);
            console.log(file.xhr.responseText);
            if(file.xhr.responseText) {
                let json = JSON.parse(file.xhr.responseText);
                console.log(json);
                let className = 'dz-error';
                if(json.status == 'success') {
                    className = "dz-success";
                    SetParser(json.key, json.fileName);
                } else {
                    alert(json.msg);
                }
                if (file.previewElement) {
                    if(json.fileExist) {
                        alert('Файл уже загружался');
                    }
                    return file.previewElement.classList.add(className);
                }
            }
            if (file.previewElement) {
                return file.previewElement.classList.add('dz-error');
            }
        }
        // complete : function(file) {
        //     console.log('complete');
        //     console.log(file);
        // }
    });
});

function SetParser(key,filename) {
    if(!key) return false;
    let html = `
        <li class="list-group-item" data-key="${key}">
            <div class="left">
                ${filename}
            </div>
            <div class="right">
                <i class="loader"></i>
            </div>
        </li>
    `;
    $('#fileList').show();
    $('#listGroup').append(html);
    $.ajax({
        url : '/ajax/parser.php',
        type : 'GET',
        data : {
            file : key
        },
        success : function(resp){
            resp = JSON.parse(resp);
            if(resp.status=='success') {
                SetSuccess(key, 100);
            }
        },
        error : function () {
            alert('Parser error!');
        }
    });
}

function SetSuccess(key, progress) {
    if(!key) return false;
    if(!progress) progress = 0;
    let el = $('[data-key="'+key+'"]');
    // if(el.length>0) {
    //     el.find('.progress-bar').attr('aria-valuenow', progress);
    //     el.find('.progress-bar').width(progress+'%');
    // }
    if(progress==100) {
        // clearInterval(window[key]);
        el.find('.loader').remove();
        if(!el.find('.right span').length) {
            el.find('.right').append('<span>Готово</span>');
        }
    }
}

function setCheckStatus(key,filename) {
    let html = `
        <li class="list-group-item" data-key="${key}">
            <div class="left">
                ${filename}
            </div>
            <div class="right">
                <i class="loader"></i>
            </div>
        </li>
    `;
// <div class="progress progress-striped active">
//         <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
//         </div>
    $('#fileList').show();
    $('#listGroup').append(html);
    $.ajax({
        url : '/ajax/status.php',
        type : 'post',
        data : {
            file : key
        },
        success : function(resp) {
            resp = JSON.parse(resp);
            if(resp.status=='success') {
                if(resp.progress <= 100) {
                    SetSuccess(key, resp.progress);
                }
            } else {
                console.log(resp);
                alert('RESPONCE error! '+resp.msg);
            }
        },
        error : function () {
            alert('Status error!');
        }
    });
    window[key] = setInterval(function() {
        console.log('setInterval='+key);
        // let xhr = new window.XMLHttpRequest();

        // xhr.abort();
    },2000);
}