<?php
function simbola_js_init(){
    $params = array(
        'sys' => simbola\Simbola::app()->getParams(),
        'url' => simbola\Simbola::app()->url->getParams(),
    )
?>
<script type="text/javascript">
    simbola.init(<?= json_encode($params) ?>);
</script>
<?php
}