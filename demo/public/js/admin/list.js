// 内容控制层信息
function tab_set(v)
{
	$("#_tab_main").attr("class","out");
	$("#_tab_biz").attr("class","out");
	$("#_tab_ext").attr("class","out");
	$("#_msg_main").hide();
	$("#_msg_biz").hide();
	$("#_msg_ext").hide();
	$("#_tab_"+v).attr("class","over");
	$("#_msg_"+v).show();
}


//样式管理器
function style_set(val)
{
	var style = getid("style").value;
	if(!style)
	{
		getid("style").value = val + ";";
		return true;
	}
	//定义数组
	var array = style.split(";");
	var len = array.length;
	var n_array = new Array();
	var m = 0;
	for(var i=0;i<len;i++)
	{
		//如果存在其值，则清空吧
		if(array[i] == val)
		{
			return true;
		}
		if(array[i] != "")
		{
			n_array[m] = array[i];
			m++;
		}
	}
	//重新组成字符串
	var n_array = n_array.join(";");
	getid("style").value = n_array+";"+val+";";
	return true;
}

//颜色管理器
function style_color(val)
{
	var style = getid("style").value;
	if(!style)
	{
		if(!val)
		{
			getid("style").value = "";
		}
		else
		{
			getid("style").value = "color:"+val+";";
		}
		return true;
	}
	var array = style.split(";");
	var len = array.length;
	var n_array = new Array();
	var m = 0;
	for(var i=0;i<len;i++)
	{
		//定义切割
		if(array[i] == "")
		{
			continue;
		}
		var t = array[i].split(":");
		if(t[0] == "color")
		{
			continue;
		}
		n_array[m] = array[i];
		m++;
	}
	//重新组成字符串
	var n_array = n_array.join(";");
	if(n_array != "")
	{
		getid("style").value = n_array+";color:"+val+";";
	}
	else
	{
		getid("style").value = "color:"+val+";";
	}
	return true;
}

function del_pl()
{
	var id = join_checkbox();
	if(!id)
	{
		alert("请选择要删除的主题");
		return false;
	}
	var qc = confirm("确定要删除此信息吗？删除后是不能恢复的");
	if(qc == "0")
	{
		return false;
	}
	var url = base_url + base_func + "=ajax_del&id="+EncodeUtf8(id);
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

function taxis_pl()
{
	var id = join_checkbox();
	if(!id)
	{
		alert("请选择要操作的主题");
		return false;
	}
	var url = base_url + base_func + "=taxis_pl&";
	//获取taxis值
	var id_array = id.split(",");
	for(var i=0;i<id_array.length;i++)
	{
		var taxis = getid("taxis_"+id_array[i]).value;
		url += "taxis["+id_array[i]+"]="+taxis+"&";
	}
	var msg = get_ajax(url);
	if(msg == "ok")
	{
		direct(window.location.href);
	}
	else
	{
		if(!msg) msg = "error: 操作非法";
		alert(msg);
		return false;
	}
}

function update_pl()
{
	var id = join_checkbox();
	if(!id)
	{
		alert("请选择要操作的主题");
		return false;
	}
	//获取执行操作的ID值
	var act = getid("act_plset").value;
	if(!act)
	{
		alert("请选择要执行的动作！");
		return false;
	}
	if(act == "del")
	{
		del_pl();
		return true;
	}
	else if(act == "taxis")
	{
		taxis_pl();
		return true;
	}
	else
	{
		var array = act.split(":");
		if(array[0] == "cate")
		{
			update_cate(array[1]);
			return true;
		}
		else
		{
			var url = base_url + base_func + "=ajax_pl&field="+array[0]+"&val="+array[1]+"&id="+id;
			var msg = get_ajax(url);
			if(msg == "ok")
			{
				direct(window.location.href);
			}
			else
			{
				if(!msg) msg = "error: 操作非法";
				alert(msg);
				return false;
			}
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

function set_taxis_time(id)
{
	var time = new Date().getTime();
	time = parseInt(time/1000);
	getid(id).value = time;
	return true;
}

function update_cate(cate_id)
{
	var id = join_checkbox();
	if(!id)
	{
		alert("请选择要操作的主题");
		return false;
	}
	var url = base_url + base_func + "=ajax_update_cate&cateid="+cate_id+"&id="+id;
	var msg = get_ajax(url);
	if(msg == "ok")
	{
		direct(window.location.href);
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