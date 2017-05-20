Dropzone.autoDiscover = false;
$(function(){
    $("#ddForm").dropzone({
        url: "/ajax/upload.php",
        acceptedFiles : 'application/pdf',
        success : function(file) {
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
    });
});

/**
 * Отправляет файл на обработку
 * @param {string} key - ключ
 * @param {string} filename - имя и расширение файла
 * @returns {boolean}
 * @constructor
 */
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
/**
 * Устанавливает успешный статус
 * @param {string} key - ключ
 * @param {integer} progress - прогресс
 * @returns {boolean}
 * @constructor
 */
function SetSuccess(key, progress) {
    if(!key) return false;
    if(!progress) progress = 0;
    let el = $('[data-key="'+key+'"]');
    if(progress==100) {
        el.find('.loader').remove();
        if(!el.find('.right span').length) {
            el.find('.right').append('<span>Готово</span>');
        }
    }
}