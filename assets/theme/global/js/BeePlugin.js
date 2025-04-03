"use strict";
! function (e) {
    if (!window.BeePlugin) {
        var t = function () {
                var e = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                return Array.apply(null, Array(50)).map(function () {
                    return e[Math.floor(Math.random() * e.length)]
                }).join("")
            },
            n = e.createElement("script");
        n.type = "text/javascript", n.src = "https://loader.getbee.io/v1/api/loader?v=" + t(), e.getElementsByTagName("head")[0].appendChild(n);
        var r = {};
        r._queue = [];
        for (var a = "create".split(","), u = function (e, t) {
                return function () {
                    t.push([e, arguments])
                }
            }, i = 0; i < a.length; i++) r[a[i]] = u(a[i], r._queue);
        window.BeePlugin = r
    }
}(document);