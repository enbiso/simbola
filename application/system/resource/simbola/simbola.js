var simbola = {
    baseUrl: '',
    params: {
        sys: {},
        url: {}
    },
    isInit: false,
    log: function(type, message) {
        //code for IE and other browsers which doesnt have consoles
        window.console = window.console || (function(){
            var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(s){};
            return c;
        })();
        
        var now = new Date(),
                now = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds() + "." + now.getMilliseconds();
        message = "Simbola " + now + " - " + message;
        switch (type) {
            case 'error':
                console.error(message);
                break;
            case 'warn':
                console.warn(message);
                break;
            case 'trace':
                console.trace(message);
                break;
            case 'info':
                console.info(message);
                break;
            case 'log':
                console.log(message);
                break;
            case 'count':
                console.count(message);
                break;
        }
    },
    checkInit: function() {
        if (!simbola.isInit) {
            simbola.log("error", "Simbola not initialized. Add <?php simbola_js_init() ?> to your layout");
        }
    },
    auth: {        
        auth_data: {username: 'guest', skey: ''},
        set: function(auth_data) {
            simbola.auth.auth_data = auth_data;
        },
        get: function() {
            return simbola.auth.auth_data;
        },
        isLogged: function() {
            return simbola.auth.username() !== 'guest';
        },
        username: function() {
            return simbola.auth.get().username;
        },
        skey: function() {
            return simbola.auth.get().skey;
        }
    },
    init: function(params, auth) {
        simbola.log('log', 'simbola.init()');
        this.params = params;
        this.baseUrl = location.protocol + "//" + location.host;
        if (this.params.url.URL_BASE) {
            this.baseUrl = this.baseUrl + "/" + this.params.url.URL_BASE;
        }
        if (!this.params.url.HIDE_INDEX) {
            this.baseUrl = this.baseUrl + "/index.php";
        }
        simbola.log("log", "simbola.baseUrl set to :" + this.baseUrl);
        simbola.auth.set(auth);
        simbola.log('info', "Simbola Initialized");
        this.isInit = true;
    },
    url: {
        action: function(action, params) {
            simbola.checkInit();
            var paramString = "";
            if (params !== undefined) {
                $.each(params, function(key, value) {
                    paramString += "[" + key + ":" + value + "]";
                });
            }
            return simbola.baseUrl + (action.indexOf("/") === 0 ? '' : '/') + action + paramString;
        },
        service: function() {
            simbola.checkInit();
            return simbola.baseUrl + "/" + simbola.params.sys.SERVICE_API;
        },
        redirect: function(url) {
            window.location = url;
        }
    },
    call: {
        service: function(module, service, action, params, callback, managed) {
            simbola.log("log", "simbola.call.service(" + module + "," + service + "," + action + ")")
            var url = simbola.url.service();
            if (params === undefined) {
                params = {};
            }
            if (managed === undefined) {
                managed = true;
            }
            var request = {
                module: module,
                service: service,
                action: action,
                auth: simbola.auth.get(),
                params: params
            };
            $.post(url, request, function(data) {
                if (callback !== undefined) {
                    if (managed) {
                        switch (data.header.status) {
                            case simbola.STATUS.OK:
                                callback(data.body.response);
                                break;
                            case simbola.STATUS.USER_ERROR:
                                $.pnotify({
                                    title: 'Application error',
                                    text: data.body.message,
                                    type: 'error'
                                });
                                break;
                            case simbola.STATUS.ERROR:
                                $.pnotify({
                                    title: 'System error',
                                    text: data.body.message,
                                    type: 'error'
                                });
                                break;
                            default:
                                alert(data.header.status_text);
                                break;
                        }
                    } else {
                        callback(data);
                    }
                }
            }, 'json');
        }
    },
    STATUS: {
        OK: '200',
        ERROR: '500',
        USER_ERROR: '201',
        INVALID_SERVICE: '404',
        BAD_REQUEST: '400',
        FORBIDDEN: '403'
    }
};