var simbola = {    
    base_url : '',
    sys_params : {},
    auth : {
        update_interval : 5000,
        set: function(auth_data){                       
            $.cookie("auth", JSON.stringify(auth_data),{ path: "/" });  
        },
        get: function(){
            var auth_data = $.cookie('auth');            
            if(auth_data === undefined){
                auth_data = {
                    username : 'guest', 
                    skey : ''
                };
            }else{
                auth_data = JSON.parse(auth_data);
            }
            return auth_data;
        },
        is_logged: function(){
            return simbola.auth.username() === 'guest';
        },
        username: function(){
            return simbola.auth.get().username;
        },
        skey: function(){
            return simbola.auth.get().skey;
        }
    },
    init : function(sys_params){
        this.base_url = location.protocol + "//" + location.hostname + (location.port && ":" + location.port);
        this.sys_params = sys_params;
    },
    url : {
        action : function(action, params){
            var paramString = "";
            if(params !== undefined){
                $.each(params,function(key,value){
                    paramString += "[" + key + ":" + value + "]";
                });
            }
            return simbola.base_url + "/" + action + paramString;
        },
        service : function(){            
            return simbola.base_url + "/" + simbola.sys_params.SERVICE_API;
        },
        redirect: function(url){
            window.location = url;
        }
    },
    call : {
        service : function(module, service, action, params, callback, managed){
            var url = simbola.url.service();
            if(params === undefined){
                params = {};            
            }
            if(managed === undefined){
                managed = true;
            }
            var request = {
                'module':module,
                'service':service,
                'action' :action,
                'auth'   :simbola.auth.get(),
                'params' :params
            };            
            $.post(url,request,function(data){
                if(callback !== undefined){
                    if(managed){
                        switch (data.header.status){
                            case simbola.STATUS.OK:
                                callback(data.body.response);
                                break;
                            case simbola.STATUS.USER_ERROR:
                                $.pnotify(data.body.message);
                                break;
                            default:
                                alert(data.header.status);
                                break;
                        }
                    }else{
                        callback(data);
                    }
                }
            },'json');
        }
    },
    STATUS : {
        OK : '200',
        USER_ERROR : '201',
        INVALID_SERVICE : '404',
        BAD_REQUEST : '400',
        FORBIDDEN : '403'
    }
};

//auto update session
setInterval(function (){
    url = simbola.url.action('system/auth/session');
    $.post(url, simbola.auth.get(), function(data){
        simbola.auth.set(data.auth);
        if(data.reload){ window.location = window.location; }
    },'json');
},simbola.auth.update_interval);