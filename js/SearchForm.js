/*
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2013 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.9.0
 *
 */

! function (e, t, o, i) {
    var n = e(t);
    e.fn.lazyload = function (r) {
            function f() {
                var t = 0;
                a.each(function () {
                    var o = e(this);
                    if (!h.skip_invisible || o.is(":visible"))
                        if (e.abovethetop(this, h) || e.leftofbegin(this, h))
                        ;
                        else if (e.belowthefold(this, h) || e.rightoffold(this, h)) {
                        if (++t > h.failure_limit)
                            return !1
                    } else
                        o.trigger("appear"),
                        t = 0
                })
            }
            var l, a = this,
                h = {
                    threshold: 0,
                    failure_limit: 0,
                    event: "scroll.lazyloading",
                    effect: "show",
                    container: t,
                    data_attribute: "original",
                    skip_invisible: !0,
                    appear: null,
                    load: null,
                    placeholder: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"
                };
            return r && (i !== r.failurelimit && (r.failure_limit = r.failurelimit,
                        delete r.failurelimit),
                    i !== r.effectspeed && (r.effect_speed = r.effectspeed,
                        delete r.effectspeed),
                    e.extend(h, r)),
                l = h.container === i || h.container === t ? n : e(h.container),
                0 === h.event.indexOf("scroll") && l.bind(h.event, function () {
                    return f()
                }),
                this.each(function () {
                    var t = this,
                        o = e(t);
                    t.loaded = !1,
                        (o.attr("src") === i || o.attr("src") === !1) && o.attr("src", h.placeholder),
                        o.on("appear", function () {
                            if (!this.loaded) {
                                if (h.appear) {
                                    var i = a.length;
                                    h.appear.call(t, i, h)
                                }
                                e("<img />").bind("load", function () {
                                    var i = o.data(h.data_attribute);
                                    o.hide(),
                                        o.is("img") ? o.attr("src", i) : o.css("background-image", "url('" + i + "')"),
                                        o[h.effect](h.effect_speed),
                                        t.loaded = !0;
                                    var n = e.grep(a, function (e) {
                                        return !e.loaded
                                    });
                                    if (a = e(n),
                                        h.load) {
                                        var r = a.length;
                                        h.load.call(t, r, h)
                                    }
                                }).bind('error', function () {
                                    o.trigger('error');
                                }).attr("src", o.data(h.data_attribute))
                            }
                        }),
                        0 !== h.event.indexOf("scroll") && o.bind(h.event, function () {
                            t.loaded || o.trigger("appear")
                        })
                }),
                n.bind("resize", function () {
                    f()
                }),
                /iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion) && n.bind("pageshow", function (t) {
                    t.originalEvent && t.originalEvent.persisted && a.each(function () {
                        e(this).trigger("appear")
                    })
                }),
                e(o).ready(function () {
                    f()
                }),
                this
        },
        e.belowthefold = function (o, r) {
            var f;
            return f = r.container === i || r.container === t ? (t.innerHeight ? t.innerHeight : n.height()) + n.scrollTop() : e(r.container).offset().top + e(r.container).height(),
                f <= e(o).offset().top - r.threshold
        },
        e.rightoffold = function (o, r) {
            var f;
            return f = r.container === i || r.container === t ? n.width() + n.scrollLeft() : e(r.container).offset().left + e(r.container).width(),
                f <= e(o).offset().left - r.threshold
        },
        e.abovethetop = function (o, r) {
            var f;
            return f = r.container === i || r.container === t ? n.scrollTop() : e(r.container).offset().top,
                f >= e(o).offset().top + r.threshold + e(o).height()
        },
        e.leftofbegin = function (o, r) {
            var f;
            return f = r.container === i || r.container === t ? n.scrollLeft() : e(r.container).offset().left,
                f >= e(o).offset().left + r.threshold + e(o).width()
        },
        e.inviewport = function (t, o) {
            return !(e.rightoffold(t, o) || e.leftofbegin(t, o) || e.belowthefold(t, o) || e.abovethetop(t, o))
        },
        e.extend(e.expr[":"], {
            "below-the-fold": function (t) {
                return e.belowthefold(t, {
                    threshold: 0
                })
            },
            "above-the-top": function (t) {
                return !e.belowthefold(t, {
                    threshold: 0
                })
            },
            "right-of-screen": function (t) {
                return e.rightoffold(t, {
                    threshold: 0
                })
            },
            "left-of-screen": function (t) {
                return !e.rightoffold(t, {
                    threshold: 0
                })
            },
            "in-viewport": function (t) {
                return e.inviewport(t, {
                    threshold: 0
                })
            },
            "above-the-fold": function (t) {
                return !e.belowthefold(t, {
                    threshold: 0
                })
            },
            "right-of-fold": function (t) {
                return e.rightoffold(t, {
                    threshold: 0
                })
            },
            "left-of-fold": function (t) {
                return !e.rightoffold(t, {
                    threshold: 0
                })
            }
        })
}(jQuery, window, document);

// moment.js
// version : 2.0.0
// author : Tim Wood
// license : MIT
// momentjs.com

(function (t) {
    function n(t, n) {
        return function (e) {
            return u(t.call(this, e), n)
        }
    }

    function e(t) {
        return function (n) {
            return this.lang().ordinal(t.call(this, n))
        }
    }

    function s() {}

    function r(t) {
        i(this, t)
    }

    function a(t) {
        var n = this._data = {},
            e = t.years || t.year || t.y || 0,
            s = t.months || t.month || t.M || 0,
            r = t.weeks || t.week || t.w || 0,
            a = t.days || t.day || t.d || 0,
            i = t.hours || t.hour || t.h || 0,
            u = t.minutes || t.minute || t.m || 0,
            d = t.seconds || t.second || t.s || 0,
            c = t.milliseconds || t.millisecond || t.ms || 0;
        this._milliseconds = c + 1e3 * d + 6e4 * u + 36e5 * i,
            this._days = a + 7 * r,
            this._months = s + 12 * e,
            n.milliseconds = c % 1e3,
            d += o(c / 1e3),
            n.seconds = d % 60,
            u += o(d / 60),
            n.minutes = u % 60,
            i += o(u / 60),
            n.hours = i % 24,
            a += o(i / 24),
            a += 7 * r,
            n.days = a % 30,
            s += o(a / 30),
            n.months = s % 12,
            e += o(s / 12),
            n.years = e
    }

    function i(t, n) {
        for (var e in n)
            n.hasOwnProperty(e) && (t[e] = n[e]);
        return t
    }

    function o(t) {
        return 0 > t ? Math.ceil(t) : Math.floor(t)
    }

    function u(t, n) {
        for (var e = t + ""; e.length < n;)
            e = "0" + e;
        return e
    }

    function d(t, n, e) {
        var s, r = n._milliseconds,
            a = n._days,
            i = n._months;
        r && t._d.setTime(+t + r * e),
            a && t.date(t.date() + a * e),
            i && (s = t.date(),
                t.date(1).month(t.month() + i * e).date(Math.min(s, t.daysInMonth())))
    }

    function c(t) {
        return "[object Array]" === Object.prototype.toString.call(t)
    }

    function h(t, n) {
        var e, s = Math.min(t.length, n.length),
            r = Math.abs(t.length - n.length),
            a = 0;
        for (e = 0; s > e; e++)
            ~~t[e] !== ~~n[e] && a++;
        return a + r
    }

    function f(t, n) {
        return n.abbr = t,
            x[t] || (x[t] = new s),
            x[t].set(n),
            x[t]
    }

    function l(t) {
        return t ? (!x[t] && W && require("./lang/" + t),
            x[t]) : O.fn._lang
    }

    function _(t) {
        return t.match(/\[.*\]/) ? t.replace(/^\[|\]$/g, "") : t.replace(/\\/g, "")
    }

    function m(t) {
        var n, e, s = t.match(A);
        for (n = 0,
            e = s.length; e > n; n++)
            rt[s[n]] ? s[n] = rt[s[n]] : s[n] = _(s[n]);
        return function (r) {
            var a = "";
            for (n = 0; e > n; n++)
                a += "function" == typeof s[n].call ? s[n].call(r, t) : s[n];
            return a
        }
    }

    function y(t, n) {
        function e(n) {
            return t.lang().longDateFormat(n) || n
        }
        for (var s = 5; s-- && P.test(n);)
            n = n.replace(P, e);
        return nt[n] || (nt[n] = m(n)),
            nt[n](t)
    }

    function M(t) {
        switch (t) {
            case "DDDD":
                return E;
            case "YYYY":
                return N;
            case "YYYYY":
                return $;
            case "S":
            case "SS":
            case "SSS":
            case "DDD":
                return J;
            case "MMM":
            case "MMMM":
            case "dd":
            case "ddd":
            case "dddd":
            case "a":
            case "A":
                return I;
            case "X":
                return R;
            case "Z":
            case "ZZ":
                return X;
            case "T":
                return j;
            case "MM":
            case "DD":
            case "YY":
            case "HH":
            case "hh":
            case "mm":
            case "ss":
            case "M":
            case "D":
            case "d":
            case "H":
            case "h":
            case "m":
            case "s":
                return V;
            default:
                return new RegExp(t.replace("\\", ""))
        }
    }

    function D(t, n, e) {
        var s, r = e._a;
        switch (t) {
            case "M":
            case "MM":
                r[1] = null == n ? 0 : ~~n - 1;
                break;
            case "MMM":
            case "MMMM":
                s = l(e._l).monthsParse(n),
                    null != s ? r[1] = s : e._isValid = !1;
                break;
            case "D":
            case "DD":
            case "DDD":
            case "DDDD":
                null != n && (r[2] = ~~n);
                break;
            case "YY":
                r[0] = ~~n + (~~n > 68 ? 1900 : 2e3);
                break;
            case "YYYY":
            case "YYYYY":
                r[0] = ~~n;
                break;
            case "a":
            case "A":
                e._isPm = "pm" === (n + "").toLowerCase();
                break;
            case "H":
            case "HH":
            case "h":
            case "hh":
                r[3] = ~~n;
                break;
            case "m":
            case "mm":
                r[4] = ~~n;
                break;
            case "s":
            case "ss":
                r[5] = ~~n;
                break;
            case "S":
            case "SS":
            case "SSS":
                r[6] = ~~(1e3 * ("0." + n));
                break;
            case "X":
                e._d = new Date(1e3 * parseFloat(n));
                break;
            case "Z":
            case "ZZ":
                e._useUTC = !0,
                    s = (n + "").match(K),
                    s && s[1] && (e._tzh = ~~s[1]),
                    s && s[2] && (e._tzm = ~~s[2]),
                    s && "+" === s[0] && (e._tzh = -e._tzh,
                        e._tzm = -e._tzm)
        }
        null == n && (e._isValid = !1)
    }

    function p(t) {
        var n, e, s = [];
        if (!t._d) {
            for (n = 0; 7 > n; n++)
                t._a[n] = s[n] = null == t._a[n] ? 2 === n ? 1 : 0 : t._a[n];
            s[3] += t._tzh || 0,
                s[4] += t._tzm || 0,
                e = new Date(0),
                t._useUTC ? (e.setUTCFullYear(s[0], s[1], s[2]),
                    e.setUTCHours(s[3], s[4], s[5], s[6])) : (e.setFullYear(s[0], s[1], s[2]),
                    e.setHours(s[3], s[4], s[5], s[6])),
                t._d = e
        }
    }

    function Y(t) {
        var n, e, s = t._f.match(A),
            r = t._i;
        for (t._a = [],
            n = 0; n < s.length; n++)
            e = (M(s[n]).exec(r) || [])[0],
            e && (r = r.slice(r.indexOf(e) + e.length)),
            rt[s[n]] && D(s[n], e, t);
        t._isPm && t._a[3] < 12 && (t._a[3] += 12),
            t._isPm === !1 && 12 === t._a[3] && (t._a[3] = 0),
            p(t)
    }

    function g(t) {
        for (var n, e, s, a, o = 99; t._f.length;) {
            if (n = i({}, t),
                n._f = t._f.pop(),
                Y(n),
                e = new r(n),
                e.isValid()) {
                s = e;
                break
            }
            a = h(n._a, e.toArray()),
                o > a && (o = a,
                    s = e)
        }
        i(t, s)
    }

    function w(t) {
        var n, e = t._i;
        if (q.exec(e)) {
            for (t._f = "YYYY-MM-DDT",
                n = 0; 4 > n; n++)
                if (G[n][1].exec(e)) {
                    t._f += G[n][0];
                    break
                }
            X.exec(e) && (t._f += " Z"),
                Y(t)
        } else
            t._d = new Date(e)
    }

    function T(n) {
        var e = n._i,
            s = Z.exec(e);
        e === t ? n._d = new Date : s ? n._d = new Date(+s[1]) : "string" == typeof e ? w(n) : c(e) ? (n._a = e.slice(0),
            p(n)) : n._d = e instanceof Date ? new Date(+e) : new Date(e)
    }

    function v(t, n, e, s, r) {
        return r.relativeTime(n || 1, !!e, t, s)
    }

    function k(t, n, e) {
        var s = U(Math.abs(t) / 1e3),
            r = U(s / 60),
            a = U(r / 60),
            i = U(a / 24),
            o = U(i / 365),
            u = 45 > s && ["s", s] || 1 === r && ["m"] || 45 > r && ["mm", r] || 1 === a && ["h"] || 22 > a && ["hh", a] || 1 === i && ["d"] || 25 >= i && ["dd", i] || 45 >= i && ["M"] || 345 > i && ["MM", U(i / 30)] || 1 === o && ["y"] || ["yy", o];
        return u[2] = n,
            u[3] = t > 0,
            u[4] = e,
            v.apply({}, u)
    }

    function S(t, n, e) {
        var s = e - n,
            r = e - t.day();
        return r > s && (r -= 7),
            s - 7 > r && (r += 7),
            Math.ceil(O(t).add("d", r).dayOfYear() / 7)
    }

    function b(t) {
        var n = t._i,
            e = t._f;
        return null === n || "" === n ? null : ("string" == typeof n && (t._i = n = l().preparse(n)),
            O.isMoment(n) ? (t = i({}, n),
                t._d = new Date(+n._d)) : e ? c(e) ? g(t) : Y(t) : T(t),
            new r(t))
    }

    function F(t, n) {
        O.fn[t] = O.fn[t + "s"] = function (t) {
            var e = this._isUTC ? "UTC" : "";
            return null != t ? (this._d["set" + e + n](t),
                this) : this._d["get" + e + n]()
        }
    }

    function H(t) {
        O.duration.fn[t] = function () {
            return this._data[t]
        }
    }

    function L(t, n) {
        O.duration.fn["as" + t] = function () {
            return +this / n
        }
    }
    for (var O, z, C = "2.0.0", U = Math.round, x = {}, W = "undefined" != typeof module && module.exports, Z = /^\/?Date\((\-?\d+)/i, A = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|YYYYY|YYYY|YY|a|A|hh?|HH?|mm?|ss?|SS?S?|X|zz?|ZZ?|.)/g, P = /(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g, V = /\d\d?/, J = /\d{1,3}/, E = /\d{3}/, N = /\d{1,4}/, $ = /[+\-]?\d{1,6}/, I = /[0-9]*[a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF]+\s*?[\u0600-\u06FF]+/i, X = /Z|[\+\-]\d\d:?\d\d/i, j = /T/i, R = /[\+\-]?\d+(\.\d{1,3})?/, q = /^\s*\d{4}-\d\d-\d\d((T| )(\d\d(:\d\d(:\d\d(\.\d\d?\d?)?)?)?)?([\+\-]\d\d:?\d\d)?)?/, B = "YYYY-MM-DDTHH:mm:ssZ", G = [
            ["HH:mm:ss.S", /(T| )\d\d:\d\d:\d\d\.\d{1,3}/],
            ["HH:mm:ss", /(T| )\d\d:\d\d:\d\d/],
            ["HH:mm", /(T| )\d\d:\d\d/],
            ["HH", /(T| )\d\d/]
        ], K = /([\+\-]|\d\d)/gi, Q = "Month|Date|Hours|Minutes|Seconds|Milliseconds".split("|"), tt = {
            Milliseconds: 1,
            Seconds: 1e3,
            Minutes: 6e4,
            Hours: 36e5,
            Days: 864e5,
            Months: 2592e6,
            Years: 31536e6
        }, nt = {}, et = "DDD w W M D d".split(" "), st = "M D H h m s w W".split(" "), rt = {
            M: function () {
                return this.month() + 1
            },
            MMM: function (t) {
                return this.lang().monthsShort(this, t)
            },
            MMMM: function (t) {
                return this.lang().months(this, t)
            },
            D: function () {
                return this.date()
            },
            DDD: function () {
                return this.dayOfYear()
            },
            d: function () {
                return this.day()
            },
            dd: function (t) {
                return this.lang().weekdaysMin(this, t)
            },
            ddd: function (t) {
                return this.lang().weekdaysShort(this, t)
            },
            dddd: function (t) {
                return this.lang().weekdays(this, t)
            },
            w: function () {
                return this.week()
            },
            W: function () {
                return this.isoWeek()
            },
            YY: function () {
                return u(this.year() % 100, 2)
            },
            YYYY: function () {
                return u(this.year(), 4)
            },
            YYYYY: function () {
                return u(this.year(), 5)
            },
            a: function () {
                return this.lang().meridiem(this.hours(), this.minutes(), !0)
            },
            A: function () {
                return this.lang().meridiem(this.hours(), this.minutes(), !1)
            },
            H: function () {
                return this.hours()
            },
            h: function () {
                return this.hours() % 12 || 12
            },
            m: function () {
                return this.minutes()
            },
            s: function () {
                return this.seconds()
            },
            S: function () {
                return ~~(this.milliseconds() / 100)
            },
            SS: function () {
                return u(~~(this.milliseconds() / 10), 2)
            },
            SSS: function () {
                return u(this.milliseconds(), 3)
            },
            Z: function () {
                var t = -this.zone(),
                    n = "+";
                return 0 > t && (t = -t,
                        n = "-"),
                    n + u(~~(t / 60), 2) + ":" + u(~~t % 60, 2)
            },
            ZZ: function () {
                var t = -this.zone(),
                    n = "+";
                return 0 > t && (t = -t,
                        n = "-"),
                    n + u(~~(10 * t / 6), 4)
            },
            X: function () {
                return this.unix()
            }
        }; et.length;)
        z = et.pop(),
        rt[z + "o"] = e(rt[z]);
    for (; st.length;)
        z = st.pop(),
        rt[z + z] = n(rt[z], 2);
    for (rt.DDDD = n(rt.DDD, 3),
        s.prototype = {
            set: function (t) {
                var n, e;
                for (e in t)
                    n = t[e],
                    "function" == typeof n ? this[e] = n : this["_" + e] = n
            },
            _months: "January_February_March_April_May_June_July_August_September_October_November_December".split("_"),
            months: function (t) {
                return this._months[t.month()]
            },
            _monthsShort: "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),
            monthsShort: function (t) {
                return this._monthsShort[t.month()]
            },
            monthsParse: function (t) {
                var n, e, s;
                for (this._monthsParse || (this._monthsParse = []),
                    n = 0; 12 > n; n++)
                    if (this._monthsParse[n] || (e = O([2e3, n]),
                            s = "^" + this.months(e, "") + "|^" + this.monthsShort(e, ""),
                            this._monthsParse[n] = new RegExp(s.replace(".", ""), "i")),
                        this._monthsParse[n].test(t))
                        return n
            },
            _weekdays: "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),
            weekdays: function (t) {
                return this._weekdays[t.day()]
            },
            _weekdaysShort: "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),
            weekdaysShort: function (t) {
                return this._weekdaysShort[t.day()]
            },
            _weekdaysMin: "Su_Mo_Tu_We_Th_Fr_Sa".split("_"),
            weekdaysMin: function (t) {
                return this._weekdaysMin[t.day()]
            },
            _longDateFormat: {
                LT: "h:mm A",
                L: "MM/DD/YYYY",
                LL: "MMMM D YYYY",
                LLL: "MMMM D YYYY LT",
                LLLL: "dddd, MMMM D YYYY LT"
            },
            longDateFormat: function (t) {
                var n = this._longDateFormat[t];
                return !n && this._longDateFormat[t.toUpperCase()] && (n = this._longDateFormat[t.toUpperCase()].replace(/MMMM|MM|DD|dddd/g, function (t) {
                            return t.slice(1)
                        }),
                        this._longDateFormat[t] = n),
                    n
            },
            meridiem: function (t, n, e) {
                return t > 11 ? e ? "pm" : "PM" : e ? "am" : "AM"
            },
            _calendar: {
                sameDay: "[Today at] LT",
                nextDay: "[Tomorrow at] LT",
                nextWeek: "dddd [at] LT",
                lastDay: "[Yesterday at] LT",
                lastWeek: "[last] dddd [at] LT",
                sameElse: "L"
            },
            calendar: function (t, n) {
                var e = this._calendar[t];
                return "function" == typeof e ? e.apply(n) : e
            },
            _relativeTime: {
                future: "in %s",
                past: "%s ago",
                s: "a few seconds",
                m: "a minute",
                mm: "%d minutes",
                h: "an hour",
                hh: "%d hours",
                d: "a day",
                dd: "%d days",
                M: "a month",
                MM: "%d months",
                y: "a year",
                yy: "%d years"
            },
            relativeTime: function (t, n, e, s) {
                var r = this._relativeTime[e];
                return "function" == typeof r ? r(t, n, e, s) : r.replace(/%d/i, t)
            },
            pastFuture: function (t, n) {
                var e = this._relativeTime[t > 0 ? "future" : "past"];
                return "function" == typeof e ? e(n) : e.replace(/%s/i, n)
            },
            ordinal: function (t) {
                return this._ordinal.replace("%d", t)
            },
            _ordinal: "%d",
            preparse: function (t) {
                return t
            },
            postformat: function (t) {
                return t
            },
            week: function (t) {
                return S(t, this._week.dow, this._week.doy)
            },
            _week: {
                dow: 0,
                doy: 6
            }
        },
        O = function (t, n, e) {
            return b({
                _i: t,
                _f: n,
                _l: e,
                _isUTC: !1
            })
        },
        O.utc = function (t, n, e) {
            return b({
                _useUTC: !0,
                _isUTC: !0,
                _l: e,
                _i: t,
                _f: n
            })
        },
        O.unix = function (t) {
            return O(1e3 * t)
        },
        O.duration = function (t, n) {
            var e, s = O.isDuration(t),
                r = "number" == typeof t,
                i = s ? t._data : r ? {} : t;
            return r && (n ? i[n] = t : i.milliseconds = t),
                e = new a(i),
                s && t.hasOwnProperty("_lang") && (e._lang = t._lang),
                e
        },
        O.version = C,
        O.defaultFormat = B,
        O.lang = function (t, n) {
            return t ? (n ? f(t, n) : x[t] || l(t),
                void(O.duration.fn._lang = O.fn._lang = l(t))) : O.fn._lang._abbr
        },
        O.langData = function (t) {
            return t && t._lang && t._lang._abbr && (t = t._lang._abbr),
                l(t)
        },
        O.isMoment = function (t) {
            return t instanceof r
        },
        O.isDuration = function (t) {
            return t instanceof a
        },
        O.fn = r.prototype = {
            clone: function () {
                return O(this)
            },
            valueOf: function () {
                return +this._d
            },
            unix: function () {
                return Math.floor(+this._d / 1e3)
            },
            toString: function () {
                return this.format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")
            },
            toDate: function () {
                return this._d
            },
            toJSON: function () {
                return O.utc(this).format("YYYY-MM-DD[T]HH:mm:ss.SSS[Z]")
            },
            toArray: function () {
                var t = this;
                return [t.year(), t.month(), t.date(), t.hours(), t.minutes(), t.seconds(), t.milliseconds()]
            },
            isValid: function () {
                return null == this._isValid && (this._a ? this._isValid = !h(this._a, (this._isUTC ? O.utc(this._a) : O(this._a)).toArray()) : this._isValid = !isNaN(this._d.getTime())),
                    !!this._isValid
            },
            utc: function () {
                return this._isUTC = !0,
                    this
            },
            local: function () {
                return this._isUTC = !1,
                    this
            },
            format: function (t) {
                var n = y(this, t || O.defaultFormat);
                return this.lang().postformat(n)
            },
            add: function (t, n) {
                var e;
                return e = "string" == typeof t ? O.duration(+n, t) : O.duration(t, n),
                    d(this, e, 1),
                    this
            },
            subtract: function (t, n) {
                var e;
                return e = "string" == typeof t ? O.duration(+n, t) : O.duration(t, n),
                    d(this, e, -1),
                    this
            },
            diff: function (t, n, e) {
                var s, r, a = this._isUTC ? O(t).utc() : O(t).local(),
                    i = 6e4 * (this.zone() - a.zone());
                return n && (n = n.replace(/s$/, "")),
                    "year" === n || "month" === n ? (s = 432e5 * (this.daysInMonth() + a.daysInMonth()),
                        r = 12 * (this.year() - a.year()) + (this.month() - a.month()),
                        r += (this - O(this).startOf("month") - (a - O(a).startOf("month"))) / s,
                        "year" === n && (r /= 12)) : (s = this - a - i,
                        r = "second" === n ? s / 1e3 : "minute" === n ? s / 6e4 : "hour" === n ? s / 36e5 : "day" === n ? s / 864e5 : "week" === n ? s / 6048e5 : s),
                    e ? r : o(r)
            },
            from: function (t, n) {
                return O.duration(this.diff(t)).lang(this.lang()._abbr).humanize(!n)
            },
            fromNow: function (t) {
                return this.from(O(), t)
            },
            calendar: function () {
                var t = this.diff(O().startOf("day"), "days", !0),
                    n = -6 > t ? "sameElse" : -1 > t ? "lastWeek" : 0 > t ? "lastDay" : 1 > t ? "sameDay" : 2 > t ? "nextDay" : 7 > t ? "nextWeek" : "sameElse";
                return this.format(this.lang().calendar(n, this))
            },
            isLeapYear: function () {
                var t = this.year();
                return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
            },
            isDST: function () {
                return this.zone() < O([this.year()]).zone() || this.zone() < O([this.year(), 5]).zone()
            },
            day: function (t) {
                var n = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
                return null == t ? n : this.add({
                    d: t - n
                })
            },
            startOf: function (t) {
                switch (t = t.replace(/s$/, "")) {
                    case "year":
                        this.month(0);
                    case "month":
                        this.date(1);
                    case "week":
                    case "day":
                        this.hours(0);
                    case "hour":
                        this.minutes(0);
                    case "minute":
                        this.seconds(0);
                    case "second":
                        this.milliseconds(0)
                }
                return "week" === t && this.day(0),
                    this
            },
            endOf: function (t) {
                return this.startOf(t).add(t.replace(/s?$/, "s"), 1).subtract("ms", 1)
            },
            isAfter: function (t, n) {
                return n = "undefined" != typeof n ? n : "millisecond",
                    +this.clone().startOf(n) > +O(t).startOf(n)
            },
            isBefore: function (t, n) {
                return n = "undefined" != typeof n ? n : "millisecond",
                    +this.clone().startOf(n) < +O(t).startOf(n)
            },
            isSame: function (t, n) {
                return n = "undefined" != typeof n ? n : "millisecond",
                    +this.clone().startOf(n) === +O(t).startOf(n)
            },
            zone: function () {
                return this._isUTC ? 0 : this._d.getTimezoneOffset()
            },
            daysInMonth: function () {
                return O.utc([this.year(), this.month() + 1, 0]).date()
            },
            dayOfYear: function (t) {
                var n = U((O(this).startOf("day") - O(this).startOf("year")) / 864e5) + 1;
                return null == t ? n : this.add("d", t - n)
            },
            isoWeek: function (t) {
                var n = S(this, 1, 4);
                return null == t ? n : this.add("d", 7 * (t - n))
            },
            week: function (t) {
                var n = this.lang().week(this);
                return null == t ? n : this.add("d", 7 * (t - n))
            },
            lang: function (n) {
                return n === t ? this._lang : (this._lang = l(n),
                    this)
            }
        },
        z = 0; z < Q.length; z++)
        F(Q[z].toLowerCase().replace(/s$/, ""), Q[z]);
    F("year", "FullYear"),
        O.fn.days = O.fn.day,
        O.fn.weeks = O.fn.week,
        O.fn.isoWeeks = O.fn.isoWeek,
        O.duration.fn = a.prototype = {
            weeks: function () {
                return o(this.days() / 7)
            },
            valueOf: function () {
                return this._milliseconds + 864e5 * this._days + 2592e6 * this._months
            },
            humanize: function (t) {
                var n = +this,
                    e = k(n, !t, this.lang());
                return t && (e = this.lang().pastFuture(n, e)),
                    this.lang().postformat(e)
            },
            lang: O.fn.lang
        };
    for (z in tt)
        tt.hasOwnProperty(z) && (L(z, tt[z]),
            H(z.toLowerCase()));
    L("Weeks", 6048e5),
        O.lang("en", {
            ordinal: function (t) {
                var n = t % 10,
                    e = 1 === ~~(t % 100 / 10) ? "th" : 1 === n ? "st" : 2 === n ? "nd" : 3 === n ? "rd" : "th";
                return t + e
            }
        }),
        W && (module.exports = O),
        "undefined" == typeof ender && (this.moment = O),
        "function" == typeof define && define.amd && define("moment", [], function () {
            return O
        })
}).call(this);

! function (t) {
    "use strict";

    function e(e, i) {
        this.$element = t(e),
            this.options = t.extend({}, this.defaults, i),
            this.options.multiple = "multiple" == this.$element.attr("multiple"),
            this.$button = t('<button type="button" class="selectable dropdown-toggle ' + this.options.buttonClass + '" data-toggle="dropdown">' + this.options.buttonText(this.getSelectedOptions(), this.$element) + "</button>"),
            this.$dropdown = t('<div class="selectable-container dropdown-menu"></div>'),
            this.options.multiple ? this.$dropdown.addClass("multi") : this.$dropdown.addClass("single"),
            this.$optionsContainer = t('<div class="options-container" />'),
            this.initOptions(),
            this.options.showChosenBox && (this.$optionsSummary = t("<fieldset />").addClass("options-summary").append(t("<legend />").text(this.options.chosenFieldsetLabel)).hide().appendTo(this.$optionsContainer)),
            this.$dropdown.append(this.$optionsContainer),
            this.options.enableFiltering && this.initFilter(),
            this.$container = t(this.options.buttonContainer).append(this.$button).append(this.$dropdown),
            this.bindEvents(),
            this.options.showChosenBox && this.fillChosenBox(),
            this.$element.hide().after(this.$container)
    }
    Array.prototype.indexOf || (Array.prototype.indexOf = function (t) {
            if (null == this)
                throw new TypeError;
            var e, i, n = Object(this),
                o = n.length >>> 0;
            if (0 === o)
                return -1;
            if (e = 0,
                arguments.length > 1 && (e = Number(arguments[1]),
                    e != e ? e = 0 : 0 != e && e != 1 / 0 && e != -(1 / 0) && (e = (e > 0 || -1) * Math.floor(Math.abs(e)))),
                e >= o)
                return -1;
            for (i = e >= 0 ? e : Math.max(o - Math.abs(e), 0); o > i; i++)
                if (i in n && n[i] === t)
                    return i;
            return -1
        }),
        e.prototype = {
            constructor: e,
            defaults: {
                defaultValue: null,
                showChosenBox: !1,
                enableFiltering: !1,
                caseSensitiveFiltering: !1,
                showSelectAll: !1,
                preventInputChangeEvent: !0,
                buttonContainer: '<div class="btn-group selectable" />',
                buttonClass: "btn",
                selectedClass: "active",
                chosenFieldsetLabel: "Summary",
                noMatchInfo: "No matches found",
                buttonText: function (e, i) {
                    if (0 == e.length)
                        return 'None selected <b class="caret"></b>';
                    if (e.length > 3)
                        return e.length + ' selected <b class="caret"></b>';
                    var n = "";
                    return e.each(function () {
                            var e = void 0 !== t(this).attr("label") ? t(this).attr("label") : t(this).text();
                            n += e + ", "
                        }),
                        n.substr(0, n.length - 2) + ' <b class="caret"></b>'
                },
                afterChange: function (t, e) {},
                afterFilter: function (t, e) {},
                afterKeyPressed: function (t, e) {},
                afterListItemCreate: function (t, e) {
                    return e
                }
            },
            getSelectedOptions: function () {
                var e = [],
                    i = t("option:selected:not(:disabled)", this.$element);
                return i.filter(function (i) {
                    return this.value.length && -1 === t.inArray(this.value, e) ? (e.push(this.value),
                        !0) : !1
                })
            },
            getSelectedListItems: function () {
                var e = [],
                    i = this.$optionsContainer.find(":not(li:not(.option-disabled)").filter(function (e) {
                        return t(this).find("input").prop("checked")
                    });
                return i.filter(function (i) {
                    var n = t(this).find("input").attr("value");
                    return -1 === t.inArray(n, e) ? (e.push(n),
                        !0) : !1
                })
            },
            updateButtonText: function () {
                this.$button.html(this.options.buttonText(this.getSelectedOptions(), this.$element))
            },
            unselectAll: function () {
                this.$element.val(null !== this.options.defaultValue ? this.options.defaultValue : !1).trigger("changed.selectable")
            },
            fillChosenBox: function (e) {
                if (this.options.showChosenBox) {
                    this.$optionsSummary.children(":not(legend)").remove();
                    var e = e || this.getSelectedListItems();
                    if (e.length) {
                        for (var i = t("<ul />"), n = 0; n < e.length; n++)
                            t(e[n]).clone().removeClass("focused").appendTo(i);
                        this.$optionsSummary.append(i)
                    }
                    this.$optionsSummary.toggle(!!e.length)
                }
            },
            initFilter: function () {
                var e = t('<div class="options-filter-container" />'),
                    i = t('<input class="options-filter-input" type="text" />').appendTo(e);
                this.$dropdown.prepend(e),
                    i.on("keyup", t.proxy(function (n) {
                        9 != n.keyCode && 27 != n.keyCode || !this.$container.hasClass("open") ? 13 === n.keyCode && this.$optionsContainer.find(".focused").length ? this.$optionsContainer.find(".focused input").click() : (this.$dropdown.find(".options-filter-no-matches").remove(),
                            this.$optionsContainer.find("fieldset").each(t.proxy(function (e, n) {
                                t(n).find("li:not(.unavailable)").each(t.proxy(function (e, n) {
                                        var o = t(n),
                                            s = o.find("input"),
                                            a = !1;
                                        this.options.caseSensitiveFiltering && s.attr("value").indexOf(i.val()) >= 0 || !this.options.caseSensitiveFiltering && s.attr("value").toLowerCase().indexOf(i.val().toLowerCase()) >= 0 ? a = !0 : (this.options.caseSensitiveFiltering && o.find("label").text().indexOf(i.val()) >= 0 || !this.options.caseSensitiveFiltering && o.find("label").text().toLowerCase().indexOf(i.val().toLowerCase()) >= 0) && (a = !0),
                                            o.toggleClass("option-disabled", !a)
                                    }, this)),
                                    t(n).find("li:not(.option-disabled)").length ? t(n).show() : t(n).hide()
                            }, this)),
                            this.$optionsContainer.find(".ungrouped-list li:not(.unavailable)").each(t.proxy(function (e, n) {
                                var o = t(n),
                                    s = o.find("input"),
                                    a = !1;
                                this.options.caseSensitiveFiltering && s.attr("value").indexOf(i.val()) >= 0 || !this.options.caseSensitiveFiltering && s.attr("value").toLowerCase().indexOf(i.val().toLowerCase()) >= 0 ? a = !0 : (this.options.caseSensitiveFiltering && o.find("label").text().indexOf(i.val()) >= 0 || !this.options.caseSensitiveFiltering && o.find("label").text().toLowerCase().indexOf(i.val().toLowerCase()) >= 0) && (a = !0),
                                    o.toggleClass("option-disabled", !a)
                            }, this)),
                            this.$optionsContainer.find(".focused").length && !this.$optionsContainer.find(".focused:not(.option-disabled)").length && (this.removeListElementFocus(),
                                this.addFocusOnListElement(this.$optionsContainer.find("li:visible:first"))),
                            this.$optionsContainer.find("li:visible").length || t("<fieldset />").addClass("options-filter-no-matches").text(this.options.noMatchInfo).insertAfter(e),
                            this.options.afterFilter(i, this.$element)) : (i.blur(),
                            this.removeListElementFocus())
                    }, this)),
                    i.on("click change", function (t) {
                        t.stopPropagation()
                    }),
                    t(document).on("click.dropdown.data-api,", "[data-toggle=dropdown],[class=search-combobox]", t.proxy(function (t) {
                        t.target === this.$button[0] && this.$dropdown.is(":visible") && i.focus().select()
                    }, this))
            },
            initOptions: function () {
                var e = t("<ul />").addClass("ungrouped-list");
                this.$element.children().each(t.proxy(function (i, n) {
                    var o = t(n).prop("tagName").toLowerCase();
                    if ("optgroup" == o) {
                        var s = n,
                            a = t(s).prop("label"),
                            l = t("<fieldset />");
                        ("Alfabetycznie" === a || "A to Z" === a || "От А до Я" === a || "Abecedně" === a) && l.addClass("sort-alphabetically"),
                            l.append("<legend />").append("<ul />"),
                            t("legend", l).text(a),
                            t("option", s).each(t.proxy(function (t, e) {
                                this.createListItem(e, l.find("ul"))
                            }, this)),
                            this.$optionsContainer.append(l)
                    } else
                        "option" == o && this.createListItem(n, e);
                    e.find("li").length && this.$optionsContainer.append(e)
                }, this))
            },
            createListItem: function (e, i) {
                var i = i || this.$optionsContainer,
                    n = t(e),
                    o = n.data("label") || n.text(),
                    s = t("<li />").append(t("<label />").text(" " + o).prepend(t("<input />").attr("type", this.options.multiple ? "checkbox" : "radio").val(n.val()).prop("checked", n.is(":selected"))));
                n.is(":selected") && (n.attr("selected", "selected").prop("selected", !0),
                        this.options.selectedClass && s.addClass(this.options.selectedClass)),
                    s = this.options.afterListItemCreate(n, s),
                    i.append(s)
            },
            bindEvents: function () {
                t(this.$optionsContainer).on("click", function (e) {
                        e.stopPropagation(),
                            t(e.target).blur()
                    }),
                    this.$optionsContainer.on("change", ":input", t.proxy(function (e) {
                        var i = t(e.target),
                            n = i.prop("checked") || !1;
                        return this.options.multiple || this.$optionsContainer.find("input").not(i).prop("checked", !1),
                            this.options.selectedClass && (this.$optionsContainer.find('li input[value="' + i.val() + '"]').prop("checked", n).closest("li").toggleClass(this.options.selectedClass, n),
                                this.$optionsContainer.trigger("multiselect-extend-after", [i, "after!! text from select"]),
                                this.options.multiple || this.$optionsContainer.find('li input[value!="' + i.val() + '"]').prop("checked", !1).closest("li").toggleClass(this.options.selectedClass, !1)),
                            this.options.multiple ? this.$element.find('option[value="' + i.val() + '"]').prop("selected", n) : this.$element.val(i.val()),
                            this.$element.change(),
                            this.options.afterChange(i, this.$element),
                            this.options.multiple || this.$container.removeClass("open"),
                            this.options.preventInputChangeEvent ? !1 : void 0
                    }, this)),
                    this.$element.on("change", t.proxy(function (e) {
                        this.updateButtonText(),
                            this.fillChosenBox(),
                            this.refreshListSelection(),
                            this.options.afterChange(t(e.target), this.$element)
                    }, this)),
                    this.$element.on("changed.selectable", t.proxy(function (e) {
                        this.refreshListSelection(),
                            this.$element.change(),
                            this.options.afterChange(t(e.target), this.$element)
                    }, this)),
                    this.keysBinding()
            },
            refreshListSelection: function () {
                var e = this;
                t("option", this.$element).each(function () {
                    var i = t(this).prop("selected") || !1;
                    e.$optionsContainer.find('li input[value="' + t(this).attr("value") + '"]').prop("checked", i).closest("li").toggleClass(e.options.selectedClass, i)
                })
            },
            keysBinding: function () {
                this.$container.on("keydown", t.proxy(function (e) {
                    if (!t('input[type="text"]', this.$container).is(":focus") || 38 === e.keyCode || 40 === e.keyCode)
                        if (9 != e.keyCode && 27 != e.keyCode || !this.$container.hasClass("open")) {
                            var i = this.$optionsContainer.find("li:visible");
                            if (!i.length)
                                return;
                            var n = i.index(i.filter(".focused"));
                            if (38 == e.keyCode && n > 0 ? n-- : 38 == e.keyCode && 0 === n ? (this.removeListElementFocus(),
                                    n = -1) : 40 == e.keyCode && n < i.length - 1 && n++,
                                n >= 0) {
                                var o = i.eq(n);
                                if (this.removeListElementFocus(),
                                    this.addFocusOnListElement(o),
                                    32 == e.keyCode || !this.options.enableFiltering && 13 == e.keyCode) {
                                    var s = o.find("input");
                                    s.prop("checked", !s.prop("checked")),
                                        s.change()
                                }
                                this.options.afterKeyPressed(e, o),
                                    e.stopPropagation(),
                                    e.preventDefault()
                            }
                        } else
                            this.removeListElementFocus()
                }, this))
            },
            removeListElementFocus: function () {
                this.$optionsContainer.find("li").removeClass("focused"),
                    this.$optionsContainer.find("li").css("background")
            },
            addFocusOnListElement: function (t) {
                t.addClass("focused").focus()
            }
        },
        t.fn.selectable = function (i, n) {
            return this.each(function () {
                var o = t(this).data("select"),
                    s = "object" == typeof i && i;
                o || t(this).data("selectable", o = new e(this, s)),
                    "string" == typeof i && o[i](n)
            })
        },
        t.fn.selectable.Constructor = e
}(window.jQuery);
/**
 * DatePicker 1.0.0
 *
 * A jQuery-based DatePicker that provides an easy way of creating both single
 * and multi-viewed calendars capable of accepting single, range, and multiple
 * selected dates.  Easily styled with two example styles provided: an aractive
 * 'dark' style, and a Google Analytics-like 'clean' style.tt
 *
 * View project page for Examples and Documentation:
 * http://foxrunsoftware.github.com/DatePicker/
 *
 * This project is distinct from and not affiliated with the jquery.ui.datepicker.
 *
 * Copyright 2012, Justin Stern (www.foxrunsoftware.net)
 * Dual licensed under the MIT and GPL Version 2 licenses.
 *
 * Based on Work by Original Author: Stefan Petre www.eyecon.ro
 *
 * Depends:
 *   jquery.js
 */

! function (e) {
    var a = function () {
        var a = {
                years: "datepickerViewYears",
                moths: "datepickerViewMonths",
                days: "datepickerViewDays"
            },
            t = {
                wrapper: '<div class="datepicker"><div class="datepickerBorderT" /><div class="datepickerBorderB" /><div class="datepickerBorderL" /><div class="datepickerBorderR" /><div class="datepickerBorderTL" /><div class="datepickerBorderTR" /><div class="datepickerBorderBL" /><div class="datepickerBorderBR" /><div class="datepickerContainer"><table cellspacing="0" cellpadding="0"><tbody><tr></tr></tbody></table></div></div>',
                head: ['<td class="datepickerBlock">', '<table cellspacing="0" cellpadding="0">', "<thead>", "<tr>", '<th colspan="7"><a class="datepickerGoPrev" href="#"><i class="icon-arrow-left"></i></a>', '<a class="datepickerMonth" href="#"><span></span></a>', '<a class="datepickerGoNext" href="#"><i class="icon-arrow-right"></i></a></th>', "</tr>", '<tr class="datepickerDoW">', "<th><span><%=day1%></span></th>", "<th><span><%=day2%></span></th>", "<th><span><%=day3%></span></th>", "<th><span><%=day4%></span></th>", "<th><span><%=day5%></span></th>", "<th><span><%=day6%></span></th>", "<th><span><%=day7%></span></th>", "</tr>", "</thead>", "</table></td>"],
                space: '<td class="datepickerSpace"><div></div></td>',
                days: ['<tbody class="datepickerDays">', "<tr>", '<td class="<%=weeks[0].days[0].classname%>"><a href="#"><span><%=weeks[0].days[0].text%></span></a></td>', '<td class="<%=weeks[0].days[1].classname%>"><a href="#"><span><%=weeks[0].days[1].text%></span></a></td>', '<td class="<%=weeks[0].days[2].classname%>"><a href="#"><span><%=weeks[0].days[2].text%></span></a></td>', '<td class="<%=weeks[0].days[3].classname%>"><a href="#"><span><%=weeks[0].days[3].text%></span></a></td>', '<td class="<%=weeks[0].days[4].classname%>"><a href="#"><span><%=weeks[0].days[4].text%></span></a></td>', '<td class="<%=weeks[0].days[5].classname%>"><a href="#"><span><%=weeks[0].days[5].text%></span></a></td>', '<td class="<%=weeks[0].days[6].classname%>"><a href="#"><span><%=weeks[0].days[6].text%></span></a></td>', "</tr>", "<tr>", '<td class="<%=weeks[1].days[0].classname%>"><a href="#"><span><%=weeks[1].days[0].text%></span></a></td>', '<td class="<%=weeks[1].days[1].classname%>"><a href="#"><span><%=weeks[1].days[1].text%></span></a></td>', '<td class="<%=weeks[1].days[2].classname%>"><a href="#"><span><%=weeks[1].days[2].text%></span></a></td>', '<td class="<%=weeks[1].days[3].classname%>"><a href="#"><span><%=weeks[1].days[3].text%></span></a></td>', '<td class="<%=weeks[1].days[4].classname%>"><a href="#"><span><%=weeks[1].days[4].text%></span></a></td>', '<td class="<%=weeks[1].days[5].classname%>"><a href="#"><span><%=weeks[1].days[5].text%></span></a></td>', '<td class="<%=weeks[1].days[6].classname%>"><a href="#"><span><%=weeks[1].days[6].text%></span></a></td>', "</tr>", "<tr>", '<td class="<%=weeks[2].days[0].classname%>"><a href="#"><span><%=weeks[2].days[0].text%></span></a></td>', '<td class="<%=weeks[2].days[1].classname%>"><a href="#"><span><%=weeks[2].days[1].text%></span></a></td>', '<td class="<%=weeks[2].days[2].classname%>"><a href="#"><span><%=weeks[2].days[2].text%></span></a></td>', '<td class="<%=weeks[2].days[3].classname%>"><a href="#"><span><%=weeks[2].days[3].text%></span></a></td>', '<td class="<%=weeks[2].days[4].classname%>"><a href="#"><span><%=weeks[2].days[4].text%></span></a></td>', '<td class="<%=weeks[2].days[5].classname%>"><a href="#"><span><%=weeks[2].days[5].text%></span></a></td>', '<td class="<%=weeks[2].days[6].classname%>"><a href="#"><span><%=weeks[2].days[6].text%></span></a></td>', "</tr>", "<tr>", '<td class="<%=weeks[3].days[0].classname%>"><a href="#"><span><%=weeks[3].days[0].text%></span></a></td>', '<td class="<%=weeks[3].days[1].classname%>"><a href="#"><span><%=weeks[3].days[1].text%></span></a></td>', '<td class="<%=weeks[3].days[2].classname%>"><a href="#"><span><%=weeks[3].days[2].text%></span></a></td>', '<td class="<%=weeks[3].days[3].classname%>"><a href="#"><span><%=weeks[3].days[3].text%></span></a></td>', '<td class="<%=weeks[3].days[4].classname%>"><a href="#"><span><%=weeks[3].days[4].text%></span></a></td>', '<td class="<%=weeks[3].days[5].classname%>"><a href="#"><span><%=weeks[3].days[5].text%></span></a></td>', '<td class="<%=weeks[3].days[6].classname%>"><a href="#"><span><%=weeks[3].days[6].text%></span></a></td>', "</tr>", "<tr>", '<td class="<%=weeks[4].days[0].classname%>"><a href="#"><span><%=weeks[4].days[0].text%></span></a></td>', '<td class="<%=weeks[4].days[1].classname%>"><a href="#"><span><%=weeks[4].days[1].text%></span></a></td>', '<td class="<%=weeks[4].days[2].classname%>"><a href="#"><span><%=weeks[4].days[2].text%></span></a></td>', '<td class="<%=weeks[4].days[3].classname%>"><a href="#"><span><%=weeks[4].days[3].text%></span></a></td>', '<td class="<%=weeks[4].days[4].classname%>"><a href="#"><span><%=weeks[4].days[4].text%></span></a></td>', '<td class="<%=weeks[4].days[5].classname%>"><a href="#"><span><%=weeks[4].days[5].text%></span></a></td>', '<td class="<%=weeks[4].days[6].classname%>"><a href="#"><span><%=weeks[4].days[6].text%></span></a></td>', "</tr>", "<tr>", '<td class="<%=weeks[5].days[0].classname%>"><a href="#"><span><%=weeks[5].days[0].text%></span></a></td>', '<td class="<%=weeks[5].days[1].classname%>"><a href="#"><span><%=weeks[5].days[1].text%></span></a></td>', '<td class="<%=weeks[5].days[2].classname%>"><a href="#"><span><%=weeks[5].days[2].text%></span></a></td>', '<td class="<%=weeks[5].days[3].classname%>"><a href="#"><span><%=weeks[5].days[3].text%></span></a></td>', '<td class="<%=weeks[5].days[4].classname%>"><a href="#"><span><%=weeks[5].days[4].text%></span></a></td>', '<td class="<%=weeks[5].days[5].classname%>"><a href="#"><span><%=weeks[5].days[5].text%></span></a></td>', '<td class="<%=weeks[5].days[6].classname%>"><a href="#"><span><%=weeks[5].days[6].text%></span></a></td>', "</tr>", "</tbody>"],
                months: ['<tbody class="<%=className%>">', "<tr>", '<td colspan="2"><a href="#"><span><%=data[0]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[1]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[2]%></span></a></td>', '<td colspan="1"><a href="#"><span><%=data[3]%></span></a></td>', "</tr>", "<tr>", '<td colspan="2"><a href="#"><span><%=data[4]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[5]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[6]%></span></a></td>', '<td colspan="1"><a href="#"><span><%=data[7]%></span></a></td>', "</tr>", "<tr>", '<td colspan="2"><a href="#"><span><%=data[8]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[9]%></span></a></td>', '<td colspan="2"><a href="#"><span><%=data[10]%></span></a></td>', '<td colspan="1"><a href="#"><span><%=data[11]%></span></a></td>', "</tr>", "</tbody>"]
            },
            s = {
                date: null,
                current: null,
                inline: !1,
                mode: "single",
                calendars: 1,
                starts: 0,
                prev: "&#9664;",
                next: "&#9654;",
                view: "days",
                position: "bottom",
                showOn: "focus",
                onRenderCell: function () {
                    return {}
                },
                onChange: function () {},
                onBeforeShow: function () {
                    return !0
                },
                onAfterShow: function () {},
                onBeforeHide: function () {
                    return !0
                },
                onAfterHide: function () {},
                locale: {
                    daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                    monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"]
                },
                extraHeight: !1,
                extraWidth: !1,
                lastSel: !1
            },
            d = function (a) {
                var s, d, n, r, i, c, l, p, o = e(a).data("datepicker"),
                    h = e(a),
                    f = Math.floor(o.calendars / 2),
                    k = 0;
                h.find("td>table tbody").remove();
                for (var y = 0; y < o.calendars; y++) {
                    s = new Date(o.current),
                        s.addMonths(-f + y),
                        p = h.find("table").eq(y + 1),
                        0 == y && p.addClass("datepickerFirstView"),
                        y == o.calendars - 1 && p.addClass("datepickerLastView"),
                        p.hasClass("datepickerViewDays") ? n = s.getMonthName(!0) + ", " + s.getFullYear() : p.hasClass("datepickerViewMonths") ? n = s.getFullYear() : p.hasClass("datepickerViewYears") && (n = s.getFullYear() - 6 + " - " + (s.getFullYear() + 5)),
                        p.find("thead tr:first th a:eq(1) span").text(n),
                        n = s.getFullYear() - 6,
                        d = {
                            data: [],
                            className: "datepickerYears"
                        };
                    for (var u = 0; 12 > u; u++)
                        d.data.push(n + u);
                    l = tmpl(t.months.join(""), d),
                        s.setDate(1),
                        d = {
                            weeks: [],
                            test: 10
                        },
                        r = s.getMonth();
                    var n = (s.getDay() - o.starts) % 7;
                    for (s.addDays(-(n + (0 > n ? 7 : 0))),
                        k = 0; 42 > k;) {
                        i = parseInt(k / 7, 10),
                            c = k % 7,
                            d.weeks[i] || (d.weeks[i] = {
                                days: []
                            }),
                            d.weeks[i].days[c] = {
                                text: s.getDate(),
                                classname: []
                            };
                        var w = new Date;
                        w.getDate() == s.getDate() && w.getMonth() == s.getMonth() && w.getYear() == s.getYear() && d.weeks[i].days[c].classname.push("datepickerToday"),
                            s > w && d.weeks[i].days[c].classname.push("datepickerFuture"),
                            r != s.getMonth() && (d.weeks[i].days[c].classname.push("datepickerNotInMonth"),
                                d.weeks[i].days[c].classname.push("datepickerDisabled")),
                            0 == s.getDay() && d.weeks[i].days[c].classname.push("datepickerSunday"),
                            6 == s.getDay() && d.weeks[i].days[c].classname.push("datepickerSaturday");
                        var m = o.onRenderCell(a, s),
                            g = s.valueOf();
                        o.date && (!e.isArray(o.date) || o.date.length > 0) && (m.selected || o.date == g || e.inArray(g, o.date) > -1 || "range" == o.mode && g >= o.date[0] && g <= o.date[1]) && d.weeks[i].days[c].classname.push("datepickerSelected"),
                            m.disabled && d.weeks[i].days[c].classname.push("datepickerDisabled"),
                            m.className && d.weeks[i].days[c].classname.push(m.className),
                            d.weeks[i].days[c].classname = d.weeks[i].days[c].classname.join(" "),
                            k++,
                            s.addDays(1)
                    }
                    l = tmpl(t.days.join(""), d) + l,
                        d = {
                            data: o.locale.monthsShort,
                            className: "datepickerMonths"
                        },
                        l = tmpl(t.months.join(""), d) + l,
                        p.append(l)
                }
            },
            n = function (e) {
                Date.prototype.tempDate || (Date.prototype.tempDate = null,
                    Date.prototype.months = e.months,
                    Date.prototype.monthsShort = e.monthsShort,
                    Date.prototype.getMonthName = function (e) {
                        return this[e ? "months" : "monthsShort"][this.getMonth()]
                    },
                    Date.prototype.addDays = function (e) {
                        this.setDate(this.getDate() + e),
                            this.tempDate = this.getDate()
                    },
                    Date.prototype.addMonths = function (e) {
                        null == this.tempDate && (this.tempDate = this.getDate()),
                            this.setDate(1),
                            this.setMonth(this.getMonth() + e),
                            this.setDate(Math.min(this.tempDate, this.getMaxDays()))
                    },
                    Date.prototype.addYears = function (e) {
                        null == this.tempDate && (this.tempDate = this.getDate()),
                            this.setDate(1),
                            this.setFullYear(this.getFullYear() + e),
                            this.setDate(Math.min(this.tempDate, this.getMaxDays()))
                    },
                    Date.prototype.getMaxDays = function () {
                        var e, a = new Date(Date.parse(this)),
                            t = 28;
                        for (e = a.getMonth(),
                            t = 28; a.getMonth() == e;)
                            t++,
                            a.setDate(t);
                        return t - 1
                    }
                )
            },
            r = function (a) {
                var t = e(a).data("datepicker"),
                    s = e("#" + t.id);
                if (t.extraHeight === !1) {
                    var d = e(a).find("div");
                    t.extraHeight = d.get(0).offsetHeight + d.get(1).offsetHeight,
                        t.extraWidth = d.get(2).offsetWidth + d.get(3).offsetWidth
                }
                var n = s.find("table:first").get(0),
                    r = n.offsetWidth,
                    i = n.offsetHeight;
                s.css({
                    width: r + t.extraWidth + "px",
                    height: i + t.extraHeight + "px"
                }).find("div.datepickerContainer").css({
                    width: r + "px",
                    height: i + "px"
                })
            },
            i = function (a) {
                (e(a.target).is("span") || e(a.target).is("i")) && (a.target = a.target.parentNode);
                var t = e(a.target);
                if (t.is("a")) {
                    if (a.target.blur(),
                        t.hasClass("datepickerDisabled"))
                        return !1;
                    var s = e(this).data("datepicker"),
                        n = t.parent(),
                        r = n.parent().parent().parent(),
                        i = e("table", this).index(r.get(0)) - 1,
                        l = new Date(s.current),
                        p = !1,
                        o = !1,
                        h = Math.floor(s.calendars / 2);
                    if (n.is("th"))
                        t.hasClass("datepickerMonth") ? (l.addMonths(i - h),
                            "range" == s.mode ? (s.date[0] = l.setHours(0, 0, 0, 0).valueOf(),
                                l.addDays(l.getMaxDays() - 1),
                                l.setHours(23, 59, 59, 0),
                                s.date[1] = l.valueOf(),
                                o = !0,
                                p = !0,
                                s.lastSel = !1) : 1 == s.calendars && (r.eq(0).hasClass("datepickerViewDays") ? (r.eq(0).toggleClass("datepickerViewDays datepickerViewMonths"),
                                t.find("span").text(l.getFullYear())) : r.eq(0).hasClass("datepickerViewMonths") ? (r.eq(0).toggleClass("datepickerViewMonths datepickerViewYears"),
                                t.find("span").text(l.getFullYear() - 6 + " - " + (l.getFullYear() + 5))) : r.eq(0).hasClass("datepickerViewYears") && (r.eq(0).toggleClass("datepickerViewYears datepickerViewDays"),
                                t.find("span").text(l.getMonthName(!0) + ", " + l.getFullYear())))) : n.parent().parent().is("thead") && (r.eq(0).hasClass("datepickerViewDays") ? s.current.addMonths(t.hasClass("datepickerGoPrev") ? -1 : 1) : r.eq(0).hasClass("datepickerViewMonths") ? s.current.addYears(t.hasClass("datepickerGoPrev") ? -1 : 1) : r.eq(0).hasClass("datepickerViewYears") && s.current.addYears(t.hasClass("datepickerGoPrev") ? -12 : 12),
                            o = !0);
                    else if (n.is("td") && !n.hasClass("datepickerDisabled")) {
                        if (r.eq(0).hasClass("datepickerViewMonths"))
                            s.current.setMonth(r.find("tbody.datepickerMonths td").index(n)),
                            s.current.setFullYear(parseInt(r.find("thead th a.datepickerMonth span").text(), 10)),
                            s.current.addMonths(h - i),
                            r.eq(0).toggleClass("datepickerViewMonths datepickerViewDays");
                        else if (r.eq(0).hasClass("datepickerViewYears"))
                            s.current.setFullYear(parseInt(t.text(), 10)),
                            r.eq(0).toggleClass("datepickerViewYears datepickerViewMonths");
                        else {
                            var f = parseInt(t.text(), 10);
                            switch (l.addMonths(i - h),
                                n.hasClass("datepickerNotInMonth") && l.addMonths(f > 15 ? -1 : 1),
                                l.setDate(f),
                                s.mode) {
                                case "multiple":
                                    f = l.setHours(0, 0, 0, 0).valueOf(),
                                        e.inArray(f, s.date) > -1 ? e.each(s.date, function (e, a) {
                                            return a == f ? (s.date.splice(e, 1),
                                                !1) : void 0
                                        }) : s.date.push(f);
                                    break;
                                case "range":
                                    s.lastSel || (s.date[0] = l.setHours(0, 0, 0, 0).valueOf()),
                                        f = l.setHours(23, 59, 59, 0).valueOf(),
                                        f < s.date[0] ? (s.date[1] = s.date[0] + 86399e3,
                                            s.date[0] = f - 86399e3) : s.date[1] = f,
                                        s.lastSel = !s.lastSel;
                                    break;
                                default:
                                    s.date = l.valueOf()
                            }
                            p = !0
                        }
                        o = !0
                    }
                    o && d(this),
                        p && s.onChange.apply(this, c(s))
                }
                return !1
            },
            c = function (a) {
                var t = null;
                return "single" == a.mode ? a.date && (t = new Date(a.date)) : (t = new Array,
                        e(a.date).each(function (e, a) {
                            t.push(new Date(a))
                        })),
                    [t, a.el, a.current]
            },
            l = function () {
                var e = "CSS1Compat" == document.compatMode;
                return {
                    l: window.pageXOffset || (e ? document.documentElement.scrollLeft : document.body.scrollLeft),
                    t: window.pageYOffset || (e ? document.documentElement.scrollTop : document.body.scrollTop),
                    w: window.innerWidth || (e ? document.documentElement.clientWidth : document.body.clientWidth),
                    h: window.innerHeight || (e ? document.documentElement.clientHeight : document.body.clientHeight)
                }
            },
            p = function (e, a, t) {
                if (e == a)
                    return !0;
                if (e.contains)
                    return e.contains(a);
                if (e.compareDocumentPosition)
                    return !!(16 & e.compareDocumentPosition(a));
                for (var s = a.parentNode; s && s != t;) {
                    if (s == e)
                        return !0;
                    s = s.parentNode
                }
                return !1
            },
            o = function (a) {
                var t = e("#" + e(this).data("datepickerId"));
                if (!t.is(":visible")) {
                    var s = t.get(0),
                        n = t.data("datepicker");
                    n.onBeforeShow.apply(this, [s]);
                    if (0 == n.onBeforeShow.apply(this, [s]))
                        return;
                    d(s);
                    var i = e(this).offset(),
                        c = l(),
                        p = i.top,
                        o = i.left;
                    e.css(s, "display");
                    switch (t.css({
                            visibility: "hidden",
                            display: "block"
                        }),
                        r(s),
                        n.position) {
                        case "top":
                            p -= s.offsetHeight;
                            break;
                        case "left":
                            o -= s.offsetWidth;
                            break;
                        case "right":
                            o += this.offsetWidth;
                            break;
                        case "bottom":
                            p += this.offsetHeight
                    }
                    p + s.offsetHeight > c.t + c.h && (p = i.top - s.offsetHeight),
                        p < c.t && (p = i.top + this.offsetHeight + s.offsetHeight),
                        o + s.offsetWidth > c.l + c.w && (o = i.left - s.offsetWidth),
                        o < c.l && (o = i.left + this.offsetWidth),
                        t.css({
                            visibility: "visible",
                            display: "block",
                            top: p + "px",
                            left: o + "px"
                        }),
                        n.onAfterShow.apply(this, [t.get(0)]),
                        e(document).bind("mousedown", {
                            cal: t,
                            trigger: this
                        }, h)
                }
                return !1
            },
            h = function (a) {
                a.target == a.data.trigger || p(a.data.cal.get(0), a.target, a.data.cal.get(0)) || 0 != a.data.cal.data("datepicker").onBeforeHide.apply(this, [a.data.cal.get(0)]) && (a.data.cal.hide(),
                    a.data.cal.data("datepicker").onAfterHide.apply(this, [a.data.cal.get(0)]),
                    e(document).unbind("mousedown", h))
            },
            f = function (a, t) {
                if ("single" == a || t || (t = []),
                    t && (!e.isArray(t) || t.length > 0))
                    if ("single" != a)
                        if (e.isArray(t)) {
                            for (var s = 0; s < t.length; s++)
                                t[s] = new Date(t[s]).setHours(0, 0, 0, 0).valueOf();
                            "range" == a && (1 == t.length && t.push(new Date(t[0])),
                                t[1] = new Date(t[1]).setHours(23, 59, 59, 0).valueOf())
                        } else
                            t = [new Date(t).setHours(0, 0, 0, 0).valueOf()],
                            "range" == a && t.push(new Date(t[0]).setHours(23, 59, 59, 0).valueOf());
                else
                    t = new Date(t).setHours(0, 0, 0, 0).valueOf();
                return t
            };
        return {
            init: function (c) {
                return c = e.extend({}, s, c || {}),
                    n(c.locale),
                    c.calendars = Math.max(1, parseInt(c.calendars, 10) || 1),
                    c.mode = /single|multiple|range/.test(c.mode) ? c.mode : "single",
                    this.each(function () {
                        if (!e(this).data("datepicker")) {
                            c.el = this,
                                c.date = f(c.mode, c.date),
                                c.current ? c.current = new Date(c.current) : c.current = new Date,
                                c.current.setDate(1),
                                c.current.setHours(0, 0, 0, 0);
                            var s, n = "datepicker_" + parseInt(1e3 * Math.random());
                            c.id = n,
                                e(this).data("datepickerId", c.id);
                            var l = e(t.wrapper).attr("id", n).bind("click", i).data("datepicker", c);
                            c.className && l.addClass(c.className);
                            for (var p = "", h = 0; h < c.calendars; h++)
                                s = c.starts,
                                h > 0 && (p += t.space),
                                p += tmpl(t.head.join(""), {
                                    prev: c.prev,
                                    next: c.next,
                                    day1: c.locale.daysMin[s++ % 7],
                                    day2: c.locale.daysMin[s++ % 7],
                                    day3: c.locale.daysMin[s++ % 7],
                                    day4: c.locale.daysMin[s++ % 7],
                                    day5: c.locale.daysMin[s++ % 7],
                                    day6: c.locale.daysMin[s++ % 7],
                                    day7: c.locale.daysMin[s++ % 7]
                                });
                            l.find("tr:first").append(p).find("table").addClass(a[c.view]),
                                d(l.get(0)),
                                c.inline ? (l.appendTo(this).show().css("position", "relative"),
                                    r(l.get(0))) : (l.appendTo(document.body),
                                    e(this).bind(c.showOn, o))
                        }
                    })
            },
            showPicker: function () {
                return this.each(function () {
                    if (e(this).data("datepickerId")) {
                        var a = e("#" + e(this).data("datepickerId")),
                            t = a.data("datepicker");
                        t.inline || o.apply(this)
                    }
                })
            },
            hidePicker: function () {
                return this.each(function () {
                    if (e(this).data("datepickerId")) {
                        var a = e("#" + e(this).data("datepickerId")),
                            t = a.data("datepicker");
                        t.inline || e("#" + e(this).data("datepickerId")).hide()
                    }
                })
            },
            setDate: function (a, t) {
                return this.each(function () {
                    if (e(this).data("datepickerId")) {
                        var s = e("#" + e(this).data("datepickerId")),
                            n = s.data("datepicker");
                        n.date = f(n.mode, a),
                            t && (itaka = new Date("single" != n.mode ? n.date[0] : n.date),
                                itaka.addMonths(1),
                                n.current = itaka),
                            d(s.get(0))
                    }
                })
            },
            getDate: function () {
                return this.size() > 0 ? c(e("#" + e(this).data("datepickerId")).data("datepicker")) : void 0
            },
            clear: function () {
                return this.each(function () {
                    if (e(this).data("datepickerId")) {
                        var a = e("#" + e(this).data("datepickerId")),
                            t = a.data("datepicker");
                        "single" == t.mode ? t.date = null : t.date = [],
                            d(a.get(0))
                    }
                })
            },
            fixLayout: function () {
                return this.each(function () {
                    if (e(this).data("datepickerId")) {
                        var a = e("#" + e(this).data("datepickerId")),
                            t = a.data("datepicker");
                        t.inline && r(a.get(0))
                    }
                })
            }
        }
    }();
    e.fn.extend({
        DatePicker: a.init,
        DatePickerHide: a.hidePicker,
        DatePickerShow: a.showPicker,
        DatePickerSetDate: a.setDate,
        DatePickerGetDate: a.getDate,
        DatePickerClear: a.clear,
        DatePickerLayout: a.fixLayout
    })
}(jQuery),
function () {
    var e = {};
    this.tmpl = function a(t, s) {
        var d = /\W/.test(t) ? new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('" + t.replace(/[\r\t\n]/g, " ").split("<%").join("	").replace(/((^|%>)[^\t]*)'/g, "$1\r").replace(/\t=(.*?)%>/g, "',$1,'").split("	").join("');").split("%>").join("p.push('").split("\r").join("\\'") + "');}return p.join('');") : e[t] = e[t] || a(document.getElementById(t).innerHTML);
        return s ? d(s) : d
    }
}();

var eventValidAdultsValues = ["1", "2", "3"];
var eventValidChildsValues = ["0", "1"];
var eventFindHistoricWeatherUrl = '/api_www/get/historic_weather';
var eventShowWeatherDetails = 'Информации о погоде';
var eventHideWeatherDetails = 'mniej szczegółów pogody';

var search_has_more = 1;
var search_staticSelectedDestination = false;
var search_childTitlePrefix = 'Ребенок';
var search_defaultSelectLabel = 'Любое';
var search_selectFilterNoMatches = 'brak wyników';
var search_selectChosenLabel = 'отборный';
var search_selectChosenCounter = '<span class="chosen-count"><%- counter %></span> отборный';
var search_selectUnselectAll = 'Удалить выбор';

var search_labelAdultsSingle = '<%= count %> dorosły';
var search_labelChildsSingle = ' i <%= count %> dziecko';

var search_groupLabelPopular = 'Самые популярные';
var search_groupLabelAlphabetically = 'От А до Я';

var search_loadAjaxResults = 1;
var search_hasMore = 1;
var temp_value = true;

function formatDate(number) {
    return number < 10 ? ('0' + number) : number;
}
var criteoDynamicResults = true;
var criteoSetSiteType = "d";
var criteoCheckinDate = moment().format('YYYY-MM-DD');
var criteoCheckOutDate = "2018-04-30";

var SearchForm = function ($form, validationUrl) {
    "use strict";
    var $mainDate = $("#date-range"),
        $dateFromInput = $('#date_from'),
        $dateToInput = $('#date_to'),
        $dateInputs = $dateFromInput.add($dateToInput),
        //$destinationSelect  =   $('#destination-select'),
        /* @todo double declaration remove */
        $destinationSelect = $('#destination-select-popup'),
        $destinationSelectPopup = $('#destination-select-popup'),
        $departureSelect = $('#departures-select'),
        $filtersList = $('#filter-tabs'),
        $filtersInput = $('#filter-input'),
        $orderList = $('#order-menu'),
        $orderOpener = $('#order-opener'),
        $orderInput = $('#order-input'),
        $priceTypes = $('#price-types'),
        $viewTypes = $('#view-types'),
        $priceTypeInput = $('#price-type-input'),
        $calRange = $('#cal-range'),
        $collapseFilterBtn = $('#collapseFilterBtn'),
        validationXhr = null,
        $locale = $('html').attr('lang'),
        calendarState = "from", //from, to, blocked - calendar states
        EVENT_TYPE_HOTELS = "31",
        _that = this;

    this.initAutoCompleteInput = function ($select) {
        var selected = $select.children(":selected"),
            $options = $select.find('option'),
            $listAll = $('<ul class="typeahead-all dropdown-menu"/>'),
            $button = $("<button />").attr({
                "tabIndex": -1,
                "title": "Pokaż wszystko",
                "class": "search-combobox"
            }),
            $input = $("<input />").attr({
                'type': 'text',
                'placeholder': $select.data('placeholder'),
                'data-provide': 'typeahead',
                'value': ''
            }),
            temp;

        /*
         * input creation
         */

        $select.hide();
        $input.insertAfter($select);

        /*
         * list of all options creation
         */
        $listAll.css({
            "left": ($input.offset().left) + "px",
            "top": ($input.offset().top + $input.height()) + "px"
        });

        $options.each(function () {
            if ($(this).attr('value').length && !$(this).is(':disabled')) {
                var value = $(this).text();

                var $listNode = $('<a />').attr('href', '#').html(value);

                $listAll.append($('<li data-value="' + $(this).attr('value') + '" />').append($listNode));
            }
        });

        $listAll.on('click', 'a', function () {
            var item = $(this).parent().data('value');

            $select.val(item).change();
            $input.val($select.find(':selected').text().replace('&emsp;', '')).removeClass('placeholder');

            $listAll.hide();
            return false;
        }).on('mouseenter', function () {
            $(this).find('.active').removeClass('active');
        }).insertAfter($input);

        $('body').click(function (event) {
            if ($listAll.is(':visible') && !$(event.target).closest($listAll).length && !$(event.target).closest($button).length) {
                $listAll.hide();
            }
        });

        /*
         * button creation
         */
        $button.click(function (ev) {
            $listAll.children().removeClass('active selected');
            $listAll.children('[data-value="' + $input.val() + '"]').addClass('active selected');
            $listAll.toggle();

            _that.initScroll($listAll);

            ev.preventDefault();
        }).insertAfter($input);

        /*
         * typeahead initialization
         */

        $input.typeahead({
            items: 1000,
            source: function (query, process) {
                $input.data('typeahead').$menu.data('jsp', false);

                var intervalId = setInterval($.proxy(function () {
                    if (this.$menu.is(':visible')) {
                        if (typeof this.$menu.data('jsp') != 'object') {
                            this.$menu.jScrollPane({
                                showArrows: true,
                                horizontalGutter: 30,
                                verticalGutter: 30,
                                mouseWheelSpeed: 30,
                                contentWidth: '0px'
                            });

                            this.$menu.height(this.$menu.data('jsp').getContentHeight());
                            this.$menu.data('jsp').reinitialise();
                        }

                        clearInterval(intervalId);
                    }
                }, this), 50);

                var matches = [];

                $options.each(function () {
                    if ($(this).attr('value').length && !$(this).is(':disabled')) {
                        var value = $(this).text();

                        matches.push($(this).text().replace('&emsp;', ''));
                    }
                });

                return matches;
            },
            updater: function (item) {
                $options.each(function () {
                    if (item === $(this).text()) {
                        $select.val($(this).attr('value'));

                        return false;
                    }
                });
                return item;
            }

        });

        $select.on('change', function () {
            if (!$select.find(':selected').attr('value').length) {
                $input.val('');
            } else {
                $input.val($select.find(':selected').text());
            }
        });

        $input.on('change', function () {
            var value = $(this).val();

            $select.val('');
            if (value.length) {
                $listAll.find('li').each(function () {
                    if (!$(this).data('disabled') && (value.toLowerCase() === $(this).text().toLowerCase().replace(/^\s+|\s+$/g, '') || value.toLowerCase() === $(this).data('value').toLowerCase())) {
                        $(this).find('a').click();

                        return false;
                    }
                });
            }
        });

        /*
         * validation support
         */
        $select.on('search.validate', function (ev, data) {
            $select.children().each(function () {
                var values = $(this).attr('value'),
                    valuesArr = values.split(',');

                if (_.intersection(data, valuesArr).length === 0) {
                    $(this).attr('disabled', true);
                    $listAll.find('li[data-value="' + values + '"]').data('disabled', true).hide();
                } else {
                    $(this).attr('disabled', false);
                    $listAll.find('li[data-value="' + values + '"]').data('disabled', false).show();
                }
            });

            /*$listAll.find('li').each(function () {
                var values = $(this).data('value').split(',');
                if (_.intersection(data, values).length === 0) {
                    $(this).data('disabled', true).hide();
                }
                else {
                    $(this).data('disabled', false).show();
                }
            });*/
        });
    };

    this.initDestinationInput = function () {
        this.initAutoCompleteInput($destinationSelect);
    }

    this.initDepartureInput = function () {
        this.initAutoCompleteInput($departureSelect);
    }

    this.initSelectsChrome = function () { // don't delete it's a new approach to deal with chrome select bug - it could be usefull :)
        //        if( navigator.userAgent.indexOf("Chrome") > -1) {
        //
        //            $('select option:selected').each(function(){
        //                var $main   =   $(this).closest('div');
        //                if ( $(this).data('label') ) { var orginalSelect = $(this).data('label'); }
        //                else    { var orginalSelect = $(this).val(); }
        //                $(this).closest('div').find('.dropdown-menu').find('.selected').removeClass('selected');
        //                $(this).closest('div').find('.dropdown-menu li').each(function(i,value){
        //                    if  ( orginalSelect == $(value).find('a').text() ) {
        //                        $main.find('.dropdown-toggle').text( orginalSelect );
        //                        $(this).addClass('active selected');
        //                        return false;
        //                    }
        //                });
        //                if  ( $main.find('input').length == 1 )  { $main.find('input').val( orginalSelect )  }
        //            });
        //        }

    };

    this.initDropdownInput = function ($select, dropdownCallback) {
        $select.hide().next().show();

        $select.parent().find('.dropdown-menu').on('click', 'a[data-value]', function (ev) {

            var $that = $(this);

            $that.closest('.dropdown-menu').find('.selected').removeClass('selected');
            $that.parent().addClass('active selected');
            $select.parent().find('.dropdown-toggle').text((typeof $that.data('label') !== 'undefined' && $that.data('label').length) ? $that.data('label') : $that.text());

            $select.val($that.data('value'));

            if (typeof (dropdownCallback) === 'function') {
                dropdownCallback($select);
            }

            $select.change();

            ev.preventDefault();

        }).on('mouseenter', function () {
            $(this).find('.active:not(.item)').removeClass('active');

        });

        /*
         * validation support
         */
        $select.on('search.validate', function (ev, data) {
            var $list = $select.next().find('.dropdown-menu');

            $select.children().each(function () {
                if ($(this).attr('value').length) {
                    var values = $(this).attr('value'),
                        valuesArr = values.split(',');

                    if (_.intersection(data, valuesArr).length === 0) {
                        $(this).attr('disabled', true);

                        $list.find('li a[data-value="' + values + '"]').closest('li').hide();
                    } else {
                        $(this).attr('disabled', false);

                        $list.find('li a[data-value="' + values + '"]').closest('li').show();
                    }
                }
            });

            /*$select.next().find('.dropdown-menu li').each(function () {
             if ($(this).children('a').data('value').toString().length) {
             var values = $(this).children('a').data('value').toString().split(',');
             if (_.intersection(data, values).length === 0) {
             $(this).hide();
             }
             else {
             $(this).show();
             }
             }
             });*/
        });

        /*
         * scroll init
         */
        $(document).on('click.dropdown.data-api', '[data-toggle=dropdown],[class=search-combobox]', function () {
            var $dropdown = $('[data-js-value="' + $select.next().find('ul.dropdown-menu').data('js-value') + '"]');

            if ($dropdown.is(':visible')) {
                if (typeof $dropdown.data('jsp') != 'object') {
                    $dropdown.jScrollPane({
                        showArrows: true,
                        horizontalGutter: 30,
                        verticalGutter: 30,
                        mouseWheelSpeed: 30,
                        contentWidth: '0px'
                    });

                    $dropdown.on('click', '.jspVerticalBar', function (ev) {
                        ev.stopPropagation();
                    });

                } else {
                    $dropdown.data('jsp').reinitialise();
                }

                $dropdown.height($dropdown.data('jsp').getContentHeight());
                $dropdown.data('jsp').reinitialise();
            }
        });
    }

    /**
     * @param $select
     * @param filterEnable
     * @param chosenBoxEnable
     * @param defaultValue
     *
     * @todo: input params as array with default values instead of params separated by commas
     */
    this.initDropdownSelect = function ($select, filterEnable, chosenBoxEnable, defaultValue, hideUnselectAll) {
        var filterEnable = filterEnable || false,
            chosenBoxEnable = chosenBoxEnable || false,
            defaultValue = (typeof defaultValue !== 'undefined') ? defaultValue : null,
            hideUnselectAll = hideUnselectAll || false,
            selectable, $unselectAllButton = $('<button />').addClass('unselect-all').html('&times;').attr('title', search_selectUnselectAll);

        function updateOfChosenLabelsAndUnselectAll($input, $select) {
            var selectable = $select.data('selectable');
            if (selectable) {
                var $chosenOptions = selectable.getSelectedOptions(),
                    chosenLabels = [];

                if ($chosenOptions.length) {
                    $chosenOptions.each(function () {
                        chosenLabels.push($(this).data('label') || $(this).text());
                    });

                    selectable.$button.attr('title', search_selectChosenLabel + ': ' + chosenLabels.join(', '));
                    $unselectAllButton.toggle(!hideUnselectAll ? true : false);
                } else {
                    selectable.$button.attr('title', '');
                    $unselectAllButton.toggle(false);
                }
            }
        }

        $select.selectable({
            defaultValue: defaultValue,
            enableFiltering: filterEnable,
            showChosenBox: chosenBoxEnable,
            noMatchInfo: search_selectFilterNoMatches,
            chosenFieldsetLabel: search_selectChosenLabel,
            buttonText: function (options, select) {
                if (options.length == 0) {
                    return search_defaultSelectLabel;
                } else if (options.length > 1) {
                    var chosenCount = _.template(search_selectChosenCounter);
                    //underscore updated 1.8.2
                    //return _.template(search_selectChosenCounter, { counter: options.length });
                    return chosenCount({
                        counter: options.length
                    });
                } else {
                    var selected = '';
                    options.each(function () {
                        var label = ($(this).data('label') !== undefined) ? $(this).data('label') : $(this).text();

                        selected += label + ', ';
                    });

                    selected = selected.substr(0, selected.length - 2);

                    if (selected.length > 29) {
                        selected = selected.substr(0, 29) + '&hellip;';
                    }

                    return selected;
                }
            },
            afterFilter: function () {
                selectable && selectable.$optionsContainer.trigger('reinit.scrollpane');
            },
            afterKeyPressed: function (event, $activeElement) {
                selectable && selectable.$optionsContainer.trigger('reposition.scrollpane', {
                    element: $activeElement
                });
            },
            afterListItemCreate: function ($option, $listItem) {
                if (typeof $option.data('description') !== 'undefined' && $option.data('description').length) {
                    var $info = $('<span />').addClass('group-description');
                    $info.text($option.data('description'));

                    $listItem.find('label').append($info);
                }

                if (typeof $option.data('class') !== 'undefined' && $option.data('class').length) {
                    $listItem.addClass($option.data('class'));
                }

                return $listItem;
            },
            afterChange: updateOfChosenLabelsAndUnselectAll
        });

        selectable = $select.data('selectable');
        $unselectAllButton.hide().appendTo($select.prev()).on('click', function (ev) {
            selectable && selectable.unselectAll();

            ev.preventDefault();
        });

        updateOfChosenLabelsAndUnselectAll(null, $select);

        /*
         * validation support
         */
        $select.on('search.validate', function (ev, data) {
            $select.find('option').each(function () {
                if ($(this).attr('value').length) {
                    var values = $(this).attr('value'),
                        valuesArr = values.split(','),
                        available = _.intersection(data, valuesArr).length > 0;

                    $(this).attr('disabled', !available);
                    selectable && selectable.$optionsContainer.find('li input[value="' + values + '"]').closest('li').toggleClass('option-disabled unavailable', !available);
                }
            });

            if (selectable) {
                selectable.$optionsContainer.find('fieldset').each(function () {
                    $(this).toggle(!!$(this).find('li:not(.unavailable)').length);
                });

                selectable.updateButtonText();
                selectable.fillChosenBox();
                updateOfChosenLabelsAndUnselectAll(null, $select);

                selectable.$optionsContainer.trigger('reinit.scrollpane');
            }
        });

        this.initScroll($select.next().find('.options-container'));
        /*show filters*/
        $('.searchbig .affix-wrapper').show();
    }

    this.initDropdownMultiselect = function ($select, filterEnable) {
        filterEnable = filterEnable || false;

        if ($.fn.multiselect) {
            $select.multiselect({
                enableFiltering: filterEnable,
                enableCaseInsensitiveFiltering: filterEnable,
                filterBehavior: 'both',
                preventInputChangeEvent: true,
                buttonText: function (options, select) {
                    if (options.length === 0) {
                        return select.find('option[value=""]').text();
                    } else if (options.length > 1) {
                        return options.length + ' zaznaczone';
                    } else {
                        var selected = '';
                        options.each(function () {
                            var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).text();

                            selected += label + ', ';
                        });

                        selected = selected.substr(0, selected.length - 2);

                        if (selected.length > 21) {
                            selected = selected.substr(0, 21) + '&hellip;';
                        }

                        return selected;
                    }
                },
                onOptionCreate: function (option) {
                    return ($(option).attr('value').toString().length > 0);
                }
            });
        }

        /*
         * validation support
         */
        $select.on('search.validate', function (ev, data) {
            var $list = $select.next().find('.dropdown-menu');

            $select.children().each(function () {
                if ($(this).attr('value').length) {
                    var values = $(this).attr('value'),
                        valuesArr = values.split(',');

                    if (_.intersection(data, valuesArr).length === 0) {
                        $(this).attr('disabled', true);

                        $list.find('li input[value="' + values + '"]').closest('li').hide();

                    } else {
                        $(this).attr('disabled', false);

                        $list.find('li input[value="' + values + '"]').closest('li').show();
                    }

                }
            });

            /*$select.next().find('.dropdown-menu li').each(function () {
             if ($(this).find('input').attr('value').toString().length) {
             var values = $(this).find('input').attr('value').toString().split(',');
             if (_.intersection(data, values).length === 0) {
             $(this).hide();
             }
             else {
             $(this).show();
             }
             }
             });*/
        });

        this.initScroll($select.next().find('ul.dropdown-menu'));
    }

    this.initChildsInput = function () {
        this.initChildsAgesDatepicker();
        this.initDropdownSelect($('#childs-select'), false, false, false, true);

        $('#childs-select').on('change', $.proxy(function () {
            this.childAgesCallback($('#childs-select'));
        }, this));
    }

    this.initCookieChildrenDate = function () {

        //after create form change values
        var cookieArr = _that.checkCookie('childDates');

        //datepicker show

        if (typeof (cookieArr) !== 'undefined' && cookieArr !== null && cookieArr.length > 0) {
            //$select.val( cookieArr.length );

            var childsSelected = ':eq(' + (cookieArr.length) + ')';
            $('#childs-select').val($('#childs-select option' + childsSelected).val());

            $('#childs-select option' + childsSelected).prop('selected', true);

            if ($('.fKids').length > 0) {
                $('.fKids a.btn').text(cookieArr.length);
            }
            //$('#childs-select').next().find('a').data('value', cookieArr.length )

            // only for main page set select
            if ($calRange.length > 0) {

                $('#childs-select').next().find('ul li').each(function (i) {

                    if ($(this).hasClass("selected")) {
                        $(this).removeClass('selected');
                    }
                    if (i === cookieArr.length) {
                        $(this).addClass('selected');
                    }
                });
            }

            _that.childAgesCallback($('#childs-select'));

        }

        if (typeof (cookieArr) !== 'undefined' && cookieArr !== null) {
            $('.dropdown .datepicker input').each(function (i, value) {
                if (cookieArr.length - 1 >= i) {
                    $(this).val(cookieArr[i]);
                }
            });
        }
    }

    this.resetCookie = function () { // $.cookie('childDates', null, { expires: 100, path: '/' });
    }

    this.saveCookieChildrenDate = function () {
        var childDataDates = $('#childs-ages input').serialize();
        // $.cookie('childDates', childDataDates, { expires: 100, path: '/' });
    }

    this.checkCookie = function (cookieName) {
        // var cookieChilds    = $.cookie( cookieName );
        if (typeof (cookieChilds) !== 'undefined' && cookieChilds != null) {
            var cookieArr = cookieChilds.match(/\d\d\.\d\d\.\d\d\d\d/g);
            return cookieArr;
        }
    }

    this.replaceCookieInput = function ($replace) {
        //console.log ("replace inputs");
        var cookieArr = _that.checkCookie('childDates');
        if (typeof (cookieArr) !== 'undefined' && cookieArr != null) {
            $('.dropdown .datepicker input').each(function (i, value) {
                //$replace.each( function(i,value ){
                if (cookieArr.length - 1 >= i) {
                    $(this).val(cookieArr[i]);
                }

            });
        }
    }

    this.initCookieFiltersTailsPrice = function () {
        return;
        var cookie = $.cookie('FilterTailsPrice'),
            cookieFTP = [];

        if (typeof (cookie) !== 'undefined' && cookie !== null) {
            //initialization of Price -> family/person value is on PHP side
            cookieFTP = JSON.parse(cookie);
            if (checkCookievalue('as-thumbs')) {
                $viewTypes.find('a').removeClass('active');
                $viewTypes.find('a:last').addClass('active');
                $('#search-results').addClass('as-thumbs').removeClass('as-list');

            }
            if (checkCookievalue('collapse')) {
                $collapseFilterBtn.addClass('fexpand').removeClass('fcollapse');
                $('.filters').removeClass('pernam');
            }
        }

        function checkCookievalue(optionName) {
            var cookieIndex = _.indexOf(cookieFTP, optionName);
            if (cookieIndex !== -1) {
                return true;
            } else {
                return false;
            }
        }

        function toggleCookieOption(optionName) {
            var cookieIndex = _.indexOf(cookieFTP, optionName);
            if (cookieIndex !== -1) {
                cookieFTP.splice(cookieIndex, 1);
            } else {
                cookieFTP.push(optionName);
            }
        }

        function saveCookie() {
            var cookieForSave = JSON.stringify(cookieFTP);
            $.cookie('FilterTailsPrice', cookieForSave, {
                expires: 7,
                path: '/'
            });
        }

        function updateCookieTails() {
            toggleCookieOption("as-thumbs");
            saveCookie();
        }

        function updateCookieFilters() {
            toggleCookieOption("collapse");
            saveCookie();
        }

        function updateCookiePrice() {
            toggleCookieOption("all");
            saveCookie();
        }

        $viewTypes.on('change', updateCookieTails);
        $collapseFilterBtn.on('change', updateCookieFilters);
        $priceTypeInput.on('change', updateCookiePrice);
    }

    this.initChildsAgesDatepicker = function () {

        $('#childs-ages > div input').datepicker({
            'language': 'ru',
            'startView': 2,
            'format': 'dd.mm.yyyy',
            'startDate': '-16y',
            'endDate': '-1d'

        }).on('changeDate', function () {

            // cookie on change date
            _that.saveCookieChildrenDate(this);
            $(this).datepicker('hide');
        });

        $('#childs-ages').on('click', '.datekids-opener', function (ev) {
            $(this).prev().datepicker('show');

            ev.preventDefault();
        });
    }

    this.childAgesCallback = function ($select) {
        var that = this;

        var value = $select.val();
        if (value > 0) {
            var fieldsCount = $('#childs-ages > div').length;

            if (fieldsCount < value) {
                for (var i = fieldsCount + 1; i <= value; i++) {
                    var $newInput = $('#childs-ages > div:last').clone();

                    //cookie set children
                    $newInput.find('input').datepicker({
                        'language': 'ru',
                        'format': 'dd.mm.yyyy',
                        'startView': 2,
                        'startDate': '-16y',
                        'endDate': '-1d'
                    }).on('changeDate', function () {
                        //  cookie on change date
                        _that.saveCookieChildrenDate();

                        $(this).datepicker('hide');
                    });

                    $newInput.find('label').text('Детей ' + i);

                    $newInput.insertAfter($('#childs-ages > div:last'));
                }
            } else {
                var deleteCount = fieldsCount - value;
                while (deleteCount--) {
                    $('#childs-ages > div:last').remove();
                }
            }

            if (!$('#childs-ages').is(':visible')) {
                $('#childs-ages').slideDown();
            }
        } else {
            $('#childs-ages').slideUp();
        }

        //set after creation

    }
    this.initParticipantsInput = function () {
        var that = this;

        this.initChildsAgesDatepicker();
        this.participantRecount();

        $('#childs-select, #adults-select').on('change', function (ev) {
            that.participantRecount();
            that.childAgesCallback($('#childs-select'));

            //          commented to catch for history API
            //          ev.stopPropagation();
        });

        $('.fParticipants > .dropdown-menu').on('click', '.childs-ages-commit', function (ev) {
            $('.fParticipants > .dropdown-toggle').data('childs', $('#childs-select').val());
            $('.fParticipants > .dropdown-toggle').data('adults', $('#adults-select').val());

            $('.fParticipants > .dropdown-toggle').dropdown('toggle');

            $form.submit();

            ev.preventDefault();
        }).on('click', '.childs-ages-rollback', function (ev) {
            $('#childs-select').val($('.fParticipants > .dropdown-toggle').data('childs')).change();
            $('#adults-select').val($('.fParticipants > .dropdown-toggle').data('adults')).change();

            $('.fParticipants > .dropdown-toggle').dropdown('toggle');

            ev.preventDefault();
        });

        $('.fParticipants > .dropdown-menu').on('click', function (e) {
            e.stopPropagation();
        });
    }

    this.participantRecount = function () {
        var label;
        var adultsLabel = _.template(search_labelAdults);
        var childLabel = _.template(search_labelChilds);

        label = adultsLabel({
            count: $('#adults-select').val()
        });
        if ($('#childs-select').val() > 0) {

            label += childLabel({
                count: $('#childs-select').val()
            });
        }

        $('#participants-count').text(label);
    }

    this.initFilters = function () {
        $filtersList.on('click', 'a', function (ev) {
            if (!$(this).parent().hasClass('active')) {
                $filtersInput.val($(this).data('filter')).change();

                $filtersList.children('li').removeClass('active');
                $(this).parent().addClass('active');
            }

            ev.preventDefault();
        });
    }

    this.initOrderInput = function () {
        $orderList.on('click', 'a', function (ev) {
            var $this = $(this);

            $orderList.find('li').removeClass('active');
            $this.parent().addClass('active');

            $orderInput.val($this.data('value')).change();
            $orderOpener.text($this.data('label'));

            ev.preventDefault();
        });

        $orderList.find('a.active').click();
    }

    this.initPriceTypeInput = function () {
        $priceTypes.on('click', 'a', function (ev) {
            var $this = $(this);

            $priceTypes.find('a').removeClass('active');
            $this.addClass('active');

            $priceTypeInput.val($this.data('value')).change();

            ev.preventDefault();
        });

        if ($priceTypeInput.val() === "person") {
            $('.pt_perperson a').addClass('active');
        } else {
            $('.pt_perfamily a').addClass('active');
        }

        //$priceTypes.find('a[data-value="' + $priceTypeInput.val() + '"]').click();

    }

    this.initViewTypeInput = function () {
        $viewTypes.on('click', 'a', function (ev) {
            var $this = $(this);

            $viewTypes.find('a').removeClass('active');
            $this.addClass('active').change();
            //change for cookieFiltersPriceTails

            $('#search-results').toggleClass('as-thumbs as-list');

            ev.preventDefault();
        });
    }

    this.initValidation = function () {
        var that = this;

        $form.on('change', ':input', function (ev) {
            that.validate();
        });
    }

    this.validate = function () {
        var params = _.reject($form.serializeArray(), function (val) {
            return (val.name === 'page');
        });

        if (validationXhr) {
            validationXhr.abort();
        }

        if ($("#event-types").find("input:checked").val() === EVENT_TYPE_HOTELS) {
            //hotels prevent
            return true;
        }

        if (validationUrl && validationUrl.indexOf('action=itaka_query') > -1) {
            validationXhr = $.getJSON(validationUrl, params, function (responce) {
                validationXhr = null;
                $destinationSelect.trigger('searchmobile.validate', [responce.data.mobileSelectOptions]);
                $destinationSelect.trigger('search.validate', [responce.data.destinations]);
                $departureSelect.trigger('search.validate', [responce.data.from]);

                if ($('#foods-select').length) {
                    $('#foods-select').trigger('search.validate', [responce.data.foods]);
                }

                $destinationSelect.trigger('searchmobile_afterajax.validate');
            });
        }
    }

    // custom calendar datarange
    this.initDateInputs2 = function (options, autoSubmit) {
        if (!$mainDate || !$mainDate.length) {
            return;
        }
        var autoSubmit = autoSubmit || false;
        //options != undefined means invoke calendar for search form

        var endDateRange = moment($mainDate.data('enddaterange'), "DD-MM-YYYY"),
            today = moment().hour(0).minute(0).second(0),
            from = moment($mainDate.data('from'), "DD-MM-YYYY"),
            to = moment($mainDate.data('to'), "DD-MM-YYYY"),
            calMonthView = moment($mainDate.data('from'), "DD-MM-YYYY").add("month", 1),
            calMonthViewTo = moment($mainDate.data('to'), "DD-MM-YYYY").add("month", 1),
            fromClickedDate = moment($mainDate.data('from'), "DD-MM-YYYY"),
            toClickedDate = moment($mainDate.data('enddaterange'), "DD-MM-YYYY"),
            arrowDirecFrom = "right",
            arrowBufferFrom = "right",
            arrowDirecTo = "right",
            arrowBufferTo = "right",
            isSearchBig = $('.searchbig').length > 0,
            that = this,
            calClickRange = 13,
            calMinSetRange = 1,
            firstClickDateTo = true,
            setFirstClickToOneDay = false,
            calState = ["leftReady"];

        function convertDateSearchBig(whichInput) {
            if (whichInput === "from") {
                var dateFull = ($mainDate.data('from')).split('.');
            } else {
                var dateFull = ($mainDate.data('to')).split('.');
            }

            var dateShort = dateFull[0] + "." + dateFull[1];

            return dateShort;

        }

        function language(lang) {
            switch (lang) {
                case "ru":
                    return {
                        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                        monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"]
                    };
                case "pl":
                    return {
                        daysMin: ["N", "Pn", "Wt", "Śr", "Cz", "Pt", "So"],
                        months: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
                        monthsShort: ["Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru"]
                    };
                case "en":
                    return {
                        daysMin: ["S", "M", "T", "W", "T", "F", "S"],
                        months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
                    };
                case "lt":
                    return {
                        daysMin: ["Sek", "Pir", "Ant", "Tre", "Ket", "Pen", "Šeš"],
                        months: ["Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis", "Gruodis"],
                        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
                    };
            }
        }

        function renderLabels(clearLabels) {
            if (typeof clearLabels === 'undefined') {
                switch (calState[0]) {
                    case 'leftReady':
                        //$dateFromInput.focus();
                        $dateFromInput.parent().addClass('placeholder-focus').removeClass('lightholder').removeClass('placeholder-hide');
                        $dateToInput.parent().addClass('lightholder');
                        $('.infield-from,.infield-to').removeClass('blocked');
                        //exception for search results
                        $('.searchbig .fRow > label:first').addClass('moveTerm');

                        $dateToInput.parent().removeClass('placeholder-focus').addClass('placeholder-hide').addClass('lightholder');
                        break;
                    case "rightReady":
                        //$dateToInput.focus();
                        $dateToInput.parent().addClass('placeholder-focus').removeClass('lightholder').removeClass('placeholder-hide');
                        $dateFromInput.parent().addClass('placeholder-focus').addClass('lightholder').removeClass('placeholder-hide');
                        $('.infield-from,.infield-to').removeClass('blocked');
                        //exception for search results
                        $('.searchbig .fRow > label:first').addClass('moveTerm');
                        break;
                    case "blocked":
                        $dateInputs.parent().addClass('placeholder-focus').removeClass('lightholder').removeClass('placeholder-hide');
                        $('.infield-from,.infield-to').addClass('blocked');
                        //exception for search results
                        $('.searchbig .fRow > label:first').addClass('moveTerm');

                        $dateFromInput.parent().removeClass('placeholder-focus').addClass('placeholder-hide').addClass('lightholder');
                }
            } else {
                $dateToInput.parent().removeClass('placeholder-focus').addClass('placeholder-hide').addClass('lightholder');
                $dateFromInput.parent().removeClass('placeholder-focus').addClass('placeholder-hide').addClass('lightholder');
                if ($('.searchbig').length > 0) {
                    $('.infield-from,.infield-to').removeClass('blocked');
                }
                //for search results
                $('.fRow > label:first').removeClass('moveTerm');
            }
        }

        function changeDateToAfterFirstClick() {
            if ($("#event-types").find(".checked input").val() === EVENT_TYPE_HOTELS) {
                setFirstClickToOneDay = true;
            }

            if (!isSearchBig && !setFirstClickToOneDay) {

                var todayOneMonthAfter = moment().hour(0).minute(0).second(0).add("month", 1);
                var firstChooseDate = moment($('#fDate-cal__from').DatePickerGetDate()[0]);

                if (firstChooseDate.isAfter(today)) {
                    if (endDateRange.isBefore(firstChooseDate.add('month', 1), 'month')) {

                        firstChooseDate.subtract('month', 1);
                    }
                    $mainDate.data('to', firstChooseDate.format("DD.MM.YYYY"));
                    $('#fDate-cal__to').DatePickerSetDate(moment(firstChooseDate.toDate()), true);
                } else {
                    $mainDate.data('to', todayOneMonthAfter.format("DD.MM.YYYY"));
                    $('#fDate-cal__to').DatePickerSetDate(moment(todayOneMonthAfter.toDate()), true);
                }

                //global don't need to convert from HTML data every time;
                toClickedDate = firstChooseDate.clone();

                initBlockCalendar();
                $('input[name="date_to"]').val($mainDate.data('to'));
                $dateToInput.val($mainDate.data('to')).change();
            }
            if (!isSearchBig && setFirstClickToOneDay) {

                var todayOneDayAfter = moment().hour(0).minute(0).second(0).add("day", 1);
                var firstChooseDateHotels = moment($('#fDate-cal__from').DatePickerGetDate()[0]);

                if (firstChooseDateHotels.isAfter(today)) {
                    if (endDateRange.isBefore(firstChooseDateHotels.add('day', 1), 'day')) {

                        firstChooseDateHotels.subtract('day', 1);
                    }
                    $mainDate.data('to', firstChooseDateHotels.format("DD.MM.YYYY"));
                    $('#fDate-cal__to').DatePickerSetDate(moment(firstChooseDateHotels.toDate()), true);
                } else {
                    $mainDate.data('to', todayOneDayAfter.format("DD.MM.YYYY"));
                    $('#fDate-cal__to').DatePickerSetDate(moment(todayOneDayAfter.toDate()), true);
                }

                //global don't need to convert from HTML data every time;
                toClickedDate = firstChooseDateHotels.clone();

                initBlockCalendar();
                $('input[name="date_to"]').val($mainDate.data('to'));
                $dateToInput.val($mainDate.data('to')).change();
            }

        }

        $('input[name="date_from"],input[name="date_to"]').on('change', function (ev) {

            //highlights dates in calendar and validate
            if ($(ev.target).is('#date_from')) {
                $('#fDate-cal__from').DatePickerSetDate(moment($mainDate.data('from'), "DD.MM.YYYY").toDate());
            } else {
                $('#fDate-cal__to').DatePickerSetDate(moment($mainDate.data('to'), "DD.MM.YYYY").toDate());
            }

            blockCalendar(ev);

            ev.preventDefault();
            that.validate();
        }).on('click', function (ev) { // ev.stopPropagation();
        });

        $('.fDate-cal__from .popover-close,.fDate-cal__to .popover-close').on('click.calendar', function (ev) {
            //                $dateFromInput.val( moment ( $daterangeOpener.data('from') ).format("DD.MM.YYYY") ).change();
            //                $dateToInput.val( moment ( $daterangeOpener.data('to') ).format("DD.MM.YYYY") ).change();
            //
            $(this).dropdown('toggle');
            renderLabels(true);
            //                renderLabels(true);
            //                that.validate();
            ev.preventDefault();
        });
        $dateToInput.one('click.calendar', changeDateToAfterFirstClick);

        //        $calRange.on('rotate.calendar', function() {
        //            calState    =   calState.concat( calState.shift() );
        //
        //            renderLabels();
        //        })

        $(document).on('click.dropdown-menu.data-api', function () {
            renderLabels(true);
        });

        //ON SEARCH FORM

        //        $mainDate.on('click.infield', function (ev) {
        //            if ( $("#date-range").find('.placeholder-focus').length > 0 ) {
        //                calState[0] = "rightReady";
        //                renderLabels();
        //
        //                renderLabels(true);
        //            }
        //            else{
        //                renderLabels();
        //            }
        //        });

        $dateInputs.on('click.infield', function (ev) {
            if ($(this).is('#date_from')) {
                calState[0] = "leftReady";
                renderLabels();
            } else {
                calState[0] = "blocked";
                renderLabels();
            }

        });

        /*
        $('.datepicker').find(".datepickerDisabled").on('click', function (ev) {

            console.log ( calState[0], "ten stan" );
            if ( calState[0] === "blocked") {
                $.when( $calRange.trigger('rotate.calendar') ).done(function(){
                    $('#render-cal').DatePickerSetDate( $('#render-cal').DatePickerGetDate()[0] );
                });
            }
        });
        */

        //render and refresh calendar after blocked mode

        function from_date() {
            var today = new Date();
            var tommorow = new Date();
            tommorow.setMonth(today.getMonth() + 1);
            return ('0' + tommorow.getDate()).slice(-2) + '.' + ('0' + tommorow.getMonth()).slice(-2) + '.' + tommorow.getFullYear();
        }

        function to_date(range) {
            var today = new Date();
            var tommorow = new Date();

            tommorow.setMonth(today.getMonth() + 1);
            tommorow.setDate(to.getDate() + range);

            return ('0' + tommorow.getDate()).slice(-2) + '.' + ('0' + tommorow.getMonth()).slice(-2) + '.' + today.getFullYear();
        }

        //$mainDate.data('to',  to.toDate() );
        //$daterangeDropdown.data('from', from.toDate() );

        $('#fDate-cal__from').DatePicker({
            showOn: 'click',
            inline: true,
            starts: 1,
            //date: [from.toDate()],
            calendars: 2,
            locale: language('ru'),
            mode: 'single',
            current: calMonthView.toDate(),

            onRenderCell: function (h, d) {
                var endClass = "",
                    disa = false;
                if (endDateRange.isBefore(d)) {
                    endClass = "datepickerEndSeason";
                    disa = true;
                }
                if (today.isAfter(d, "day")) {
                    endClass = "datepickerPast ";
                    disa = true;
                }
                //                if  ( moment ( this.date[0] ).isSame (d,"days")) { endClass = "datepickerLeft"; }
                //                if  ( moment ( this.date[1] ).isSame (d,"days")) { endClass = "datepickerRight"; }
                //                if  ( moment ( this.date[0] ).isAfter (d,"days") &&  (calState[0] === "rightReady" || calState[0] === "blocked" ) ) { disa = true; }
                //                if  ( moment ( this.date[1] ).isBefore (d,"days") && calState[0] === "blocked" ) { disa = true; }
                //                if  ( calState[0] == "rightReady"  ) { endClass = endClass + " " + "datepickerHoverTo"; }
                //                if  ( calState[0] == "blocked"  ) { endClass = endClass + " " + "datepickerHoverNone"; }
                //
                return {
                    selected: false,
                    disabled: disa,
                    className: endClass
                };
            },
            onChange: calClickChangeFrom
        });
        //

        $('#fDate-cal__to').DatePicker({
            showOn: 'click',
            inline: true,
            starts: 1,
            //date: [to.toDate()],
            calendars: 2,
            locale: language('ru'),
            mode: 'single',
            current: calMonthViewTo.toDate(),

            onRenderCell: function (h, d) {
                var endClass = "",
                    disa = false;

                if (endDateRange.isBefore(d)) {
                    endClass = "datepickerEndSeason";
                    disa = true;
                }
                if (today.isAfter(d, "day")) {
                    endClass = "datepickerPast ";
                    disa = true;
                }
                if (today.isSame(d, "day")) {
                    endClass = "datepickerDisabled";
                }
                //                if  ( moment ( this.date[0] ).isSame (d,"days")) { endClass = "datepickerLeft"; }

                if (moment($('input[name=date_from]').val(), "DD-MM-YYYY").isSame(d, "days")) {
                    disa = true;
                }
                //                if  ( moment ( $('input[name=date_from]').val(), "DD-MM-YYYY").add("day",1).isSame (d,"days")) { disa = true; }

                //if ( Number( $('#autosuggest-input').data('hotels-max-offer')) === 28 &&
                //    moment ( $('input[name=date_from]').val(), "DD-MM-YYYY").add("day",28).isBefore (d,"days") ){
                //    disa = true;
                //}
                //                if  ( moment ( this.date[1] ).isSame (d,"days")) { endClass = "datepickerRight"; }
                if (fromClickedDate.isAfter(d, "days")) {
                    disa = true;
                }
                //                if  ( moment ( this.date[1] ).isBefore (d,"days") && calState[0] === "blocked" ) { disa = true; }
                //                if  ( calState[0] == "rightReady"  ) { endClass = endClass + " " + "datepickerHoverTo"; }
                //                if  ( calState[0] == "blocked"  ) { endClass = endClass + " " + "datepickerHoverNone"; }

                return {
                    selected: false,
                    disabled: disa,
                    className: endClass

                };
            },
            onChange: calClickChangeTo

        });

        //init dates from validate inputs (backend) to alias inputs
        $('#fDate-cal__from').DatePickerSetDate(moment($mainDate.data('from'), "DD.MM.YYYY").toDate());
        $('#fDate-cal__to').DatePickerSetDate(moment($mainDate.data('to'), "DD.MM.YYYY").toDate());
        if (isSearchBig && $(window).width() > 767) {

            // var aliasDateFrom = convertDateSearchBig("from");
            // var aliasDateTo = convertDateSearchBig("to");

            // $dateToInput.val(aliasDateTo);
            // $dateFromInput.val(aliasDateFrom);

        }
        initBlockCalendar();

        $('#fDate-cal__from,#fDate-cal__to').find('.datepicker').on("click.calendar", "thead i", blockCalendar);

        function initBlockCalendar(jump) {

            var dateFromView = moment($('#fDate-cal__from').DatePickerGetDate()[2]);
            var dateToView = moment($('#fDate-cal__to').DatePickerGetDate()[2]);

            if (!jump) {
                //not check after add one month ( auto jump )
                eachCalendar($('#fDate-cal__from'), dateFromView);
            }

            eachCalendar($('#fDate-cal__to'), dateToView);

            function eachCalendar($el, dateView) {
                if (today.isAfter(dateView, "year") && today.isAfter(dateView.subtract("month", 2), "month")) {
                    $el.find('.datepickerBlock:first .datepickerGoPrev').addClass("icon-arrow-none");
                }
                if (endDateRange.isBefore(dateView, "year") && endDateRange.isBefore(dateView.add("month", 2), "month")) {
                    $el.find('.datepickerBlock:last .datepickerGoNext').addClass("icon-arrow-none");
                }
            }

        }

        function blockCalendar(ev) {
            /*jshint validthis: true */
            var $el = $(this);

            if ($(this).closest('.datepicker').parent().is('#fDate-cal__from')) {
                if ($(this).hasClass('icon-arrow-right')) {
                    arrowDirecFrom = "right";
                } else {
                    arrowDirecFrom = "left";
                }
            } else {
                if ($(this).hasClass('icon-arrow-right')) {
                    arrowDirecTo = "right";
                } else {
                    arrowDirecTo = "left";
                }
            }

            var dateFromView = moment($('#fDate-cal__from').DatePickerGetDate()[2]);
            var dateToView = moment($('#fDate-cal__to').DatePickerGetDate()[2]);

            if ($(this).closest('.datepicker').parent().is('#fDate-cal__from')) {
                eachCalendarBlock(arrowDirecFrom, arrowBufferFrom, dateFromView);
                arrowBufferFrom = arrowDirecFrom;
            } else {
                eachCalendarBlock(arrowDirecTo, arrowBufferTo, dateToView);
                arrowBufferTo = arrowDirecTo;
            }

            function eachCalendarBlock(arrowDirect, arrowBuffer, dateView) {

                if ((arrowDirect === "right" && arrowBuffer === "right") || (arrowDirect === "right" && arrowBuffer === "left")) {
                    if (endDateRange.isBefore(dateView.add("month", 2), "month")) {
                        $el.parent().addClass("icon-arrow-none");
                    } else {
                        $el.closest('tbody').find('.datepickerBlock:first .datepickerGoPrev').removeClass("icon-arrow-none");
                    }
                }

                if ((arrowDirect === "left" && arrowBuffer === "left") || (arrowDirect === "left" && arrowBuffer === "right")) {
                    if (today.isAfter(dateView.subtract("month", 3), "month")) {
                        $el.parent().addClass("icon-arrow-none");
                    } else {
                        $el.closest('tbody').find('.datepickerBlock:last .datepickerGoNext').removeClass("icon-arrow-none");
                    }
                }

                if (arrowDirect === "left" && arrowBuffer === "right") {
                    $el.closest('tbody').find('.datepickerBlock:last .datepickerGoNext').removeClass("icon-arrow-none");

                }
                //console.log ( dateView.format('MM-YYYY'),arrowDirecFrom,arrowBufferFrom );
            }
        }

        function calClickChangeFrom(dates, el) {
            var clickedDate = moment($('#fDate-cal__from').DatePickerGetDate()[0]);
            fromClickedDate = clickedDate.clone();

            //refresh calendar without change event
            if (clickedDate.isSame(toClickedDate) || clickedDate.isAfter(toClickedDate)) {

                var updateDateToCalendar = clickedDate.clone();

                if (endDateRange.isBefore(updateDateToCalendar.add('month', 1))) {
                    updateDateToCalendar.subtract('month', 1);
                    toClickedDate = updateDateToCalendar.clone();
                }

                toClickedDate = updateDateToCalendar.clone();

                $mainDate.data('to', updateDateToCalendar.format("DD.MM.YYYY"));
                toInputAlias();

                $('input[name="date_to"]').val($mainDate.data('to'));
                $('#fDate-cal__to').DatePickerSetDate(moment($mainDate.data('to'), "DD.MM.YYYY").toDate(), true);
                initBlockCalendar(true);
            } else {
                $('#fDate-cal__to').DatePickerSetDate(moment($mainDate.data('to'), "DD.MM.YYYY").toDate());

            }
            //refresh second calendar
            $mainDate.data('from', clickedDate.format("DD.MM.YYYY"));

            renderLabels(true);
            $('#fDate-open__from').dropdown('toggle');
            //alias input
            fromInputAlias();

            //validate real input
            $('input[name="date_from"]').val($mainDate.data('from')).change();
        }

        function calClickChangeTo(dates, el) {
            var clickedDate = moment($('#fDate-cal__to').DatePickerGetDate()[0]);
            toClickedDate = clickedDate.clone();

            $mainDate.data('to', clickedDate.format("DD.MM.YYYY"));

            renderLabels(true);
            $('#fDate-open__to').dropdown('toggle');

            toInputAlias();

            //validate real input
            $('input[name="date_to"]').val($mainDate.data('to')).change();
        }

        function fromInputAlias() {
            if (isSearchBig && $(window).width() > 767) {
                var aliasDate = convertDateSearchBig("from");
                $dateFromInput.val(aliasDate);
                $form.submit();
            } else {
                $dateFromInput.val($mainDate.data('from'));
            }

        }

        function toInputAlias() {
            //alias inputs
            if (isSearchBig && $(window).width() > 767) {
                var aliasDate = convertDateSearchBig("to");
                $dateToInput.val(aliasDate);
                $form.submit();
            } else {
                $dateToInput.val($mainDate.data('to'));
            }
        }

        function changeCalDropdown(ev, el) {
            $daterangeOpener.dropdown('toggle');
        }
    }

    this.initFiltersToggle = function () {
        $(window).on('load', function () {
            $(window).on('resize.searchfilters', showHideInputs);
            $(window).on('scroll.affix.data-api', showHideInputs);
        });
        $(".filters").on('affixed-top.bs.affix', fCollapse);
        $collapseFilterBtn.on('click.searchfilters', fToggle);
        if ($(document).width() > 641) {
            $('#filters-txt').hide();

        }

        function showHideInputs() {

            //if  ( $(document).width() < 730  ) { return true; }

            var $panel = $('#search-form').width(),
                $elem = $('.fRow:first').width(),
                maxElem = $('.searchbig .fRow').length,
                maxInline = Math.floor($panel / $elem),
                elemVisible = $('.searchbig .fRow').filter(function () {
                    return $(this).css('display') !== 'none';
                }).length;

            //console.log ( maxInline,maxElem,$panel,$elem,elemVisible );
            if ($(".filters").hasClass('affix-top') && $(document).width() > 730) {
                $('.searchbig .fRow').show();
                $('#filters-txt').hide();

            } else {
                if (elemVisible > maxInline && !$(".filters").hasClass('pernam') && $(document).width() > 730) {
                    //$('.searchbig .fRow:nth-of-type('+maxInline+')').nextAll().hide();
                    $('.searchbig .fRow').hide();
                    $collapseFilterBtn.addClass('fexpand');
                    $collapseFilterBtn.removeClass('fcollapse');

                    if ($(this).hasClass('fexpand')) { //    $('#filters-txt').text( $('#filters-txt').data('filtercollapse') ).show();
                    } else {
                        $('#filters-txt').text($('#filters-txt').data('filterexpand')).show();
                    }

                } else { //$('.fRow:nth-of-type('+(maxInline+1)+')').prevAll().show();

                    //$btnFilters.addClass('fcollapse');
                    //$btnFilters.removeClass('fexpand');

                }
            }

        }

        function fCollapse() {
            $('.searchbig .fRow').show();

            if ($(document).width() > 640) {
                $('#filters-txt').text($('#filters-txt').data('filtercollapse')).hide();

            }

            $collapseFilterBtn.addClass('fcollapse');
            $collapseFilterBtn.removeClass('fexpand');
        }

        function fToggle(ev) {
            /*jshint validthis: true */
            ev.preventDefault();
            var $filters = $('.filters');

            //add pernam
            if ($filters.hasClass('affix')) {
                if ($filters.hasClass('pernam')) {
                    $filters.removeClass('pernam');
                } else {
                    $filters.addClass('pernam');
                }
            }

            if ($(this).hasClass('fexpand')) {
                fCollapse();
            } else {
                //$(window).trigger('resize.searchfilters');

                var $frows = $('.searchbig .fRow');
                $frows.filter(function (index, frow) {
                    return $(frow).offset().top > $frows.first().offset().top;
                }).hide();

                $('#filters-txt').text($('#filters-txt').data('filterexpand')).show();
                $(this).addClass('fexpand');
                $(this).removeClass('fcollapse');
            }

            $(this).change();

        }
    }

    this.initScroll = function (dropdownSelector) {
        if (typeof isOldIE === 'undefined' || !isOldIE) {
            var $dropdown = $(dropdownSelector);

            $(document).on('click.dropdown.data-api,', '[data-toggle=dropdown],[class=search-combobox]', function () {
                if ($dropdown.is(':visible')) {
                    if (typeof $dropdown.data('jsp') != 'object') {
                        $dropdown.jScrollPane({
                            showArrows: true,
                            horizontalGutter: 30,
                            verticalGutter: 30,
                            mouseWheelSpeed: 30,
                            contentWidth: '0px'
                        });

                        $dropdown.on('click', '.jspVerticalBar', function (ev) {
                            ev.stopPropagation();
                        });
                    } else {
                        $dropdown.data('jsp').reinitialise();
                    }

                    $dropdown.height($dropdown.data('jsp').getContentHeight());
                    $dropdown.data('jsp').reinitialise();
                    //                    $('.popup-bg').is(":visible") ? $('.popup-bg').remove() : false;
                    $destinationSelectPopup.trigger('reinitNEW.newselect', dropdownSelector);
                }
            });

            $dropdown.on('reinit.scrollpane', function () {
                // not DRY :(

                if ($dropdown.is(':visible')) {
                    if (typeof $dropdown.data('jsp') != 'object') {
                        $dropdown.jScrollPane({
                            showArrows: true,
                            horizontalGutter: 30,
                            verticalGutter: 30,
                            mouseWheelSpeed: 30,
                            contentWidth: '0px'
                        });

                        $dropdown.on('click', '.jspVerticalBar', function (ev) {
                            ev.stopPropagation();
                        });

                    } else {
                        $dropdown.data('jsp').reinitialise();
                    }

                    $dropdown.height($dropdown.data('jsp').getContentHeight());
                    $dropdown.data('jsp').reinitialise();

                }
            });

            $dropdown.on('reposition.scrollpane', function (ev, data) {
                $dropdown.data('jsp').scrollToElement(data.element);
            });
        }

    }
};

var SearchResults = function ($form, searchUrl) {
    var that = this,
        currentXhr = null,
        responseCache = {},
        $resultsContainer = $('#search-results'),
        $resultsContainerLoaderWrapper = $('#loader-wrapper'),
        $pageInput = $('#page-input'),
        rowTemplate = _.template(_.unescape($('#list-item-template').html())),
        rowPlus7Template = _.template(_.unescape($('#list-item-plus7-template').html())),
        variantsTemplate = _.template(_.unescape($('#variants-popover-template').html())),
        assetTemplate = _.template(_.unescape($('#assets-item-template').html())),
        attributesTemplate = _.template(_.unescape($('#special-attr-item-template').html())),
        ratingTemplate = _.template(_.unescape($('#rating-item-template').html())),
        loadMoreTemplate = _.template(_.unescape($('#load-more-item-template').html())),
        loadingTemplate = _.template(_.unescape($('#loading-item-template').html())),
        noResultsTemplate = _.template(_.unescape($('#no-results-item-template').html())),
        resultsCounterTemplate = _.template(_.unescape($('#results-counter-template').html()));

    $form.on('change', ':input, input', function (ev) {
        $pageInput.val(1);
        $form.submit();
    });
    /*
    $('#search-results').on('click', '.hotel-list-item header h2', function(){
        var url = $(this).data('offerlink');

        if(_.indexOf(url, '/') !== 0) {
            url = '/' + url;
        }

        window.location.href = ( window.location.protocol + "//" + window.location.hostname + url);

        return false;
    });
    */

    $form.on('submit', function (ev) {
        if (!$(this).data('block-ajax')) {
            that.prepareResultsList(!(parseInt($pageInput.val(), 10) > 1), function () {
                that.requestForResults($form.serialize(), that.processResponse);
            });

            ev.preventDefault();
        }
    });

    $resultsContainer.on('click', 'a#load-more-offers', function (ev) {
        $pageInput.val(parseInt($pageInput.val(), 10) + 1);

        $form.submit();

        ev.preventDefault();
        $(this).parent().remove();
        //$(this).remove();
    });

    $(document).click(function (e) {
        if ((!$(e.target).closest('.popover').length && !$(e.target).data('variants-id')) || $(e.target).hasClass('popover-close')) {

            $('[data-variants-id]').each(function () {
                if ($(this).data('popover') && $(this).data('popover').tip().hasClass('in')) {
                    $(this).popover('hide');
                }
            });
        }
    });

    $('#search-results').on('click', '.popover-close', function (e) {
        e.preventDefault();
    });

    this.requestForResults = function (formData, callback) {
        if (currentXhr) {
            currentXhr.abort();
        }

        if (typeof responseCache[formData] !== 'undefined') {
            callback(responseCache[formData]);
        } else {
            currentXhr = $.getJSON(searchUrl, formData, function (responce) {
                currentXhr = null;
                if (responce.data.source == 'itaka' || responce.data.source == 'sletat') {
                    var exchange_rate = 0;
                    var $exchange_rate = responce.data.source === 'itaka' ?
                        $('.exchange-rates__value_pln') : $('.exchange-rates__value_rub');
                    if ($exchange_rate.length > 0) {
                        exchange_rate = parseFloat($exchange_rate.text());
                    }
                    for (var i = 0; responce.data.results && i < responce.data.results.length; i++) {
                        var tour = responce.data.results[i],
                            price = parseFloat(tour.price.toString().replace(/\&nbsp\;/, "")),
                            ymaxPriceItakaHit = parseFloat(tour.ymaxPriceItakaHit.toString().replace(/\&nbsp\;/, ""));

                        tour.price = Math.round(100 * price * exchange_rate) / 100;
                        tour.ymaxPriceItakaHit = Math.round(100 * ymaxPriceItakaHit * exchange_rate) / 100;
                    }
                }
                callback(responce.data);

                responseCache[formData] = responce.data;
            });
        }
    }

    this.processResponse = function (data) {

        if (parseInt($pageInput.val(), 10) === 1) {
            $('#path h1 span.results-counter').remove();

            if ((parseInt(data.count, 10) > 0) || (data.results && data.results.length > 0)) {
                $('#path h1').append(resultsCounterTemplate({
                    'counter': ((parseInt(data.count, 10) > 0) ? data.count : data.results.length)
                }));
            }
        }

        if (typeof (data) !== 'undefined' && data !== null && typeof (data.results) !== 'undefined' && data.results !== null && data.results.length) {
            var listHtml = '';
            var criteoHotelsList = [];
            for (var i = 0; i < data.results.length; i++) {
                var values = data.results[i];
                values.priceTypeLabel = data.priceTypeLabel;
                values.priceTypeClass = data.priceTypeClass;

                if (typeof values.additionalHotel !== 'undefined') {
                    listHtml += that.generatePlus7Row(values);
                } else {
                    listHtml += that.generateRow(values);
                }
                if (criteoDynamicResults) {
                    criteoHotelsList.push(values.productCode);
                }

            }

            if (criteoDynamicResults) {
                window.criteo_q = window.criteo_q || [];
                window.criteo_q.push({
                    event: "setAccount",
                    account: 20310
                }, {
                    event: "setHashedEmail",
                    email: ""
                }, {
                    event: "setSiteType",
                    type: criteoSetSiteType
                }, {
                    event: "viewList",
                    item: criteoHotelsList
                }, {
                    event: "viewSearch",
                    checkin_date: criteoCheckinDate,
                    checkout_date: criteoCheckOutDate
                });
            }

            if (listHtml.length) {
                $(window).off('scroll.lazybloading');
                $('.results-loading').remove();
                $('.overlay').remove();
                $('.hotel-list-photo-preview-blank').parent().remove();
                listHtml = $(listHtml);

                if (parseInt(data.page, 10) > 1) {
                    $pageInput.val(data.page);
                } else {
                    $pageInput.val(1);
                    $resultsContainer.empty();
                }
                $resultsContainer.append(listHtml);

                if (data.has_more) {
                    $resultsContainer.append(loadMoreTemplate);
                }
                listHtml.find('.read-more-list-desc').each(function () {
                    var $this = $(this);
                    var $prev = $this.prev();
                    $this.toggle($prev.outerHeight() < $prev[0].scrollHeight);
                });

                var mob = false;

                function imageSP() {
                    var w = $(window).width();
                    if (w < 768 && !mob) {
                        var _images = $resultsContainer.find('.hotel-list-photo-preview img[src$="sp.gif"]');
                        _.each(_images, function (element, i) {
                            var $element = $(element),
                                src = $element.attr('src');
                            $element.attr('src', src.replace('sp.gif', 'mobile_sp.gif'));
                        });
                        mob = true;
                    } else if (w >= 768 && mob) {
                        var _images = $resultsContainer.find('.hotel-list-photo-preview img[src$="sp.gif"]');
                        _.each(_images, function (element, i) {
                            var $element = $(element),
                                src = $element.attr('src');
                            $element.attr('src', src.replace('mobile_sp.gif', 'sp.gif'));
                        });
                        mob = false;
                    }
                }
                $(window).on('resize', function () {
                    imageSP();
                }).resize();

                if (mob && parseInt(data.page, 10) === 1) {
                    $(window).scrollTop(0);
                }

                $resultsContainer.find('.hotel-list-photo-preview img[src$="sp.gif"]').lazyload({
                    effect: 'fadeIn',
                    skip_invisible: false,
                    threshold: 300
                });

            } else {
                that.prepareNoResultsList();
            }

        } else {
            that.prepareNoResultsList();
        }

        $("#search-results").trigger("ajaxloaded", [data]);
    };

    this.prepareResultsList = function (clear, callback) {
        var forceClear = clear || false;
        $resultsContainer.removeClass('search-results_none');
        if (forceClear) {
            $("body").animate({
                scrollTop: 0
            }, {
                'duration': 'fast',
                'complete': function () {
                    if (search_loadAjaxResults === 1) {
                        search_loadAjaxResults = 0;
                    } else {
                        $('#search-results').prepend('<div class="overlay" ></div>');
                    }
                    $resultsContainerLoaderWrapper.html(loadingTemplate()).fadeIn('fast', function () {
                        if (typeof (callback) === 'function') {
                            callback();
                            $('.mainbottom').removeClass('mainbottom_no-result');
                        }
                    });
                }
            });
        } else {
            $resultsContainer.append(loadingTemplate);
            if (typeof (callback) === 'function') {
                callback();
            }
        }
    };

    this.prepareNoResultsList = function () {
        $resultsContainer.html(noResultsTemplate).addClass('search-results_none');
        $resultsContainerLoaderWrapper.html('');
        $('.mainbottom').addClass('mainbottom_no-result');
    };

    this.generateRow = function (data) {
        data.ratingHtml = parseFloat(data.rating) ? ratingTemplate({
            ratingHtml: data.rating,
            showOpinionsProjectIframe: data.showOpinionsProjectIframe
        }) : '';
        data.assets = (data.assetsList) ? assetTemplate({
            list: data.assetsList
        }) : '';
        data.offerAttributes = ((data.attributesList && data.attributesList.length > 0)) ? attributesTemplate({
            list: data.attributesList
        }) : '';
        return rowTemplate(data);
    }

    this.generatePlus7Row = function (data) {
        data.ratingHtml = parseFloat(data.rating) ? ratingTemplate({
            ratingHtml: data.rating,
            showOpinionsProjectIframe: data.showOpinionsProjectIframe
        }) : '';
        data.assets = (data.assetsList) ? assetTemplate({
            list: data.assetsList
        }) : '';
        data.offerAttributes = (data.attributesList && data.attributesList.length > 0) ? attributesTemplate({
            list: data.attributesList
        }) : '';

        data.additionalHotel.ratingHtml = parseFloat(data.additionalHotel.rating) ? ratingTemplate({
            ratingHtml: data.additionalHotel.rating,
            showOpinionsProjectIframe: data.showOpinionsProjectIframe
        }) : '';
        data.additionalHotel.assets = (data.additionalHotel.assetsList) ? assetTemplate({
            list: data.additionalHotel.assetsList
        }) : '';
        data.additionalHotel.offerAttributes = (data.additionalHotel.attributesList && data.additionalHotel.attributesList.length > 0) ? attributesTemplate({
            list: data.additionalHotel.attributesList
        }) : '';

        return rowPlus7Template(data);
    }
}
var searchForm;
$(function () {
    if (typeof SearchForm !== 'undefined' && typeof search_validateUrl !== 'undefined') {
        searchForm = new SearchForm($('#search-form'), search_validateUrl);

        //searchForm.initDateButton();
        //searchForm.initDateInputs(true);

        searchForm.initCookieFiltersTailsPrice();
        searchForm.initCookieChildrenDate();

        searchForm.initDateInputs2(true, true);
        //searchForm.dateShowWatcher();

        //searchForm.initDropdownInput($('#duration-select'));
        searchForm.initDropdownSelect($('#duration-select'));

        // ========================
        /**
         * @todo: Alias select switch
         */
        //        searchForm.initDropdownInput($('#destination-select'), function() {
        //            if(search_staticSelectedDestination) {
        //                $('#search-form').data('block-ajax', 'true').attr('action', search_mainSearchPageUrl);
        //            }
        //        });
        //        searchForm.initDropdownInput($('#departure-select'));
        //        searchForm.initDropdownInput($('#foods-select'));
        //        searchForm.initDropdownInput($('#category-select'));

        // searchForm.initDropdownSelect($('#destination-select'), true, true);
        searchForm.initDropdownSelect($('#destination-select-popup'), true, true);
        searchForm.initDropdownSelect($('#departures-select'));

        if (!$(".impt11").length > 0) {

            searchForm.initDropdownSelect($('#foods-select'));
            searchForm.initDropdownSelect($('#standard-select'), false, false, '');
            searchForm.initDropdownSelect($('#price-select'), false, false, '');
            searchForm.initDropdownSelect($('#categories-select'));
            searchForm.initDropdownSelect($('#promotions-select'));
            searchForm.initDropdownSelect($('#easements-select'));
            searchForm.initDropdownSelect($('#grade-select'));

        } else {

            searchForm.initDropdownSelect($('#activity-select'));
            searchForm.initDropdownSelect($('#difficulty-select'));
        }

        searchForm.initDropdownSelect($('#order-select'), false, false, null, true);
        // searchForm.initDropdownMultiselect($('#destination-select'));
        searchForm.initDropdownMultiselect($('#departure-select'));
        searchForm.initDropdownMultiselect($('#food-select'));
        // searchForm.initDropdownMultiselect($('#category-select'));

        // ======================

        searchForm.initParticipantsInput();

        searchForm.initFilters();
        //searchForm.initOrderInput();
        searchForm.initViewTypeInput();
        searchForm.initPriceTypeInput();

        searchForm.initValidation();
        searchForm.validate();

        searchForm.initFiltersToggle();

    }
});
var SearchFormDestinationPlugin = function (e) {
    "use strict";

    function t() {
        m.find(".selectable").removeClass("open"),
            m.removeClass("pushItUp"),
            $(".popup-bg").hide(),
            $("#head, .searchbig_header").attr("style", "display: block"),
            $("body, html").css({
                overflow: "auto",
                height: "auto"
            })
    }

    function o() {
        m.addClass("pushItUp"),
            y && $("body, html").css({
                overflow: "hidden",
                height: "100%"
            }),
            $(window).width() < 768 && $("#head, .searchbig_header").attr("style", "display: none !important"),
            l(),
            $(".options-filter-input").keyup(function () {
                $(this).val().length > 0 ? (m.find(".option-child").removeClass("option-disabled-search").addClass("option-common-appearance"),
                    m.find(".option-parent").addClass("option-common-appearance")) : (m.find(".option-child").addClass("option-disabled-search").removeClass("option-common-appearance"),
                    m.find(".option-parent").removeClass("option-common-appearance"))
            }),
            p()
    }

    function n(e) {
        e.preventDefault(),
            e.stopPropagation()
    }

    function i(e, t) {
        b = $(t)
    }

    function a() {
        m.addClass("alreadyset");
        var t = _.template(_.unescape($("#destination-popup-header-template").html())),
            o = _.template(_.unescape($("#destination-popup-footer-template").html())),
            n = _.template(_.unescape($("#popup-bg-template").html())),
            i = _.template(_.unescape($("#destination-popup-regions-template").html()));
        m.find(".option-parent").each(function () {
            var e = $(this);
            e.attr("data-children-counter", 0),
                $(this).nextUntil(".option-parent").each(function () {
                    var t = parseInt(e.attr("data-children-counter"));
                    t++,
                    e.attr("data-children-counter", t)
                });
            var t = parseInt(e.attr("data-children-counter"));
            t > 0 && e.find("label").append(i({
                parentNodeChildrenCounter: t
            }))
        });
        var a = $(e.sourceRowSelector + " .selectable-container");
        a.find(".destination-popup-header").remove(),
            a.find(".destination-popup-footer").remove(),
            a.prepend(t({
                title: m.data("popup-title")
            })),
            a.append(o({
                showRegions: e.hesRegions
            })),
            $("body").append(n),
            m.find(".option-parent-children-counter").on("click", function (e) {
                e.preventDefault(),
                    e.stopPropagation();
                var t = 0,
                    o = $(this),
                    n = null,
                    i = o.closest(".option-parent");
                i.nextUntil(".option-parent").each(function () {
                        $(this).hasClass("option-disabled") || (t++,
                            n = $(this).toggleClass("child-active"))
                    }),
                    t % 2 === 1 && n.addClass("last-child-odd"),
                    t > 0 && n.addClass("last-child"),
                    i.toggleClass("parent-active"),
                    f(),
                    g(i.position().top)
            }),
            k && f(),
            m.find(".option-child").removeClass("child-active").addClass("option-disabled-search");
        var l = m.data("placeholder-trans");
        $(e.sourceRowSelector + " .options-filter-input").attr("placeholder", l),
            p()
    }

    function l() {
        if (!y) {
            var e = 3;
            e = $(window).width() > 1024 ? 3 : $(window).width() > 992 ? 3 : 2;
            var t = m.find("fieldset.sort-alphabetically").children("ul"),
                o = t.find("li.option-parent").filter(":not(.unavailable)"),
                n = Math.ceil(o.length / e) - 1;
            t.children("div").contents().unwrap();
            var i = [],
                a = 0;
            _.each(t.children("li"), function (t, o) {
                    $(t).hasClass("option-parent") && (a > n && (s(i, e),
                                a = 0,
                                i = []),
                            $(t).hasClass("unavailable") || a++),
                        i.push(t)
                }),
                i.length && s(i, e),
                k = !0,
                $(window).trigger("destinationPopupColumnsReady")
        }
    }

    function s(e, t) {
        $(e).wrapAll('<div class="column-' + t + '" />')
    }

    function c(t) {
        t.preventDefault(),
            t.stopPropagation(),
            m.find(":selected").each(function () {
                $(this).prop("selected", !1)
            }),
            $(e.targetForSelectChange).trigger("changed.selectable"),
            m.find(".active").removeClass("active"),
            $(e.sourceRowSelector).find(".half-active").removeClass("half-active"),
            w.find("option:selected").prop("selected", !1),
            d("selectAll")
    }

    function r(t) {
        t.preventDefault(),
            t.stopPropagation(),
            S.each(function () {
                $(this).prop("selected", !0),
                    w.find('option[value="' + $(this).val() + '"]').prop("selected", !0)
            }),
            $(e.targetForSelectChange).trigger("changed.selectable"),
            d("unselectAll")
    }

    function d(t) {
        var o = $(e.sourceRowSelector).find(".destination-toggle-translate");
        "selectAll" === t ? $(e.sourceRowSelector + " .destination-toggle").removeClass("destination-clear").addClass("destination-all").html(o.attr("data-on-all")) : $(e.sourceRowSelector + " .destination-toggle").removeClass("destination-all").addClass("destination-clear").html(o.attr("data-off-all"))
    }

    function p() {
        S.length || (S = m.find("option").not(":disabled")),
            m.find("option:selected").length >= S.length && d("unselectAll")
    }

    function h(t) {
        t.preventDefault(),
            t.stopPropagation();
        var o = $(".destination-regions-open"),
            n = $(".destination-regions-close");
        o.is(":visible") ? (n.css("display", "inline-block"),
                o.css("display", "none"),
                $(e.sourceRowSelector).find(".option-child").addClass("child-active").removeClass("option-disabled-search"),
                $(e.sourceRowSelector).find(".option-parent").each(function () {
                    var e = $(this).attr("data-children-counter");
                    "undefined" != typeof e && e !== !1 && parseInt(e) > 0 && $(this).addClass("parent-active")
                })) : (n.css("display", "none"),
                o.css("display", "inline-block"),
                $(e.sourceRowSelector).find(".option-child").removeClass("child-active").addClass("option-disabled-search"),
                $(e.sourceRowSelector).find(".option-parent").each(function () {
                    var e = $(this).attr("data-children-counter");
                    "undefined" != typeof e && e !== !1 && parseInt(e) > 0 && $(this).removeClass("parent-active")
                })),
            f()
    }

    function u(e) {
        $(e.currentTarget).val()
    }

    function f() {
        null !== b && ("object" != typeof b.data("jsp") ? (b.jScrollPane({
                showArrows: !0,
                horizontalGutter: 30,
                verticalGutter: 30,
                mouseWheelSpeed: 30,
                contentWidth: "0px"
            }),
            b.on("click", ".jspVerticalBar", function (e) {
                e.stopPropagation()
            }),
            b.height(b.data("jsp").getContentHeight()),
            b.data("jsp").reinitialise()) : (b.height(b.data("jsp").getContentHeight()),
            b.data("jsp").reinitialise()))
    }

    function g(e) {
        if (null !== b && "object" == typeof b.data("jsp")) {
            var t = b.data("jsp");
            t.scrollToY(parseInt(e))
        }
    }

    function v() {
        w.find("option:selected").length > 0 ? $(e.sourceRowSelector).find(".destination-clear").removeClass("inactive") : $(e.sourceRowSelector).find(".destination-clear").addClass("inactive")
    }
    var m = $(e.sourceRowSelector),
        w = $(e.sourceSelectSelector),
        b = null;
    m.on("click.selectable", function () {
        $(this).hasClass("alreadyset") || a()
    });
    var C = $("body"),
        y = C.hasClass("is_mobile"),
        k = !1,
        S = [];
    m.on("click", ".destination-regions", h),
        m.on("click", ".destination-clear", c),
        m.on("click", ".destination-all", r),
        m.on("click", "button.selectable", o),
        m.on("click", ".destination-popup-header", n),
        m.on("click", ".destination-popup-footer", n),
        m.on("click", ".options-filter-container", n),
        m.on("click", ".destination-submit", t),
        m.on("click", ".destination-popup-close", t),
        m.on("click", ".options-container", v),
        C.on("click", ".popup-bg", t),
        m.on("input", ".options-filter-input", u),
        $("#search-form").on("reinitNEW.newselect", i),
        $(window).on("reinit.scrollpane", l),
        $(window).resize(function () {
            $(window).width() >= 768 && $("#head, .searchbig_header").attr("style", "display: block")
        })
};
var draggableElement = function (e, t) {
    "use strict";

    function o(e) {
        return $(window).width() < 768 ? (s.css({
                left: "0px",
                top: "0px",
                right: "auto",
                bottom: "auto"
            }),
            !1) : (l.dragging = !0,
            s.append('<div class="clickblock"></div>'),
            h.css({
                width: s.outerWidth(),
                height: s.outerHeight()
            }).show(),
            r = [s.offset().left, s.offset().top - $(window).scrollTop()],
            void(p = [e.clientX, e.clientY]))
    }

    function n(e) {
        l.dragging = !1,
            s.removeClass("disableclick"),
            h.hide(),
            s.children(".clickblock").remove(),
            a()
    }

    function i(e) {
        var t = [p[0] - e.clientX, p[1] - e.clientY];
        d[0] = r[0] - t[0],
            d[1] = r[1] - t[1],
            h.css({
                left: d[0] + "px",
                top: d[1] + "px",
                right: "auto",
                bottom: "auto"
            })
    }

    function a() {
        var e = $(window).width() - s.outerWidth(),
            t = $(window).height() - s.outerHeight();
        d[0] < 0 ? d[0] = 0 : d[0] > e && (d[0] = e),
            d[1] < 0 ? d[1] = 0 : d[1] > t && (d[1] = t),
            s.css({
                left: d[0] + "px",
                top: d[1] + "px",
                right: "auto",
                bottom: "auto"
            })
    }
    this.dragging = !1;
    var l = this,
        s = $(e),
        c = $(t),
        r = [0, 0],
        d = [0, 0],
        p = [0, 0],
        h = s.clone();
    h.attr("class", "draggableShadow").children().remove(),
        h.appendTo("body").hide(),
        this.init = function () {
            $("body").hasClass("is_mobile") || (c || (c = s),
                c.on("mousedown", o),
                $(document).on("mouseup", function (e) {
                    l.dragging && n(e)
                }).on("mousemove", function (e) {
                    l.dragging && i(e)
                }))
        },
        this.init()
};
SearchFormDestinationPlugin({
    sourceRowSelector: '.destination-popup',
    sourceSelectSelector: '#destination-select-popup',
    targetForSelectChange: '#destination-select-popup'
});

$(function () {
    /*global search_loadAjaxResults:true */
    /*jshint validthis:true */
    "use strict";
    if (typeof SearchResults !== 'undefined' && typeof search_resultsUrl !== 'undefined') {
        var searchResults = new SearchResults($('#search-form'), search_resultsUrl);

        if (search_loadAjaxResults) {
            searchResults.prepareResultsList(true, function () {
                searchResults.requestForResults($('#search-form').serialize(), searchResults.processResponse);
            });
        } else {
            if (search_has_more === 1) {
                $('#search-results').append(_.template(_.unescape($('#load-more-item-template').html())));
            }
            $('#search-results').find('.hotel-list-photo-preview img[src$="sp.gif"]').lazyload({
                effect: 'fadeIn',
                skip_invisible: false,
                threshold: 300
            });
            search_loadAjaxResults = true;
        }

        $("#search-results").on('click', '.hotel-list-description-show', function () {
            if ($(this).parent().hasClass('description-show')) {
                $(this).parent().removeClass('description-show');
            } else {
                $(this).parent().addClass('description-show');
            }
        });

        /*searchForm.initDateButton();
        searchForm.initDateInputs();
        searchForm.initDropdownInput($('#duration-select'));
        searchForm.initDropdownInput($('#destination-select'));
        searchForm.initDropdownInput($('#departure-select'));
        searchForm.initDropdownInput($('#foods-select'));
        searchForm.initDropdownInput($('#category-select'));

        searchForm.initParticipantsInput();

        searchForm.initValidation();*/

    }
    if (window.top !== window.self) {
        setInterval(sendMessToIFrame, 1000);
    }

    function sendMessToIFrame() {
        if (!!window.postMessage) {
            //We are using the parent object because this iframe is sending the message to its parent //'*' is used so that the message is broadcasted to any parent who may use it //In a real example do not use '*' it may become a vector for security problems

            parent.postMessage(checkHeight(), '*');
            //myMessage should be define in your function

        }
    }

    function checkHeight() {
        return $('html').height();
    }

    $("#main").on('click.gallery', '.hotel-list-photo-preview', searchResultsGallery);

    function searchResultsGallery(ev) {
        if ($(window).width() < 1200) {
            return true;
        }
        ev.preventDefault();

        var imagesData = $(this).find('.hotel-list-gallery').data('search-gallery');
        var imagesList = imagesData.split(",");
        var items = [];
        for (var i = 0; i < imagesList.length; i++) {
            items.push({
                src: imagesList[i],
                type: 'image'
            });
        }
        $.magnificPopup.open({
            items: items,
            type: 'image',
            gallery: {
                tPrev: 'Wcześniejszy (przycisk lewej strzłaki)',
                // Alt text on left arrow
                tNext: 'Następny (przycisk prawej strzałki)',
                // Alt text on right arrow
                tCounter: '%curr% z %total%',
                // Markup for "1 of 7" counter
                enabled: true
            },
            image: {
                tError: 'Przepraszamy - problem przy ładowaniu zdjęcia.' // Error message when image could not be loaded
            }
        });
        //console.log ("click");
    }
});