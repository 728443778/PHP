var mychart=echarts.init(document.getElementById('main'));
	    mychart.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});
    var proCount=echarts.init(document.getElementById('proname'));
    proCount.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});
var ageCount=echarts.init(document.getElementById('age'));
ageCount.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});
var typeCount=echarts.init(document.getElementById("type"));
typeCount.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});
var sizeCount=echarts.init(document.getElementById("size"));
sizeCount.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});
var priceCount=echarts.init(document.getElementById("price"));
priceCount.setOption({
    tooltip : {
        show:true,
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    /* 需要动态生成*/
    legend: {       //提醒
        show:true,
        orient : 'vertical',
        x : 'left',
        data:['未审核','审核','到访','认筹','认购','签约','回款','过期','审核不通过']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true, 
                title:{
                    line:'折线图切换',
                    bar:'柱形图切换',
                    stack:'堆积',
                    tiled:'平铺',
                    force:'力导向布局图',
                    chord:'和弦图切换',
                    pie:'饼图切换',
                    funnel:'漏斗图切换'
                },
                type: ['pie', 'funnel','line','bar','force','chord'],
                option: {
                    funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: 1548
                    },
                    bar:{
                        
                    }
                }
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    /* 需要动态生成*/
    series : [
        {
            name:'状态统计',
            type:'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'未审核'},
                {value:310, name:'审核'},
                {value:234, name:'到访'},
                {value:135, name:'认筹'},
                {value:8562, name:'认购'},
                {value:8562, name:'签约'},
                {value:8562, name:'回款'},
                {value:8562, name:'过期'},
                {value:8562, name:'审核不通过'}
            ]
        }
    ]
});