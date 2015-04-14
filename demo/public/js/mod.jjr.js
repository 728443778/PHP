/********************************************************************************************
* 时 间: 2014年12月4日14:22:42
* 备 注: 第一次修改
* 内 容: 页面按钮单击事件的变更
* 修改者： sa
*********************************************************************************************/
function usersearch()
{
    var phone=document.getElementById('userphone').value;
    window.location.href=base_url+"&f=index&phone="+phone+'';
}
function sh(phone,id,status)
{
    if(status==1)
    {
        if(confirm("确认禁用:"+phone+" 吗？"))
        {
            window.location.href=base_url+"&f=view&id="+id+"&status="+status+"";
        }
        else
            return;
    }
    if(status==0)
    {
        if(confirm("确认激活:"+phone+"吗?"))
        {
            window.location.href=base_url+"&f=view&id="+id+"&status="+status+"";
        }
        else
            return ;
    }
}
function csh(id,status)
{
    window.location.href=base_url+"&f=sh&id="+id+"&status="+status+"";
}
KISSY.use('node,io', function(S, Node, IO)
{
	var $ = Node.all;
	function loadImages(sources, callback) 
    {
		var count = 0,
				images = {},
				imgNum = 0;
		for (src in sources) 
        {
			imgNum++;
		}
		for (src in sources) 
        {
			images[src] = new Image();
			images[src].onload = function()
             {
				if (++count >= imgNum) 
                {
					callback(images);
				}
			}
			images[src].src = sources[src];
		}
	}//loadImages定义结束
	loadImages(['tpl/www/images/bg-loader.jpg', 'tpl/www/images/ico-logo.png', 'tpl/www/images/sales-bg-loader.jpg', 'tpl/www/images/ico-sales-logo.png', 'tpl/www/images/recommend-tips.png', 'tpl/www/images/recommend-submit.png', 'tpl/www/images/recommend-logo.png', 'tpl/www/images/icon-jjr.png', 'tpl/www/images/icon-prize.png', 'tpl/www/images/gift_11.png', 'tpl/www/images/gift_01.png'], function() 
    {
		setTimeout(function() 
        {
			$('.loader').addClass('fadeOut').hide();
			$('.user-loader').addClass('fadeOut').hide();
			$('.main-box').addClass('fadeIn');
			$('#loading-style').remove();
		}, 1000);
	});//loadImages结束
	var REG = {
        gwmname: /^[a-zA-Z0-9_]{4,12}$/,
		name: /^[a-zA-Z\u4e00-\u9fa5]{2,12}$/,
		phone: /(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/,
		wxid: /^[a-zA-Z][a-zA-Z0-9_-]{5,19}$/,
		number: /^[+\-]?\d+(\.\d+)?$/,
        idCard:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/
        }
    var userStatus={0:'无效', 1: '新客户',2:'已跟进',3:'到访',4 :'认筹',5:'认购',6:'签约', 7:'回款',8: '导入客户'}

	//经纪人注册
	var submit_broker = $('#J_submitReg');
	var companyName = $('.company-name');
	var name = $('#username');
	var phone = $('#phone');
	var job = $('#job');
    var password=$('#password');
	var company = $('#company');
	var agree = $('#agree');
    var sje=$('#sjexist');
	var DATA = {}

    if (job.val() == 'ZJGS' || job.val() == 'DLGS' || job.val() == 'HZHB' || job.val() == 'HZSP')
     {
        companyName.show();
     } 
     else 
     {
        companyName.hide();
     }

	job.on('change', function() 
    {
		if (job.val() == 'ZJGS' || job.val() == 'DLGS' || job.val() == 'HZHB' || job.val() == 'HZSP')
         {
			companyName.show();
		 }
         else 
         {
			companyName.hide();
		 }
	}); //job.on结束
    $('#gw_btn').on('click',function()
    {
        var gwid=S.trim($('#gwid').val());
        var gwpass=S.trim($('#gwpass').val());
        var admin=0;
        if(document.getElementById('admin').checked)
        {
            admin=1;
        }
        if(gwid=='')
        {
            $('#sjexist').html("用户名不能为空");
            return false;
        }
        else if(gwpass=='')
        {
            $('#sjexist').html("密码不能为空");
            return false;
        }
        else
        {
             IO.post('customer.php?c=login&f=login&gwid='+gwid+'&gwpass='+gwpass+'&admin='+admin+'',function(data) {
                    document.write(data);
            });
        }
    });
    $('#phone').on('blur',function()
    {
        var pv = S.trim(phone.val());
        if (pv == '') 
        {
            $('#sjexist').html("手机号不能为空");
            
            return false;
        } 
        else if (!REG.gwmname.test(pv)) 
        {
            $('#sjexist').html('请填写正确的手机号！');
            return false;
        }
        else
        {
            IO.post('index.php?c=register&a=checkphone&phone='+pv+'',function(data) {
                if (data == 1) {
                    $('#sjexist').html('手机号已被使用');
                } 
            });
        }
    });
     $('#gwmname').on('blur',function()
    {
        var pv = S.trim($('#gwmname').val());
        if (pv == '') 
        {
            $('#sjexist').html("登录名不能为空");
            
            return false;
        } 
        else if (!REG.gwmname.test(pv)) 
        {
            $('#sjexist').html('请填写正确的登录名');
            gwbz=2;
            return false;
        }
        else
        {
            IO.post('customer.php?c=hbc&f=check&gwid='+pv+'',function(data) {
                if (data == 1) {
                    gwbz=8
                    $('#sjexist').html("<b style='color:blue'>可以使用</b>");
                } 
                if (data == 0) {
                    gwbz=0;
                    $('#sjexist').html("<b style='color:red'>用户名已被使用</b>");
                } 
                if (data == -1) {
                    gwbz=-1;
                    $('#sjexist').html("<b style='color:red'>系统检测到你的登录已过期，请重新登录</b>");
                } 
            });
        }
    });
	submit_broker.on('click', function()
    {
		//姓名
		if (name.length == 1) 
        {
			var nv = S.trim(name.val());
			if (nv == '') 
            {
				alert('姓名不能为空！');
				return false;
			}
            else if (name.length > 6) 
            {
				alert('姓名不能超过6个字！');
				return false;
			} 
            else if (!REG.name.test(nv)) 
            {
				alert('请填写正确的姓名！');
				return false;
			}
		}
		//手机
		if (phone.length == 1)
         {
			var pv = S.trim(phone.val());
			if (pv == '') 
            {
				alert('手机号为空！');
				return false;
			} 
            else if (!REG.phone.test(pv)) 
            {
				alert('请填写正确的手机号！');
				return false;
			}
		}
        //密码
        if(password.length==1)
        {
            var psw=S.trim(password.val());
            if(psw=='')
            {
                alert('密码不能为空！');
                return false;
            }
            else if(psw.length<6 ||  psw.length>8 || !REG.number.test(psw))
            {
                alert('密码必须为6到8个数字！');
                return false;
            }
        }

		//职业
		if (job.length == 1) 
        {
			var prv = job.val();
            var prCompany=S.trim(company.val());
			if (prv == 0) 
            {
				alert('请选择您的职业');
				return false;
			}
            else if (prv == 'ZJGS' || prv == 'DLGS' || prv == 'HZHB' || prv == 'HZSP') 
            {
               if(prCompany=='')
               {
                   alert('公司名称不能为空！');
                   return false;
               }
            }
		}
		//注册协议
		if (agree.prop('checked') == false) {
			alert('请同意注册协议');
			return false;
		}
		
		IO.post('index.php?c=register&a=checkphone&phone='+pv+'',function(data)
        {
			if (data == 0)
            {
                nv=encodeURI(nv);
				IO.post('index.php?c=register&a=register&name='+nv+'&phone='+pv+'&password='+psw+'&job='+prv+'&company='+prCompany+'',function(data) {
                    document.write(data);
				});

			}
            else
            {
				if (data == 1)
                {
					alert('手机号为空或已被使用');
				}
                else
                {
					alert('系统异常，请稍后重试！');
				}
			}
		});
	});
    //经纪人登录
    var J_login = $('#J_login');
    var userpsw=$('#userpsw');
    J_login.on('click', function() {
		if (phone.length == 1) {
			var pv = S.trim(phone.val());
			if (pv == '') {
				alert('手机号为空！');
				return false;
			} else if (!REG.phone.test(pv)) {
				alert('请填写正确手机号！');
				return false;
			}
			
		}
        //密码
        if(userpsw.length==1){
            var ups=S.trim(userpsw.val());
            if(ups==''){
                alert('密码不能为空！');
                return false;
            }else if(ups.length<6 ||  ups.length>8 || !REG.number.test(ups)){
                alert('密码必须为6到8个数字！');
                return false;
            }
        }
        IO.post('index.php?c=login&a=login&phone='+pv+'&password='+ups+'', function(data) {
            document.write(data);
        });
    });


    //个人中心登录
    var J_login_my = $('#J_login_my');
    var username=$('#user-name');
    var userPsw=$('#user-psw');

    J_login_my.on('click', function() {
        if(username.length==1){
            var username=S.trim(username.val());
            if(username==''){
                alert('手机号不能为空！');
                return false;
            }
            DATA.username=username;
        }
        //密码
        if(userPsw.length==1){
            var psw=S.trim(userPsw.val());
            if(psw==''){
                alert('密码不能为空！');
                return false;
            }else if(psw.length<6 ||  psw.length>8 || !REG.number.test(psw)){
                alert('密码必须为6到8个数字！');
                return false;
            }
            DATA.password=psw;
        }

        IO.post('/index.php?c=login&', DATA, function(data) {
            if (data.status == 200) {
                location.href = data.url;
            } else {
                alert('密码错误');
            }
        }, 'json');
    });

	//我要推荐提交
	var submitRec = $('#J_submitRec');
	var floor = $('#floor');
	var selorderTime = $('#selorderTime');
	var selorderTime2 = $('#selorderTime2');
    var remark = $('#remark');
    var phone1=$('#phone1');
    $('#phone1').on('blur',function()
    {
        var pv = S.trim(phone1.val());
        if (pv == '') 
        {
            $('#sjexist').html("手机号不能为空");
            return false;
        } 
        else if (!REG.phone.test(pv)) 
        {
            $('#sjexist').html('请填写正确的手机号！');
            return false;
        }
        else
        {
            IO.post('index.php?c=recommend&a=checkCustomer&cellphone='+pv,function(data) {
                if (data == 1) {
                    $('#sjexist').html('手机号已经被使用');
                } 
            });
        }
    });
	submitRec.on('click', function() {
		//姓名
		if (name.length == 1) {
			var nv = S.trim(name.val());
			if (nv == '')
             {
				alert('姓名不能为空！');
				return false;
			} else if (name.length > 6) 
            {
				alert('姓名不能超过6个字！');
				return false;
			} else if (!REG.name.test(nv)) {
				alert('请填写正确的姓名！');
				return false;
			}
            nv=encodeURI(nv);
		}
		//手机
		if (phone1.length == 1) {
			var pv = S.trim(phone1.val());
			if (pv == '') {
				alert('手机号不能为空！');
				return false;
			} else if (!REG.phone.test(pv)) {
				alert('请填写正确的手机号！');
				return false;
			}
		}
		//意向楼盘
		if (floor.length == 1) {
			var prv = S.trim(floor.val());
			if (prv == 0) {
				alert('请选择客户的意向楼盘');
				return false;
			}
            prv=encodeURI(prv);

		}
		//意向楼盘
		if (selorderTime.length == 1) {
			var st = S.trim(selorderTime.val());
			if (st == 0) {
				alert('请输入预约日期');
				return false;
			}
		}
		if (selorderTime2.length == 1) {
			var st2 = selorderTime2.val();
			if (st2 == 0) {
				alert('请选择预约时段');
				return false;
			}
		}
        if (remark.length == 1) {
            var pre = S.trim(remark.val());
            if (pre.length > 50) {
                alert('备注不能超过50个字');
                return false;
            }
            pre=encodeURI(pre);
        }


		//请求
		IO.post('index.php?c=recommend&f=save&username='+nv+'&cellphone='+pv+'&proname='+prv+'&selorderTime='+st+'&selorderTime2='+st2+'&remark='+pre+'', function(data) {
			if (data == 1) {
                $('.recommend-pop').show();
                $('.pop-bg').show();
			} else {
				alert(data);
			}
		});
	});

	var dialogs = $('#dialog');
	var ad = $('.ad');
	dialogs.on('click', function() {
			ad.addClass('adshow');
	});
	ad.on('click', function() {
			ad.remove('adshow');
	});
	//保存银行卡信息
	var saveCard = $('#J_saveCard');
	var accountName=$('#bankAccount');
	var card = $('#cardCode');
	var bank = $('#bankName');

	saveCard.on('click', function() {
		//户名
        if(accountName.length==1){
            var account=S.trim(accountName.val());
            if(account==''){
                alert('户名不能为空！');
                return false;
            } else if (!REG.name.test(account)) {
				alert('请填写正确的用户名！');
				return false;
			}
        }

		//银行卡号
		if (card.length == 1) {
			var num = S.trim(card.val());
			if (num == '') {
				alert('银行卡号不能为空！');
				return false;
			} else if (!REG.number.test(num)) {
				alert('请填写正确的银行号！');
				return false;
			}
		}
		//银行卡名称
		if (bank.length == 1) {
			var name = S.trim(bank.val());
			if (name == '') {
				alert('银行名称不能为空！');
				return false;
			}
		}

		//请求
		IO.post('index.php?c=usercp&f=savebank&bankAccount='+account+'&cardCode='+num+'&bankName='+name+'', function(data) {
			if (data == 1) {
				window.location.href = base_file+'?c=commission';
			} else {
				alert(data);
			}
		});

	});



    //编辑个人资料
    var editSave=$('#J_edit_save');
    editSave.on('click',function(){
        //姓名
        if (name.length == 1) {
            var nv = S.trim(name.val());
            if (nv == '') {
                alert('姓名不能为空！');
                return false;
            } else if (name.length > 6) {
                alert('姓名不能超过6个字！');
                return false;
            } else if (!REG.name.test(nv)) {
                alert('请填写正确的姓名！');
                return false;
            }
        }
        //手机
        if (phone.length == 1) {
            var pv = S.trim(phone.val());
            if (pv == '') {
                alert('手机号不能为空！');
                return false;
            } else if (!REG.phone.test(pv)) {
                alert('请填写正确的手机号！');
                return false;
            }
        }
       
        //职业
        if (job.length == 1) {
                var prv = job.val();
                var prCompany=S.trim(company.val());
                if (prv == 0) {
                    alert('请选择您的职业');
                    return false;
                }else if (job.val() == 'ZJGS' || job.val() == 'DLGS' || job.val() == 'HZHB' || job.val() == 'HZSP') {
                    if(prCompany==''){
                        alert('公司名称不能为空！');
                        return false;
                    }
                }
        }
        //请求
        IO.post('index.php?c=usercp&f=ok&name='+nv+'&job='+prv+'&company='+prCompany+'',function(data){
            if(data==1){
                alert('修改成功！');
            }else{
                alert(data);
            }
        });
    })


    //初始化页面高度
    var v_h  = null;     //记录设备的高度

    function init_pageH(){
        var fn_h = function() {
            if(document.compatMode == 'BackCompat')
                var Node = document.body;
            else
                var Node = document.documentElement;
             return Math.max(Node.scrollHeight,Node.clientHeight);
        }
        var page_h = fn_h();

        // //设置各种模块页面的高度，扩展到整个屏幕高度
        $('.gift').height(page_h);
        $('.regift-page').height(page_h);
    };
    init_pageH(); 

    //注册有礼添加动画
    setTimeout(function(){
        $('.gift-box').addClass('animated tada');
    },200);
    setTimeout(function(){
        $('.gift-text').addClass('animated fadeInUp');
    },500);
    setTimeout(function(){
        $('.gift-open').addClass('animated fadeInDown');
    },600);
    setTimeout(function(){
        $('.gift-flash-1').addClass('animated flash');
        $('.gift-flash-2').addClass('animated flash');
        $('.gift-flash-3').addClass('animated flash');
        $('.gift-flash-4').addClass('animated flash');
        $('.gift-flash-5').addClass('animated flash');
    },1200);
    
    //打开奖品
    var gift=$('.gift-amount');
    var prize=$('.prize');
    gift.on('click',function(){
        prize.removeClass('animated zoomOut');
        prize.addClass('animated zoomInPrice');
        bg.show();
    })

    //关闭奖品
    var prizeClose=$('.prize-close');
    prizeClose.on('click',function(){
        prize.removeClass('animated zoomInPrice');
        prize.addClass('animated zoomOut');
        bg.hide();
    })

    //分享朋友圈提示
    var share=$('.J_share');
    share.on('click',function(){
        if($('.share-tips').length==0){
            $('body').append('<div class="share-tips"><a href="javascript:;" class="close">关闭</a><img src="/tpl/www/bg-guide.png" alt="" /></div>');
        }
        $('.share-tips').on('click',function(){
            $('.share-tips,.share-tips .close,.share-tips img').remove();
        });
    });

    //案场经理全选
    var checkAll=$('.checkbox-all');
    var checkOne=$('.checkbox-btn .regular-checkbox');
    checkAll.on('click',function(){
        var is_pitch=$(this).prop('checked');
        if(is_pitch){
            checkOne.each(function(){
                $(this).prop('checked',true);
            });
        }else{
            checkOne.each(function(){
                $(this).prop('checked',false);
            });
        }
    });

    //案场经理点击checkbox
    var clients=$('.checkbox-btn');
    clients.on('click',function(){
        checkAll.prop('checked',checkOne.length==checkOne.filter(':checked').length);
    });

    //置业顾问注销
    var logOut=$('.J_logout');
    var ok_delete=$('.J_ok_delete');
    var logoutBox=$('.logout-box');
    var logoutUrl = $('#logoutUrl').val();
    var uid = $('#uid').val();
    logOut.on('click',function(){
        logoutBox.show();
    });
    ok_delete.on('click',function(){
        IO.post(logoutUrl, {uid:uid}, function(data) {
            if (data.status == 200) {
                location.href = data.teamUrl;
            } else {
                alert("注销失败");
            }
        }, 'json');
    });

    //关闭置业顾问注销弹出层
    var Cons_cancel=$('.Cons_cancel');
    Cons_cancel.on('click',function(){
        logoutBox.hide();
    });

    //经纪人信息显示隐藏
    var jjrTitle=$('.jjr-title');
    var jjrHide=$('.jjr-hide');
    var iconDown=$('.icon-down-open-big');
    jjrTitle.on('click',function(){
        jjrHide.toggle();
        iconDown.toggleClass('icon-down-transform');
    });



    //案场经理修改客户状态操作
    var J_save_status = $('.J_save_status');
    var cid = $('#cid').val();
    var zid = $('#zid').val();
    var statusUrl = $('#statusUrl').val();

    J_save_status.on('click', function() {
        var now_status = $('#now_status').val();
        var updated_status = $('#updated_status').val();
        var number_status = updated_status - now_status;
        if (number_status == 1) {
            var DATA = {};
            DATA.customer_id = cid;
            DATA.zid = zid;
            DATA.waid = waid;
            if (updated_status==2) { //已跟进（有意向无意向）
                DATA.status = 2;
                DATA.intent = $('#intent').val();
                //请求
                IO.post(statusUrl, DATA, function(data) {
                    if (data.status == 200) {
                        $('#now_status').val(data.now_status);
                        location.href = data.url;
                    } else {
                        alert('操作失败');
                    }
                }, 'json');
            } else if (updated_status==6) {//签约
                   DATA.intent = 1;
                   DATA.price = $('#price').val();
                   DATA.status = 6;

                   IO.post(statusUrl, DATA, function(data) {
                        if (data.status == 200) {
                            $("#now_status").val(data.now_status);
                            location.href = data.url;
                        } else {
                            alert('操作失败');
                        }
                   }, 'json');
             }else{
                var DATA = {};
                DATA.customer_id = cid;
                DATA.zid = zid;
                DATA.waid = waid;
                DATA.intent = 1;
                DATA.status = updated_status;

                IO.post(statusUrl, DATA, function(data) {
                    if (data.status == 200) {
                        $("#now_status").val(data.now_status);
                        location.href = data.url;
                    } else {
                        alert('操作失败');
                    }
                }, 'json');
            }
        } else {
            alert('请先确认上步操作')
        }
    });
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
function url_encode(str){return transform(str);}//短时间搞不懂kissy 新写前端ajax事件
function sjhe(name)
{
    var id=document.getElementById(phone);

}