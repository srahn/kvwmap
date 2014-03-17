
<script language="JavaScript">
/**
 * @license GPL licenses.
 * @author Jason Green [guileen AT gmail.com]
 * Migrate from jquery Form Plugin : http://malsup.com/jquery/form/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html.
 *
 *
 */

function fieldValue(el) {
    var n = el.name, t = el.type, tag = el.tagName.toLowerCase();

    if (!n || el.disabled || t == 'reset' || t == 'button' ||
        (t == 'checkbox' || t == 'radio') && !el.checked ||
        (t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
        tag == 'select' && el.selectedIndex == -1) {
            return null;
    }

    if (tag == 'select') {
        var index = el.selectedIndex;
        if (index < 0) {
            return null;
        }
        var a = [], ops = el.options;
        var one = (t == 'select-one');
        var max = (one ? index + 1 : ops.length);
        for (var i = (one ? index : 0); i < max; i++) {
            var op = ops[i];
            if (op.selected) {
                var v = op.value;
                if (!v) { // extra pain for IE...
                    v = (op.attributes && op.attributes['value'] &&
                            !(op.attributes['value'].specified)) ?
                            op.text : op.value;
                }
                if (one) {
                    return v;
                }
                a.push(v);
            }
        }
        return a;
    }
    return el.value;
};


function _appendNameValue(arr, name, value) {
    if (arr && arr.constructor == Array) {
        arr.push({name: name, value: value});
    }else {
        old = arr[name];
        if (old) {
            if (old.constructor != Array)
                arr[name] = [old];
            arr[name].push(value);
        }else {
            arr[name] = value;
        }
    }
}


/**
 * formToArray() gathers form element data into an array of objects that can
 * be passed to any of the following ajax functions: $.get, $.post, or load.
 * Each object in the array has both a 'name' and 'value' property.  An example
 * of an array for a simple login form might be:
 *
 * [ { name: 'username', value: 'jresig' },
 * { name: 'password', value: 'secret' } ]
 *
 * It is this array that is passed to pre-submit callback functions provided to
 * the ajaxSubmit() and ajaxForm() methods.
 */
function formToArray(form, arr) {
    var a = arr || [];

    var els = form.elements;
    if (!els) {
        return a;
    }

    var i, j, n, v, el, max, jmax;
    for (i = 0, max = els.length; i < max; i++) {
        el = els[i];
        n = el.name;
        if (!n) {
            continue;
        }

        v = fieldValue(el);

        if (v && v.constructor == Array) {
            for (j = 0, jmax = v.length; j < jmax; j++) {
                _appendNameValue(a, n, v[j]);
            }
        }
        else if (v !== null && typeof v != 'undefined') {
            _appendNameValue(a, n, v);
        }
    }

    return a;
}

/**
 * {"formname": {"name1":value1, "name2":["item1","item2","item3"] } }
 */
function formToObject(form, name) {
    var obj = formToArray(form, {});
    if (name) {
        var o = {};
        o[name] = obj;
        return o;
    }
    return obj;
}

// Serialize an array of form elements or a set of
// key/values into a query string
function buildQueryString(a) {

    var s = [];
    var isArray = a.constructor == Array;

    if (isArray) {
        for (var i = 0; i < a.length; i++) {
            var v = a[i];
            var k = v.name;
            v = v.value;
            s[s.length] = encodeURIComponent(k) + '=' + encodeURIComponent(v);
        }
    }else {
        for (var k in a) {
            var v = a[k];
            if (v && v.constructor == Array) {
                for (var i in v) {
                    s[s.length] = encodeURIComponent(k) +
                        '=' + encodeURIComponent(v[i]);
                }
            }else {
                s[s.length] = encodeURIComponent(k) +
                    '=' + encodeURIComponent(v);
            }
        }
    }

    // Return the resulting serialization
    return s.join('&').replace(' ', '+');
}

/**
 * serialize the form to query string
 */
function formSerialize(form) {
    return buildQueryString(formToArray(form));
}


</script>
