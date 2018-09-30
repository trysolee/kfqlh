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

function test1() {
    var s = '11:30';
    var arr = s.split(':');
    // 
    var d = new Date();
    d.setHours(arr[0]);
    d.setMinutes(arr[1]);
    // 
    var n = new Date();
    if (d > n) {
        alert('过时');
    } else {
        alert('OK')
    }
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

function 更新孩子数据() {
    httpPost( //
        'tb9_update.php', {})
}

function 获取家长邀请码() {
    httpPost( //
        'tb9_login_get.php', {})
}

function 获取好友邀请码() {
    httpPost( //
        'tb9_add_f_get.php', {})
}

function 添加家长() {
    var 称为 = document.getElementById("添加家长1").value;
    var 邀请码 = document.getElementById("添加家长2").value;
    var code = document.getElementById("添加家长33").value;
    httpPost( //
        'tb9_login_in_old.php', {
            code: code,
            j_NA: 称为,
            invite: 邀请码,
            cType: 'browser',
        })
}

function 添加好友() {
    var 邀请码 = document.getElementById("添加好友1").value;
    httpPost( //
        'tb9_add_f.php', {
            id: 邀请码,
        })
}
//889756
//  http://localhost/post_test.html
//
//288655
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