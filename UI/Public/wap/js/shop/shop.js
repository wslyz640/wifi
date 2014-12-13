var utils = {
    getQuery: function (a) {
        var b = arguments[1] || window.location.search,
			c = new RegExp("(^|&)" + a + "=([^&]*)(&|$)"),
			d = b.substr(b.indexOf("?") + 1).match(c);
        return null != d ? d[2] : ""
    },
    namespace: function (str) {
        for (var arr = str.split(","), i = 0, len = arr.length; len > i; i++) for (var arrJ = arr[i].split("."), parent = {}, j = 0, jLen = arrJ.length; jLen > j; j++) {
            var name = arrJ[j],
				child = parent[name];
            0 === j ? eval("(typeof " + name + ')==="undefined"?(' + name + '={}):"";parent=' + name) : parent = parent[name] = "undefined" == typeof child ? {} : child
        }
    },
    urlReplace: function (a, b) {
        var c = b.url || location.search.substring(1),
			d = new RegExp("(^|&)(" + a + "=)([^&]*)"),
			e = b.content ? b.content : "";
        return c.replace(d, function (a, b, c) {
            return e ? b + c + e : ""
        })
    },
    showBubble: function (a, b) {
        if (a) {
            b = b || 1500;
            var c = $("#bubble");
            c.css("opacity", 1), c.hasClass("qb_none") || c.html(a), c.html(a).removeClass("qb_none"), setTimeout(function () {
                c.animate({
                    opacity: 0
                }, 500, "swing", function () {
                    $(this).addClass("qb_none").removeAttr("style")
                })
            }, b)
        }
    },
    showConfirm: function () {
        var a = {
            sureNode: $("#notice-sure"),
            cancelNode: $("#notice-cancel"),
            contentNode: $("#notice-content"),
            dialogNode: $("#message-notice")
        };
        return function (b) {
            function c() {
                f.sureFn.call(this, arguments), e()
            }
            function d() {
                f.cancelFn.call(this, arguments), e()
            }
            function e() {
                a.contentNode.empty(), a.sureNode.html("确定").off("click", c), a.cancelNode.html("取消").off("click", d), a.dialogNode.addClass("qb_none")
            }
            var f = {
                describeText: "",
                sureText: "确定",
                cancelText: "取消",
                showNum: 2,
                sureFn: function () {
                    return !0
                },
                cancelFn: function () {
                    return !0
                }
            };
            $.extend(f, b), a.dialogNode.hasClass("qb_none") && (a.dialogNode.removeClass("qb_none"), a.sureNode.on("click", c), a.cancelNode.on("click", d), 1 == f.showNum && a.cancelNode.addClass("qb_none"), a.sureNode.html(f.sureText), a.cancelNode.html(f.cancelText), a.contentNode.html(f.describeText))
        }
    }(),
    ajaxReq: function (a, b, c) {
        var d = {
            type: "GET",
            url: "",
            data: "",
            dataType: "html",
            timeout: 5e3
        };
        $.extend(d, a), c || (c = function () { }), $.ajax({
            type: d.type,
            url: d.url,
            data: d.data,
            dataType: d.dataType,
            success: function (a) {
                "json" == d.dataType && (a.errCode = parseInt(a.errCode, 10)), b(a)
            },
            error: c
        })
    },
    showAjaxErr: function (a, b) {
        utils.showBubble(a.msgType ? a.errMsg : b)
    },
    strReplace: function (a, b) {
        var c = a;
        for (var d in b) {
            var e = new RegExp("{#" + d + "#}", "g");
            c = c.replace(e, b[d])
        }
        return c
    },
    cssProperty: function () {
        var a = document.documentElement,
			b = "modernizr";
        return {
            injectStyle: function (c, d) {
                var e, f, g = document.createElement("div"),
					h = document.body,
					i = h || document.createElement("body");
                return e = ["&#173;", '<style id="s', b, '">', c, "</style>"].join(""), g.id = b, (h ? g : i).innerHTML += e, i.appendChild(g), h || (i.style.background = "", a.appendChild(i)), f = d(g, c), h ? g.parentNode.removeChild(g) : i.parentNode.removeChild(i), !!f
            },
            pSupport: function (b) {
                for (var c = a.style, d = "Webkit Moz O ms".split(" "), e = b.charAt(0).toUpperCase() + b.substr(1), f = (e + " " + d.join(e + " ") + e).split(" "), g = null, h = 0, i = f.length; i > h; h++) if (f[h] in c) {
                    g = f[h];
                    break
                }
                return g
            },
            has3d: function () {
                var b = !!this.pSupport("perspective");
                return b && "webkitPerspective" in a.style && this.injectStyle("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}", function (a) {
                    b = 9 === a.offsetLeft && 3 === a.offsetHeight
                }), b
            }
        }
    }(),
    getCookie: function (a) {
        var b = new RegExp("(^| )" + a + "(?:=([^;]*))?(;|$)"),
			c = document.cookie.match(b);
        return c ? c[2] ? unescape(c[2]) : "" : null
    },
    delCookie: function (a, b, c, d) {
        var e = utils.getCookie(a);
        if (null != e) {
            var f = new Date;
            f.setMinutes(f.getMinutes() - 1e3), b = b || "/", document.cookie = a + "=;expires=" + f.toGMTString() + (b ? ";path=" + b : "") + (c ? ";domain=" + c : "") + (d ? ";secure" : "")
        }
    },
    setCookie: function (a, b, c, d, e, f) {
        var g = new Date,
			c = arguments[2] || null,
			d = arguments[3] || "/",
			e = arguments[4] || null,
			f = arguments[5] || !1;
        c ? g.setMinutes(g.getMinutes() + parseInt(c)) : "", document.cookie = a + "=" + escape(b) + (c ? ";expires=" + g.toGMTString() : "") + (d ? ";path=" + d : "") + (e ? ";domain=" + e : "") + (f ? ";secure" : "")
    },
    validate: function (rule) {
        function showError(a, b) {
            var c = $("#" + b + "_msg");
            c.removeClass("qb_none").html(a), errArr.push(b)
        }
        var errArr = [];
        return $.each(rule, function (id, item) {
            var node = item.node || $("#" + id),
				value = item.value || node.val().toString();
            if (value = value.replace(/^\s*|\s*$/g, ""), !node.attr("disabled")) {
                var valLen = value.length;
                if (item.dByte && (valLen = value.replace(/[\u0391-\uFFE5]/g, "__").length), item.required) if ("" == value) showError(item.emptyMsg || "您输入的" + item.itemName + "不能为空", id);
                else if ("" == value || item.reg.test(value)) if (item.maxLen && valLen > item.maxLen) showError(item.errMsg || "您输入的" + item.itemName + "超过" + item.maxLen + "个字符", id);
                else if (item.minLen && valLen < item.minLen) showError(item.errMsg || "您输入的" + item.itemName + "不足" + item.minLen + "个字符", id);
                else if (item.minVal && value < item.minVal || item.maxVal && value > item.maxVal) showError("请输入" + item.minVal + "-" + item.maxVal + "的数字", id);
                else {
                    var err = $("#" + id + "_msg");
                    err.addClass("qb_none"), item.callback && eval(item.callback + "(value, err)")
                } else showError(item.errMsg, id);
                else "" == value || item.reg.test(value) ? item.maxLen && valLen > item.maxLen ? showError(item.errMsg || "您输入的" + item.itemName + "超过" + item.maxLen + "个字符", id) : item.minLen && valLen < item.minLen ? showError(item.errMsg || "您输入的" + item.itemName + "不足" + item.minLen + "个字符", id) : $("#" + id + "_msg").addClass("qb_none") : showError(item.errMsg, id)
            }
        }), errArr.length > 0 ? (document.getElementById(errArr[0]).focus(), !1) : !0
    },
    payDeal: function (a, b) {
        var c, d = a.data;
        d.payType ? location.href = window.basePath + "/cn/deal/codSuc.xhtml?dc=" + d.dealCode + "&suin=" + d.sellerUin + "&" + window.baseParam : d.payChannel ? (c = window.basePath + "/cn/minipay/tcallback.xhtml?paySuc=true&dealCode=" + d.dealCode + "&feeCash=" + d.minipayPo.feeCash + "&t=" + (new Date).getTime(), WeixinJSBridge.invoke("getBrandWCPayRequest", {
            appId: d.minipayPo.appId,
            timeStamp: d.minipayPo.timeStamp + "",
            nonceStr: d.minipayPo.nonceStr,
            "package": d.minipayPo.packageStr,
            signType: "SHA1",
            paySign: d.minipayPo.sign
        }, function (a) {
            b && b.removeClass("btn_disabled"), "get_brand_wcpay_request:ok" == a.err_msg && (location.href = c)
        })) : location.href = d.tenpayUrl
    },
    form_submit:function (theForm, eventTarget, eventArgument) {  
                    theForm.__EVENTTARGET.value = eventTarget;
                    theFrom.__EVENTARGUMENT.value = eventArgument;
                    theForm.submit();
    }
};
$(document).ready(function () {
	$(".go_back").on("click", function () {
	    history.go(-1)
	});
});