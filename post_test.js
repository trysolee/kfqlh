function sys() {
    httpPost( //
        'a0_sys.php',
        // 'test16.php',
        ///
        {})
}

function cls() {
    httpPost( //
        'tb0_del_all_db.php',
        // 'test16.php',
        ///
        {})
}

function ZC() {
    var code = document.getElementById("ZC1").value;
    var h_NA = document.getElementById("ZC2").value;
    var j_NA = document.getElementById("ZC3").value;
    var LJ = document.getElementById("ZC4").value;
    httpPost( //
        'tb9_login_in.php', {
            code: code,
            h_NA: h_NA,
            j_NA: j_NA,
            LJ: LJ,
            cType: 'browser',
        })
}

function DL() {
    var DL1 = document.getElementById("DL1").value;
    httpPost( //
        'tb9_login.php', {
            code: DL1,
            cType: 'browser',
        })
}

function add_c() {
    var a = document.getElementById("add_c1").value;
    httpPost( //
        'tb5_add_c.php', {
            h_NA: a,
        })
}

function rename() {
    var a = document.getElementById("rename1").value;
    var b = document.getElementById("rename2").value;
    httpPost( //
        'tb5_rename.php', {
            UID: a,
            NA: b,
        })
}
//
//  http://localhost/post_test.html
//
//
//
function httpPost(URL, PARAMS) {
    var temp = document.createElement("form");
    temp.action = URL;
    temp.method = "post";
    temp.style.display = "none";
    for (var x in PARAMS) {
        var opt = document.createElement("textarea");
        opt.name = x;
        opt.value = PARAMS[x];
        temp.appendChild(opt);
    }
    document.body.appendChild(temp);
    temp.submit();
    return temp;
}