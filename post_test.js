function cls() {
    httpPost( //
        'a9_login_in.php',
        // 'test16.php',
        ///
       
        {
            code: '123',
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
            code: '123',
            // in : '777',
            in : '345',
            username: 'abc'
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