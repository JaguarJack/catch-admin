/**
 * 树形表格 2.x
 * date:2019-11-08   License By http://easyweb.vip
 */
layui.define(['layer', 'laytpl', 'form'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var laytpl = layui.laytpl;
    var form = layui.form;
    var device = layui.device();
    var MOD_NAME = 'treeTable';  // 绑定事件的模块名
    layui.link(layui.cache.base + 'treetable-lay/treeTable.css');

    /** TreeTable类构造方法 */
    var TreeTable = function (options) {
        // 表格默认参数
        var defaultOption = {
            elem: undefined,   // table容器
            data: [],  // 数据
            cols: [],  // 列配置
            reqData: undefined,  // 异步加载数据的方法
            width: undefined,  // 容器宽度
            height: undefined,  // 容器高度
            cellMinWidth: 100,  // 单元格最小宽度
            skin: undefined,  // 表格风格
            size: undefined,  // 表格尺寸
            even: undefined,  // 是否开启隔行变色
            style: undefined,   // 容器样式
            getThead: function () {  // 获取表头
                return getThead(this);
            },
            getAllChooseBox: function () {  // 获取全选按钮
                return getAllChooseBox(this);
            },
            getColgroup: function () {  // 获取colgroup
                return getColgroup(this);
            },
            getTbWidth: function () {  // 计算table的宽度
                return getTbWidth(this);
            },
            tree: {},
            text: {}
        };
        // 默认tree参数
        var treeDefaultOption = {
            idName: 'id',  // id的字段名
            pidName: 'pid',  // pid的字段名
            childName: 'children',  // children的字段名
            haveChildName: 'haveChild',  // 是否有children标识的字段名
            openName: 'open',  // 是否默认展开的字段名
            isPidData: false,  // 是否是pid形式的数据
            iconIndex: 0,  // 图标列的索引
            arrowType: undefined,  // 箭头类型
            onlyIconControl: false,  // 仅允许点击图标折叠
            getIcon: function (d) {  // 自定义图标
                return getIcon(d, this);
            }
        };
        // 默认提示文本
        var textDefaultOption = {
            none: '<div style="padding: 15px 0;">暂无数据</div>'  // 空文本提示文字
        };
        this.options = $.extend(defaultOption, options);
        this.options.tree = $.extend(treeDefaultOption, options.tree);
        this.options.text = $.extend(textDefaultOption, options.text);
        for (var i = 0; i < options.cols.length; i++) {
            // 列默认参数
            var colDefaultOption = {
                field: undefined,   // 字段名
                title: undefined,   // 标题
                align: undefined,  // 对齐方式
                templet: undefined,  // 自定义模板
                toolbar: undefined,  // 工具列
                width: undefined,   // 宽度
                type: undefined,    // 列类型
                style: undefined,   // 单元格样式
                fixed: undefined,    // 固定列
                unresize: false   // 关闭拖拽列宽
            };
            this.options.cols[i] = $.extend(colDefaultOption, options.cols[i]);
        }
        this.init();  // 初始化表格
        this.bindEvents();  // 绑定事件
    };

    /** 初始化表格 */
    TreeTable.prototype.init = function () {
        var options = this.options;
        var tbFilter = options.elem.substring(1);  // 树表格的filter
        var $elem = $(options.elem);  // 原始表格

        // 生成树表格dom
        $elem.removeAttr('lay-filter');
        $elem.next('.ew-tree-table').remove();
        var viewHtml = '<div class="layui-form ew-tree-table" style="' + (options.style || '') + '">';
        viewHtml += '      <div class="ew-tree-table-group">';
        viewHtml += '         <div class="ew-tree-table-head">';
        viewHtml += '            <div class="ew-tree-table-border bottom"></div>';
        viewHtml += '            <table class="layui-table"></table>';
        viewHtml += '         </div>';
        viewHtml += '         <div class="ew-tree-table-box">';
        viewHtml += '            <table class="layui-table"></table>';
        viewHtml += '            <div class="ew-tree-table-loading"><i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i></div>';
        viewHtml += '            <div class="ew-tree-table-empty" style="display: none;">' + (options.text.none || '') + '</div>';
        viewHtml += '         </div>';
        viewHtml += '      </div>';
        viewHtml += '      <div class="ew-tree-table-border top"></div>';
        viewHtml += '      <div class="ew-tree-table-border left"></div>';
        viewHtml += '      <div class="ew-tree-table-border right"></div>';
        viewHtml += '      <div class="ew-tree-table-border bottom"></div>';
        viewHtml += '   </div>';
        $elem.after(viewHtml);

        // 获取各个组件
        var components = this.getComponents();
        var $view = components.$view;   // 容器
        $view.attr('lay-filter', tbFilter);
        var $group = components.$group;  // 表格容器
        var $tbBox = components.$tbBox;  // 表格主体部分容器
        var $table = components.$table;  // 主体表格
        var $headTb = components.$headTb;  // 表头表格
        var $tbEmpty = components.$tbEmpty;  // 空视图
        var $tbLoading = components.$tbLoading;  // 空视图

        // 基础参数设置
        options.width && $view.css('width', options.width);
        options.skin && $table.attr('lay-skin', options.skin);
        options.size && $table.attr('lay-size', options.size);
        options.even && $table.attr('lay-even', options.even);

        // 容器边框调整
        if (device.ie) {
            $view.find('.ew-tree-table-border.bottom').css('height', '1px');
            $view.find('.ew-tree-table-border.right').css('width', '1px');
        }

        // 计算表格宽度
        var tbWidth = options.getTbWidth();
        $tbBox.css('min-width', tbWidth.minWidth);
        $headTb.parent().css('min-width', tbWidth.minWidth);
        if (tbWidth.setWidth) {
            $tbBox.css('width', tbWidth.width);
            $headTb.parent().css('width', tbWidth.width);
        }

        // 渲染表结构及表头
        var colgroupHtmlStr = options.getColgroup();
        var headHtmlStr = colgroupHtmlStr + '<thead>' + options.getThead() + '</thead>';
        if (options.height) {  // 固定表头
            $table.html(colgroupHtmlStr + '<tbody></tbody>');
            $headTb.html(headHtmlStr);
            $table.css('margin-top', '-1px');
            if (options.height.indexOf('full-') == 0) {  // 差值高度
                var h = parseFloat(options.height.substring(5));
                var cssStr = '<style>.ew-tree-table > .ew-tree-table-group > .ew-tree-table-box {';
                cssStr += '      height: ' + (getPageHeight() - h) + 'px;';
                cssStr += '      height: -moz-calc(100vh - ' + h + 'px);';
                cssStr += '      height: -webkit-calc(100vh - ' + h + 'px);';
                cssStr += '      height: calc(100vh - ' + h + 'px);';
                cssStr += '   }</style>';
                $tbBox.after(cssStr);
                $tbBox.attr('ew-tree-full', h);
            } else {  // 固定高度
                $tbBox.css('height', options.height);
            }
        } else {
            $table.html(headHtmlStr + '<tbody></tbody>');
        }
        form.render('checkbox', tbFilter);  // 渲染表头的表单元素

        // 渲染数据
        if (options.reqData) {  // 异步加载
            this.renderBodyAsync();
        } else {  // 一次性渲染
            if (options.data && options.data.length > 0) {
                // 处理数据
                if (options.tree.isPidData) {  // pid形式数据
                    treeTb.pidToChildren(options.data, options.tree.idName, options.tree.pidName, options.tree.childName);
                } else {  // children形式数据
                    addPidField(options.data, options.tree);
                }
                $table.children('tbody').html(this.renderBody(options.data));
                $tbLoading.hide();
                this.renderNumberCol();  // 渲染序号列
                form.render(null, tbFilter);  // 渲染表单元素
                this.checkChooseAllCB();  // 联动全选框
                updateFixedTbHead($view);
            } else {
                $tbLoading.hide();
                $tbEmpty.show();
            }
        }
    };

    /** 绑定各项事件 */
    TreeTable.prototype.bindEvents = function () {
        var that = this;
        var options = this.options;
        var components = this.getComponents();
        var $view = components.$view;
        var $table = components.$table;
        var $tbEmpty = components.$tbEmpty;
        var tbFilter = components.tbFilter;
        var checkboxFilter = components.checkboxFilter;
        var radioFilter = components.radioFilter;
        var cbAllFilter = components.cbAllFilter;
        var $tbody = $table.children('tbody');

        /** 行事件公共返回对象 */
        var commonMember = function (ext) {
            var $tr = $(this);
            if (!$tr.is('tr')) {
                $tr = $tr.parentsUntil('tr[data-id]').parent();
            }
            var id = $tr.data('id');
            var data = getDataById(options.data, id, options.tree);
            var obj = {
                tr: $tr,  // 当前行
                data: data, //当前行数据
                del: function () { // 删除行
                    var indent = parseInt(this.tr.data('indent'));
                    this.tr.nextAll('tr').each(function () {
                        if (parseInt($(this).data('indent')) <= indent) {
                            return false;
                        }
                        $(this).remove();
                    });
                    var $parentTr = this.tr.prevAll('tr');
                    this.tr.remove();
                    delDataById(options.data, id, options.tree);
                    if (!options.data || options.data.length <= 0) {
                        $tbEmpty.show();
                    }
                    that.renderNumberCol();  // 渲染序号列
                    // 联动父级
                    $parentTr.each(function () {
                        var tInd = parseInt($(this).data('indent'));
                        if (tInd < indent) {
                            that.checkParentCB($(this));
                            indent = tInd;
                        }
                    });
                    that.checkChooseAllCB();  // 联动全选框
                },
                update: function (fields) {  // 修改行
                    data = $.extend(data, fields);
                    var indent = parseInt(this.tr.data('indent'));
                    that.renderBodyTr(data, indent, undefined, this.tr);
                    form.render(null, tbFilter);  // 渲染表单元素
                    that.checkIndeterminateCB();  // 恢复半选框状态
                    that.checkChooseAllCB();  // 联动全选框
                }
            };
            return $.extend(obj, ext);
        };

        // 绑定折叠展开事件
        $tbody.off('click.fold').on('click.fold', '.ew-tree-pack', function (e) {
            layui.stope(e);
            var $tr = $(this).parent().parent();
            if ($tr.hasClass('ew-tree-table-loading')) {  // 已是加载中
                return;
            }
            var haveChild = $tr.data('have-child');
            if (haveChild != true && haveChild != 'true') {  // 子节点
                return;
            }
            var id = $tr.data('id');
            var isOpen = $tr.hasClass('ew-tree-table-open');
            var data = getDataById(options.data, id, options.tree);
            if (!isOpen && (!data[options.tree.childName] || data[options.tree.childName].length <= 0)) {
                that.renderBodyAsync(data, $tr);
            } else {
                toggleRow($tr);
            }
        });

        // 绑定lay-event事件
        $tbody.off('click.tool').on('click.tool', '*[lay-event]', function (e) {
            layui.stope(e);
            var $this = $(this);
            layui.event.call(this, MOD_NAME, 'tool(' + tbFilter + ')', commonMember.call(this, {
                event: $this.attr('lay-event')
            }));
        });

        // 绑定单选框事件
        form.on('radio(' + radioFilter + ')', function (data) {
            var d = getDataById(options.data, data.value, options.tree);
            that.removeAllChecked();
            d.LAY_CHECKED = true;  // 同时更新数据
            layui.event.call(this, MOD_NAME, 'checkbox(' + tbFilter + ')', {checked: true, data: d, type: 'one'});
        });

        // 绑定复选框事件
        form.on('checkbox(' + checkboxFilter + ')', function (data) {
            var checked = data.elem.checked;
            var $cb = $(data.elem);
            var $layCb = $cb.next('.layui-form-checkbox');
            // 如果是半选状态，点击全选
            if (!checked && $layCb.hasClass('ew-form-indeterminate')) {
                checked = true;
                $cb.prop('checked', checked);
                $cb.data('indeterminate', 'false');
                $layCb.addClass('layui-form-checked');
                $layCb.removeClass('ew-form-indeterminate');
            }
            var d = getDataById(options.data, data.value, options.tree);
            d.LAY_CHECKED = checked;  // 同时更新数据
            // 联动操作
            var $tr = $cb.parentsUntil('tr').parent();
            if (d[options.tree.childName] && d[options.tree.childName].length > 0) {
                that.checkSubCB($tr, checked);  // 联动子级
            }
            var indent = parseInt($tr.data('indent'));
            $tr.prevAll('tr').each(function () {
                var tInd = parseInt($(this).data('indent'));
                if (tInd < indent) {
                    that.checkParentCB($(this));  // 联动父级
                    indent = tInd;
                }
            });
            that.checkChooseAllCB();  // 联动全选框
            // 回调事件
            layui.event.call(this, MOD_NAME, 'checkbox(' + tbFilter + ')', {
                checked: checked,
                data: d,
                type: 'one'
            });
        });

        // 绑定全选复选框事件
        form.on('checkbox(' + cbAllFilter + ')', function (data) {
            var checked = data.elem.checked;
            var $cb = $(data.elem);
            var $layCb = $cb.next('.layui-form-checkbox');
            if (!options.data || options.data.length <= 0) {  // 如果数据为空
                $cb.prop('checked', false);
                $cb.data('indeterminate', 'false');
                $layCb.removeClass('layui-form-checked ew-form-indeterminate');
                return;
            }
            // 如果是半选状态，点击全选
            if (!checked && $layCb.hasClass('ew-form-indeterminate')) {
                checked = true;
                $cb.prop('checked', checked);
                $cb.data('indeterminate', 'false');
                $layCb.addClass('layui-form-checked');
                $layCb.removeClass('ew-form-indeterminate');
            }
            layui.event.call(this, MOD_NAME, 'checkbox(' + tbFilter + ')', {
                checked: checked,
                data: undefined,
                type: 'all'
            });
            that.checkSubCB($table.children('tbody'), checked);  // 联动操作
        });

        // 绑定行单击事件
        $tbody.off('click.row').on('click.row', 'tr', function () {
            layui.event.call(this, MOD_NAME, 'row(' + tbFilter + ')', commonMember.call(this, {}));
        });

        // 绑定行双击事件
        $tbody.off('dblclick.rowDouble').on('dblclick.rowDouble', 'tr', function () {
            layui.event.call(this, MOD_NAME, 'rowDouble(' + tbFilter + ')', commonMember.call(this, {}));
        });

        // 绑定单元格点击事件
        $tbody.off('click.cell').on('click.cell', 'td', function (e) {
            var $td = $(this);
            var type = $td.data('type');
            // 判断是否是复选框、单选框列
            if (type == 'checkbox' || type == 'radio') {
                layui.stope(e);
                return;
            }
            var edit = $td.data('edit');
            var field = $td.data('field');
            if (edit) {  // 开启了单元格编辑
                layui.stope(e);
                if ($tbody.find('.ew-tree-table-edit').length > 0) {
                    return;
                }
                var index = $td.data('index');
                var indentSize = $td.children('.ew-tree-table-indent').length;
                var id = $td.parent().data('id');
                var d = getDataById(options.data, id, options.tree);
                if ('text' == edit || 'number' == edit) {  // 文本框
                    var $input = $('<input type="' + edit + '" class="layui-input ew-tree-table-edit"/>');
                    $input[0].value = d[field];
                    $td.append($input);
                    $input.focus();
                    $input.blur(function () {
                        var value = $(this).val();
                        if (value == d[field]) {
                            $(this).remove();
                            return;
                        }
                        var rs = layui.event.call(this, MOD_NAME, 'edit(' + tbFilter + ')', commonMember.call(this, {
                            value: value,
                            field: field
                        }));
                        if (rs == false) {
                            $(this).addClass('layui-form-danger');
                            $(this).focus();
                        } else {
                            d[field] = value;  // 同步更新数据
                            that.renderBodyTd(d, indentSize, index, $td);  // 更新单元格
                        }
                    });
                } else {
                    console.error('不支持的单元格编辑类型:' + edit);
                }
            } else {  // 回调单元格点击事件
                var rs = layui.event.call(this, MOD_NAME, 'cell(' + tbFilter + ')', commonMember.call(this, {
                    td: $td,
                    field: field
                }));
                if (rs == false) {
                    layui.stope(e);
                }
            }
        });

        // 绑定单元格双击事件
        $tbody.off('dblclick.cellDouble').on('dblclick.cellDouble', 'td', function (e) {
            var $td = $(this);
            var type = $td.data('type');
            // 判断是否是复选框、单选框列
            if (type == 'checkbox' || type == 'radio') {
                layui.stope(e);
                return;
            }
            var edit = $td.data('edit');
            var field = $td.data('field');
            if (edit) {  // 开启了单元格编辑
                layui.stope(e);
            } else {  // 回调单元格双击事件
                var rs = layui.event.call(this, MOD_NAME, 'cellDouble(' + tbFilter + ')', commonMember.call(this, {
                    td: $td,
                    field: field
                }));
                if (rs == false) {
                    layui.stope(e);
                }
            }
        });

        // 列宽拖拽调整
        /*$view.off('mousedown.resize').on('mousedown.resize', '.ew-tb-resize', function (e) {
            layui.stope(e);
            var index = $(this).parent().data('index');
            $(this).data('move', 'true');
            $(this).data('x', e.clientX);
            var w = $(this).parent().parent().parent().parent().children('colgroup').children('col').eq(index).attr('width');
            $(this).data('width', w);
        });
        $view.off('mousemove.resize').on('mousemove.resize', '.ew-tb-resize', function (e) {
            layui.stope(e);
            var move = $(this).data('move');
            if ('true' == move) {
                var x = $(this).data('x');
                var w = $(this).data('width');
                var index = $(this).parent().data('index');
                var nw = parseFloat(w) + e.clientX - parseFloat(x);
                $(this).parent().parent().parent().parent().children('colgroup').children('col').eq(index).attr('width', nw);
            }
        });
        $view.off('mouseup.resize').on('mouseup.resize', '.ew-tb-resize', function (e) {
            layui.stope(e);
            $(this).data('move', 'false');
        });
        $view.off('mouseleave.resize').on('mouseleave.resize', '.ew-tb-resize', function (e) {
            layui.stope(e);
            $(this).data('move', 'false');
        });*/

    };

    /** 获取各个组件 */
    TreeTable.prototype.getComponents = function () {
        var $view = $(this.options.elem).next();   // 容器
        var $group = $view.children('.ew-tree-table-group');  // 表格容器
        var $tbBox = $group.children('.ew-tree-table-box');  // 表格主体部分容器
        var $table = $tbBox.children('.layui-table');  // 主体表格
        var $headTb = $group.children('.ew-tree-table-head').children('.layui-table');  // 表头表格
        var $tbEmpty = $tbBox.children('.ew-tree-table-empty');  // 空视图
        var $tbLoading = $tbBox.children('.ew-tree-table-loading');  // 加载视图
        var tbFilter = $view.attr('lay-filter');  // 容器filter
        var checkboxFilter = 'ew_tb_checkbox_' + tbFilter;  // 复选框filter
        var radioFilter = 'ew_tb_radio_' + tbFilter;  // 单选框filter
        var cbAllFilter = 'ew_tb_choose_all_' + tbFilter;  // 全选按钮filter
        return {
            $view: $view,
            $group: $group,
            $tbBox: $tbBox,
            $table: $table,
            $headTb: $headTb,
            $tbEmpty: $tbEmpty,
            $tbLoading: $tbLoading,
            tbFilter: tbFilter,
            checkboxFilter: checkboxFilter,
            radioFilter: radioFilter,
            cbAllFilter: cbAllFilter
        };
    };

    /**
     * 递归渲染表格主体部分
     * @param data 数据列表
     * @param indentSize 缩进大小
     * @param isHide 是否默认隐藏
     * @returns {string}
     */
    TreeTable.prototype.renderBody = function (data, indentSize, isHide) {
        var options = this.options;
        var treeOption = options.tree;
        indentSize || (indentSize = 0);
        var htmlStr = '';
        for (var i = 0; i < data.length; i++) {
            var d = data[i];
            htmlStr += this.renderBodyTr(d, indentSize, isHide);
            // 递归渲染子集
            var children = d[treeOption.childName];
            if (children && children.length > 0) {
                htmlStr += this.renderBody(children, indentSize + 1, !d[treeOption.openName]);
            }
        }
        return htmlStr;
    };

    /**
     * 渲染一行数据
     * @param d 行数据
     * @param option 配置
     * @param indentSize 缩进大小
     * @param isHide 是否隐藏
     * @param $tr
     * @returns {string}
     */
    TreeTable.prototype.renderBodyTr = function (d, indentSize, isHide, $tr) {
        var options = this.options;
        var cols = options.cols;
        var treeOption = options.tree;
        indentSize || (indentSize = 0);
        var htmlStr = '';
        var haveChild = getHaveChild(d, treeOption);
        if ($tr) {
            $tr.data('pid', d[treeOption.pidName] || '');
            $tr.data('have-child', haveChild);
            $tr.data('indent', indentSize);
            $tr.removeClass('ew-tree-table-loading');
        } else {
            var classNames = '';
            if (haveChild && d[treeOption.openName]) {
                classNames += 'ew-tree-table-open';
            }
            if (isHide) {
                classNames += 'ew-tree-tb-hide';
            }
            htmlStr += '<tr class="' + classNames + '" data-id="' + d[treeOption.idName] + '"';
            htmlStr += ' data-pid="' + (d[treeOption.pidName] || '') + '" data-have-child="' + haveChild + '"';
            htmlStr += ' data-indent="' + indentSize + '">';
        }
        for (var j = 0; j < cols.length; j++) {
            var $td;
            if ($tr) {
                $td = $tr.children('td').eq(j);
            }
            htmlStr += this.renderBodyTd(d, indentSize, j, $td);
        }
        htmlStr += '</tr>';
        return htmlStr;
    };

    /**
     * 渲染每一个单元格数据
     * @param d 行数据
     * @param indentSize 缩进大小
     * @param index 第几列
     * @param $td
     * @returns {string}
     */
    TreeTable.prototype.renderBodyTd = function (d, indentSize, index, $td) {
        var options = this.options;
        var col = options.cols[index];
        var treeOption = options.tree;
        var components = this.getComponents();
        var checkboxFilter = components.checkboxFilter;
        var radioFilter = components.radioFilter;
        indentSize || (indentSize = 0);
        // 内容填充
        var fieldStr = '';
        if (col.type == 'numbers') {  // 序号列
            fieldStr += '<span class="ew-tree-table-numbers"></span>';
        } else if (col.type == 'checkbox') {  // 复选框列
            var attrStr = 'name="' + checkboxFilter + '" lay-filter="' + checkboxFilter + '" value="' + d[treeOption.idName] + '"';
            attrStr += d.LAY_CHECKED ? ' checked="checked"' : '';
            fieldStr += '<input type="checkbox" lay-skin="primary" ' + attrStr + ' class="ew-tree-table-checkbox" />';
        } else if (col.type == 'radio') {  // 单选框列
            var attrStr = 'name="' + radioFilter + '" lay-filter="' + radioFilter + '" value="' + d[treeOption.idName] + '"';
            attrStr += d.LAY_CHECKED ? ' checked="checked"' : '';
            fieldStr += '<input type="radio" ' + attrStr + ' class="ew-tree-table-radio" />';
        } else if (col.templet) {  // 自定义模板
            if (typeof col.templet == 'function') {
                fieldStr += col.templet(d);
            } else if (typeof col.templet == 'string') {
                laytpl($(col.templet).html()).render(d, function (html) {
                    fieldStr += html;
                });
            }
        } else if (col.toolbar) {  // 工具列
            laytpl($(col.toolbar).html()).render(d, function (html) {
                fieldStr += html;
            });
        } else if (col.field && d[col.field] != undefined && d[col.field] != null) {  // 普通字段
            fieldStr += d[col.field];
        }
        var tdStr = '';
        // 图标列处理
        if (index == treeOption.iconIndex) {
            // 缩进
            for (var k = 0; k < indentSize; k++) {
                tdStr += '<span class="ew-tree-table-indent"></span>';
            }
            tdStr += '<span class="ew-tree-pack">';
            // 加箭头
            var haveChild = getHaveChild(d, treeOption);
            tdStr += ('<i class="layui-icon ew-tree-table-arrow ' + (haveChild ? '' : 'ew-tree-table-arrow-hide') + ' ' + (options.tree.arrowType || '') + '"></i>');
            // 加图标
            tdStr += treeOption.getIcon(d);
            if (options.tree.onlyIconControl) {
                tdStr += '</span>';
                tdStr += ('<span>' + fieldStr + '</span>');
            } else {
                tdStr += ('<span>' + fieldStr + '</span>');
                tdStr += '</span>';
            }
        } else {
            tdStr += fieldStr;
        }
        if ($td && col.type != 'numbers') {
            $td.html(tdStr);
        }
        var htmlStr = '<td data-index="' + index + '" ';
        col.field && (htmlStr += (' data-field="' + col.field + '"'));
        col.edit && (htmlStr += (' data-edit="' + col.edit + '"'));
        col.type && (htmlStr += (' data-type="' + col.type + '"'));
        col.align && (htmlStr += (' align="' + col.align + '"'));  // 对齐方式
        col.style && (htmlStr += (' style="' + col.style + '"'));  // 单元格样式
        htmlStr += '>';
        htmlStr += (tdStr + '</td>');
        return htmlStr;
    };

    /**
     * 异步加载渲染
     * @param data 父级数据
     * @param $tr 父级dom
     */
    TreeTable.prototype.renderBodyAsync = function (d, $tr) {
        var that = this;
        var options = this.options;
        var components = this.getComponents();
        var $tbEmpty = components.$tbEmpty;
        var $tbLoading = components.$tbLoading;
        // 显示loading
        if ($tr) {
            $tr.addClass('ew-tree-table-loading');
            $tr.children('td').children('.ew-tree-pack').children('.ew-tree-table-arrow').addClass('layui-anim layui-anim-rotate layui-anim-loop');
        } else {
            if (options.data && options.data.length > 0) {
                $tbLoading.addClass('ew-loading-float');
            }
            $tbLoading.show();
        }
        // 请求数据
        options.reqData(d, function (res) {
            if (options.tree.isPidData) {
                res = treeTb.pidToChildren(res, options.tree.idName, options.tree.pidName, options.tree.childName);
            }
            that.renderBodyData(res, d, $tr);  // 渲染内容
            // 移除loading
            if ($tr) {
                $tr.removeClass('ew-tree-table-loading');
                $tr.children('td').children('.ew-tree-pack').children('.ew-tree-table-arrow').removeClass('layui-anim layui-anim-rotate layui-anim-loop');
            } else {
                $tbLoading.hide();
                $tbLoading.removeClass('ew-loading-float');
            }
            // 是否为空
            if ((!res || res.length == 0) && !$tr) {
                $tbEmpty.show();
            }
        });
    };

    /**
     * 根据数据渲染body
     * @param data  数据集合
     * @param option 配置项
     * @param d 父级数据
     * @param $tr 父级dom
     */
    TreeTable.prototype.renderBodyData = function (data, d, $tr) {
        var that = this;
        var options = this.options;
        var components = this.getComponents();
        var $view = components.$view;
        var $table = components.$table;
        var tbFilter = components.tbFilter;
        addPidField(data, options.tree, d);  // 补充pid字段
        // 更新到数据
        if (d == undefined) {
            options.data = data;
        } else {
            d[options.tree.childName] = data;
        }
        var indent;
        if ($tr) {
            indent = parseInt($tr.data('indent')) + 1;
        }
        var htmlStr = this.renderBody(data, indent);
        if ($tr) {
            // 移除旧dom
            $tr.nextAll('tr').each(function () {
                if (parseInt($(this).data('indent')) <= (indent - 1)) {
                    return false;
                }
                $(this).remove();
            });
            // 渲染新dom
            $tr.after(htmlStr);
            $tr.addClass('ew-tree-table-open');
        } else {
            $table.children('tbody').html(htmlStr);
        }
        form.render(null, tbFilter);  // 渲染表单元素
        this.renderNumberCol();  // 渲染序号列
        this.checkIndeterminateCB();  // 恢复复选框半选状态
        if ($tr) {
            // 更新父级复选框状态
            this.checkParentCB($tr);
            $tr.prevAll('tr').each(function () {
                var tInd = parseInt($(this).data('indent'));
                if (tInd < (indent - 1)) {
                    that.checkParentCB($(this));
                    indent = tInd + 1;
                }
            });
        }
        this.checkChooseAllCB();  // 联动全选框
        updateFixedTbHead($view);
    };

    /**
     * 联动子级复选框状态
     * @param $tr 当前tr的dom
     * @param checked
     */
    TreeTable.prototype.checkSubCB = function ($tr, checked) {
        var that = this;
        var components = this.getComponents();
        var cbFilter = components.checkboxFilter;
        var indent = -1, $trList;
        if ($tr.is('tbody')) {
            $trList = $tr.children('tr');
        } else {
            indent = parseInt($tr.data('indent'));
            $trList = $tr.nextAll('tr')
        }
        $trList.each(function () {
            if (parseInt($(this).data('indent')) <= indent) {
                return false;
            }
            var $cb = $(this).children('td').children('input[name="' + cbFilter + '"]');
            $cb.prop('checked', checked);
            if (checked) {
                $cb.data('indeterminate', 'false');
                $cb.next('.layui-form-checkbox').addClass('layui-form-checked');
                $cb.next('.layui-form-checkbox').removeClass('ew-form-indeterminate');
            } else {
                $cb.data('indeterminate', 'false');
                $cb.next('.layui-form-checkbox').removeClass('layui-form-checked ew-form-indeterminate');
            }
            that.update($(this).data('id'), {LAY_CHECKED: checked});  // 同步更新数据
        });
    };

    /**
     * 联动父级复选框状态
     * @param $tr 父级的dom
     */
    TreeTable.prototype.checkParentCB = function ($tr) {
        var that = this;
        var components = this.getComponents();
        var cbFilter = components.checkboxFilter;
        var indent = parseInt($tr.data('indent'));
        var ckNum = 0, unCkNum = 0;
        $tr.nextAll('tr').each(function () {
            if (parseInt($(this).data('indent')) <= indent) {
                return false;
            }
            var $cb = $(this).children('td').children('input[name="' + cbFilter + '"]');
            if ($cb.prop('checked')) {
                ckNum++;
            } else {
                unCkNum++;
            }
        });
        var $cb = $tr.children('td').children('input[name="' + cbFilter + '"]');
        if (ckNum > 0 && unCkNum == 0) {  // 全选
            $cb.prop('checked', true);
            $cb.data('indeterminate', 'false');
            $cb.next('.layui-form-checkbox').addClass('layui-form-checked');
            $cb.next('.layui-form-checkbox').removeClass('ew-form-indeterminate');
            that.update($tr.data('id'), {LAY_CHECKED: true});  // 同步更新数据
        } else if (ckNum == 0 && unCkNum > 0) {  // 全不选
            $cb.prop('checked', false);
            $cb.data('indeterminate', 'false');
            $cb.next('.layui-form-checkbox').removeClass('layui-form-checked ew-form-indeterminate');
            that.update($tr.data('id'), {LAY_CHECKED: false});  // 同步更新数据
        } else if (ckNum > 0 && unCkNum > 0) {  // 半选
            $cb.prop('checked', true);
            $cb.data('indeterminate', 'true');
            $cb.next('.layui-form-checkbox').addClass('layui-form-checked ew-form-indeterminate');
            that.update($tr.data('id'), {LAY_CHECKED: true});  // 同步更新数据
        }
    };

    /** 联动全选复选框 */
    TreeTable.prototype.checkChooseAllCB = function () {
        var components = this.getComponents();
        var cbAllFilter = components.cbAllFilter;
        var cbFilter = components.checkboxFilter;
        var $tbody = components.$table.children('tbody');
        var ckNum = 0, unCkNum = 0;
        $tbody.children('tr').each(function () {
            var $cb = $(this).children('td').children('input[name="' + cbFilter + '"]');
            if ($cb.prop('checked')) {
                ckNum++;
            } else {
                unCkNum++;
            }
        });
        var $cb = $('input[lay-filter="' + cbAllFilter + '"]');
        if (ckNum > 0 && unCkNum == 0) {  // 全选
            $cb.prop('checked', true);
            $cb.data('indeterminate', 'false');
            $cb.next('.layui-form-checkbox').addClass('layui-form-checked');
            $cb.next('.layui-form-checkbox').removeClass('ew-form-indeterminate');
        } else if ((ckNum == 0 && unCkNum > 0) || (ckNum == 0 && unCkNum == 0)) {  // 全不选
            $cb.prop('checked', false);
            $cb.data('indeterminate', 'false');
            $cb.next('.layui-form-checkbox').removeClass('layui-form-checked ew-form-indeterminate');
        } else if (ckNum > 0 && unCkNum > 0) {  // 半选
            $cb.prop('checked', true);
            $cb.data('indeterminate', 'true');
            $cb.next('.layui-form-checkbox').addClass('layui-form-checked ew-form-indeterminate');
        }
    };

    /** 填充序号列 */
    TreeTable.prototype.renderNumberCol = function () {
        var components = this.getComponents();
        var $tbody = components.$table.children('tbody');
        $tbody.children('tr').each(function (index) {
            $(this).children('td').children('.ew-tree-table-numbers').text(index + 1);
        });
    };

    /* 解决form.render之后半选框被重置的问题 */
    TreeTable.prototype.checkIndeterminateCB = function () {
        var components = this.getComponents();
        var cbFilter = components.checkboxFilter;
        $('input[lay-filter="' + cbFilter + '"]').each(function () {
            var $cb = $(this);
            if ($cb.data('indeterminate') == 'true' && $cb.prop('checked')) {
                $cb.next('.layui-form-checkbox').addClass('ew-form-indeterminate');
            }
        });
    };

    /**
     * 搜索数据
     * @param ids 关键字或数据id集合
     */
    TreeTable.prototype.filterData = function (ids) {
        var components = this.getComponents();
        var $trList = components.$table.children('tbody').children('tr');
        if (typeof ids == 'string') {  // 关键字
            var keyword = ids;
            ids = [];
            $trList.each(function () {
                var id = $(this).data('id');
                $(this).children('td').each(function () {
                    if ($(this).text().indexOf(keyword) != -1) {
                        ids.push(id);
                        return false;
                    }
                });
            });
        }
        $trList.addClass('ew-tree-table-filter-hide');
        for (var i = 0; i < ids.length; i++) {
            var $tr = $trList.filter('[data-id="' + ids[i] + '"]');
            $tr.removeClass('ew-tree-table-filter-hide');
            // 联动父级
            var indent = parseInt($tr.data('indent'));
            $tr.prevAll('tr').each(function () {
                var tInd = parseInt($(this).data('indent'));
                if (tInd < indent) {
                    $(this).removeClass('ew-tree-table-filter-hide');  // 联动父级
                    if (!$(this).hasClass('ew-tree-table-open')) {
                        toggleRow($(this));
                    }
                    indent = tInd;
                }
            });
        }
    };

    /** 重置搜索 */
    TreeTable.prototype.clearFilter = function () {
        var components = this.getComponents();
        var $trList = components.$table.children('tbody').children('tr');
        $trList.removeClass('ew-tree-table-filter-hide');
    };

    /** 展开指定行 */
    TreeTable.prototype.expand = function (id, cascade) {
        var components = this.getComponents();
        var $tr = components.$table.children('tbody').children('tr[data-id="' + id + '"]');
        if (!$tr.hasClass('ew-tree-table-open')) {
            $tr.children('td').children('.ew-tree-pack').trigger('click');
        }
        if (cascade == false) {
            return;
        }
        // 联动父级
        var indent = parseInt($tr.data('indent'));
        $tr.prevAll('tr').each(function () {
            var tInd = parseInt($(this).data('indent'));
            if (tInd < indent) {
                if (!$(this).hasClass('ew-tree-table-open')) {
                    $(this).children('td').children('.ew-tree-pack').trigger('click');
                }
                indent = tInd;
            }
        });
    };

    /** 折叠指定行 */
    TreeTable.prototype.fold = function (id, cascade) {
        var components = this.getComponents();
        var $tr = components.$table.children('tbody').children('tr[data-id="' + id + '"]');
        if ($tr.hasClass('ew-tree-table-open')) {
            $tr.children('td').children('.ew-tree-pack').trigger('click');
        }
        if (cascade == false) {
            return;
        }
        // 联动父级
        var indent = parseInt($tr.data('indent'));
        $tr.prevAll('tr').each(function () {
            var tInd = parseInt($(this).data('indent'));
            if (tInd < indent) {
                if ($(this).hasClass('ew-tree-table-open')) {
                    $(this).children('td').children('.ew-tree-pack').trigger('click');
                }
                indent = tInd;
            }
        });
    };

    /** 全部展开 */
    TreeTable.prototype.expandAll = function () {
        var that = this;
        var components = this.getComponents();
        var $trList = components.$table.children('tbody').children('tr');
        $trList.each(function () {
            that.expand($(this).data('id'), false);
        });
    };

    /** 全部折叠 */
    TreeTable.prototype.foldAll = function () {
        var that = this;
        var components = this.getComponents();
        var $trList = components.$table.children('tbody').children('tr');
        $trList.each(function () {
            that.fold($(this).data('id'), false);
        });
    };

    /** 获取当前数据 */
    TreeTable.prototype.getData = function () {
        return this.options.data;
    };

    /** 重载表格 */
    TreeTable.prototype.reload = function (opt) {
        treeTb.render($.extend(this.options, opt));
    };

    /** 根据id更新数据 */
    TreeTable.prototype.update = function (id, fields) {
        var data = getDataById(this.getData(), id, this.options.tree);
        $.extend(data, fields);
    };

    /** 根据id删除数据 */
    TreeTable.prototype.del = function (id) {
        delDataById(this.getData(), id, this.options.tree);
    };

    /** 获取当前选中行 */
    TreeTable.prototype.checkStatus = function () {
        var that = this;
        var components = this.getComponents();
        var $table = components.$table;
        var checkboxFilter = components.checkboxFilter;
        var radioFilter = components.radioFilter;
        var list = [];
        // 获取单选框选中数据
        var $radio = $table.find('input[name="' + radioFilter + '"]');
        if ($radio.length > 0) {
            var id = $radio.filter(':checked').val();
            var d = getDataById(this.getData(), id, this.options.tree);
            if (d) {
                list.push(d);
            }
        } else {  // 获取复选框数据
            $table.find('input[name="' + checkboxFilter + '"]:checked').each(function () {
                var id = $(this).val();
                var d = getDataById(that.getData(), id, that.options.tree);
                if (d) {
                    list.push(d);
                }
            });
        }
        return list;
    };

    /** 设置复/单选框选中 */
    TreeTable.prototype.setChecked = function (ids) {
        var components = this.getComponents();
        var $table = components.$table;
        var checkboxFilter = components.checkboxFilter;
        var radioFilter = components.radioFilter;
        var $radio = $table.find('input[name="' + radioFilter + '"]');
        if ($radio.length > 0) {  // 开启了单选框
            $radio.each(function () {
                if (ids[ids.length - 1] == $(this).val()) {
                    $(this).next('.layui-form-radio').trigger('click');
                    return false;
                }
            });
        } else {  // 开启了复选框
            $table.find('input[name="' + checkboxFilter + '"]').each(function () {
                var $cb = $(this);
                var value = $cb.val();
                var $layCb = $cb.next('.layui-form-checkbox');
                for (var i = 0; i < ids.length; i++) {
                    if (value == ids[i]) {
                        var checked = $cb.prop('checked');
                        var indeterminate = $layCb.hasClass('ew-form-indeterminate');
                        if (!checked || indeterminate) {
                            $layCb.trigger('click');
                        }
                    }
                }
            });
        }
    };

    /** 移除全部选中 */
    TreeTable.prototype.removeAllChecked = function () {
        var components = this.getComponents();
        var $table = components.$table;
        var checkboxFilter = components.checkboxFilter;
        this.checkSubCB($table.children('tbody'), false);
    };

    /**
     * 刷新指定父级下的节点
     * @param id 父级id,空则全部刷新
     * @param data 非异步模式替换的数据
     */
    TreeTable.prototype.refresh = function (id, data) {
        var components = this.getComponents().$table;
        var $table = components.$table;
        var d, $tr;
        if (id != undefined) {
            d = getDataById(this.getData(), id, this.options.tree);
            $tr = $table.children('tbody').children('tr[data-id="' + id + '"]');
        }
        if (data) {  // 数据模式
            components.$tbLoading.addClass('ew-loading-float');
            components.$tbLoading.show();
            this.renderBodyData(data, d, $tr);
            components.$tbLoading.hide();
            components.$tbLoading.removeClass('ew-loading-float');
        } else {  // 异步模式
            this.renderBodyAsync(d, $tr);
        }
    };

    /** 生成表头 */
    function getThead(options) {
        var htmlStr = '<tr>';
        for (var i = 0; i < options.cols.length; i++) {
            var col = options.cols[i];
            htmlStr += '<td data-index="' + i + '" ';
            col.align && (htmlStr += ' align="' + col.align + '"');  // 对齐方式
            htmlStr += ' >';
            // 标题
            if (col.type == 'checkbox') {
                htmlStr += options.getAllChooseBox();
            } else {
                htmlStr += (col.title || '');
            }
            // 列宽拖拽
            if (!col.unresize && 'checkbox' != col.type && 'radio' != col.type && 'numbers' != col.type && 'space' != col.type) {
                htmlStr += '<span class="ew-tb-resize"></span>';
            }
            htmlStr += '</td>';
        }
        htmlStr += '</tr>';
        return htmlStr;
    }

    /** 生成colgroup */
    function getColgroup(options) {
        var htmlStr = '<colgroup>';
        for (var i = 0; i < options.cols.length; i++) {
            var col = options.cols[i];
            htmlStr += '<col ';
            // 设置宽度
            if (col.width) {
                htmlStr += 'width="' + col.width + '"'
            } else if (col.type == 'space') {  // 空列
                htmlStr += 'width="15"'
            } else if (col.type == 'numbers') {  // 序号列
                htmlStr += 'width="40"'
            } else if (col.type == 'checkbox' || col.type == 'radio') {  // 复/单选框列
                htmlStr += 'width="48"'
            }
            htmlStr += ' />';
        }
        htmlStr += '</colgroup>';
        return htmlStr;
    }

    /** 计算table宽度 */
    function getTbWidth(options) {
        var minWidth = 0, width = 0, setWidth = true;
        for (var i = 0; i < options.cols.length; i++) {
            var col = options.cols[i];
            if (col.type == 'space') {  // 空列
                minWidth += 15;
                width += 15;
            } else if (col.type == 'numbers') {  // 序号列
                minWidth += 40;
                width += 40;
            } else if (col.type == 'checkbox' || col.type == 'radio') {  // 复/单选框列
                minWidth += 48;
                width += 48;
            } else if (!col.width || /\d+%$/.test(col.width)) {  // 列未固定宽度
                setWidth = false;
                if (this.cellMinWidth != undefined) {
                    minWidth += options.cellMinWidth;
                    width += options.cellMinWidth;
                }
            } else {  // 列固定宽度
                minWidth += col.width;
                width += col.width;
            }
        }
        return {minWidth: minWidth, width: width, setWidth: setWidth};
    }

    /** 生成全选按钮 */
    function getAllChooseBox(options) {
        var tbFilter = $(options.elem).next().attr('lay-filter');
        var cbAllFilter = 'ew_tb_choose_all_' + tbFilter;
        return '<input type="checkbox" lay-filter="' + cbAllFilter + '" lay-skin="primary" class="ew-tree-table-checkbox"/>';
    }

    /** 获取列图标 */
    function getIcon(d, treeOption) {
        if (getHaveChild(d, treeOption)) {
            return '<i class="ew-tree-icon layui-icon layui-icon-layer"></i>';
        } else {
            return '<i class="ew-tree-icon layui-icon layui-icon-file"></i>';
        }
    }

    /** 折叠/展开行 */
    function toggleRow($tr) {
        var indent = parseInt($tr.data('indent'));
        var isOpen = $tr.hasClass('ew-tree-table-open');
        if (isOpen) {  // 折叠
            $tr.removeClass('ew-tree-table-open');
            $tr.nextAll('tr').each(function () {
                if (parseInt($(this).data('indent')) <= indent) {
                    return false;
                }
                $(this).addClass('ew-tree-tb-hide');
            });
        } else {  // 展开
            $tr.addClass('ew-tree-table-open');
            var hideInd;
            $tr.nextAll('tr').each(function () {
                var ind = parseInt($(this).data('indent'));
                if (ind <= indent) {
                    return false;
                }
                if (hideInd != undefined && ind > hideInd) {
                    return true;
                }
                $(this).removeClass('ew-tree-tb-hide');
                if (!$(this).hasClass('ew-tree-table-open')) {
                    hideInd = parseInt($(this).data('indent'));
                } else {
                    hideInd = undefined;
                }
            });
        }
        updateFixedTbHead($tr.parent().parent().parent().parent().parent());
    }

    /** 固定表头减去滚动条 */
    function updateFixedTbHead($view) {
        var $group = $view.children('.ew-tree-table-group');
        var $tbBox = $group.children('.ew-tree-table-box');
        var sWidth = $tbBox.width() - $tbBox.prop('clientWidth');
        if (sWidth > 0) {
            if (!(device.ie && device.ie < 9)) {
                sWidth = sWidth - 0.48;
            }
            $group.children('.ew-tree-table-head').css('padding-right', sWidth);
        } else {
            $group.children('.ew-tree-table-head').css('padding-right', 0);
        }
    }

    // 监听窗口大小改变
    $(window).resize(function () {
        $('.ew-tree-table').each(function () {
            updateFixedTbHead($(this));
            var $tbBox = $(this).children('.ew-tree-table-group').children('.ew-tree-table-box');
            var full = $tbBox.attr('ew-tree-full');
            if (full && device.ie && device.ie < 10) {
                $tbBox.css('height', getPageHeight() - full);
            }
        });
    });

    /** 判断是否还有子节点 */
    function getHaveChild(d, treeOption) {
        var haveChild = false;
        if (d[treeOption.haveChildName] != undefined) {
            haveChild = d[treeOption.haveChildName];
            haveChild = haveChild == true || haveChild == 'true';
        } else if (d[treeOption.childName]) {
            haveChild = d[treeOption.childName].length > 0;
        }
        return haveChild;
    }

    /** 补充pid字段 */
    function addPidField(data, treeOption, parent) {
        for (var i = 0; i < data.length; i++) {
            if (parent) {
                data[i][treeOption.pidName] = parent[treeOption.idName];
            }
            if (data[i][treeOption.childName] && data[i][treeOption.childName].length > 0) {
                addPidField(data[i][treeOption.childName], treeOption, data[i]);
            }
        }
    }

    /** 根据id获取数据 */
    function getDataById(data, id, treeOption) {
        for (var i = 0; i < data.length; i++) {
            if (data[i][treeOption.idName] == id) {
                return data[i];
            }
            if (data[i][treeOption.childName] && data[i][treeOption.childName].length > 0) {
                var d = getDataById(data[i][treeOption.childName], id, treeOption);
                if (d != undefined) {
                    return d;
                }
            }
        }
    }

    /** 根据id删除数据 */
    function delDataById(data, id, treeOption) {
        for (var i = 0; i < data.length; i++) {
            if (data[i][treeOption.idName] == id) {
                data.splice(i, 1);
                return true;
            }
            if (data[i][treeOption.childName] && data[i][treeOption.childName].length > 0) {
                var rs = delDataById(data[i][treeOption.childName], id, treeOption);
                if (rs) {
                    return true;
                }
            }
        }
    }

    /** 获取顶级的pId */
    function getPids(list, idName, pidName) {
        var pids = [];
        for (var i = 0; i < list.length; i++) {
            var hasPid = false;
            for (var j = 0; j < list.length; j++) {
                if (i != j && list[j][idName] == list[i][pidName]) {
                    hasPid = true;
                }
            }
            if (!hasPid) {
                pids.push(list[i][pidName]);
            }
        }
        return pids;
    }

    /** 判断pId是否相等 */
    function pidEquals(pId, pIds) {
        if (isClass(pIds) == 'Array') {
            for (var i = 0; i < pIds.length; i++) {
                if (pId == pIds[i]) {
                    return true;
                }
            }
        } else {
            return pId == pIds;
        }
        return false;
    }

    /** 获取变量类型 */
    function isClass(o) {
        if (o === null)
            return 'Null';
        if (o === undefined)
            return 'Undefined';
        return Object.prototype.toString.call(o).slice(8, -1);
    }

    /* 获取浏览器高度 */
    function getPageHeight() {
        return document.documentElement.clientHeight || document.body.clientHeight;
    }

    /** 对外提供的方法 */
    var treeTb = {
        /* 渲染 */
        render: function (options) {
            return new TreeTable(options);
        },
        /* 事件监听 */
        on: function (events, callback) {
            return layui.onevent.call(this, MOD_NAME, events, callback);
        },
        /* pid转children形式 */
        pidToChildren: function (data, idName, pidName, childName, pId) {
            childName || (childName = 'children');
            var newList = [];
            for (var i = 0; i < data.length; i++) {
                (pId == undefined) && (pId = getPids(data, idName, pidName));
                if (pidEquals(data[i][pidName], pId)) {
                    var children = this.pidToChildren(data, idName, pidName, childName, data[i][idName]);
                    (children.length > 0) && (data[i][childName] = children);
                    newList.push(data[i]);
                }
            }
            return newList;
        }
    };

    exports('treeTable', treeTb);
});
