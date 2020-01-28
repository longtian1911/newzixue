## 一、JsDOM操作-节点操作

### 1、DOM操作（document object model）文档对象模型

```javascript
document.getElementById()
document.getElementsByClassName() //得到的是一个集合
document.getElementsByName
document.getElementsByTagName //这些得到的都是一个集合
document.title //获取网页标题
doucument.body //获取body节点
document.documentElement.clientWidth //获取可视区的宽度
document.documentElement.clientHeight //获取可视区的高度
```

### 2、onload加载

```
onload //当页面全部加载完毕之后执行其中的代码
onresize //当页面窗口大小发生改变时执行期中的代码
```

### 3、获取属性

```
【注意】下面的方法只能获取设置行内属性的值
获取对象属性，获取对象的style
oDiv.className //获取类名
oDiv.style.width //获取宽度
oDiv..style.backgroundColor //获取背景颜色
//通过style然后再获取div的宽度、高度和背景色
[注意]css属性中带杠的，在js对象中属性命名谷歌为小驼峰获取标签文本
//得到div中的文本内容，下面这两个都可以得到内容
oDiv.innerHTML //会得到div中的html标签
oDiv.innerText //和获取div中纯文本内容
```

### 4、获取自定义的属性

```
setAttribute 通过这个属性可以修改自定义属性的值
getAttribute 可以获取自定义属性的值
```

### 5、获取非行内样式

```
由于该方法涉及到兼容性问题我们可以写一个函数来使用
//obj为一个节点对象，name为需要获取的样式名称
function getAttr(obj,name){
	//currentStyle支持ie 火狐谷歌不支持，getComputedStyle不支持ie
	return obj.currentStyle ?  obj.currentStyle[name] : getComputedStyle(obj, null)[name];
}
```

### 5、节点操作

```
children //根据父亲找到所有的儿子
parentNode //根据儿子找到父亲
火狐或者谷歌支持下面操作
firstElementChild //第一个子节点
laseElementChild //最后一个子节点
previousElementSibling //同辈中的上一个节点
nextElementSibling //同辈中的下一个节点

ie中支持下面操作
firstChild 
laseChild
previousSibling
nextSibling
```

### 6、添加或者删除节点

```
createElement //创建节点，该方法只属于docunment
removeChild //删除节点，该方法任何父对象都能用
appendChild //追加节点，在父对象的下面追加一个节点
insertBefore //插入节点，插入到父对象下面的某个节点前面
```

## 二、JsDOM操作-事件

### 1、js核心

根据事件来修改标签的属性值

### 2、常用的事件

```
onmouseover //当鼠标悬停的时候
onmouseout //当鼠标离开的时候
onmouseup //当鼠标松开的时候
onmousedown //当鼠标按下的时候
onmousemove //当鼠标移动的时候
onclick //点击事件
ondblclick //双击事件
onblur //失去焦点的时候
onfocus //得到焦点的时候
```

### 3、事件绑定（一个节点需要执行两个事件时使用）

```
执行两个onclick事件
addEventListener //绑定事件，适合主流浏览器
attachEvent //IE浏览器绑定事件
removeEventListener //移除绑定，主流浏览器
detachEvent //移除绑定  IE浏览器
兼容写法
//obj为需要绑定的节点，ev是需要绑定的事件，如onclick等，但是传递参数时要去掉on ，fn为绑定事件的函数
function addEvent(obj, ev, fn){
	if(obj.addEventListener){
	obj.addEventListener(ev, fn);
	}else{
	obj.attachEvent('on' + ev, fn);
	}
}
```

### 4、事件对象

```
时间ev(主流)和window.event 兼容性写法
var oEvent = ev || window.event;
事件源对象
srcElement(谷歌和IE)
target (火狐专用)  例：oEvent.target
获取鼠标的x和y坐标
console.log(oEvent.clientX, oEvent.clientY);
```

### 5、事件冒泡

```
说明：当一个div里面包裹住一个div后点击包裹住的div两个div都操作了 这就是事件冒泡
取消时间冒泡
var oEvent = ev || window.event;
oEvent.cancelBubble = ture;
```

### 6、阻止超链接和点击事件同时触发

```
阻止默认的行为不去响应，只响应自己添加的事件
return false  在函数中添加return false   然后再onclick中  return demo()
例子： <a href="http://www.baidu.com" onclick="return demo()">百度</a>
			<script>
			function demo(){
				alert('你是傻逼');
				return false;
			}
			</script>
或者使用 oEvent.preventDefault() (无论是针对普通事件还是针对绑定的事件都管用)
例子：
<a href="http://www.baidu.com" onclick="return demo()">百度</a>
<script>
			function demo(ev){
			var oEvent = ev || event;
			oEvent.preventDefault();
				alert('你是傻逼');
			}
			</script>
```

## Js定时器

定时器 ：

一次性定时器：var timer = setTimeout(方法，时间); 时间以ms为单位，该方法会返回一个定时器对象

销毁一次性定时器：clearTimeout(timer)；

周期性定时器：var timer = setInterval(方法，时间);  时间以ms为单位

销毁周期性定时器：clearInterval(timer);

### 懒加载

```
测试前提：都要在h5标准下进行 <!DOCTYPE html>
oDiv.offsetWidth //获取div宽度
oDiv.offsetHeight //获取div高度
oDiv.offsetTop //距离网页上面的高度
oDiv.offsetLeft //距离网页左面的宽度
document.documentElement.clientHeight //可视区高度
document.documentElement.clientWidht //可视区宽度
var scrollTop = document.documentElement.scrollTop||document.body.scrollTop; //卷起高度，两者都可以，但是有兼容性问题，这样书写可以解决大部分浏览器的问题
window.onscroll = function(){} //当右侧滚动条滚动时触发该事件
```

## 随机数

```
随机产生n到m的随机数
function rand(n,m){
return parseInt(Math.random() * (m - n)) + m;
}
```

## ajax

```
1、创建对象
var xhr = new XMLHttpRequest(); //主流浏览器中创建ajax对象
get请求
//参数一：请求方式，参数二：请求地址
xhr.open('get','1.php?username=goudan');
//send：发送请求
xhr.send();
post请求
xhr.open('post', '1.php');
//在send函数中写post要传递的参数字符串
//注意，如果要以post方式请求，必须要设置这个函数
xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
xhr.send('username=goudan&pwd=123');
onreadystatechange :当ajax状态改变的时候出发这个时间
readyState ：ajax的状态，对我们有用的就是4
0：初始化   1：执行了open方法  2：执行了send方法  3：得到了部分响应的数据  4：得到了全部响应的数据
status ：获取heep的状态码，当状态码为200的时候，来得到响应的数据
responseText：获取到交互的数据一般json格式
将返回的json字符串转化为js对象
var obj = JSONparse(xhr.responseText);
将js对象转化为json格式的字符串
JSON.stringify(obj);
同步和异步：
第三个参数：是否异步
同步的时候，事件绑定函数必须在open的上面
异步的时候，事件绑定函数在哪都可以，所以都放到open的上面
xhr.open('post','goudan.php',false);
ajax去请求数据的时候，js这一端的代码是否要接着执行还是要等着ajax的结果
同步：必须等着ajax的结果，才能接着乡下执行
异步：使用的居多，而且效率要比同步的效率高

跨域
两种方法：
1、header头设置(在请求的文件中使用，不常用)
header('Access-Control-Allow-Origin:*');
2、使用jsonp

```

























