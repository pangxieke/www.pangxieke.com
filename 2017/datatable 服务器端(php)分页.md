---
title: datatable 服务器端(php)分页
tags:
  - datatable
id: 850
categories:
  - linux
date: 2015-10-13 20:46:14
---

html代码
```php
<table cellpading="0" cellspacing="0" border="0" class="dTable acelistTable">
    <thead>
        <tr>
            <th>用户名</th>
            <th>跟随</th>
            <th>新外汇指数</th>
            <th>月均交易(手)</th>
            <th>账户余额($)</th>
            <th>近一个月收益</th>
            <th>当前持仓(单)</th>
            <th>浮动收益($)</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <!--tbody可以不写-->
    <!--<tbody></tbody>-->
    <!--tfoot 可选-->
    <tfoot> 
        <tr>
            <th>用户名</th>
            <th>跟随</th>
            <th>新外汇指数</th>
            <th>月均交易(手)</th>
            <th>账户余额($)</th>
            <th>近一个月收益</th>
            <th>当前持仓(单)</th>
            <th>浮动收益($)</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </tfoot>
</table>
```

js代码
```php
var oTable = $('.acelistTable').dataTable(
{
    "sPaginationType": "full_numbers", //分页风格，full_number会把所有页码显示出来（大概是，自己尝试）
    "sDom": "<'row-fluid inboxHeader'<'span6'<'dt_actions'>l><'span6'f>r>t<'row-fluid inboxFooter'<'span6'i><'span6'p>>", //待补充
    "iDisplayLength": 10,//每页显示10条数据
    "bAutoWidth": false,//宽度是否自动，感觉不好使的时候关掉试试
　　　　　　　　　　"bLengthChange": false, 
　　　　　　　　　　"bFilter": false,
 
    "oLanguage": {//下面是一些汉语翻译
        "sSearch": "搜索",
        "sLengthMenu": "每页显示 _MENU_ 条记录",
        "sZeroRecords": "没有检索到数据",
        "sInfo": "显示 _START_ 至 _END_ 条 &nbsp;&nbsp;共 _TOTAL_ 条",
        "sInfoFiltered": "(筛选自 _MAX_ 条数据)",
        "sInfoEmtpy": "没有数据",
        "sProcessing": "正在加载数据...",
        "sProcessing": "<img src='{{rootUrl}}global/img/ajaxLoader/loader01.gif' />", //这里是给服务器发请求后到等待时间显示的 加载gif
                "oPaginate":
                {
                    "sFirst": "首页",
                    "sPrevious": "前一页",
                    "sNext": "后一页",
                    "sLast": "末页"
                }
    },
    "bProcessing": true, //开启读取服务器数据时显示正在加载中……特别是大数据量的时候，开启此功能比较好
    "bServerSide": true, //开启服务器模式，使用服务器端处理配置datatable。注意：sAjaxSource参数也必须被给予为了给datatable源代码来获取所需的数据对于每个画。 这个翻译有点别扭。开启此模式后，你对datatables的每个操作 每页显示多少条记录、下一页、上一页、排序（表头）、搜索，这些都会传给服务器相应的值。 
    "sAjaxSource": "{{rootUrl}}ace_management/ace_list", //给服务器发请求的url
    "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
        {"mData": 'nickname'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
        {"mData": 'follower_count'},
        {"mData": 'rank'},
        {"mData": 'month_count'},
        {"mData": 'equity'},
        {"mData": 'month_ror'},
        {"mData": 'now_orders'},
        {"mData": 'profit_total'},
        {"sDefaultContent": ''}, // sDefaultContent 如果这一列不需要填充数据用这个属性，值可以不写，起占位作用
        {"sDefaultContent": '', "sClass": "action"},//sClass 表示给本列加class
    ],
    "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
        {"bSortable": false, "aTargets": [1, 3, 6, 7, 8, 9]},  //这句话意思是第1,3,6,7,8,9列（从0开始算） 不能排序
        {"bSearchable": false, "aTargets": [1, 2, 3, 4, 5, 6, 7, 8, 9]}, //bSearchable 这个属性表示是否可以全局搜索，其实在服务器端分页中是没用的
    ],
　　　　　　　　　　"aaSorting": [[2, "desc"]], //默认排序
    "fnRowCallback": function(nRow, aData, iDisplayIndex) {// 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格 
        if (aData.status == 1) {
            $('td:eq(8)', nRow).html("<span class='text-error'>审核中</span>");
        } else if (aData.status == 4) {
            $('td:eq(8)', nRow).html("<span class='text-error'>审核失败</span>");
        } else if (aData.active == 0) {
            $('td:eq(8)', nRow).html("<span>隐藏</span>");
        } else {
            $('td:eq(8)', nRow).html("<span class='text-success'>显示</span>");
        }
        $('td:eq(9)', nRow).html("<a href='' user_id='" + aData.user_id + "' class='ace_detail'>详情</a>");
        if (aData.status != 1 && aData.status != 4 && aData.active == 0) {
            $("<a class='change_ace_status'>显示</a>").appendTo($('td:eq(9)', nRow));
        } else if (aData.status != 1 && aData.status != 4 && aData.active == 1) {
            $("<a class='change_ace_status'>隐藏</a>").appendTo($('td:eq(9)', nRow));
        }
        return nRow;
    },
    "fnInitComplete": function(oSettings, json) { //表格初始化完成后调用 在这里和服务器分页没关系可以忽略
        $("input[aria-controls='DataTables_Table_0']").attr("placeHolder", "请输入高手用户名");
    }
 
});
```

服务器端代码

先说一下我们需要的目标格式是这样
```php
{
    "sEcho": 3,
    "iTotalRecords": 57,
    "iTotalDisplayRecords": 57,
    "aaData": [
        [
            "Gecko",
            "Firefox 1.0",
            "Win 98+ / OSX.2+",
            "1.7",
            "A"
        ],
        [
            "Gecko",
            "Firefox 1.5",
            "Win 98+ / OSX.2+",
            "1.8",
            "A"
        ],
        ...
    ] 
}
```

php代码
```php
$sEcho = XUtil::xget('sEcho'); // DataTables 用来生成的信息
$start = XUtil::xget('iDisplayStart'); //显示的起始索引
$length = XUtil::xget('iDisplayLength');//显示的行数
$sort_th = XUtil::xget('iSortCol_0');//被排序的列
$sort_type = XUtil::xget('sSortDir_0');//排序的方向 "desc" 或者 "asc".
$search = XUtil::xget('sSearch');//全局搜索字段 
 
//下面的代码就是根据上面的信息取数据并且组织信息
$agent_id = $this->vdata['agent_user']->id;
if ($sort_th == 2) {
    $order_key = "et.rank";
} elseif ($sort_th == 4) {
    $order_key = "ea.equity";
} elseif ($sort_th == 5) {
    $order_key = "et.month_ror";
} else {
    $order_key = "cu.nickname";
}
$total = AgentUtil::get_total_ace_acount($agent_id);
$price_table = $this->get_price_table();
$sql = "select aul.user_id ,
        cu.nickname,
        et.rank,
        ea.equity,
        et.month_ror,
        aul.active,
        cmt.`status`,
        cmt.fail_type
        from agent_user_list aul 
        left join common_userprofile cu on cu.user_id = aul.user_id
        left join ea_traderlistindex et on et.user_id = aul.user_id
        left join ea_account ea on ea.id = et.account_id
        left join common_mt4_trusteeship cmt on cmt.user_id = aul.user_id
        where et.visible = 1 and aul.agent_id = {$agent_id} and aul.type=1  and cu.nickname like '%{$search}%'
        order by {$order_key} {$sort_type}
        limit {$start},{$length}";
$res = Doo::db()->fetchAll($sql);
$display_total = count($res);
$aaData = array();
foreach ($res as $k => $v) {
    $user_id = $v['user_id'];
    $aaData[$k]['nickname'] = $v['nickname']; //还记得刚才给每一列的mData设置的名字么 和这里是对应的 
    $aaData[$k]['follower_count'] = UserUtil::get_follower_count($user_id, $agent_id);
    $aaData[$k]['rank'] = XUtil::tofloat($v['rank'] * 100);
    $aaData[$k]['month_count'] = XUtil::tofloat(UserUtil::get_month_count($user_id));
    $aaData[$k]['equity'] = XUtil::tofloat($v['equity']);
    $aaData[$k]['month_ror'] = XUtil::tofloat($v['month_ror'] * 100) . '%';
    $aaData[$k]['now_orders'] = UserUtil::get_now_orders($user_id);
    $aaData[$k]['profit_total'] = XUtil::tofloat(UserUtil::get_profit_total($user_id, $price_table));
    $aaData[$k]['status'] = $v['status'];
    $aaData[$k]['fail_type'] = $v['fail_type'];
    $aaData[$k]['active'] = $v['active'];
    $aaData[$k]['user_id'] = $v['user_id'];
}
 
 
$output['sEcho'] = $sEcho;
if ($search) {//如果有全局搜索，搜索出来的个数
    $output['iTotalDisplayRecords'] = $display_total;
} else {
    $output['iTotalDisplayRecords'] = $total;
}
$output['iTotalRecords'] = $total; //总共有几条数据
$output['aaData'] = $aaData;
 
echo json_encode($output); //最后把数据以json格式返回
```

上面displaytotal的处理方式有问题,当全局搜索存在时, displaytotal等于按条件搜索的全部数量,注意自己该下

有个需求要求打开某个页面，会自动列出所有数据，然后上面有表单个提交，要求提交表单后表格数据刷新。

实现办法就是表单用js验证成功后组织一个加上参数的url，最后
```php

var oSettings = oTable.fnSettings();
oSettings.sAjaxSource = new_filter_url;
 oTable.fnDraw();
```

原文地址http://www.cnblogs.com/firesnow/archive/2013/04/07/3006508.html