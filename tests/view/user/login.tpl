{{layout 'common/layout.tpl'}}
{{fill header}}
<script>
    alert('userLogin')
</script>
{{end header}}

{{fill body}}
<h1>UserLogin</h1>
{{include 'common/common.tpl'}}
<h1>
    {{if $info['age']<18}}
        未成年
    {{else}}
        成年
    {{/if}}
</h1>
<h1>登陆时间:{{php echo date('Y-m-d H:i:s',$time)}}</h1>
<ul>
    {{loop $info $k $v}}
        <li>{{$k}}=>{{$v}}</li>
    {{/loop}}

    {{loop $info $k $v}}
        <li>{{$k}}=>{{$v}}</li>
    {{pool}}

</ul>
{{APP_Name}}
{{end body}}