<?php
/**
 *Page.class.php
 *Create on 2020/1/21 2:04 下午
 *Create by lxllsy520
 */
$page = new Page(5,60);
var_dump($page->allUrl());
class Page{
    protected $number; //每页显示多少条数据
    protected $totalCount;//一共有多少条数据
    protected $page; //当前页
    protected $totalPage;//总页数
    protected $url; //URL
    public function __construct($number, $totalCount)
    {
        $this->number = $number;
        $this->totalCount = $totalCount;
        //得到总页数
        $this->totalPage = $this->getTotalPage();
        //得到当前页数
        $this->page = $this->getPage();
        //得到url
        $this->url = $this->getUrl();
    }

    protected function getTotalPage(){
        return ceil($this->totalCount / $this->number);
    }

    protected function getPage(){
        if (empty($_GET['page'])){
            $page = 1;
        }elseif ($_GET['page'] > $this->totalPage){
            $page = $this->totalPage;
        }elseif ($_GET['page'] < 1){
            $page = 1;
        }else{
            $page = $_GET['page'];
        }
        return $page;
    }

    protected function getUrl(){
        //例如：http://zx.com/class/Page.class.php?page=5&a=1
        //得到协议名 如：http
        $scheme = $_SERVER['REQUEST_SCHEME'];
        //得到主机名 如： zx.com
        $host = $_SERVER['SERVER_NAME'];
        //得到端口号 如：80
        $port = $_SERVER['SERVER_PORT'];
        //得到路径和请求字符串 如：/class/Page.class.php?page=5&a=1
        $uri = $_SERVER['REQUEST_URI'];
        //中间做处理，要将page=5等这种字符串拼接url中，所以如果原来的url中有page这个参数，我们首先要将原来的page参数给清空，parse_url得到一个数组，如：array(2) {
        //  ["path"]=>
        //  string(21) "/class/Page.class.php"
        //  ["query"]=>
        //  string(10) "page=5&a=1"
        //}
        $urlArray = parse_url($uri);
        $path = $urlArray['path'];
        if (!empty($urlArray['query'])){
            //首先将请求字符串变为关联数组
            parse_str($urlArray['query'], $array);
            //清除掉关联数组中的page键值对
            unset($array['page']);
            //将剩下的参数拼接为请求字符串
            $query = http_build_query($array);
            //再讲请求字符串拼接到路径的后面
            if ($query != ''){
                $path = $path . '?' . $query;
            }
        }
        return $scheme . '://' . $host . ':' . $port . $path;

    }

    protected function setUrl($str){
        if (strstr($this->url, '?')){
            $url = $this->url . '&' . $str;
        }else{
            $url = $this->url . '?' . $str;
        }
        return $url;
    }

    public function allUrl()
    {
        return [
            'first' => $this->first(),
            'prev' => $this->prev(),
            'next' => $this->next(),
            'end' => $this->end()
        ];
    }
    public function first(){
        return $this->setUrl('page=1');
    }

    public function next(){
        //根据当前page得到下一页的页码
        if ($this->page + 1 > $this->totalPage){
            $page = $this->totalPage;
        }else{
            $page = $this->page + 1;
        }
        return $this->setUrl('page='.$page);
    }

    public function prev(){
        //根据当前page得到上一页的页码
        if ($this->page - 1 < 1){
            $page = 1;
        }else{
            $page = $this->page - 1;
        }
        return $this->setUrl('page='.$page);
    }

    public function end(){
        return $this->setUrl('page='.$this->totalPage);
    }

    public function limit(){
        $offset = ($this->page - 1) * $this->number;
        return $offset. ',' . $this->number;
    }
}