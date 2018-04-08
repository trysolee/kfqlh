var constants = require('./constants');
var SESSION_KEY = 'weapp_session_' + constants.WX_SESSION_MAGIC_ID;
var Session = {
    get: function() {
        return wx.getStorageSync(SESSION_KEY) || null;
    },
    set: function(session) {
        wx.setStorageSync(SESSION_KEY, session);
    },
    clear: function() {
        wx.removeStorageSync(SESSION_KEY);
    },
};
module.exports = Session;
s = ﻿﻿ {
    name: '迎春花', // 昵称 
    picW: 'http......', // 头像
    com: '公司名',
    phone: 13823888888,
    role: { // 角色
        1: ['系统管理员', '巡查', '日常工作', ],
        2: ['系统管理员', '巡查', '日常工作', ],
    },
    proID: 1, // 当前项目
    inUserID: 0, // 介绍人
};
s = ﻿﻿ {
    "name": "西片区",
    "group": {
        "1": {
            "name": "系统管理",
            "roleNEW": ["管理员待审批"], // 邀请人员时 , 新建的权限
            "role": ["管理员", "管理员待审批"],
            "user": {
                "7": ["管理员待审批"],
                "6": ["管理员"],
            },
        },
        "2": {
            "name": "甲方",
            "roleNEW": ["巡查"], // 邀请人员时 , 新建的权限
            "role": ["巡查", "管理员", "维护"],
            "user": {
                "3": ["巡查", "日常工作"], // 可以有 "施工单位" 的权限
                "4": ["巡查"],
            },
        },
        "3": {
            "name": "监理",
            "roleNEW": ["监理巡查"], // 邀请人员时 , 新建的权限
            "role": ["监理巡查", "监理管理员", "监理维护"],
            "user": {
                "1": ["监理巡查", "日常工作"],
                "2": ["巡查"],
            },
        },
        "4": {
            "name": "施工单位",
            "roleNEW": ["施工巡查"], // 邀请人员时 , 新建的权限
            "role": ["施工巡查", "施工管理员", "施工处理"],
            "user": {
                "11": ["施工巡查", "施工管理员"],
                "22": ["巡查"],
            }
        }
    }
}
s = [{
    "name": "pro_work",
    "arr": [{
        "WID": "1",
        "JID": "1",
        "FT": "2018-02-01 00:00:00",
        "CT": "2018-02-01",
        "JSON": {
            "open": 1,
            "type": 3,
            "lx": 113.32452,
            "dx": 23.099994,
            "UID": 4,
            "D": "2018-01-14 10:30",
            "WT": 4,
            "picA": 11,
            "msg": [{
                "UID": 4,
                "D": "2018-01-14 10:30",
                "pic": 3
            }, {
                "UID": 4,
                "D": "2018-01-14 11:23",
                "pic": 4
            }, {
                "UID": 3,
                "T": "今天天气很好",
                "D": "2018-01-14 12:00"
            }]
        }
    }, {
        "WID": "2",
        "JID": "1",
        "FT": "2018-01-18 00:00:00",
        "CT": "2018-02-01",
        "JSON": {
            "open": 1,
            "type": 3,
            "lx": 113.32492,
            "dx": 23.099914,
            "UID": 3,
            "WT": 4,
            "picA": 11,
            "msg": [{
                "UID": 4,
                "D": "2018-01-14 10:30",
                "pic": 3
            }]
        }
    }]
}]
//
user = {
        UID: 1,
        FT: "2018-01-21 00:00:00",
        LT: "2018-01-21 00:00:00",
        JSon: {
            name: '迎春花', // 昵称 
            picW: 'http......', // 头像
            com: '公司名',
            phone: 13823888888,
            role: { // 角色
                1: ['系统管理员', '巡查', '日常工作', ],
                2: ['系统管理员', '巡查', '日常工作', ],
            },
            `
            }
        }
        pic = {
            PID: 34501,
            JID: 34, // 项目ID
            UID: 488,
            path: 20180114, // int
            T: fixTime,
            lx: 经度,
            dx: 维度,
            car: 3,
            size: 34555,
        }
       
        $p = {
            "openId": "oGZUI0egBJY1zhBYw2KhdUfwVJJE",
            "nickName": "Band",
            "gender": 1,
            "language": "zh_CN",
            "city": "Guangzhou",
            "province": "Guangdong",
            "country": "CN",
            "avatarUrl": "http://wx.qlogo.cn/mmopen/vi_32/aSKcBBPpibyKNicHNTMM0qJVh8Kjgiak2AHWr8MHM4WgMEm7GFhsf8OYrySdbvAMvTsw3mo8ibKicsnfN5pRjl1p8HQ/0",
            "unionId": "ocMvos6NjeKLIBqg5Mr9QjxrP1FA",
            "watermark": {
                "timestamp": 1477314187,
                "appid": "wx4f4bc4dec97d474b"
            }
        }
        a = {
            "openId": "OPENID",
            "nickName": "NICKNAME",
            "gender": GENDER,
            "city": "CITY",
            "province": "PROVINCE",
            "country": "COUNTRY",
            "avatarUrl": "AVATARURL",
            "unionId": "UNIONID",
            "watermark": {
                "appid": "APPID",
                "timestamp": TIMESTAMP
            }
        }