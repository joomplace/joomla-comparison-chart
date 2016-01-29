(function(a) {
    var p, u, v, e, B, m, C, j, y, z, s = 0, d = {}, q = [], r = 0, b = {}, k = [], E = null, n = new Image, H = /\.(jpg|gif|png|bmp|jpeg)(.*)?$/i, S = /[^\.]\.(swf)\s*$/i, I, J = 1, x = 0, w = "", t, g, l = !1, A = a.extend(a("<div/>")[0], {prop: 0}), K = a.browser.msie && 7 > a.browser.version && !window.XMLHttpRequest, L = function() {
        u.hide();
        n.onerror = n.onload = null;
        E && E.abort();
        p.empty()
    }, M = function() {
        !1 === d.onError(q, s, d) ? (u.hide(), l = !1) : (d.titleShow = !1, d.width = "auto", d.height = "auto", p.html('<p id="fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>'), D())
    }, G = function() {
        var c = q[s], b, f, e, g, k, j;
        L();
        d = a.extend({}, a.fn.fancybox.defaults, "undefined" == typeof a(c).data("fancybox") ? d : a(c).data("fancybox"));
        j = d.onStart(q, s, d);
        if (!1 === j)
            l = !1;
        else {
            "object" == typeof j && (d = a.extend(d, j));
            e = d.title || (c.nodeName ? a(c).attr("title") : c.title) || "";
            c.nodeName && !d.orig && (d.orig = a(c).children("img:first").length ? a(c).children("img:first") : a(c));
            "" === e && (d.orig && d.titleFromAlt) && (e = d.orig.attr("alt"));
            b = d.href || (c.nodeName ? a(c).attr("href") : c.href) || null;
            if (/^(?:javascript)/i.test(b) || "#" == b)
                b = null;
            d.type ? (f = d.type, b || (b = d.content)) : d.content ? f = "html" : b && (f = b.match(H) ? "image" : b.match(S) ? "swf" : a(c).hasClass("iframe") ? "iframe" : 0 === b.indexOf("#") ? "inline" : "ajax");
            if (f)
                switch ("inline" == f && (c = b.substr(b.indexOf("#")), f = 0 < a(c).length ? "inline" : "ajax"), d.type = f, d.href = b, d.title = e, d.autoDimensions && ("html" == d.type || "inline" == d.type || "ajax" == d.type ? (d.width = "auto", d.height = "auto") : d.autoDimensions = !1), d.modal && (d.overlayShow = !0, d.hideOnOverlayClick = !1, d.hideOnContentClick = !1, d.enableEscapeButton = !1, d.showCloseButton = !1), d.padding = parseInt(d.padding, 10), d.margin = parseInt(d.margin, 10), p.css("padding", d.padding + d.margin), a(".fancybox-inline-tmp").unbind("fancybox-cancel").bind("fancybox-change", function() {
                        a(this).replaceWith(m.children())
                    }), f){case "html":
                        p.html(d.content);
                        D();
                        break;
                    case "inline":
                        if (!0 === a(c).parent().is("#fancybox-content")) {
                            l = !1;
                            break
                        }
                        a('<div class="fancybox-inline-tmp" />').hide().insertBefore(a(c)).bind("fancybox-cleanup", function() {
                            a(this).replaceWith(m.children())
                        }).bind("fancybox-cancel", function() {
                            a(this).replaceWith(p.children())
                        });
                        a(c).appendTo(p);
                        D();
                        break;
                    case "image":
                        l = !1;
                        a.fancybox.showActivity();
                        n = new Image;
                        n.onerror = function() {
                            M()
                        };
                        n.onload = function() {
                            l = !0;
                            n.onerror = n.onload = null;
                            d.width = n.width;
                            d.height = n.height;
                            a("<img />").attr({id: "fancybox-img", src: n.src, alt: d.title}).appendTo(p);
                            N()
                        };
                        n.src = b;
                        break;
                    case "swf":
                        d.scrolling = "no";
                        g = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + d.width + '" height="' + d.height + '"><param name="movie" value="' + b + '"></param>';
                        k = "";
                        a.each(d.swf, function(a, c) {
                            g += '<param name="' + a + '" value="' + c + '"></param>';
                            k += " " + a + '="' + c + '"'
                        });
                        g += '<embed src="' + b + '" type="application/x-shockwave-flash" width="' + d.width + '" height="' + d.height + '"' + k + "></embed></object>";
                        p.html(g);
                        D();
                        break;
                    case "ajax":
                        l = !1;
                        a.fancybox.showActivity();
                        d.ajax.win = d.ajax.success;
                        E = a.ajax(a.extend({}, d.ajax, {url: b, data: d.ajax.data || {}, error: function(a) {
                                0 < a.status && M()
                            }, success: function(a, c, f) {
                                if (200 == ("object" == typeof f ? f : E).status) {
                                    if ("function" == typeof d.ajax.win) {
                                        j = d.ajax.win(b, a, c, f);
                                        if (!1 === j) {
                                            u.hide();
                                            return
                                        }
                                        if ("string" == typeof j || "object" == typeof j)
                                            a = j
                                    }
                                    p.html(a);
                                    D()
                                }
                            }}));
                        break;
                    case "iframe":
                        N()
                }
            else
                M()
        }
    }, D = function() {
        var c = d.width, b = d.height, c = -1 < c.toString().indexOf("%") ? parseInt((a(window).width() - 2 * d.margin) * parseFloat(c) / 100, 10) + "px" : "auto" == c ? "auto" : c + "px", b = -1 < b.toString().indexOf("%") ? parseInt((a(window).height() - 2 * d.margin) * parseFloat(b) / 100, 10) + "px" : "auto" == b ? "auto" : b + "px";
        p.wrapInner('<div style="width:' + c + ";height:" + b + ";overflow: " + ("auto" == d.scrolling ? "auto" : "yes" == d.scrolling ? "scroll" : "hidden") + ';position:relative;"></div>');
        d.width = p.width();
        d.height = p.height();
        N()
    }, N = function() {
        var c, h;
        u.hide();
        if (e.is(":visible") && !1 === b.onCleanup(k, r, b))
            a.event.trigger("fancybox-cancel"), l = !1;
        else {
            l = !0;
            a(m.add(v)).unbind();
            a(window).unbind("resize.fb scroll.fb");
            a(document).unbind("keydown.fb");
            e.is(":visible") && "outside" !== b.titlePosition && e.css("height", e.height());
            k = q;
            r = s;
            b = d;
            if (b.overlayShow) {
                if (v.css({"background-color": b.overlayColor, opacity: b.overlayOpacity, cursor: b.hideOnOverlayClick ? "pointer" : "auto", height: a(document).height()}), !v.is(":visible")) {
                    if (K)
                        a("select:not(#fancybox-tmp select)").filter(function() {
                            return"hidden" !== this.style.visibility
                        }).css({visibility: "hidden"}).one("fancybox-cleanup", function() {
                            this.style.visibility = "inherit"
                        });
                    v.show()
                }
            } else
                v.hide();
            c = O();
            var f = {}, F = b.autoScale, n = 2 * b.padding;
            f.width = -1 < b.width.toString().indexOf("%") ? parseInt(c[0] * parseFloat(b.width) / 100, 10) : b.width + n;
            f.height = -1 < b.height.toString().indexOf("%") ? parseInt(c[1] * parseFloat(b.height) / 100, 10) : b.height + n;
            if (F && (f.width > c[0] || f.height > c[1]))
                "image" == d.type || "swf" == d.type ? (F = b.width / b.height, f.width > c[0] && (f.width = c[0], f.height = parseInt((f.width - n) / F + n, 10)), f.height > c[1] && (f.height = c[1], f.width = parseInt((f.height - n) * F + n, 10))) : (f.width = Math.min(f.width, c[0]), f.height = Math.min(f.height, c[1]));
            f.top = parseInt(Math.max(c[3] - 20, c[3] + 0.5 * (c[1] - f.height - 40)), 10);
            f.left = parseInt(Math.max(c[2] - 20, c[2] + 0.5 * (c[0] - f.width - 40)), 10);
            g = f;
            w = b.title || "";
            x = 0;
            j.empty().removeAttr("style").removeClass();
            if (!1 !== b.titleShow && (w = a.isFunction(b.titleFormat) ? b.titleFormat(w, k, r, b) : w && w.length ? "float" == b.titlePosition ? '<table id="fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="fancybox-title-float-left"></td><td id="fancybox-title-float-main">' + w + '</td><td id="fancybox-title-float-right"></td></tr></table>' : '<div id="fancybox-title-' + b.titlePosition + '">' + w + "</div>" : !1) && "" !== w)
                switch (j.addClass("fancybox-title-" + b.titlePosition).html(w).appendTo("body").show(), b.titlePosition) {
                    case "inside":
                        j.css({width: g.width - 2 * b.padding, marginLeft: b.padding, marginRight: b.padding});
                        x = j.outerHeight(!0);
                        j.appendTo(B);
                        g.height += x;
                        break;
                    case "over":
                        j.css({marginLeft: b.padding, width: g.width - 2 * b.padding, bottom: b.padding}).appendTo(B);
                        break;
                    case "float":
                        j.css("left", -1 * parseInt((j.width() - g.width - 40) / 2, 10)).appendTo(e);
                        break;
                    default:
                        j.css({width: g.width - 2 * b.padding, paddingLeft: b.padding, paddingRight: b.padding}).appendTo(e)
                }
            j.hide();
            e.is(":visible") ? (a(C.add(y).add(z)).hide(), c = e.position(), t = {top: c.top, left: c.left, width: e.width(), height: e.height()}, h = t.width == g.width && t.height == g.height, m.fadeTo(b.changeFade, 0.3, function() {
                var c = function() {
                    m.html(p.contents()).fadeTo(b.changeFade, 1, P)
                };
                a.event.trigger("fancybox-change");
                m.empty().removeAttr("filter").css({"border-width": b.padding, width: g.width - 2 * b.padding, height: d.autoDimensions ? "auto" : g.height - x - 2 * b.padding});
                h ? c() : (A.prop = 0, a(A).animate({prop: 1}, {duration: b.changeSpeed, easing: b.easingChange, step: Q, complete: c}))
            })) : (e.removeAttr("style"), m.css("border-width", b.padding), "elastic" == b.transitionIn ? (t = R(), m.html(p.contents()), e.show(), b.opacity && (g.opacity = 0), A.prop = 0, a(A).animate({prop: 1}, {duration: b.speedIn, easing: b.easingIn, step: Q, complete: P})) : ("inside" == b.titlePosition && 0 < x && j.show(), m.css({width: g.width - 2 * b.padding, height: d.autoDimensions ? "auto" : g.height - x - 2 * b.padding}).html(p.contents()), e.css(g).fadeIn("none" == b.transitionIn ? 0 : b.speedIn, P)))
        }
    }, P = function() {
        a.support.opacity || (m.get(0).style.removeAttribute("filter"), e.get(0).style.removeAttribute("filter"));
        d.autoDimensions && m.css("height", "auto");
        e.css("height", "auto");
        w && w.length && j.show();
        b.showCloseButton && C.show();
        (b.enableEscapeButton || b.enableKeyboardNav) && a(document).bind("keydown.fb", function(c) {
            if (27 == c.keyCode && b.enableEscapeButton)
                c.preventDefault(), a.fancybox.close();
            else if ((37 == c.keyCode || 39 == c.keyCode) && b.enableKeyboardNav && "INPUT" !== c.target.tagName && "TEXTAREA" !== c.target.tagName && "SELECT" !== c.target.tagName)
                c.preventDefault(), a.fancybox[37 == c.keyCode ? "prev" : "next"]()
        });
        b.showNavArrows ? ((b.cyclic && 1 < k.length || 0 !== r) && y.show(), (b.cyclic && 1 < k.length || r != k.length - 1) && z.show()) : (y.hide(), z.hide());
        b.hideOnContentClick && m.bind("click", a.fancybox.close);
        b.hideOnOverlayClick && v.bind("click", a.fancybox.close);
        a(window).bind("resize.fb", a.fancybox.resize);
        b.centerOnScroll && a(window).bind("scroll.fb", a.fancybox.center);
        "iframe" == b.type && a('<iframe id="fancybox-frame" name="fancybox-frame' + (new Date).getTime() + '" frameborder="0" hspace="0" ' + (a.browser.msie ? 'allowtransparency="true""' : "") + ' scrolling="' + d.scrolling + '" src="' + b.href + '"></iframe>').appendTo(m);
        e.show();
        l = !1;
        a.fancybox.center();
        b.onComplete(k, r, b);
        var c, h;
        k.length - 1 > r && (c = k[r + 1].href, "undefined" !== typeof c && c.match(H) && (h = new Image, h.src = c));
        0 < r && (c = k[r - 1].href, "undefined" !== typeof c && c.match(H) && (h = new Image, h.src = c))
    }, Q = function(a) {
        var d = {width: parseInt(t.width + (g.width - t.width) * a, 10), height: parseInt(t.height + (g.height - t.height) * a, 10), top: parseInt(t.top + (g.top - t.top) * a, 10), left: parseInt(t.left + (g.left - t.left) * a, 10)};
        "undefined" !== typeof g.opacity && (d.opacity = 0.5 > a ? 0.5 : a);
        e.css(d);
        m.css({width: d.width - 2 * b.padding, height: d.height - x * a - 2 * b.padding})
    }, O = function() {
        return[a(window).width() - 2 * b.margin, a(window).height() - 2 * b.margin, a(document).scrollLeft() + b.margin, a(document).scrollTop() + b.margin]
    }, R = function() {
        var c = d.orig ? a(d.orig) : !1, h = {};
        c && c.length ? (h = c.offset(), h.top += parseInt(c.css("paddingTop"), 10) || 0, h.left += parseInt(c.css("paddingLeft"), 10) || 0, h.top += parseInt(c.css("border-top-width"), 10) || 0, h.left += parseInt(c.css("border-left-width"), 10) || 0, h.width = c.width(), h.height = c.height(), h = {width: h.width + 2 * b.padding, height: h.height + 2 * b.padding, top: h.top - b.padding - 20, left: h.left - b.padding - 20}) : (c = O(), h = {width: 2 * b.padding, height: 2 * b.padding, top: parseInt(c[3] + 0.5 * c[1], 10), left: parseInt(c[2] + 0.5 * c[0], 10)});
        return h
    }, T = function() {
        u.is(":visible") ? (a("div", u).css("top", -40 * J + "px"), J = (J + 1) % 12) : clearInterval(I)
    };
    a.fn.fancybox = function(c) {
        if (!a(this).length)
            return this;
        a(this).data("fancybox", a.extend({}, c, a.metadata ? a(this).metadata() : {})).unbind("click.fb").bind("click.fb", function(c) {
            c.preventDefault();
            l || (l = !0, a(this).blur(), q = [], s = 0, c = a(this).attr("rel") || "", !c || "" == c || "nofollow" === c ? q.push(this) : (q = a("a[rel=" + c + "], area[rel=" + c + "]"), s = q.index(this)), G())
        });
        return this
    };
    a.fancybox = function(c, b) {
        var d;
        if (!l) {
            l = !0;
            d = "undefined" !== typeof b ? b : {};
            q = [];
            s = parseInt(d.index, 10) || 0;
            if (a.isArray(c)) {
                for (var e = 0, g = c.length; e < g; e++)
                    "object" == typeof c[e] ? a(c[e]).data("fancybox", a.extend({}, d, c[e])) : c[e] = a({}).data("fancybox", a.extend({content: c[e]}, d));
                q = jQuery.merge(q, c)
            } else
                "object" == typeof c ? a(c).data("fancybox", a.extend({}, d, c)) : c = a({}).data("fancybox", a.extend({content: c}, d)), q.push(c);
            if (s > q.length || 0 > s)
                s = 0;
            G()
        }
    };
    a.fancybox.showActivity = function() {
        clearInterval(I);
        u.show();
        I = setInterval(T, 66)
    };
    a.fancybox.hideActivity = function() {
        u.hide()
    };
    a.fancybox.next = function() {
        return a.fancybox.pos(r + 1)
    };
    a.fancybox.prev = function() {
        return a.fancybox.pos(r - 1)
    };
    a.fancybox.pos = function(a) {
        l || (a = parseInt(a), q = k, -1 < a && a < k.length ? (s = a, G()) : b.cyclic && 1 < k.length && (s = a >= k.length ? 0 : k.length - 1, G()))
    };
    a.fancybox.cancel = function() {
        l || (l = !0, a.event.trigger("fancybox-cancel"), L(), d.onCancel(q, s, d), l = !1)
    };
    a.fancybox.close = function() {
        function c() {
            v.fadeOut("fast");
            j.empty().hide();
            e.hide();
            a.event.trigger("fancybox-cleanup");
            m.empty();
            b.onClosed(k, r, b);
            k = d = [];
            r = s = 0;
            b = d = {};
            l = !1
        }
        if (!l && !e.is(":hidden"))
            if (l = !0, b && !1 === b.onCleanup(k, r, b))
                l = !1;
            else if (L(), a(C.add(y).add(z)).hide(), a(m.add(v)).unbind(), a(window).unbind("resize.fb scroll.fb"), a(document).unbind("keydown.fb"), m.find("iframe").attr("src", K && /^https/i.test(window.location.href || "") ? "javascript:void(false)" : "about:blank"), "inside" !== b.titlePosition && j.empty(), e.stop(), "elastic" == b.transitionOut) {
                t = R();
                var h = e.position();
                g = {top: h.top, left: h.left, width: e.width(), height: e.height()};
                b.opacity && (g.opacity = 1);
                j.empty().hide();
                A.prop = 1;
                a(A).animate({prop: 0}, {duration: b.speedOut, easing: b.easingOut, step: Q, complete: c})
            } else
                e.fadeOut("none" == b.transitionOut ? 0 : b.speedOut, c)
    };
    a.fancybox.resize = function() {
        v.is(":visible") && v.css("height", a(document).height());
        a.fancybox.center(!0)
    };
    a.fancybox.center = function(a) {
        var d, f;
        if (!l && (f = !0 === a ? 1 : 0, d = O(), f || !(e.width() > d[0] || e.height() > d[1])))
            e.stop().animate({top: parseInt(Math.max(d[3] - 20, d[3] + 0.5 * (d[1] - m.height() - 40) - b.padding)), left: parseInt(Math.max(d[2] - 20, d[2] + 0.5 * (d[0] - m.width() - 40) - b.padding))}, "number" == typeof a ? a : 200)
    };
    a.fancybox.init = function() {
        a("#fancybox-wrap").length || (a("body").append(p = a('<div id="fancybox-tmp"></div>'), u = a('<div id="fancybox-loading"><div></div></div>'), v = a('<div id="fancybox-overlay"></div>'), e = a('<div id="fancybox-wrap"></div>')), B = a('<div id="fancybox-outer"></div>').append('<div class="fancybox-bg" id="fancybox-bg-n"></div><div class="fancybox-bg" id="fancybox-bg-ne"></div><div class="fancybox-bg" id="fancybox-bg-e"></div><div class="fancybox-bg" id="fancybox-bg-se"></div><div class="fancybox-bg" id="fancybox-bg-s"></div><div class="fancybox-bg" id="fancybox-bg-sw"></div><div class="fancybox-bg" id="fancybox-bg-w"></div><div class="fancybox-bg" id="fancybox-bg-nw"></div>').appendTo(e), B.append(m = a('<div id="fancybox-content"></div>'), C = a('<a id="fancybox-close"></a>'), j = a('<div id="fancybox-title"></div>'), y = a('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"></span></a>'), z = a('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"></span></a>')), C.click(a.fancybox.close), u.click(a.fancybox.cancel), y.click(function(c) {
            c.preventDefault();
            a.fancybox.prev()
        }), z.click(function(c) {
            c.preventDefault();
            a.fancybox.next()
        }), a.fn.mousewheel && e.bind("mousewheel.fb", function(c, b) {
            if (l)
                c.preventDefault();
            else if (0 == a(c.target).get(0).clientHeight || a(c.target).get(0).scrollHeight === a(c.target).get(0).clientHeight)
                c.preventDefault(), a.fancybox[0 < b ? "prev" : "next"]()
        }), a.support.opacity || e.addClass("fancybox-ie"), K && (u.addClass("fancybox-ie6"), e.addClass("fancybox-ie6"), a('<iframe id="fancybox-hide-sel-frame" src="' + (/^https/i.test(window.location.href || "") ? "javascript:void(false)" : "about:blank") + '" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(B)))
    };
    a.fn.fancybox.defaults = {padding: 10, margin: 40, opacity: !1, modal: !1, cyclic: !1, scrolling: "auto", width: 560, height: 340, autoScale: !0, autoDimensions: !0, centerOnScroll: !1, ajax: {}, swf: {wmode: "transparent"}, hideOnOverlayClick: !0, hideOnContentClick: !1, overlayShow: !0, overlayOpacity: 0.7, overlayColor: "#777", titleShow: !0, titlePosition: "float", titleFormat: null, titleFromAlt: !1, transitionIn: "fade", transitionOut: "fade", speedIn: 300, speedOut: 300, changeSpeed: 300, changeFade: "fast", easingIn: "swing", easingOut: "swing", showCloseButton: !0, showNavArrows: !0, enableEscapeButton: !0, enableKeyboardNav: !0, onStart: function() {
        }, onCancel: function() {
        }, onComplete: function() {
        }, onCleanup: function() {
        }, onClosed: function() {
        }, onError: function() {
        }};
    a(document).ready(function() {
        a.fancybox.init()
    })
})(jplace.jQuery);