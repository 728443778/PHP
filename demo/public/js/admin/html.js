// 静态页生成相关JS
if(!base_url)
{
	alert("操作有错误，没有获取当前目录");
}

function to_sitemap()
{
	var qc = confirm("确定要生成sitemap吗？");
	if(qc == "0")
	{
		return false;
	}
	var url = base_url + base_func + "=sitemap";
	direct(url);
}

function to_baidu_sitemap()
{
	var qc = confirm("确定要生成百度Sitemap吗？");
	if(qc == "0")
	{
		return false;
	}
	var url = base_url + base_func + "=baidu_sitemap";
	direct(url);
}

function to_ror()
{
	var qc = confirm("确定要生成ror吗？");
	if(qc == "0")
	{
		return false;
	}
	var url = base_url + base_func + "=ror";
	direct(url);
}

function to_urllist()
{
	var qc = confirm("确定要生成urllist吗？");
	if(qc == "0")
	{
		return false;
	}
	var url = base_url + base_func + "=urllist";
	direct(url);
}

function to_html(val)
{
	if(val == "index")
	{
		var url = base_url + base_func + "=create_index&";
		get_ajax(url,js_create_status);
	}
	else if(val == "list")
	{
		var typeid = getid("typeid").value;
		var url = base_url + base_func + "=create_list_set&typeid="+EncodeUtf8(typeid);
		get_ajax(url,js_create_list);
	}
	else if(val == "msg")
	{
		var typeid = getid("typeid").value;
		var url = base_url + base_func + "=create_msg&typeid="+EncodeUtf8(typeid);
		var sid = getid("startid").value;
		if(sid && parseInt(sid)>0)
		{
			url += "&sid="+sid;
		}
		var eid = getid("endid").value;
		if(eid && parseInt(eid)>0)
		{
			url += "&eid="+eid;
		}
		get_ajax(url,js_create_msg);
	}
	return true;
}

function js_create_msg(msg)
{
	var rs = $.evalJSON(msg);
	if(rs["status"] == "ok" || rs["status"] == "error")
	{
		return html_notice(rs);
	}
	else
	{
		var url = base_url + base_func + "=create_msg&";
		url+= "mid="+rs["mid"]+"&";
		url+= "cid="+rs["cid"]+"&";
		url+= "sid="+rs["sid"]+"&";
		url+= "eid="+rs["eid"]+"&";
		url+= "tid="+rs["tid"]+"&";
		html_notice(rs);
		eval_js(0.3,"get_ajax('"+url+"',js_create_msg)");
	}
	return true;
}

function js_create_list(msg)
{
	if(msg)
	{
		var rs = $.evalJSON(msg);
		if(rs["status"] == "ok" || rs["status"] == "error")
		{
			return html_notice(rs);
		}
		else
		{
			var url = base_url + base_func + "=create_list&";
			url+= "mid="+rs["mid"]+"&";
			url+= "endmid="+rs["endmid"]+"&";
			url+= "cid="+rs["cid"]+"&";
			url+= "endcid="+rs["endcid"]+"&";
			if(rs["pageid"] && rs["pageid"] != "undefined")
			{
				url+= "pageid="+rs["pageid"];
			}
			html_notice(rs);
			eval_js(0.3,"get_ajax('"+url+"',js_create_list)");
		}
		return true;
	}
}

function js_create_status(msg)
{
	var array = $.evalJSON(msg);
	html_notice(array);
}

function html_notice(array)
{
	if(array["status"] == "error")
	{
		getid("creat_html_status").style.color = "red";
	}
	else if(array["status"] == "ok")
	{
		getid("creat_html_status").style.color = "darkgreen";
	}
	else if(array["status"] == "next")
	{
		getid("creat_html_status").style.color = "darkblue";
	}
	else
	{
		getid("creat_html_status").style.color = "darkred";
	}
	if(!array["subject"])
	{
		array["subject"] = "操作异常，请检查！";
	}
	getid("creat_html_status").innerHTML = array["subject"];
	return true;
}