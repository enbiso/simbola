<?php $url = simbola\Simbola::app()->url; ?>
<script>    
    $(function(){
    var ur_url = "/system/rbam/accessObjects";
    $("#access_obj_list").dynatree({
        debugLevel: 2,
        persist: false,
        checkbox: true,
        selectMode: 3,
        initAjax: { url: ur_url }
    });
});
</script>
<script>

function registerModule(){
    var name = $('#select_modules').val();
    <?php $page = new \simbola\core\component\url\lib\Page;
          $page->loadFromArray(array('system/rbam/registerModule', 'module' => '__MDL__')); ?>
    var url = "<?php echo $page->getUrl(); ?>";
    url = url.replace("__MDL__",name);
    $("body").css("cursor", "progress");
    $.post(url, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);        
        $("#access_obj_list").dynatree('getTree').reload();
    },'json');
}

function registerAllModules(){    
    var url = "/system/rbam/registerAllModules";    
    $("body").css("cursor", "progress");
    $.post(url, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);        
        $("#access_obj_list").dynatree('getTree').reload();
    },'json');
}
    
function removeObjs(){
    var selNodes = $("#access_obj_list").dynatree('getTree').getSelectedNodes();
    var selKeys = $.map(selNodes, function(node){
        return node.data.key;
    });
    var post_data = {            
        'objs': selKeys            
    };
    $("body").css("cursor", "progress");
    $.post("/system/rbam/unregister", post_data, function(data){
        $("body").css("cursor", "auto");
        $.pnotify(data);
        $("#access_obj_list").dynatree('getTree').reload();
    }, 'json');
}
</script>