# Template php轻量的模板解析引擎
php Template View

内置主要方法
```php
interface ViewInterface
{   
    // 渲染，返回解析后的html模板
    public static function render(string $source, array $data = []);
    
    // 渲染并输出 没有返回
    public static function display(string $source, array $data = []);
}
```
## 基本使用

### 输出一个变量
输出标签是由一对花括号做为定界符的，不支持输出数组
```html
<b>跑车简介：</b>{{$item['aaa']['description']}}<br>
```

### if分支判断
```html
{{if $pp['active']}}
<li class="am-active"><a href="javascript:;">{{$p}}</a></li>
{{else}}
<li><a href="{{$pp['url']}}">{{$p}}</a></li>
{{/if}}
```

#### if elseif 
```html
{{if $pp['active']==1}}
<li class="am-active"><a href="javascript:;">{{$p}}</a></li>
{{elseif $pp['active']==2}}
<li><a href="{{$pp['url']}}">{{$p}}</a></li>
{{else}}
<li><a href="">{{$p}}</a></li>
{{/if}}
```

### 循环语句
循环一个数组用 loop 标签，与php中的 foreach 函数类似，第一个参数为数组的索引，第二个参数为数组第一项的值
只要标签成对匹配，模板中的标签是可以嵌套使用的。
```html
{{loop $list $k $item}}
<div class="am-panel am-panel-default">
    <div class="am-panel-bd">
        <table class="am-table am-table-striped am-table-bordered">
            <thead>
            <tr>
                <th>车牌号</th>
                <th>跑车名</th>
                <th>发布日期</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$item['ccc']}}</td>
                <td>{{$item['sss']['name']}}</td>
                <td>{{$item['date']}}</td>
            </tr>
            </tbody>
        </table>
        <b>车牌名：</b>{{$item['eee']}}<br>
        <b>跑车简介：</b>{{$item['rere']['description']}}<br>
    </div>
</div>
{{/loop}}
```

### 一行php语句
由于某些时候需要使用一些php来输出内容，比如格式化时间戳为日期时，此写法并不支持多行php语句
```html
<input type="text" name="sdfsd" value="{{php echo $_GET['ffg']??''}}" class="am-form-field" placeholder="车牌号">
```

### 引用一个模板文件
在模板中如果需要引用其它模板文件可以使用以下的方法，路径从View下级开始写，被引入的模板里也可以引入其他模板，多层次嵌套
```html
{{include /common/header.html}} 
<div class="am-panel am-panel-default">
    <div class="am-panel-bd">
        <form class="am-form-inline" role="form">
....
        </ul>
{{include common/footer.html}}
```

### layout布局
可以多层次嵌套布局，比如article控制器的模板，使用叫做main1的布局，而main1又使用了main2的布局...
#### 父模板挖坑 baseLayout.html
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$page_title}}</title>
    <meta name="description" content="">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/assets/i/favicon.png">
    <link rel="stylesheet" href="/assets/css/app.css">
    <!--挖个坑 叫做header-->
    {{hole header}}
</head>
<body class="theme-white">
<div class="am-g tpl-g">
<!--挖个坑 叫做content-->
{{hole content}}
</div>
</body>
</html>
```
#### 子模板继承父模板填坑。mainLayout.html
```html 
<!--使用baseLayout 布局，路径从View下级开始写-->
{{layout /common/baseLayout.html}}
<!--填坑 header-->
{{fill header}}
    <!--填父亲一个坑 为儿子挖两个坑-->
    {{hole header-js}}
    {{hole header-css}}
{{end header}}

<!--填坑 content-->
{{fill content}}
    <!--引入公共的top 和sidebar-->
    {{include common/top.html}}
    {{include common/sidebar.html}}

    <div class="tpl-content-wrapper">
        <div class="container-fluid am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                    <div class="page-header-heading"><span class="am-icon-home page-header-heading-icon"></span>
                        {{$page_title}}
                    </div>
                    <p class="page-header-description">{{$page_description}}</p>
                </div>
            </div>
        </div>
        <!--又为儿子挖了一个叫做content的坑-->
        {{hole content}}
        </div>
    <script src="/assets/js/amazeui.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <!--又为儿子挖了一个叫做javascript的坑-->
    {{hole javascript}}
{{end content}}
```
#### 孙模板  具体的action对应的模板
```html
<!--使用布局-->
{{layout common/mainLayout.html}}

<!--填坑 header-js-->
{{fill header-js}}
    <script src="/assets/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>
{{end header-js}}

<!--填父亲的坑 header-css-->
{{fill header-css}}
    <link rel="stylesheet" type="text/css" href="/assets/uploadifive/uploadifive.css">
{{end header-css}}

<!--填坑 content-->
{{fill content}}
    <div class="row-content am-cf">
        <div class="row">
            hello
        </div>
    </div>
{{end content}}
<!--注意：坑和坑之间的字符不会输出，比如下面的abc就不会输出到页面，模板编译时会直接丢弃填坑标签之外的字符-->
abc

{{fill javascript}}
    <script>
        alert(111);
    </script>
{{end javascript}}
```

#### 注意
1. 并不是每个父模板挖的坑子模板都必须填，可以根据需求填。
2. 子模板不能跨级填坑，只能填它父亲的坑，不能填爷爷的坑。
3. 坑和坑之间的字符不会输出，模板编译时会直接丢弃填坑标签之外的字符。

### 具体示例代码：tests目录