JS LogFlush
===============

_JS LogFlush_ is an integrated JavaScript logging solution which include:
* cross-browser UI-less replacement of console.log - on client side.
* log storage system - on server side.

[Demo (logger + manager)](http://demos.savreen.com/jslogflush-manager/) (login credentials: demo/demo) | [Demo sources](https://github.com/hindmost/jslogflush-manager)

[Review article in russian](http://savreen.com/krossbrauzernaya-alternativa-console-log-ili-kak-ya-perestal-volnovatsya-i-polyubil-klientskuyu-otladku/)


Key Features
-------------
* **Easy to use**. All that's needed to use _JS LogFlush_ in your web applications is include its processing script as JavaScript. No need to instantiate special JS classes. No need to use special syntax to call logging functionality. You can use well-known console.log syntax. Each console.log call will be automatically substituted with _JS LogFlush_ functionality. This mean you don't need to touch already written JavaScript code containing console.log calls. _JS LogFlush_ starts to work immediately once you include its processing script in your web page.
* **Automated and invisible**. No need to place special button/link/etc on your web page to have possibility to save/download prepared logs. Instead of outputting logs into browser's console, _JS LogFlush_ save all logged data on your server automatically and invisibly for end user so he/she couldn't even guess about such hidden work. However you have to remember: it's only for debug purposes. You should not use it on live (production) sites.
* **Lightweight and dependency-free**. The only requirement is standard PHP 5+ configuration on server side as well as JavaScript support on client.


How it works
-------------
As mentioned above, _JS LogFlush_ consist of two parts: client-side and server-side.

**_The client-side part_** (generated by the server-side part) is embedded as JavaScript code in a web page. It stores all logged data in some buffer and send (flush) its content to the server-side part as the buffer is about to be overflowed. The flushed content will be saved/appended in appropriate file on server.

**_The server-side part_** is a PHP script which processes two types of requests: _initializing_ and _flushing_. _Initializing request_ is sent when you include processing script in a web page. Each init request start/create a new log session with unique ID. Depending on that ID the processing script generates the code of client-side part. Also log session ID is sent with each flushing request to identify the log session which a request belongs to.


Usage Sample
-------------
Server-side processing script (must be accessible on the web):

``` php
// include JsLogFlush class source file
require 'JsLogFlush.php';

// hash of configuration options to be passed into JsLogFlush constructor.
// See the list of available options in phpDoc for JsLogFlush constructor.
// Note that each option has default value defined in JsLogFlush.
// So you can omit any of them or even pass empty value instead of hash.
$cfg = array(
    'interval' => 1,
    'expire' => 0.5,
);

// instantiate JsLogFlush class
$obj = new JsLogFlush($cfg);

// call process() method - entry point of JsLogFlush class
$ret = $obj->process();

if ($ret) {
    // if result is non-empty output it as JavaScript code
    header('Content-Type: text/javascript');
    echo $ret;
}
```

Some web page located anywhere (even on different domain from your processing script):

``` html
<html>
    <head>
        <script type="text/javascript" src="//somedomain.com/path_to_your_processing_script.php?buffSize=1000&logTimeshifts=1"></script>
    </head>
    <body>
    ...
    <script type="text/javascript">
        ...
        console.log('some text/data');
        ...
    </script>
    </body>
<html>
```


Browser Compatibility
-------------
Client-side part of _JS LogFlush_ (javascript code) written mainly in pure JS and only use few standard DOM methods (for cross domain AJAX requests) as well as standardized Web API (for same domain AJAX requests). It doesn't use browser-specific syntax except same domain requests implementation (XMLHttpRequest/Microsoft.XMLHTTP). So theoretically it could work in any browser, even such ancient one as IE 5.0.


License
-------------
* [GPL v2](http://opensource.org/licenses/GPL-2.0)
