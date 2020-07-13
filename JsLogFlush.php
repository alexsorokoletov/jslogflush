if (!('logflush' in window)) {
    (function() {
        window.logflush = {
            log: function(s) {
                window.oldsole.log(`${s} ${JSON.stringify(arguments)}`);
                if (arguments.length <= 1) {
                    if (typeof s == 'undefined') flushEx();
                    else log(s);
                    return;
                }
                log(`${s} ${JSON.stringify(arguments)}`);
            },
            warn: function(s) {
                window.oldsole.warn(`${s} ${JSON.stringify(arguments)}`);
                if (arguments.length <= 1) {
                    if (typeof s == 'undefined') flushEx();
                    else log(s);
                    return;
                }
                log(`${s} ${JSON.stringify(arguments)}`);
            },
            error: function(s) {
                window.oldsole.error(`${s} ${JSON.stringify(arguments)}`);
                if (arguments.length <= 1) {
                    if (typeof s == 'undefined') flushEx();
                    else log(s);
                    return;
                }
                log(`${s} ${JSON.stringify(arguments)}`);
            }
        };
        window.oldsole = window.console;
        window.console = window.logflush;
        var ID = '5f0cbf95cfbe4';
        var URL = 'http://46.101.247.101/logs/gen.php';
        var BUFF_SIZE = 1000;
        var INTERVAL = 1500;
        var INTERVAL_BK = 20500;
        var aQueue = [];
        var sBuff = '';
        var iTmr = 0;
        var iStamp0 = now();
        var iStamp = 0;
        var nBkFlag = 0;

        function log(s) {
            if (!ID || !s) return false;
            s = (iStamp0 ? (now() - iStamp0) + '\t' : '') + s + '\n';
            if (encodeURIComponent(sBuff + s).length > BUFF_SIZE) push2Queue();
            sBuff += s;
            if (aQueue.length) flushEx();
            if (!nBkFlag) {
                nBkFlag = 1;
                setInterval(flushEx, INTERVAL_BK);
            }
            return true;
        }

        function flush() {
            if (!ID) return;
            if (iTmr) clearTimeout(iTmr);
            iTmr = iStamp = 0;
            if (!aQueue.length && sBuff) push2Queue();
            if (!aQueue.length) return;
            send('id=' + ID + '&data=' + encodeURIComponent(aQueue.shift()), 1);
            iStamp = now();
            iTmr = setTimeout(flush, INTERVAL);
        }

        function flushEx() {
            if (!iStamp || now() - iStamp >= INTERVAL) flush();
        }

        function push2Queue() {
            if (aQueue.length > 30) aQueue.length = 0;
            aQueue.push(sBuff);
            sBuff = '';
        }

        function send(query) {
            var script = document.createElement('script');
            script.src = URL + '?' + query + '&_=' + now();
            document.body.appendChild(script);
        }

        function onResponse(v) {
            if (v == 'denied') ID = '';
        }

        function now() {
            return (new Date()).getTime();
        }

        function outExpr(x) {
            if (typeof x != 'object' || x instanceof Date || x instanceof RegExp) return x;
            var s = '';
            for (var key in x) s += ', ' + key + ':' + outExpr(x[key]);
            return '{' + (s ? s.substr(2) : s) + '}';
        }
    })();
}
