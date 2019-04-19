<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>角色</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="<?= $path ?>/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="<?= $path ?>/style/admin.css" media="all">
</head>
<body>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline" style="float: right;">
                    <button id="add" class="layui-btn layuiadmin-btn-order" lay-submit lay-filter="my-table-search">
                        添加
                    </button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="my-table-data" lay-filter="my-table-data">
                <script type="text/html" id="my-table-toolbar">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-xs" lay-event="allot">权限</a>
                    {{#  if(d.status == 2){ }}
                    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="open">启用</a>
                    {{#  } }}
                    {{#  if(d.status == 1){ }}
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="close">删除</a>
                    {{#  } }}
                </script>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?= $path ?>/layui/layui.js"></script>
<script>
    var table;
    layui.config({
        base: '<?= $path ?>' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'tablePlug'], function () {
        var form = layui.form;
        table = layui.table;

        layui.tablePlug.smartReload.enable(true);

        table.render({
            elem: '#my-table-data',
            url: 'index',
            smartReloadModel: true,
            cols: [[
                {field: 'id', title: 'ID'},
                {field: 'title', title: '角色名'},
                {field: 'num', title: '用户个数'},
                {field: 'status', title: '状态'
                    ,templet: function(d){
                        return d.status == 1 ? '<font style="color:green">正常</font>' : '<font style="color:red">删除</font>'
                    }
                },
                {fixed: 'right', title: '操作', toolbar: '#my-table-toolbar', width: 350}
            ]],
            page: false
        });

        table.on('sort(my-table-data)', function (obj) {
            table.reload('my-table-data', {initSort: obj, where: {field: obj.field, order: obj.type}});
        });

        form.on('submit(my-table-search)', function (data) {
            table.reload('my-table-data', {where: data.field});
        });

        table.on('tool(my-table-data)', function (obj) {
            var data = obj.data;
            if (obj.event === 'open') {
                var index = layer.confirm('你确定要启用么？', function () {
                    layer.close(index);
                    return $.change_status({url: 'open', layer: layer, index: index, table: table, data: {'id':data['id']}});
                });
            } else if (obj.event === 'close') {
                var index = layer.confirm('你确定要删除么？', function () {
                    layer.close(index);
                    return $.change_status({url: 'close', layer: layer, index: index, table: table, data: {'id':data['id']}});
                });
            } else if (obj.event === 'edit') {
                layer.open({type: 2, title: '编辑', shadeClose: true, shade: 0.8, area: ['50%', '50%'], maxmin: true, content: 'edit?id=' + data['id']});
                return false;
            } else if (obj.event === 'allot') {
                layer.open({type: 2, title: '权限', shadeClose: true, shade: 0.8, area: ['80%', '80%'], maxmin: true, content: 'allot?id=' + data['id']});
                return false;
            }
        });

        $('#add').click(function(){
            layer.open({type: 2, title: '添加', shadeClose: true, shade: 0.8, area: ['50%', '50%'], maxmin: true, content: 'add'});
            return false;
        });
    });
</script>
<script>
    jQuery.change_status = function (options) {
        options = options || {};
        $.ajax({
            url: options['url'],
            dataType: 'json',
            method: 'POST',
            data: options['data'] || '',
            success: function (data) {
                if (data.status) {
                    if (options['table']) {
                        options['table'].reload('my-table-data', {});//me.remove();
                    } else {
                        window.location.reload();//刷新父页面
                    }
                } else layer.msg('数据保存失败', {icon: 5});
            },
            error: function () {
                layer.msg('数据保存失败!', {icon: 5});
            },
            complete: function () {
                submitting = false;
            }
        });
        return false;
    };
</script>
</body>
</html>