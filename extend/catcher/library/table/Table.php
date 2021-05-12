<?php
namespace catcher\library\table;


class Table
{
    use Events;

    /**
     * 头信息
     *
     * @var array
     */
    protected $headers = [];

    /**
     * table 操作
     *
     * @var array
     */
    protected $actions = [];

    /**
     * 搜索参数
     *
     * @var array
     */
    protected $search = [];

    /**
     * 表格引用
     *
     * @var string
     */
    protected $ref;

    /**
     * @var array
     */
    protected $defaultQueryParams = [];

    /**
     * 表单事件
     *
     * @var array
     */
    protected $events = [];


    /**
     * 是否隐藏分页
     *
     * @var bool
     */
    protected $hidePagination = false;

    /**
     * tree table
     *
     * @var array
     */
    protected $tree = [];

    /**
     * @var string
     */
    protected $apiRoute;


    /**
     * @var bool
     */
    protected $loading = false;

    /**
     * @var array
     */
    protected $dialog;

    /**
     * @var array
     */
    protected $filterParams;

    /**
     * @var string
     */
    protected $importRoute;

    /**
     * @var string
     */
    protected $exportRoute;


    /**
     * @var bool
     */
    protected $forceUpdate = false;


    /**
     * @var array
     */
    protected $excel = [];

    /**
     * 斑马纹
     *
     * @var bool
     */
    protected $stripe = false;

    /**
     * 导出 excel 所使用 model
     *
     * @var string
     */
    protected $usedModel;

    /**
     * 固定表头
     *
     * @var int
     */
    protected $height;

    /**
     * 树状展开
     *
     * @var bool
     */
    protected $defaultExpandAll;

    /**
     * 默认
     *
     * @var bool
     */
    protected $border = true;

    /**
     * bind table
     *
     * @var bool
     */
    protected $bind = false;

    /**
     * @var string
     */
    protected $tips = null;


    /**
     * Table constructor.
     * @param string $ref
     */
    public function __construct(string $ref)
    {
        $this->ref = $ref;
    }

    /**
     * 设置头信息
     *
     * @time 2021年03月21日
     * @param array $header
     * @return $this
     */
    public function header(array $header): Table
    {
        foreach ($header as $h) {
            $this->headers[] = $h->attributes;
        }

        return $this;
    }

    /**
     * 设置 actions
     *
     * @time 2021年03月21日
     * @param array $actions
     * @return $this
     */
    public function withActions(array $actions): Table
    {
        foreach ($actions as $action) {
            $this->actions[] = $action->render();
        }

        return $this;
    }

    /**
     * 设置搜索参数
     *
     * @time 2021年03月21日
     * @param array $search
     * @return $this
     */
    public function withSearch(array $search): Table
    {
        $this->search = $search;

        return $this;
    }

    /**
     * 表单事件
     *
     * @time 2021年03月21日
     * @param array $events
     * @return $this
     */
    public function withEvents(array $events): Table
    {
        $this->events = $events;

        return $this;
    }

    /**
     * excel 信息
     *
     * @time 2021年04月21日
     * @param array $excel
     * @param string $usedModel
     * @return $this
     */
    public function withUsedModelAndExcel(string $usedModel, array $excel = []): Table
    {
        foreach ($excel as $e) {
            $this->excel[] = $e->render();
        }

        $this->usedModel = $usedModel;

        return $this;
    }

    /**
     * set
     *
     * @time 2021年03月29日
     * @param array $params
     * @return $this
     */
    public function withDefaultQueryParams(array $params): Table
    {
        $this->defaultQueryParams = $params;

        return $this;
    }

    /**
     * filter params
     *
     * @time 2021年03月30日
     * @param array $filterParams
     * @return $this
     */
    public function withFilterParams(array $filterParams): Table
    {
        $this->filterParams = $filterParams;

        return $this;
    }

    /**
     * 隐藏分页
     *
     * @time 2021年03月29日
     * @return $this
     */
    public function withHiddenPaginate(): Table
    {
        $this->hidePagination = true;

        return $this;
    }

    /**
     * 展开
     *
     * @time 2021年04月26日
     * @param bool $expand
     * @return $this
     */
    public function expandAll($expand = true): Table
    {
        $this->defaultExpandAll = $expand;

        return $this;
    }

    /**
     * 设置 api route
     *
     * @time 2021年03月29日
     * @param string $apiRoute
     * @return $this
     */
    public function withApiRoute(string $apiRoute): Table
    {
        $this->apiRoute = $apiRoute;

        return $this;
    }

    /**
     * loading
     *
     * @time 2021年03月29日
     * @return $this
     */
    public function withLoading(): Table
    {
        $this->loading = true;

        return $this;
    }


    /**
     * 设置弹出层的宽度
     *
     * @time 2021年03月29日
     * @param string $width
     * @return $this
     */
    public function withDialogWidth(string $width): Table
    {
        $this->dialog['width'] = $width;

        return $this;
    }

    /**
     * 导出路由
     *
     * @time 2021年04月02日
     * @param string $route
     * @return $this
     */
    public function withImportRoute(string $route): Table
    {
        $this->importRoute = $route;

        return $this;
    }

    /**
     * 开启斑马纹
     *
     * @time 2021年05月07日
     * @return $this
     */
    public function withStripe(): Table
    {
        $this->stripe = true;

        return $this;
    }

    /**
     * 固定表头
     *
     * @time 2021年05月07日
     * @param int $height
     * @return $this
     */
    public function withHeight(int $height): Table
    {
        $this->height = $height;

        return $this;
    }

    /**
     * 表格提示
     *
     * @time 2021年05月12日
     * @param string $content
     * @param string $type
     * @return $this
     */
    public function withTips(string $content, string $type = 'success'): Table
    {
        $this->tips = [
            'content' => $content,
            'type' => $type
        ];

        return $this;
    }

    /**
     * 导出路由
     *
     * @time 2021年04月02日
     * @param string $route
     * @return $this
     */
    public function withExportRoute(string $route): Table
    {
        $this->exportRoute = $route;

        return $this;
    }

    /**
     * table 使用 v-bind
     *
     * @time 2021年04月27日
     * @return $this
     */
    public function withBind(): Table
    {
        $this->bind = true;

        return $this;
    }

    /**
     * 变成 tree table
     *
     * @time 2021年03月29日
     * @param string $rowKey
     * @param array $props ['children' => '', 'hasChildren' => '']
     * @return $this
     */
    public function toTreeTable(string $rowKey = 'id', array $props = []): Table
    {
        $this->tree['row_key'] = $rowKey;

        $this->tree['props'] = count($props) ? $props : [
            'children' => 'children',
            'hasChildren' => 'hasChildren'
        ];

        return $this;
    }

    /**
     * 强制更新组件
     *
     * @time 2021年04月05日
     * @return $this
     */
    public function forceUpdate(): Table
    {
        $this->forceUpdate = true;

        return $this;
    }

    /**
     * 渲染
     *
     * @time 2021年03月21日
     * @return array
     */
    public function render(): array
    {

        $render = [];

        foreach (get_class_vars(self::class) as $property => $v) {
            if (!empty($this->{$property})) {
                $render[$property] = $this->{$property};
            }
        }

        return $render;
    }


    /**
     * 追加 headers
     *
     * @time 2021年03月28日
     * @param $header
     * @return $this
     */
    public function appendHeaders($header): Table
    {
        if ($header instanceof HeaderItem) {
            $this->headers[] = $header;
        }


        if (is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        }

        return $this;
    }

    /**
     * 追加
     *
     * @time 2021年03月29日
     * @param string $param
     * @return $this
     */
    public function appendDefaultQueryParams(string $param): Table
    {
        $this->defaultQueryParams[] = $param;

        return $this;
    }

    /**
     * 追加 events
     *
     * @time 2021年03月28日
     * @param array $events
     * @return $this
     */
    public function appendEvents(array $events): Table
    {
        $this->events = array_merge($this->events, $events);

        return $this;
    }

    /**
     * 追加 events
     *
     * @time 2021年03月28日
     * @param array $actions
     * @return $this
     */
    public function appendActions(array $actions): Table
    {
        $this->actions = array_merge($this->actions, $actions);

        return $this;
    }

    /**
     * 追加 header
     *
     * @time 2021年03月28日
     * @param array $header
     * @return $this
     */
    public function appendHeader(array $header): Table
    {
        $this->headers = array_merge($this->headers, $header);

        return $this;
    }

    /**
     * 新增 filter params
     *
     * @time 2021年03月30日
     * @param array $params
     * @return $this
     */
    public function appendFilterParams(array $params): Table
    {
        $this->filterParams = array_merge($this->filterParams, $params);

        return $this;
    }

    /**
     * 获取头部
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getHeader(): array
    {
        return $this->headers;
    }

    /**
     * 获取事件
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * 获取表格操作
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * 获取 默认 query params
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getDefaultQueryParams(): array
    {
        return $this->defaultQueryParams;
    }

    /**
     * get filter params
     *
     * @time 2021年03月30日
     * @return array
     */
    public function getFilterParams(): array
    {
        return $this->filterParams;
    }
}