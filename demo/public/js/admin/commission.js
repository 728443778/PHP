//订单相关JS操作
var commission_url = base_file + "?"+base_ctrl+"=commission&"+base_func+"=";

function show_user(id)
{
	var url = base_file + "?"+base_ctrl+"=user&"+base_func+"=view&id="+id;
	Layer.init(url,550,400);
}

function to_show(id)
{
	var url = commission_url + "show&id="+id;
	Layer.init(url,550,400);
}



function del(id)
{
	if(!id)
	{
		alert("操作非法");
		return false;
	}
	var q = confirm("确定要删除此信息吗？删除后是不能恢复的");
	if(q != 0)
	{
		var url = base_url + base_func + "=ajax_del&id="+id;
		var msg = get_ajax(url);
		if(!msg) msg = "error: 操作非法";
		if(msg == "ok")
		{
			direct(window.location.href);
		}
		else
		{
			alert(msg);
			return false;
		}
	}
}

//更改权限状态
function status(id,t)
{
	if(!id)
	{
		alert("操作非法");
		return false;
	}
	var url = base_url + base_func + "=ajax_status&id="+id;
	var msg = get_ajax(url);
	if(msg == "ok")
	{
		var n_t = t == 1 ? 0 : 1;
		$("#status_"+id+" > a").attr("class","status"+n_t);
		$("#status_"+id+" > a").attr("href","javascript:status("+id+","+n_t+");void(0)");
		return true;
	}
	else
	{
		if(!msg) msg = "error: 操作非法";
		alert(msg);
		return false;
	}
}


function to_check_one(id,vid,syn,must)
{
	getid("identifier_note").innerHTML = "";
	var val = getid(vid).value;
	if(!must || must == "undefined")
	{
		must = false;
	}
	if(!val)
	{
		return (must ? false : true);
	}
	var ajax_url = base_url + base_func + "=chkone&sign="+encode_utf8(val)+"&id="+id;
	if(syn)
	{
		get_ajax(ajax_url,js_tocheck_one)
		return false;
	}
	else
	{
		var msg = get_ajax(ajax_url);
		if(msg == "ok")
		{
			return true;
		}
		else
		{
			if(!msg) msg = "error: 标识串错误！";
			alert(msg);
			return false;
		}
	}
}

function js_tocheck_one(msg)
{
	if(msg)
	{
		if(msg == "ok")
		{
			getid("identifier_note").innerHTML = str_right;
			return true;
		}
		else
		{
			if(!msg) msg = "error: 标识串错误！";
			getid("identifier_note").innerHTML = str_wrong + msg;
			return false;
		}
	}
	return true;
}