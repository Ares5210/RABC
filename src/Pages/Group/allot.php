<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>分配权限</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="<?= $path ?>layui/css/layui.css" media="all">
    <link rel="stylesheet" href="<?= $path ?>style/admin.css" media="all">
</head>
<body>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">角色名称：<?= $res['title'] ?></div>
                <div class="layui-card-body">
                    <?php $access = isset($res['rules']) ? explode(',', $res['rules']) : array() ?>
                    <form id="uploadForm" class="layui-form" method="post" lay-filter="component-form-group">
                        <table class="layui-table layui-form">
                            <tbody>
                            <?php
                            for ($row = 1; $row <= $data['rows']; $row++) {
                                echo '<tr>';
                                foreach ($data['list'] as $v) {
                                    if ($v['row'] == $row) {
                                        $rowspan = $v['rowspan'] ? "rowspan='{$v['rowspan']}'" : '';
                                        $colspan = $v['colspan'] ? "colspan='{$v['colspan']}'" : '';
                                        echo "<td {$rowspan} {$colspan}><input lay-filter='group' type='checkbox' name='group_id[]' value='{$v['id']}' title='{$v['title']}' lay-skin='primary' " . (in_array($v['id'], $access) ? ' checked' : '') . "></td>";
                                    }
                                }
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>

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
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="<?= $path ?>/layui/layui.js"></script>

<script>
    layui.config({
        base: '<?= $path ?>' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user', 'form', 'laydate'], function () {
        var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            , laydate = layui.laydate
            , form = layui.form;

        form.render();

        form.on('checkbox(group)', function(data){
            console.log(data.elem); //得到checkbox原始DOM对象
            console.log(data.elem.checked); //是否被选中，true或者false
            console.log(data.value); //复选框value值，也可以通过data.elem.value得到
            console.log(data.othis); //得到美化后的DOM对象
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
</script>

</body>
</html>