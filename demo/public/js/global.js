////扩展函数
var Globaldomain = "http://192.168.0.112/";
var Currenturl = window.location.href;
Currenturl = Currenturl.replace(Globaldomain, "")

//判断是否通过微信内置浏览器打开
function is_weixin() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}

function isoauth() {
    var randomcode = Math.random() * 100000;
    var pars = 'type=isoauth';
    var state = $.ajax({
        type: "Get",
        url: "oauthweixi.aspx",
        data: pars + "&code=" + randomcode,
        async: false
    }).responseText;

    if (state == 1) {
            getlogininfo();

    } else {

            if (window.location.href.indexOf("login.shtml") > -1|| window.location.href.indexOf("register.shtml") > -1 || window.location.href.indexOf("register_txt.shtml") > -1 || window.location.href.indexOf("password.shtml") > -1) 
            {
                //在首页或登录页不需要跳到登录页
            }
            else {
               
                RedirectAuthUrl("2014/partner2014/oauth.html?refurl=" + Currenturl.replace("#rd",""));
            }

    }
}



var WinMoney = 0;
function getlogininfo() {
    var randomcode = Math.random() * 100000;
    var pars = 'type=getlogininfo';
    var state = $.ajax({
        type: "Get",
        url: "partnerserver.aspx",
        data: pars + "&code=" + randomcode,
        async: false
    }).responseText;

    json = jQuery.parseJSON(state);

    if (json.loginstate == "0") {
        if (window.location.href.indexOf("login.shtml") > -1 || window.location.href.indexOf("register.shtml") > -1 || window.location.href.indexOf("register_txt.shtml") > -1 || window.location.href.indexOf("password.shtml") > -1 || window.location.href.indexOf("index.shtml") > -1 || window.location.href.indexOf("activityrules.shtml") > -1) {
            //在首页或登录页不需要跳到登录页
            $(".member a").click(function () {
                window.location = "login.shtml";
            })
        }
        else {
            window.location = "login.shtml";
        }
    }
    else {
        $(".member img").attr("src", json.HeadImgUrl == "" ? "tpl/www/images/member_on.png" : json.HeadImgUrl);
        $(".member a").html(json.UserName);
//        WinMoney = json.WinMoney == "" ? 0 : parseFloat(json.WinMoney).toFixed(2);
////        if (json.Iswin == "True") {
////            $(".m320 .hb").html("已领取红包");
////        }
////        else {
////            $(".m320 .hb").html("领取红包");
////        }
//       
//        if (window.location.href.indexOf("redpaper.shtml") > -1) {
//            if (WinMoney > 0) {
//                $(".step2").html("感谢您的参与,您已领取该红包 金额" + WinMoney+"元");
//            }
//        }
       
    }

}
$(function () {
//    if (!is_weixin()) {
        getlogininfo();
//    }
//    else {
//        isoauth();
//    }

});

function direct(url,frameid,isparent)
{
	url = url.replace(/&amp;/g,"&");
	if(!isparent || isparent == "" || isparent == "undefined")
	{
		if(frameid)
		{
			window.frames[frameid].location.href = url;
		}
		else
		{
			window.location.href=url;
		}
	}
	else
	{
		if(!frameid || frameid == "" || frameid == "undefined")
		{
			parent.window.location.href = url;
		}
		else
		{
			window.parent.frames[frameid].location.href = url;
		}
	}
}

//设定多长时间运行脚本
//参数 time 是时间单位是毫秒，为0时表示直接运行 大于0小于10毫秒将自动*1000
//参数 js 要运行的脚本
function eval_js(time,js)
{
	time = parseFloat(time);
	if(time < 0.01)
	{
		eval(js);
	}
	else
	{
		if(time < 10)
		{
			time = time*1000;
		}
		window.setTimeout(js,time);
	}
}

//编码网址
function url_encode(str)
{
	return transform(str);
}
