<?php
/**
 * Desc: 内置标签
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午4:10
 */
return [
    '/{{php\s+(.+?)}}/'                                            => '<?php $1?>',
    '/{{if\s+(.+?)}}/'                                             => '<?php if($1) { ?>',
    '/{{else}}/'                                                   => '<?php } else { ?>',
    '/{{else ?if\s+(.+?)}}/'                                       => '<?php } else if($1) { ?>',
    '/{{\/if}}/'                                                   => '<?php } ?>',
    '/{{loop\s+(\S+)\s+(\S+)}}/'                                   => '<?php if(is_array($1)) { foreach($1 as $2) { ?>',
    '/{{loop\s+(\S+)\s+(\S+)\s+(\S+)}}/'                           => '<?php if(is_array($1)) { foreach($1 as $2 => $3) { ?>',
    '/{{\/loop}}/'                                                 => '<?php } } ?>',
    '/{{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}}/'           => '<?php echo $1;?>',
    '/{{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\[\]\'\"\$]*)}}/' => '<?php echo $1;?>',
    '/{{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}}/s'                  => '<?php echo $1;?>',
];
