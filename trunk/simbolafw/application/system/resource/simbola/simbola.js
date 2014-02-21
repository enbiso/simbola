var simbola = {
    baseUrl: '',
    params: {
        sys: {},
        url: {}
    },
    isInit: false,
    log: function(type, message) {
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
        updateInterval: 5000,
        set: function(auth_data) {
            $.cookie("auth", JSON.stringify(auth_data), {path: "/"});
        },
        get: function() {            
            var auth_data = $.cookie('auth');
            if (auth_data === undefined) {
                auth_data = {username: 'guest', skey: ''};
            } else {
                auth_data = JSON.parse(auth_data);
            }
            return auth_data;
        },
        isLogged: function() {            
            return simbola.auth.username() === 'guest';
        },
        username: function() {            
            return simbola.auth.get().username;
        },
        skey: function() {
            return simbola.auth.get().skey;
        }
    },
    init: function(params) {
        simbola.log('log','simbola.init()');
        this.params = params;
        this.baseUrl = location.protocol + "//" + location.host;
        if (this.params.url.URL_BASE) {
            this.baseUrl = this.baseUrl + "/" + this.params.url.URL_BASE;
        }
        if (!this.params.url.HIDE_INDEX) {
            this.baseUrl = this.baseUrl + "/index.php";
        }
        simbola.log("log", "simbola.baseUrl set to :" + this.baseUrl);
        this.isInit = true;
        setInterval(function() {            
            url = simbola.url.action('system/auth/session');
            $.post(url, simbola.auth.get(), function(data) {
                simbola.auth.set(data.auth);
                if (data.reload) {
                    window.location = window.location;
                }
            }, 'json');
        }, simbola.auth.updateInterval);
        simbola.log('info', "Simbola Initialized");
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
            return simbola.baseUrl + "/" + action + paramString;
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
                                $.pnotify(data.body.message);
                                break;
                            default:
                                alert(data.header.status);
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
        USER_ERROR: '201',
        INVALID_SERVICE: '404',
        BAD_REQUEST: '400',
        FORBIDDEN: '403'
    }
};