
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{:config('admin.title')}</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="__CSS__/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__CSS__/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="__CSS__/animate.css" rel="stylesheet">
    <link href="__CSS__/style.css?v=4.1.0" rel="stylesheet">
    <link href="__PLUGINS__/css/toastr/toastr.min.css" rel="stylesheet">
    {block name="css"}{/block}
    <style>
        .page-form-control {
            background-color: rgb(255, 255, 255);
            background-image: none;
            color: inherit;
            font-size: 1px;
            border-color: rgb(229, 230, 231);
            border-image: initial;
            border-radius: 1px;
            padding: 5px 12px;
        }
        .page-form-control-input {
            background-color: rgb(255, 255, 255);
            background-image: none;
            color: inherit;
            font-size: 1px;
            padding: 5px 5px 1px 12px;
            width: 10%;
        }
    </style>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="ibox-title">首页 / {block name="menu"}{/block}</div>
    <form role="form" class="form-inline">
    <div class="ibox-title">
            {block name="search"}{/block}
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    {block name="button-create"}{/block}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                 {block name="table-head"}{/block}
                            </thead>
                            <tbody>
                                {block name="table-body"}{/block}
                            </tbody>
                        </table>
                    </div>
                    {block name="paginate"}{/block}
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
<!-- 全局js -->
<script src="__JS__/jquery.min.js?v=2.1.4"></script>
<script src="__JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__PLUGINS__/js/toastr/toastr.min.js"></script>
<script src="__JS__/content.js?v=1.0.0"></script>
<script src="__PLUGINS__/js/layer/layer.js"></script>
{block name="js"}{/block}
</body>
</html>
<script>
    $('.delete').click(function () {
        id = $(this).attr('data');
        url = $(this).attr('data-url')
        var index = layer.confirm('确认删除？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            layer.close(index)
            $.post(url, {id:id}, function (response) {
                if (!response.code) {
                    warning(response.msg)
                } else {
                    success(response.msg)
                    setTimeout(function(){
                        window.location.href = response.url
                    }, response.wait * 1000);
                }
            })
        });
    })
    $('.hrefTo').click(function () {
        $('form').submit();
    })
    $('.limit').change(function () {
        $('input[name=page]').val(1)
        $('form').submit();
    })
</script>
