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
    <style type="text/css">
        .laytable-cell-1-0-0 {
            width: 80px;
        }

        .laytable-cell-1-0-1 {
            width: 200px;
        }

        .laytable-cell-1-0-2 {
            width: 200px;
        }

        .laytable-cell-1-0-3 {
            width: 200px;
        }

        .laytable-cell-1-0-4 {
            width: 100px;
        }

        .layadmin-side-none .layui-side {
            display: none
        }

        .layui-layer-btn3 {
            float: right;
        }

        .layui-card {
            box-shadow: 0 0 0;
        }

        html {
            background-color: #fff;
        }

        .layadmin-side-none .layadmin-pagetabs, .layadmin-side-none .layui-layout-admin .layui-body, .layadmin-side-none .layui-layout-admin .layui-footer, .layadmin-side-none .layui-layout-admin .layui-layout-left {
            left: 0
        }
    </style>
</head>
<body layadmin-themealias="fashion-red-header">


<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body" style="padding: 15px;">
            <form class="layui-form" id="uploadForm" method="post" action="" lay-filter="component-form-group" enctype="multipart/form-data">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" value="<?php if(isset($res['title'])) { echo $res['title']; } ?>" lay-verify="required" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button type="button" class="layui-btn" id="jjj">提交</button>
                            <button type="button" class="layui-btn layui-btn-primary" id="close">关闭</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?= $path ?>/layui/layui.js"></script>

<script>
    layui.config({
        base: '<?= $path ?>' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user','form', 'laydate'], function () {
        var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            ,laydate = layui.laydate
            , form = layui.form;

        form.render();

        //时间插件
        laydate.render({
            elem: '#LAY-component-form-group-date'
        });

        //提交
        $("#jjj").on("click", function(){
            $.ajax({
                type: 'POST',
                cache: false,
                data: $('#uploadForm').serialize(),
                dataType: 'json',
                success : function(res) {
                    if (res.status) {
                        parent.table.reload('my-table-data', {});
                        var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);
                    } else {
                        layer.msg(res.message);
                        return false;
                    }
                }
            });
        });
        return false;
    });

    $('#close').on('click', function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
        return false;
    });
</script>

</body>
</html>
