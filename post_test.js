function sys() {
    httpPost( //
        'a0_sys.php',
        // 'test16.php',
        ///
        {})
}

function cls() {
    httpPost( //
        'a9_login_in.php',
        // 'test16.php',
        ///
        {
            code: 'admin',
            in : '777',
            // in : '345',
            username: 'abc'
        })
}

function begin() {
    httpPost( //
        'a9_login_in.php',
        // 'test16.php',
        ///
        {
            code: 'admin',
            // in : '777',
            in : '345',
            username: 'abc'
        })
}

function login_admin() {
    httpPost( //
        'a9_login.php',
        // 'test16.php',
        ///
        {
            code: 'admin',
        })
}

function login_user() {
    httpPost( //
        'a9_login.php',
        // 'test16.php',
        ///
        {
            code: 'user',
        })
}

function fix_pro_name() {
    var JN = document.getElementById("JN2").value;
    httpPost( //
        'a1_fix_project_name.php',
        // 'test16.php',
        ///
        {
            name: JN,
        })
}

function fix_group_name() {
    var JN = document.getElementById("JN3").value;
    httpPost( //
        'a5_fix_group_name.php',
        // 'test16.php',
        ///
        {
            name: JN,
        })
}

function new_login_in() {
    httpPost( //
        'a9_new_login_in.php',
        // 'test16.php',
        ///
        {})
}
// user 接收邀请
function user_login_in() {
    var i = document.getElementById("INID1").value;
    httpPost( //
        'a9_login_in.php',
        // 'test16.php',
        ///
        {
            code: 'user',
            // in : '777',
            in : i,
            username: '飞机场'
        })
}
// 改变分组
function chg_group() {
    var JID = document.getElementById("JID1").value;
    var FZ = document.getElementById("FZ1").value;
    httpPost( //
        'a9_chg_group.php',
        //
        {
            JID: JID,
            group: FZ,
        })
}
// 设置 管理员
function set_sys_admin() {
    var UID = document.getElementById("UID1").value;
    httpPost( //
        'a1_set_sys_admin.php',
        // 'test16.php',
        ///
        {
            UID: UID,
        })
}
// 删除 管理员
function remove_sys_admin() {
    var UID = document.getElementById("UID2").value;
    httpPost( //
        'a1_remove_sys_admin.php',
        // 'test16.php',
        ///
        {
            UID: UID,
        })
}
// 修改 分组 人员权限
function fix_group() {
    var txt = document.getElementById("R1").innerHTML;
    var arr = JSON.parse(txt);
    // var arr = {
    //     46: ["甲方巡查", "管理员"]
    // }
    httpPost( //
        'a5_fix_group.php',
        // 'test16.php',
        ///
        {
            ARR: JSON.stringify(arr),
        })
}
// 发布 work
function new_work() {
    var WT = document.getElementById("WT1").value;
    var arr = JSON.parse(WT);
    // var arr = ["600001", "600002"]
    httpPost( //
        'a9_new_work.php',
        // 'test16.php',
        ///
        {
            WT: JSON.stringify(arr),
        })
}
// 发布 work
function fix_WT() {
    var WID = document.getElementById("WID3").value;
    var WT = document.getElementById("WT2").value;
    var arr = JSON.parse(WT);
    // var arr = ["600001", "600002"]
    httpPost( //
        'a9_fix_wrok_wt.php',
        // 'test16.php',
        ///
        {
            WID: WID,
            ARR: JSON.stringify(arr)
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