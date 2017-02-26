function List() {
    var selected = -1;
    var item = null;
    var length = null;
    var cur_path = null;
    var cur_path_focus = false;
    var time = 0;
    var key = {
        Enter: 13,
        Page_Up: 33,
        Page_Down: 34,
        End: 35,
        Home: 36,
        Up: 38,
        Down: 40
    };

    document.addEventListener('DOMContentLoaded', function ready() {
        item = document.getElementsByClassName('list-item');
        length = item.length;

        for (var k in item) {
            item[k].onclick = item_onkeydown;
        }
        document.body.onkeydown = body_onkeydown;

        var cur_path_el = document.getElementById('cur_path');
        cur_path = cur_path_el.value;
        cur_path_el.onfocus = function () {
            cur_path_focus = true;
        };
        cur_path_el.onblur = function () {
            cur_path_focus = false;
        };
    });

    var body_onkeydown = function (e) {
        if (cur_path_focus) {
            return cur_path_focus;
        }

        item_reset();
        if (e.keyCode == key.Up) {
            selected--;
            if (selected < 0) {
                selected = length - 1;
            }
        }
        else if (e.keyCode == key.Down) {
            selected++;
            if (selected >= length) {
                selected = 0;
            }
        }
        else if (e.keyCode == key.Enter) {
            var str = item[selected].getElementsByClassName('list-item-name')[0].innerHTML;
            var next_path = cur_path + "\\" + str;
            post('./index.php', {cur_path: next_path});
        }
        else if (e.keyCode == key.End) {
            selected = length - 1;
        }
        else if (e.keyCode == key.Home) {
            selected = 0;
        }
        else if (e.keyCode == key.Page_Up) {
            selected -= 10;
            if (selected < 0) {
                selected = 0;
            }
        }
        else if (e.keyCode == key.Page_Down) {
            selected += 10;
            if (selected >= length) {
                selected = length - 1;
            }
        }
        else {
            item[selected].className += ' list-item-selected';
            return true;
        }
        item[selected].className += ' list-item-selected';
        scrollToElement(item[selected]);

        return false;
    }

    function scrollToElement(theElement) {
        if (typeof theElement === "string") {
            theElement = document.getElementById(theElement);
        }
        var selectedPosX = 0;
        var selectedPosY = 0;
        while (theElement != null) {
            selectedPosX += theElement.offsetLeft;
            selectedPosY += theElement.offsetTop;
            theElement = theElement.offsetParent;
        }
        selectedPosY -= (window.innerHeight / 2 );
        window.scrollTo(selectedPosX, selectedPosY);
    }

    var post = function (path, params, method) {
        var method = method || "post";
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

    var item_reset = function () {
        var item_selected = document.getElementsByClassName('list-item-selected');
        for (var k in item_selected) {
            item_selected[k].className = 'list-item';
        }
    }

    var item_onkeydown = function () {
        item_reset();
        this.className += ' list-item-selected';
        var el_num = Array.prototype.indexOf.call(item, this);
        if (selected != el_num) {
            selected = el_num;
        }
        else if (selected == el_num && time + 500 > new Date().getTime()) {
            var str = item[selected].getElementsByTagName('td')[0].innerHTML;
            var next_path = cur_path + "\\" + str;
            post('./index.php', {cur_path: next_path});
        }
        time = new Date().getTime();
    }
}

var list = new List();