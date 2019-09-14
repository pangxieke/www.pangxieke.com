---
title: datatable服务器端分页-附带搜索
id: 1001
categories:
  - linux
date: 2016-05-10 18:18:18
tags: datatable
---

最近写后台，觉得datatable的插件很好用。Datatables是一款jquery表格插件。它是一个高度灵活的工具，可以将任何HTML表格添加高级的交互功能。

但是以前一直没有使用服务器分页。数据量庞大后表格加载太慢。因而研究了下服务器分页。遇到了不少坑，终于成功解决了问题.

## 一、html代码

```php
<!DOCTYPE html>
<head>
<title>index</title>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
 
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.10.2.min.js">
</script>
 
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.7/js/jquery.dataTables.js">
</script>
 
</head>
<body>
<div class="form-horizontal">
    <div class="form-group">
        <label for="status" class="col-sm-1 control-label">状态筛选</label>
        <div class="col-sm-2">
            <select id="status" class="form-control">
                    <option value="1">状态1</option>
                    <option value="2">状态2</option>
                    <option value="3">状态3</option>
                <option value="">全部</option>
 
            </select>
        </div>
    </div>
</div>
 
<table id="table" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>username</th>
            <th>to_username</th>
            <th>desc</th>
            <td>status</td>
 
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
 
</body>
</html>

```

## 二、js代码

```php
<script>
    $(function(){
    var table = $("#table").DataTable({
        "oLanguage": {
            "sLengthMenu": "每页显示 _MENU_ 条记录",
            "sZeroRecords": "抱歉， 没有找到",
            "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
            "sInfoEmpty": "没有数据",
            "sSearch": "关键字筛选：",
            "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "前一页",
                "sNext": "后一页",
                "sLast": "尾页"
            },
        },
        "ajax": {
            "url":"/?c=site&a=ajaxTable",
            "data": function ( d ) {
                //添加额外的参数传给服务器
                d.status = $('#status').val();
            }
        },
        "processing": true,
        "serverSide": true,
        "fnRowCallback": function(nRow, aaData, iDisplayIndex) {
            if(aaData[3]){
                $('td:eq(3)', nRow).html('时间' + aaData[3]);
            }
            return nRow;
        },
        initComplete:initComplete,
    });
 
    function initComplete(){
        $("#status").on('change', function(){
            //当选择时间后，出发dt的重新加载数据的方法
            table.ajax.reload();
        });
    }
 
});
</script>
```

## 三、服务器代码（php）

```php
public function ajaxTable()
{
    $start = $_GET['start']; //显示的起始索引
    $length = $_GET['length'];//显示的行数
 
    //搜索条件
    $status = $_GET['status'];
    $where = ['status'=>$status];
 
    $res = [];
    $aaData = [];
    for($i=0; $i<=20; $i++){
        $res[] = [
            $i,
            'username',
            'tousername',
            time(),
            'status'
        ];
    }
    $aaData = array_slice($res, $start, $length);
 
    $data = [];
    $data['sEcho'] = $_GET['sEcho'];
    $data['iTotalDisplayRecords'] = count($res);
    $data['iTotalRecords'] = count($res);
    $data['aaData'] = $aaData;
    $data['iDisplayLength'] = $length;
    echo json_encode($data);
}
```

## 四、服务器返回的json代码示例

```php
{
    "sEcho": null,
    "iTotalDisplayRecords": 3,
    "iTotalRecords": 3,
    "aaData": [
        [
            0,
            "username",
            "tousername",
            1462873816,
            "status"
        ],
        [
            1,
            "username",
            "tousername",
            1462873816,
            "status"
        ],
        [
            2,
            "username",
            "tousername",
            1462873816,
            "status"
        ]
    ],
    "iDisplayLength": null
}
```

## 五、总结

### 1、搜索功能，使用table.ajax.reload()触发从新加载table。

```php
function initComplete(){
    $("#status").on('change', function(){
        //当选择时间后，出发dt的重新加载数据的方法
        table.ajax.reload();
    });
}
```

### 2、触发时传递搜索参数

```php
"ajax": {
    "url":"/?c=site&a=ajaxTable",
    "data": function ( d ) {
        //添加额外的参数传给服务器
        d.status = $('#status').val();
    }
}
```

### 3、服务器端接受搜索参数

```$status = $_GET['status']```

### 4、回调函数fnRowCallback

服务器返回的信息有时候需要转换，可以使用回调函数fnRowCallback处理

### 5、参考网站

1.  [Datatables中文网](http://datatables.club/index.html)[
](http://datatables.club/index.html)
2.  [官网https://datatables.net](https://datatables.net/ "官网https://datatables.net/")