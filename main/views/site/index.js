var selected = -1;
var length = document.getElementsByClassName('file').length;
var list = document.getElementsByClassName('file');
var cur_path = document.getElementById('cur_path').value;
var time = 0;

document.addEventListener('DOMContentLoaded', function ready() {
    for (var k in list) {
        list[k].onclick = tr_onclick;
    }

    document.body.onkeydown = function (e) {
        for (var k in list) {
            list[k].style = '';
        }

        switch (e.keyCode) {
            case 38:    //up arrow
                selected--;
                if (selected < 0) {
                    selected = length - 1;
                }
                break;
            case 40:    //down arrow
                selected++;
                if (selected >= length) {
                    selected = 0;
                }
                break;
            case 13:    //down enter
                var str = list[selected].getElementsByTagName('td')[0].innerHTML;
                var next_path = cur_path + "\\" + str;
                post('./index.php', {cur_path: next_path});
                break;
            default:
                //alert(e.keyCode)
                break;
        }
        document.getElementsByClassName('file')[selected].style = 'background-color: lightblue';
    }


});

function tr_onclick() {
    for (var k in list) {
        list[k].style = '';
    }
    this.style = 'background-color: lightblue';
    var el = Array.prototype.indexOf.call(list, this);
    if (selected != el) {
        selected = el;
    }
    else if (selected == el && time + 500 > new Date().getTime()) {
        var str = list[selected].getElementsByTagName('td')[0].innerHTML;
        var next_path = cur_path + "\\" + str;
        post('./index.php', {cur_path: next_path});
    }
    time = new Date().getTime();
}

function post(path, params, method) {
    method = method || "post";
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}