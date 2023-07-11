<?php
class Page{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows = 0; // 总行数
    public $totalPages = 0; // 分页总页面数
    public $rollPage   = 11;// 分页栏每页显示的页数
    public $lastSuffix = true; // 最后一页是否显示总页数
    public $pageclass = '';

    private $p       = 'p'; //分页参数名
    public $url     = ''; //当前链接URL
    public $nowPage = 1;

    // 分页显示定制
    private $config  = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '<<',
        'next'   => '>>',
        'first'  => '1...',
        'last'   => '...%TOTAL_PAGE%',
        'total_page'   => '<span class="totalpage">共 %TOTAL_PAGE% 页</span>',
        'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
        'home_page' => false  //是否是前端页面分页
    );

    /**
     * 架构函数
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows, $listRows=20, $parameter = array()) {
        /* 基础设置 */
        $this->totalRows  = $totalRows; //设置总记录数
        $this->listRows   = $listRows;  //设置每页显示行数
        $this->parameter  = empty($parameter) ? $_REQUEST : $parameter;
        $this->nowPage    = (isset($_REQUEST[$this->p]) && $_REQUEST[$this->p]) ? $_REQUEST[$this->p] : 1;
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);


    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){

        return str_replace('[PAGE]', $page, $this->url);
    }

    /**
     * 组装分页链接
     * @return string
     */
    public function show() {
        if($this->totalRows < $this->listRows)return '';//如果总数小于每页的数量，则不输出
        if(0 == $this->totalRows) return '';
//        if($empty) return '';

        $params = [];
        foreach ($this->parameter as $key => $val){
            if($key != $this->p ){
                $params[] = $key . '=' . $val;
            }
        }
        $params[] = $this->p . '=' . '[PAGE]';
        $this->url = $this->url . '?' . implode('&' , $params);

        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        if($up_row > 0 ) {
            $up_page = '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>';
        }else{
            if($this->config['home_page'] == false){
                $up_page = '<a class="prev disabled" href="javascript:void(0)">' . $this->config['prev'] . '</a>';
            }
        }
        //下一页
        $down_row  = $this->nowPage + 1;
        if($down_row <= $this->totalPages) {
            $down_page = '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>';
        }else{
            if($this->config['home_page'] == false){
                $down_page = '<a class="next disabled" href="javascript:void(0)">' . $this->config['next'] . '</a>';
            }
        }
        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = '<a class="first" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<a class="end" href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
            if(($this->nowPage - $now_cool_page) <= 0 ){
                $page = $i;
            }elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
                $page = $this->totalPages - $this->rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<a class="num" href="' . $this->url($page) . '">' . $page . '</a>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<span class="current">' . $page . '</span>';
                }
            }
        }

        $jump_page = '<select name="page_change">';

        for($i = 1 ; $i<=$this->totalPages ; $i++){

            if($i == $this->nowPage){

                $jump_page .= '<option value="' . $this->url($i) . '" selected="selected">' . $i . '</option>';

            }else{

                $jump_page .= '<option value="' . $this->url($i) . '">' . $i . '</option>';

            }

        }

        $jump_page .= '</select>';

        $jump_page .= '<script type="text/javascript">$(function(){$("select[name=\'page_change\']").on("change" , function(){window.location.href=$(this).val();});})</script>';

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTALPAGE%', '%TOTAL_PAGE%', '%JUMP_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows,  $this->config['total_page'] , $this->totalPages , $jump_page),
            $this->config['theme']);
        return "<div class='" . $this->pageclass . "'>{$page_str}</div>";
    }
}