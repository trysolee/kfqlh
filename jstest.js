function test() {
    var s = '12345.33'
    var reg = /\d+/;
    if (reg.test(s)) {
        return s + '_OK';
    } else {
        return s + '_ERR';
    }
}

function input() {
    var a = document.getElementById("T1").value;
    var b = document.getElementById("T2").value;
    // 
    var reg = new RegExp(a);
    if (reg.test(b)) {
        b = b + '_OK';
    } else {
        b = b + '_ERR';
    }
    document.getElementById("T2").value = b;
}