<?php
/**
 *Model.class.php
 *Create on 2020/1/31 12:33 下午
 *Create by lxllsy520
 */
$config = array(
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PWD' =>'liuliliuli',
    'DB_NAME' => 'test',
    'DB_CHARSET' => 'utf8',
    'DB_PREFIX' => ''
);
$model = new Model($config);
//测试查询函数
//$m = $model->limit('0,5')->table('user')->field('id,age,name')->order('money desc')->where('id>1')->select();
//var_dump($m);
//echo $model->sql;
//测试插入函数
//$data = ['age'=> 30, 'name'=>'成龙', 'money'=>2000];
//echo $model->table('user')->insert($data);
//测试删除语句
//echo $model->table('user')->where('id>2')->delete();
//测试修改语句
//$data = ['age'=>18,'name'=>'李素英1'];
//echo $model->table('user')->where('id=2')->update($data);
//测试max函数
//echo $model->table('user')->max('money');
//测试getBy函数
var_dump($model->table('user')->getByName('流量'));

class Model{
    protected $host; //主机名
    protected $user; //用户名
    protected $pwd; //密码
    protected $dbname; //数据库名
    protected $charset; //字符集
    protected $prefix; //数据表前缀
    protected $link; //数据库连接资源
    protected $tableName; //数据表名  这里可以指定表名
    protected $sql; //sql语句
    protected $options; //操作数据，存放的就是所有的查询条件

    //构造方法，对成员变量进行初始化
    public function __construct(array $config)
    {
        //对成员变量一一进行初始化
        $this->host = $config['DB_HOST'];
        $this->user = $config['DB_USER'];
        $this->pwd = $config['DB_PWD'];
        $this->dbname = $config['DB_NAME'];
        $this->charset = $config['DB_CHARSET'];
        $this->prefix = $config['DB_PREFIX'];
        //连接数据库
        $this->link = $this->connect();
        //得到数据表名
        $this->tableName = $this->getTableName();
        //初始化options数组
        $this->initOptions();
    }

    //连接数据库
    protected function connect(){
        $link = mysqli_connect($this->host, $this->user, $this->pwd);
        if (!$link){
            die('数据库连接失败');
        }
        mysqli_select_db($link, $this->dbname);
        mysqli_set_charset($link, $this->charset);
        return $link;
    }

    //得到数据表名
    protected function getTableName(){
        //第一种，如果设置了成员变量，那么通过成员变量来得到表名
        if (!empty($this->tableName)){
            return $this->prefix . $this->tableName;
        }
        //第二种，如果没有设置成员变量，那么通过类名来得到表名
        //得到类名字符串
        $className = get_class($this);
        $table = strtolower(substr($className, 0, -5));
        return $this->prefix . $table;
    }

    protected function initOptions(){
        $arr = ['where','table','field','order','group','having','limit'];
        foreach ($arr as $value){
            //将options数组中这些键对应的值全部清空
            $this->options[$value] = '';
            //将table默认设置为tableName
            if ($value == 'table'){
                $this->options[$value] = $this->tableName;
            }elseif ($value == 'field'){
                $this->options[$value] = '*';
            }
        }
    }

    //field方法
    public function field($field){
        if (!empty($field)){
            if (is_string($field)){
                $this->options['field'] = $field;
            }elseif (is_array($field)){
                $this->options['field'] = join(',', $field);
            }
        }
        return $this;
    }

    //table方法
    public function table($table){
        if (!empty($table)){
            $this->options['table'] = $table;
        }
        return $this;
    }

    //where方法
    public function where($where){
        if (!empty($where)){
            $this->options['where'] = 'WHERE ' . $where;
        }
        return $this;
    }

    //group方法
    public function group($group){
        if (!empty($group)){
            $this->options['group'] = 'GROUP BY ' . $group;
        }
        return $this;
    }

    //having方法
    public function having($having){
        if (!empty($having)){
            $this->options['having'] = 'HAVING ' . $having;
        }
        return$this;
    }

    //order方法
    public function order($order){
        if (!empty($order)){
            $this->options['order'] = 'ORDER BY ' . $order;
        }
        return $this;
    }

    //limit方法
    public function limit($limit){
        if (!empty($limit)){
            if (is_string($limit)){
                $this->options['limit'] = 'LIMIT ' . $limit;
            }elseif(is_array($limit)){
                $this->options['limit'] = 'LIMIT ' . join(',', $limit);
            }
        }
        return $this;
    }

    //select方法
    public function select(){
        //先预写一个带有占位符的sql语句
        $sql = 'SELECT %FIELD% FROM %TABLE% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';
        //将options中对应的值依次替换上面的占位符
        $sql = str_replace(['%FIELD%', '%TABLE%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%'], [$this->options['field'], $this->options['table'], $this->options['where'], $this->options['group'], $this->options['having'], $this->options['order'], $this->options['limit'],
                ], $sql);
        //保存一份sql语句
        $this->sql = $sql;
        //执行sql语句
        return $this->query($sql);
    }

    //query方法
    public function query($sql){
        //清空options数组中的值，避免对下次执行有影响
        $this->initOptions();
        //执行sql语句
        $result = mysqli_query($this->link, $sql);
        //提取结果集到数组中
        if ($result && mysqli_affected_rows($this->link)){
            while ($data = mysqli_fetch_assoc($result)){
                $newData[] = $data;
            }
        }
        return $newData;
    }

    //获取sql语句
    public function __get($name)
    {
        if ($name == 'sql'){
            return $this->sql;
        }
        return false;
    }

    //insert函数
    //$data:关联数组，键就是字段名，值就是字段值
    public function insert($data){
        //处理值是字符串问题，两边需要添加单或双引号
        $data = $this->parseValue($data);
        //INSERT INTO 表名(字段) VALUES(值）
        //提取所有的键，即所有的字段
        $keys = array_keys($data);
        //提取所有的值
        $values = array_values($data);
        //增加数据的sql语句
        $sql = 'INSERT INTO %TABLE%(%FIELD%) VALUES(%VALUES%)';
        $sql = str_replace(['%TABLE%', '%FIELD%', '%VALUES%'], [$this->options['table'], join(',', $keys), join(',', $values)], $sql);
        $this->sql = $sql;
        return $this->exec($sql, true);
    }

    //传递进来一个数组，将数组中值 为字符串的两边加单双引号
    protected function parseValue($data){
        //遍历数组，判断是否为 字符串，若是字符串，将其两边添加引号
        foreach ($data as $key => $value) {
            if (is_string($value)){
                $value = '"' . $value . '"';
            }
            $newData[$key] = $value;
        }
        //返回处理后的数组
        return $newData;
    }

    //exec方法
    public function exec($sql, $isInsert = false){
        //清空options数组中的值，避免对下次执行有影响
        $this->initOptions();
        //执行sql语句
        $result = mysqli_query($this->link, $sql);
        if ($result && mysqli_affected_rows($this->link)){
            //判断是否是插入语句，根据不同的语句返回不同的结果
            if ($isInsert){
                return mysqli_insert_id($this->link);
            }else{
                return mysqli_affected_rows($this->link);
            }
        }
        return false;
    }

    //删除函数
    public function delete(){
        //拼接sql语句
        $sql = 'DELETE FROM %TABLE% %WHERE%';
        $sql = str_replace(['%TABLE%', '%WHERE%'], [$this->options['table'], $this->options['where']], $sql);
        //保存sql语句
        $this->sql = $sql;
        return $this->exec($sql);
    }

    //更新函数
    //update 表名  set 字段名=字段值，字段名=字段值 wher id=1
    public function update($data){
        //处理$data数组中值为字符串加引号的问题。
        $data = $this->parseValue($data);
        //将关联数组拼接为固定的格式，键=值，键=值
        $value = $this->parseUpadte($data);
        $sql = 'UPDATE %TABLE% SET %VALUE% %WHERE%';
        $sql = str_replace(['%TABLE%', '%VALUE%', '%WHERE%'], [$this->options['table'], $value ,$this->options['where']], $sql);
        $this->sql = $sql;
        return $this->exec($sql);
    }

    protected function parseUpadte($data){
        foreach ($data as $key => $value){
            $newData[] = $key . '=' . $value;
        }
        return join(',', $newData);
    }

    //max函数
    public function max($field){
        //通过调用自己封装的方法进行查询
        $result = $this->field('max(' . $field . ') as max')->select();
        //select方法返回的是一个二维数组
        return $result[0]['max'];
    }

    //析构方法
    public function __destruct()
    {
        mysqli_close($this->link);
    }

    //getByName getByAge
    public function __call($name, $arguments)
    {
        //获取前5个字符
        $str = substr($name, 0, 5);
        //获取后面的字段名
        $field = strtolower(substr($name, 5));
        //判断前五个字符是否是getby
        if ($str == 'getBy'){
            return $this->where($field . '="' . $arguments[0] . '"')->select();
        }
        return false;
    }
}


















