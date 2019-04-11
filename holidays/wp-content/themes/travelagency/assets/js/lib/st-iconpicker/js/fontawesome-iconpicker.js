(function(a, b) {
    a.ui = a.ui || {};
    var c, d = Math.max, e = Math.abs, f = Math.round, g = /left|center|right/, h = /top|center|bottom/, i = /[\+\-]\d+(\.[\d]+)?%?/, j = /^\w+/, k = /%$/, l = a.fn.pos;
    function m(a, b, c) {
        return [ parseFloat(a[0]) * (k.test(a[0]) ? b / 100 : 1), parseFloat(a[1]) * (k.test(a[1]) ? c / 100 : 1) ];
    }
    function n(b, c) {
        return parseInt(a.css(b, c), 10) || 0;
    }
    function o(b) {
        var c = b[0];
        if (c.nodeType === 9) {
            return {
                width: b.width(),
                height: b.height(),
                offset: {
                    top: 0,
                    left: 0
                }
            };
        }
        if (a.isWindow(c)) {
            return {
                width: b.width(),
                height: b.height(),
                offset: {
                    top: b.scrollTop(),
                    left: b.scrollLeft()
                }
            };
        }
        if (c.preventDefault) {
            return {
                width: 0,
                height: 0,
                offset: {
                    top: c.pageY,
                    left: c.pageX
                }
            };
        }
        return {
            width: b.outerWidth(),
            height: b.outerHeight(),
            offset: b.offset()
        };
    }
    a.pos = {
        scrollbarWidth: function() {
            if (c !== b) {
                return c;
            }
            var d, e, f = a("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"), g = f.children()[0];
            a("body").append(f);
            d = g.offsetWidth;
            f.css("overflow", "scroll");
            e = g.offsetWidth;
            if (d === e) {
                e = f[0].clientWidth;
            }
            f.remove();
            return c = d - e;
        },
        getScrollInfo: function(b) {
            var c = b.isWindow || b.isDocument ? "" : b.element.css("overflow-x"), d = b.isWindow || b.isDocument ? "" : b.element.css("overflow-y"), e = c === "scroll" || c === "auto" && b.width < b.element[0].scrollWidth, f = d === "scroll" || d === "auto" && b.height < b.element[0].scrollHeight;
            return {
                width: f ? a.pos.scrollbarWidth() : 0,
                height: e ? a.pos.scrollbarWidth() : 0
            };
        },
        getWithinInfo: function(b) {
            var c = a(b || window), d = a.isWindow(c[0]), e = !!c[0] && c[0].nodeType === 9;
            return {
                element: c,
                isWindow: d,
                isDocument: e,
                offset: c.offset() || {
                    left: 0,
                    top: 0
                },
                scrollLeft: c.scrollLeft(),
                scrollTop: c.scrollTop(),
                width: d ? c.width() : c.outerWidth(),
                height: d ? c.height() : c.outerHeight()
            };
        }
    };
    a.fn.pos = function(b) {
        if (!b || !b.of) {
            return l.apply(this, arguments);
        }
        b = a.extend({}, b);
        var c, k, p, q, r, s, t = a(b.of), u = a.pos.getWithinInfo(b.within), v = a.pos.getScrollInfo(u), w = (b.collision || "flip").split(" "), x = {};
        //ƒƒconsole.log(t);
        s = o(t);
        if (t[0].preventDefault) {
            b.at = "left top";
        }
        k = s.width;
        p = s.height;
        q = s.offset;
        r = a.extend({}, q);
        a.each([ "my", "at" ], function() {
            var a = (b[this] || "").split(" "), c, d;
            if (a.length === 1) {
                a = g.test(a[0]) ? a.concat([ "center" ]) : h.test(a[0]) ? [ "center" ].concat(a) : [ "center", "center" ];
            }
            a[0] = g.test(a[0]) ? a[0] : "center";
            a[1] = h.test(a[1]) ? a[1] : "center";
            c = i.exec(a[0]);
            d = i.exec(a[1]);
            x[this] = [ c ? c[0] : 0, d ? d[0] : 0 ];
            b[this] = [ j.exec(a[0])[0], j.exec(a[1])[0] ];
        });
        if (w.length === 1) {
            w[1] = w[0];
        }
        if (b.at[0] === "right") {
            r.left += k;
        } else if (b.at[0] === "center") {
            r.left += k / 2;
        }
        if (b.at[1] === "bottom") {
            r.top += p;
        } else if (b.at[1] === "center") {
            r.top += p / 2;
        }
        c = m(x.at, k, p);
        r.left += c[0];
        r.top += c[1];
        return this.each(function() {
            var g, h, i = a(this), j = i.outerWidth(), l = i.outerHeight(), o = n(this, "marginLeft"), s = n(this, "marginTop"), y = j + o + n(this, "marginRight") + v.width, z = l + s + n(this, "marginBottom") + v.height, A = a.extend({}, r), B = m(x.my, i.outerWidth(), i.outerHeight());
            if (b.my[0] === "right") {
                A.left -= j;
            } else if (b.my[0] === "center") {
                A.left -= j / 2;
            }
            if (b.my[1] === "bottom") {
                A.top -= l;
            } else if (b.my[1] === "center") {
                A.top -= l / 2;
            }
            A.left += B[0];
            A.top += B[1];
            if (!a.support.offsetFractions) {
                A.left = f(A.left);
                A.top = f(A.top);
            }
            g = {
                marginLeft: o,
                marginTop: s
            };
            a.each([ "left", "top" ], function(d, e) {
                if (a.ui.pos[w[d]]) {
                    a.ui.pos[w[d]][e](A, {
                        targetWidth: k,
                        targetHeight: p,
                        elemWidth: j,
                        elemHeight: l,
                        collisionPosition: g,
                        collisionWidth: y,
                        collisionHeight: z,
                        offset: [ c[0] + B[0], c[1] + B[1] ],
                        my: b.my,
                        at: b.at,
                        within: u,
                        elem: i
                    });
                }
            });
            if (b.using) {
                h = function(a) {
                    var c = q.left - A.left, f = c + k - j, g = q.top - A.top, h = g + p - l, m = {
                        target: {
                            element: t,
                            left: q.left,
                            top: q.top,
                            width: k,
                            height: p
                        },
                        element: {
                            element: i,
                            left: A.left,
                            top: A.top,
                            width: j,
                            height: l
                        },
                        horizontal: f < 0 ? "left" : c > 0 ? "right" : "center",
                        vertical: h < 0 ? "top" : g > 0 ? "bottom" : "middle"
                    };
                    if (k < j && e(c + f) < k) {
                        m.horizontal = "center";
                    }
                    if (p < l && e(g + h) < p) {
                        m.vertical = "middle";
                    }
                    if (d(e(c), e(f)) > d(e(g), e(h))) {
                        m.important = "horizontal";
                    } else {
                        m.important = "vertical";
                    }
                    b.using.call(this, a, m);
                };
            }
            i.offset(a.extend(A, {
                using: h
            }));
        });
    };
    a.ui.pos = {
        _trigger: function(a, b, c, d) {
            if (b.elem) {
                b.elem.trigger({
                    type: c,
                    position: a,
                    positionData: b,
                    triggered: d
                });
            }
        },
        fit: {
            left: function(b, c) {
                a.ui.pos._trigger(b, c, "posCollide", "fitLeft");
                var e = c.within, f = e.isWindow ? e.scrollLeft : e.offset.left, g = e.width, h = b.left - c.collisionPosition.marginLeft, i = f - h, j = h + c.collisionWidth - g - f, k;
                if (c.collisionWidth > g) {
                    if (i > 0 && j <= 0) {
                        k = b.left + i + c.collisionWidth - g - f;
                        b.left += i - k;
                    } else if (j > 0 && i <= 0) {
                        b.left = f;
                    } else {
                        if (i > j) {
                            b.left = f + g - c.collisionWidth;
                        } else {
                            b.left = f;
                        }
                    }
                } else if (i > 0) {
                    b.left += i;
                } else if (j > 0) {
                    b.left -= j;
                } else {
                    b.left = d(b.left - h, b.left);
                }
                a.ui.pos._trigger(b, c, "posCollided", "fitLeft");
            },
            top: function(b, c) {
                a.ui.pos._trigger(b, c, "posCollide", "fitTop");
                var e = c.within, f = e.isWindow ? e.scrollTop : e.offset.top, g = c.within.height, h = b.top - c.collisionPosition.marginTop, i = f - h, j = h + c.collisionHeight - g - f, k;
                if (c.collisionHeight > g) {
                    if (i > 0 && j <= 0) {
                        k = b.top + i + c.collisionHeight - g - f;
                        b.top += i - k;
                    } else if (j > 0 && i <= 0) {
                        b.top = f;
                    } else {
                        if (i > j) {
                            b.top = f + g - c.collisionHeight;
                        } else {
                            b.top = f;
                        }
                    }
                } else if (i > 0) {
                    b.top += i;
                } else if (j > 0) {
                    b.top -= j;
                } else {
                    b.top = d(b.top - h, b.top);
                }
                a.ui.pos._trigger(b, c, "posCollided", "fitTop");
            }
        },
        flip: {
            left: function(b, c) {
                a.ui.pos._trigger(b, c, "posCollide", "flipLeft");
                var d = c.within, f = d.offset.left + d.scrollLeft, g = d.width, h = d.isWindow ? d.scrollLeft : d.offset.left, i = b.left - c.collisionPosition.marginLeft, j = i - h, k = i + c.collisionWidth - g - h, l = c.my[0] === "left" ? -c.elemWidth : c.my[0] === "right" ? c.elemWidth : 0, m = c.at[0] === "left" ? c.targetWidth : c.at[0] === "right" ? -c.targetWidth : 0, n = -2 * c.offset[0], o, p;
                if (j < 0) {
                    o = b.left + l + m + n + c.collisionWidth - g - f;
                    if (o < 0 || o < e(j)) {
                        b.left += l + m + n;
                    }
                } else if (k > 0) {
                    p = b.left - c.collisionPosition.marginLeft + l + m + n - h;
                    if (p > 0 || e(p) < k) {
                        b.left += l + m + n;
                    }
                }
                a.ui.pos._trigger(b, c, "posCollided", "flipLeft");
            },
            top: function(b, c) {
                a.ui.pos._trigger(b, c, "posCollide", "flipTop");
                var d = c.within, f = d.offset.top + d.scrollTop, g = d.height, h = d.isWindow ? d.scrollTop : d.offset.top, i = b.top - c.collisionPosition.marginTop, j = i - h, k = i + c.collisionHeight - g - h, l = c.my[1] === "top", m = l ? -c.elemHeight : c.my[1] === "bottom" ? c.elemHeight : 0, n = c.at[1] === "top" ? c.targetHeight : c.at[1] === "bottom" ? -c.targetHeight : 0, o = -2 * c.offset[1], p, q;
                if (j < 0) {
                    q = b.top + m + n + o + c.collisionHeight - g - f;
                    if (b.top + m + n + o > j && (q < 0 || q < e(j))) {
                        b.top += m + n + o;
                    }
                } else if (k > 0) {
                    p = b.top - c.collisionPosition.marginTop + m + n + o - h;
                    if (b.top + m + n + o > k && (p > 0 || e(p) < k)) {
                        b.top += m + n + o;
                    }
                }
                a.ui.pos._trigger(b, c, "posCollided", "flipTop");
            }
        },
        flipfit: {
            left: function() {
                a.ui.pos.flip.left.apply(this, arguments);
                a.ui.pos.fit.left.apply(this, arguments);
            },
            top: function() {
                a.ui.pos.flip.top.apply(this, arguments);
                a.ui.pos.fit.top.apply(this, arguments);
            }
        }
    };
    (function() {
        var b, c, d, e, f, g = document.getElementsByTagName("body")[0], h = document.createElement("div");
        b = document.createElement(g ? "div" : "body");
        d = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        };
        if (g) {
            a.extend(d, {
                position: "absolute",
                left: "-1000px",
                top: "-1000px"
            });
        }
        for (f in d) {
            b.style[f] = d[f];
        }
        b.appendChild(h);
        c = g || document.documentElement;
        c.insertBefore(b, c.firstChild);
        h.style.cssText = "position: absolute; left: 10.7432222px;";
        e = a(h).offset().left;
        a.support.offsetFractions = e > 10 && e < 11;
        b.innerHTML = "";
        c.removeChild(b);
    })();
})(jQuery);

(function(a) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([ "jquery" ], a);
    } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
        a(window.jQuery);
    }
})(function(a) {
    "use strict";
    var b = {
        isEmpty: function(a) {
            return a === false || a === "" || a === null || a === undefined;
        },
        isEmptyObject: function(a) {
            return this.isEmpty(a) === true || a.length === 0;
        },
        isElement: function(b) {
            return a(b).length > 0;
        },
        isString: function(a) {
            return typeof a === "string" || a instanceof String;
        },
        isArray: function(b) {
            return a.isArray(b);
        },
        inArray: function(b, c) {
            return a.inArray(b, c) !== -1;
        },
        throwError: function(a) {
            throw "Font Awesome Icon Picker Exception: " + a;
        }
    };
    var c = function(d, e) {
        this._id = c._idCounter++;
        this.element = a(d).addClass("iconpicker-element");
        this._trigger("iconpickerCreate");
        this.options = a.extend({}, c.defaultOptions, this.element.data(), e);
        this.options.templates = a.extend({}, c.defaultOptions.templates, this.options.templates);
        this.options.originalPlacement = this.options.placement;
        this.container = b.isElement(this.options.container) ? a(this.options.container) : false;
        if (this.container === false) {
            this.container = this.element.is("input") ? this.element.parent() : this.element;
        }
        if (this.container.addClass("iconpicker-container").is(".dropdown-menu")) {
            this.options.placement = "inline";
        }
        this.input = this.element.is("input") ? this.element.addClass("iconpicker-input") : false;
        if (this.input === false) {
            this.input = this.container.find(this.options.input);
        }
        this.component = this.container.find(this.options.component).addClass("iconpicker-component");
        if (this.component.length === 0) {
            this.component = false;
        } else {
            this.component.find("i").addClass(this.options.iconComponentBaseClass);
        }
        this._createPopover();
        this._createIconpicker();
        if (this.getAcceptButton().length === 0) {
            this.options.mustAccept = false;
        }
        if (this.container.is(".input-group")) {
            this.container.parent().append(this.popover);
        } else {
            this.container.append(this.popover);
        }
        this._bindElementEvents();
        this._bindWindowEvents();
        this.update(this.options.selected);
        if (this.isInline()) {
            this.show();
        }
        this._trigger("iconpickerCreated");
    };
    c._idCounter = 0;
    c.defaultOptions = {
        title: false,
        selected: false,
        defaultValue: false,
        placement: "bottom",
        collision: "none",
        animation: true,
        hideOnSelect: true,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: "bg-primary",
        icons: [],
        iconBaseClass: "fa",
        iconComponentBaseClass: "fa fa-fw",
        iconClassPrefix: "fa-",
        input: "input",
        component: ".input-group-addon",
        container: false,
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<a class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</a>' + ' <a class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</a>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<div class="iconpicker-item"><i></i></div>'
        }
    };
    c.batch = function(b, c) {
        var d = Array.prototype.slice.call(arguments, 2);
        return a(b).each(function() {
            var b = a(this).data("iconpicker");
            if (!!b) {
                b[c].apply(b, d);
            }
        });
    };
    c.prototype = {
        constructor: c,
        options: {},
        _id: 0,
        _trigger: function(b, c) {
            c = c || {};
            this.element.trigger(a.extend({
                type: b,
                iconpickerInstance: this
            }, c));
        },
        _createPopover: function() {
            this.popover = a(this.options.templates.popover);
            var c = this.popover.find(".popover-title");
            if (!!this.options.title) {
                c.append(a('<div class="popover-title-text">' + this.options.title + "</div>"));
            }
            if (!this.options.searchInFooter && !b.isEmpty(this.options.templates.buttons)) {
                c.append(this.options.templates.search);
            } else if (!this.options.title) {
                c.remove();
            }
            if (this.options.showFooter && !b.isEmpty(this.options.templates.footer)) {
                var d = a(this.options.templates.footer);
                if (!b.isEmpty(this.options.templates.search) && this.options.searchInFooter) {
                    d.append(a(this.options.templates.search));
                }
                if (!b.isEmpty(this.options.templates.buttons)) {
                    d.append(a(this.options.templates.buttons));
                }
                this.popover.append(d);
            }
            if (this.options.animation === true) {
                this.popover.addClass("fade");
            }
            return this.popover;
        },
        _createIconpicker: function() {
            var b = this;
            this.iconpicker = a(this.options.templates.iconpicker);
            var c = function(c) {
                var d = a(this);
                if (d.is("." + b.options.iconBaseClass)) {
                    d = d.parent();
                }
                b._trigger("iconpickerSelect", {
                    iconpickerItem: d,
                    iconpickerValue: b.iconpickerValue
                });
                if (b.options.mustAccept === false) {
                    b.update(d.data("iconpickerValue"));
                    b._trigger("iconpickerSelected", {
                        iconpickerItem: this,
                        iconpickerValue: b.iconpickerValue
                    });
                } else {
                    b.update(d.data("iconpickerValue"), true);
                }
                if (b.options.hideOnSelect && b.options.mustAccept === false) {
                    b.hide();
                }
            };
            for (var d in this.options.icons) {
                var e = a(this.options.templates.iconpickerItem);
                e.find("i").addClass(b.options.iconBaseClass + " " + this.options.iconClassPrefix + this.options.icons[d]);
                e.data("iconpickerValue", this.options.icons[d]).on("click.iconpicker", c);
                this.iconpicker.find(".iconpicker-items").append(e.attr("title", "." + this.getValue(this.options.icons[d])));
            }
            this.popover.find(".popover-content").append(this.iconpicker);
            return this.iconpicker;
        },
        _isEventInsideIconpicker: function(b) {
            var c = a(b.target);
            if ((!c.hasClass("iconpicker-element") || c.hasClass("iconpicker-element") && !c.is(this.element)) && c.parents(".iconpicker-popover").length === 0) {
                return false;
            }
            return true;
        },
        _bindElementEvents: function() {
            var c = this;
            this.getSearchInput().on("keyup", function() {
                c.filter(a(this).val().toLowerCase());
            });
            this.getAcceptButton().on("click.iconpicker", function() {
                var a = c.iconpicker.find(".iconpicker-selected").get(0);
                c.update(c.iconpickerValue);
                c._trigger("iconpickerSelected", {
                    iconpickerItem: a,
                    iconpickerValue: c.iconpickerValue
                });
                if (!c.isInline()) {
                    c.hide();
                }
            });
            this.getCancelButton().on("click.iconpicker", function() {
                if (!c.isInline()) {
                    c.hide();
                }
            });
            this.element.on("focus.iconpicker", function(a) {
                c.show();
                a.stopPropagation();
            });
            if (this.hasComponent()) {
                this.component.on("click.iconpicker", function() {
                    c.toggle();
                });
            }
            if (this.hasInput()) {
                this.input.on("keyup.iconpicker", function(a) {
                    if (!b.inArray(a.keyCode, [ 38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86 ])) {
                        c.update();
                    } else {
                        c._updateFormGroupStatus(c.getValid(this.value) !== false);
                    }
                });
            }
        },
        _bindWindowEvents: function() {
            var b = a(window.document);
            var c = this;
            var d = ".iconpicker.inst" + this._id;
            a(window).on("resize.iconpicker" + d + " orientationchange.iconpicker" + d, function(a) {
                if (c.popover.hasClass("in")) {
                    c.updatePlacement();
                }
            });
            if (!c.isInline()) {
                b.on("mouseup" + d, function(a) {
                    if (!c._isEventInsideIconpicker(a) && !c.isInline()) {
                        c.hide();
                    }
                    a.stopPropagation();
                    a.preventDefault();
                    return false;
                });
            }
            return false;
        },
        _unbindElementEvents: function() {
            this.popover.off(".iconpicker");
            this.element.off(".iconpicker");
            if (this.hasInput()) {
                this.input.off(".iconpicker");
            }
            if (this.hasComponent()) {
                this.component.off(".iconpicker");
            }
            if (this.hasContainer()) {
                this.container.off(".iconpicker");
            }
        },
        _unbindWindowEvents: function() {
            a(window).off(".iconpicker.inst" + this._id);
            a(window.document).off(".iconpicker.inst" + this._id);
        },
        updatePlacement: function(b, c) {
            b = b || this.options.placement;
            this.options.placement = b;
            c = c || this.options.collision;
            c = c === true ? "flip" : c;
            var d = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() ? this.input : this.container,
                collision: c === true ? "flip" : c,
                within: window
            };
            this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
            if (typeof b === "object") {
                return this.popover.pos(a.extend({}, d, b));
            }
            switch (b) {
              case "inline":
                {
                    d = false;
                }
                break;

              case "topLeftCorner":
                {
                    d.my = "right bottom";
                    d.at = "left top";
                }
                break;

              case "topLeft":
                {
                    d.my = "left bottom";
                    d.at = "left top";
                }
                break;

              case "top":
                {
                    d.my = "center bottom";
                    d.at = "center top";
                }
                break;

              case "topRight":
                {
                    d.my = "right bottom";
                    d.at = "right top";
                }
                break;

              case "topRightCorner":
                {
                    d.my = "left bottom";
                    d.at = "right top";
                }
                break;

              case "rightTop":
                {
                    d.my = "left bottom";
                    d.at = "right center";
                }
                break;

              case "right":
                {
                    d.my = "left center";
                    d.at = "right center";
                }
                break;

              case "rightBottom":
                {
                    d.my = "left top";
                    d.at = "right center";
                }
                break;

              case "bottomRightCorner":
                {
                    d.my = "left top";
                    d.at = "right bottom";
                }
                break;

              case "bottomRight":
                {
                    d.my = "right top";
                    d.at = "right bottom";
                }
                break;

              case "bottom":
                {
                    d.my = "center top";
                    d.at = "center bottom";
                }
                break;

              case "bottomLeft":
                {
                    d.my = "left top";
                    d.at = "left bottom";
                }
                break;

              case "bottomLeftCorner":
                {
                    d.my = "right top";
                    d.at = "left bottom";
                }
                break;

              case "leftBottom":
                {
                    d.my = "right top";
                    d.at = "left center";
                }
                break;

              case "left":
                {
                    d.my = "right center";
                    d.at = "left center";
                }
                break;

              case "leftTop":
                {
                    d.my = "right bottom";
                    d.at = "left center";
                }
                break;

              default:
                {
                    return false;
                }
                break;
            }
            this.popover.css({
                display: this.options.placement === "inline" ? "" : "block"
            });
            if (d !== false) {
                this.popover.pos(d).css("maxWidth", a(window).width() - this.container.offset().left - 5);
            } else {
                this.popover.css({
                    top: "auto",
                    right: "auto",
                    bottom: "auto",
                    left: "auto",
                    maxWidth: "none"
                });
            }
            this.popover.addClass(this.options.placement);
            return true;
        },
        _updateComponents: function() {
            this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
            this.iconpicker.find("." + this.options.iconBaseClass + "." + this.options.iconClassPrefix + this.iconpickerValue).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
            if (this.hasComponent()) {
                var a = this.component.find("i");
                if (a.length > 0) {
                    a.attr("class", this.options.iconComponentBaseClass + " " + this.getValue());
                } else {
                    this.component.html(this.getValueHtml());
                }
            }
        },
        _updateFormGroupStatus: function(a) {
            if (this.hasInput()) {
                if (a !== false) {
                    this.input.parents(".form-group:first").removeClass("has-error");
                } else {
                    this.input.parents(".form-group:first").addClass("has-error");
                }
                return true;
            }
            return false;
        },
        getValid: function(c) {
            if (!b.isString(c)) {
                c = "";
            }
            var d = c === "";
            c = a.trim(c.replace(this.options.iconClassPrefix, ""));
            if (b.inArray(c, this.options.icons) || d) {
                return c;
            }
            return false;
        },
        setValue: function(a) {
            var b = this.getValid(a);
            if (b !== false) {
                this.iconpickerValue = b;
                this._trigger("iconpickerSetValue", {
                    iconpickerValue: b
                });
                return this.iconpickerValue;
            } else {
                this._trigger("iconpickerInvalid", {
                    iconpickerValue: a
                });
                return false;
            }
        },
        getValue: function(a) {
            return this.options.iconClassPrefix + (a ? a : this.iconpickerValue);
        },
        getValueHtml: function() {
            return '<i class="' + this.options.iconBaseClass + " " + this.getValue() + '"></i>';
        },
        setSourceValue: function(a) {
            a = this.setValue(a);
            if (a !== false && a !== "") {
                if (this.hasInput()) {
                    this.input.val(this.getValue());
                } else {
                    this.element.data("iconpickerValue", this.getValue());
                }
                this._trigger("iconpickerSetSourceValue", {
                    iconpickerValue: a
                });
            }
            return a;
        },
        getSourceValue: function(a) {
            a = a || this.options.defaultValue;
            var b = a;
            if (this.hasInput()) {
                b = this.input.val();
            } else {
                b = this.element.data("iconpickerValue");
            }
            if (b === undefined || b === "" || b === null || b === false) {
                b = a;
            }
            return b;
        },
        hasInput: function() {
            return this.input !== false;
        },
        hasComponent: function() {
            return this.component !== false;
        },
        hasContainer: function() {
            return this.container !== false;
        },
        getAcceptButton: function() {
            return this.popover.find(".iconpicker-btn-accept");
        },
        getCancelButton: function() {
            return this.popover.find(".iconpicker-btn-cancel");
        },
        getSearchInput: function() {
            return this.popover.find(".iconpicker-search");
        },
        filter: function(c) {
            if (b.isEmpty(c)) {
                this.iconpicker.find(".iconpicker-item").show();
                return a(false);
            } else {
                var d = [];
                this.iconpicker.find(".iconpicker-item").each(function() {
                    var b = a(this);
                    var e = b.attr("title").toLowerCase();
                    var f = false;
                    try {
                        f = new RegExp(c, "g");
                    } catch (g) {
                        f = false;
                    }
                    if (f !== false && e.match(f)) {
                        d.push(b);
                        b.show();
                    } else {
                        b.hide();
                    }
                });
                return d;
            }
        },
        show: function() {
            if (this.popover.hasClass("in")) {
                return false;
            }
            a.iconpicker.batch(a(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
            this._trigger("iconpickerShow");
            this.updatePlacement();
            this.popover.addClass("in");
            setTimeout(a.proxy(function() {
                this.popover.css("display", this.isInline() ? "" : "block");
                this._trigger("iconpickerShown");
            }, this), this.options.animation ? 300 : 1);
        },
        hide: function() {
            if (!this.popover.hasClass("in")) {
                return false;
            }
            this._trigger("iconpickerHide");
            this.popover.removeClass("in");
            setTimeout(a.proxy(function() {
                this.popover.css("display", "none");
                this.getSearchInput().val("");
                this.filter("");
                this._trigger("iconpickerHidden");
            }, this), this.options.animation ? 300 : 1);
        },
        toggle: function() {
            if (this.popover.is(":visible")) {
                this.hide();
            } else {
                this.show(true);
            }
        },
        update: function(a, b) {
            a = a ? a : this.getSourceValue(this.iconpickerValue);
            this._trigger("iconpickerUpdate");
            if (b === true) {
                a = this.setValue(a);
            } else {
                a = this.setSourceValue(a);
                this._updateFormGroupStatus(a !== false);
            }
            if (a !== false) {
                this._updateComponents();
            }
            this._trigger("iconpickerUpdated");
            return a;
        },
        destroy: function() {
            this._trigger("iconpickerDestroy");
            this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
            this._unbindElementEvents();
            this._unbindWindowEvents();
            a(this.popover).remove();
            this._trigger("iconpickerDestroyed");
        },
        disable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", true);
                return true;
            }
            return false;
        },
        enable: function() {
            if (this.hasInput()) {
                this.input.prop("disabled", false);
                return true;
            }
            return false;
        },
        isDisabled: function() {
            if (this.hasInput()) {
                return this.input.prop("disabled") === true;
            }
            return false;
        },
        isInline: function() {
            return this.options.placement === "inline" || this.popover.hasClass("inline");
        }
    };
    a.iconpicker = c;
    a.fn.iconpicker = function(b) {
        return this.each(function() {
            var d = a(this);
            if (!d.data("iconpicker")) {
                d.data("iconpicker", new c(this, typeof b === "object" ? b : {}));
            }
        });
    };
    c.defaultOptions.icons = [  "glass" ,"music" ,"search" ,"envelope-o" ,"heart" ,"star" ,"star-o" ,"user" ,"film" ,"th-large" ,"th" ,"th-list" ,"check" ,"remove","close","times" ,"search-plus" ,"search-minus" ,"power-off" ,"signal" ,"gear" ,"cog" ,"trash-o" ,"home" ,"file-o" ,"clock-o" ,"road" ,"download" ,"arrow-circle-o-down" ,"arrow-circle-o-up" ,"inbox" ,"play-circle-o" ,"rotate-right" ,"repeat" ,"refresh" ,"list-alt" ,"lock" ,"flag" ,"headphones" ,"volume-off" ,"volume-down" ,"volume-up" ,"qrcode" ,"barcode" ,"tag" ,"tags" ,"book" ,"bookmark" ,"print" ,"camera" ,"font" ,"bold" ,"italic" ,"text-height" ,"text-width" ,"align-left" ,"align-center" ,"align-right" ,"align-justify" ,"list" ,"dedent" ,"outdent" ,"indent" ,"video-camera" ,"photo ","image" ,"picture-o" ,"pencil" ,"map-marker" ,"adjust" ,"tint" ,"edit" ,"pencil-square-o" ,"share-square-o" ,"check-square-o" ,"arrows" ,"step-backward" ,"fast-backward" ,"backward" ,"play" ,"pause" ,"stop" ,"forward" ,"fast-forward" ,"step-forward" ,"eject" ,"chevron-left" ,"chevron-right" ,"plus-circle" ,"minus-circle" ,"times-circle" ,"check-circle" ,"question-circle" ,"info-circle" ,"crosshairs" ,"times-circle-o" ,"check-circle-o" ,"ban" ,"arrow-left" ,"arrow-right" ,"arrow-up" ,"arrow-down" ,"mail-forward" ,"share" ,"expand" ,"compress" ,"plus" ,"minus" ,"asterisk" ,"exclamation-circle" ,"gift" ,"leaf" ,"fire" ,"eye" ,"eye-slash" ,"warning" ,"exclamation-triangle" ,"plane" ,"calendar" ,"random" ,"comment" ,"magnet" ,"chevron-up" ,"chevron-down" ,"retweet" ,"shopping-cart" ,"folder" ,"folder-open" ,"arrows-v" ,"arrows-h" ,"bar-chart-o" ,"bar-chart" ,"twitter-square" ,"facebook-square" ,"camera-retro" ,"key" ,"gears" ,"cogs" ,"comments" ,"thumbs-o-up" ,"thumbs-o-down" ,"star-half" ,"heart-o" ,"sign-out" ,"linkedin-square" ,"thumb-tack" ,"external-link" ,"sign-in" ,"trophy" ,"github-square" ,"upload" ,"lemon-o" ,"phone" ,"square-o" ,"bookmark-o" ,"phone-square" ,"twitter" ,"facebook-f" ,"facebook" ,"github" ,"unlock" ,"credit-card" ,"feed" ,"rss" ,"hdd-o" ,"bullhorn" ,"bell" ,"certificate" ,"hand-o-right" ,"hand-o-left" ,"hand-o-up" ,"hand-o-down" ,"arrow-circle-left" ,"arrow-circle-right" ,"arrow-circle-up" ,"arrow-circle-down" ,"globe" ,"wrench" ,"tasks" ,"filter" ,"briefcase" ,"arrows-alt" ,"group" ,"users" ,"chain" ,"link" ,"cloud" ,"flask" ,"cut" ,"scissors" ,"copy" ,"files-o" ,"paperclip" ,"save" ,"floppy-o" ,"square" ,"navicon" ,"reorder" ,"bars" ,"list-ul" ,"list-ol" ,"strikethrough" ,"underline" ,"table" ,"magic" ,"truck" ,"pinterest" ,"pinterest-square" ,"google-plus-square" ,"google-plus" ,"money" ,"caret-down" ,"caret-up" ,"caret-left" ,"caret-right" ,"columns" ,"unsorted" ,"sort" ,"sort-down" ,"sort-desc" ,"sort-up" ,"sort-asc" ,"envelope" ,"linkedin" ,"rotate-left" ,"undo" ,"legal" ,"gavel" ,"dashboar" ,"tachometer" ,"comment-o" ,"comments-o" ,"flash" ,"bolt" ,"sitemap" ,"umbrella" ,"paste" ,"clipboard" ,"lightbulb-o" ,"exchange" ,"cloud-download" ,"cloud-upload" ,"user-md" ,"stethoscope" ,"suitcase" ,"bell-o" ,"coffee" ,"cutlery" ,"file-text-o" ,"building-o" ,"hospital-o" ,"ambulance" ,"medkit" ,"fighter-jet" ,"beer" ,"h-square" ,"plus-square" ,"angle-double-left" ,"angle-double-right" ,"angle-double-up" ,"angle-double-down" ,"angle-left" ,"angle-right" ,"angle-up" ,"angle-down" ,"desktop" ,"laptop" ,"tablet" ,"mobile-phone" ,"mobile" ,"circle-o" ,"quote-left" ,"quote-right" ,"spinner" ,"circle" ,"mail-reply" ,"reply" ,"github-alt" ,"folder-o" ,"folder-open-o" ,"smile-o" ,"frown-o" ,"meh-o" ,"gamepad" ,"keyboard-o" ,"flag-o" ,"flag-checkered" ,"terminal" ,"code" ,"mail-reply-all" ,"reply-all" ,"star-half-empty" ,"star-half-full" ,"star-half-o" ,"location-arrow" ,"crop" ,"code-fork" ,"unlink" ,"chain-broken" ,"question" ,"info" ,"exclamation" ,"superscript" ,"subscript" ,"eraser" ,"puzzle-piece" ,"microphone" ,"microphone-slash" ,"shield" ,"calendar-o" ,"fire-extinguisher" ,"rocket" ,"maxcdn" ,"chevron-circle-left" ,"chevron-circle-right" ,"chevron-circle-up" ,"chevron-circle-down" ,"html5" ,"css3" ,"anchor" ,"unlock-alt" ,"bullseye" ,"ellipsis-h" ,"ellipsis-v" ,"rss-square" ,"play-circle" ,"ticket" ,"minus-square" ,"minus-square-o" ,"level-up" ,"level-down" ,"check-square" ,"pencil-square" ,"external-link-square" ,"share-square" ,"compass" ,"toggle-down" ,"caret-square-o-down" ,"toggle-up" ,"caret-square-o-up" ,"toggle-right" ,"caret-square-o-right" ,"euro" ,"eur" ,"gbp" ,"dollar" ,"usd" ,"rupee" ,"inr" ,"cny" ,"rmb" ,"yen" ,"jpy" ,"ruble","rouble" ,"rub" ,"won" ,"krw" ,"bitcoin" ,"btc" ,"file" ,"file-text" ,"sort-alpha-asc" ,"sort-alpha-desc" ,"sort-amount-asc" ,"sort-amount-desc" ,"sort-numeric-asc" ,"sort-numeric-desc" ,"thumbs-up" ,"thumbs-down" ,"youtube-square" ,"youtube" ,"xing" ,"xing-square" ,"youtube-play" ,"dropbox" ,"stack-overflow" ,"instagram" ,"flickr" ,"adn" ,"bitbucket" ,"bitbucket-square" ,"tumblr" ,"tumblr-square" ,"long-arrow-down" ,"long-arrow-up" ,"long-arrow-left" ,"long-arrow-right" ,"apple" ,"windows" ,"android" ,"linux" ,"dribbble" ,"skype" ,"foursquare" ,"trello" ,"female" ,"male" ,"gittip" ,"gratipay" ,"sun-o" ,"moon-o" ,"archive" ,"bug" ,"vk" ,"weibo" ,"renren" ,"pagelines" ,"stack-exchange" ,"arrow-circle-o-right" ,"arrow-circle-o-left" ,"toggle-left" ,"caret-square-o-left" ,"dot-circle-o" ,"wheelchair" ,"vimeo-square" ,"turkish-lira" ,"try" ,"plus-square-o" ,"space-shuttle" ,"slack" ,"envelope-square" ,"wordpress" ,"openid" ,"institution" ,"bank" ,"university" ,"mortar-board" ,"graduation-cap" ,"yahoo" ,"google" ,"reddit" ,"reddit-square" ,"stumbleupon-circle" ,"stumbleupon" ,"delicious" ,"digg" ,"pied-piper" ,"pied-piper-alt" ,"drupal" ,"joomla" ,"language" ,"fax" ,"building" ,"child" ,"paw" ,"spoon" ,"cube" ,"cubes" ,"behance" ,"behance-square" ,"steam" ,"steam-square" ,"recycle" ,"automobile" ,"car" ,"cab" ,"taxi" ,"tree" ,"spotify" ,"deviantart" ,"soundcloud" ,"database" ,"file-pdf-o" ,"file-word-o" ,"file-excel-o" ,"file-powerpoint-o" ,"file-photo-o" ,"file-picture-o" ,"file-image-o" ,"file-zip-o" ,"file-archive-o" ,"file-sound-o" ,"file-audio-o" ,"file-movie-o" ,"file-video-o" ,"file-code-o" ,"vine" ,"codepen" ,"jsfiddle" ,"life-bouy" ,"life-buoy" ,"life-saver" ,"support" ,"life-ring" ,"circle-o-notch" ,"ra" ,"rebel" ,"ge" ,"empire" ,"git-square" ,"git" ,"y-combinator-square" ,"yc-square" ,"hacker-news" ,"tencent-weibo" ,"qq" ,"wechat" ,"weixin" ,"send" ,"paper-plane" ,"send-o" ,"paper-plane-o" ,"history" ,"circle-thin" ,"header" ,"paragraph" ,"sliders" ,"share-alt" ,"share-alt-square" ,"bomb" ,"soccer-ball-o" ,"futbol-o" ,"tty" ,"binoculars" ,"plug" ,"slideshare" ,"twitch" ,"yelp" ,"newspaper-o" ,"wifi" ,"calculator" ,"paypal" ,"google-wallet" ,"cc-visa" ,"cc-mastercard" ,"cc-discover" ,"cc-amex" ,"cc-paypal" ,"cc-stripe" ,"bell-slash" ,"bell-slash-o" ,"trash" ,"copyright" ,"at" ,"eyedropper" ,"paint-brush" ,"birthday-cake" ,"area-chart" ,"pie-chart" ,"line-chart" ,"lastfm" ,"lastfm-square" ,"toggle-off" ,"toggle-on" ,"bicycle" ,"bus" ,"ioxhost" ,"angellist" ,"cc" ,"shekel" ,"sheqel" ,"ils" ,"meanpath" ,"buysellads" ,"connectdevelop" ,"dashcube" ,"forumbee" ,"leanpub" ,"sellsy" ,"shirtsinbulk" ,"simplybuilt" ,"skyatlas" ,"cart-plus" ,"cart-arrow-down" ,"diamond" ,"ship" ,"user-secret" ,"motorcycle" ,"street-view" ,"heartbeat" ,"venus" ,"mars" ,"mercury" ,"intersex" ,"transgender" ,"transgender-alt" ,"venus-double" ,"mars-double" ,"venus-mars" ,"mars-stroke" ,"mars-stroke-v" ,"mars-stroke-h" ,"neuter" ,"genderless" ,"facebook-official" ,"pinterest-p" ,"whatsapp" ,"server" ,"user-plus" ,"user-times" ,"hotel" ,"bed" ,"viacoin" ,"train" ,"subway" ,"medium" ,"yc" ,"y-combinator" ,"optin-monster" ,"opencart" ,"expeditedssl" ,"battery-4" ,"battery-full" ,"battery-3" ,"battery-three-quarters" ,"battery-2" ,"battery-half" ,"battery-1" ,"battery-quarter" ,"battery-0" ,"battery-empty" ,"mouse-pointer" ,"i-cursor" ,"object-group" ,"object-ungroup" ,"sticky-note" ,"sticky-note-o" ,"cc-jcb" ,"cc-diners-club" ,"clone" ,"balance-scale" ,"hourglass-o" ,"hourglass-1" ,"hourglass-start" ,"hourglass-2" ,"hourglass-half" ,"hourglass-3" ,"hourglass-end" ,"hourglass" ,"hand-grab-o" ,"hand-rock-o" ,"hand-stop-o" ,"hand-paper-o" ,"hand-scissors-o" ,"hand-lizard-o" ,"hand-spock-o" ,"hand-pointer-o" ,"hand-peace-o" ,"trademark" ,"registered" ,"creative-commons" ,"gg" ,"gg-circle" ,"tripadvisor" ,"odnoklassniki" ,"odnoklassniki-square" ,"get-pocket" ,"wikipedia-w" ,"safari" ,"chrome" ,"firefox" ,"opera" ,"internet-explorer" ,"tv" ,"television" ,"contao" ,"500px" ,"amazon" ,"calendar-plus-o" ,"calendar-minus-o" ,"calendar-times-o" ,"calendar-check-o" ,"industry" ,"map-pin" ,"map-signs" ,"map-o" ,"map" ,"commenting" ,"commenting-o" ,"houzz" ,"vimeo" ,"black-tie" ,"fonticons" ,"reddit-alien" ,"edge" ,"credit-card-alt" ,"codiepie" ,"modx" ,"fort-awesome" ,"usb" ,"product-hunt" ,"mixcloud" ,"scribd" ,"pause-circle" ,"pause-circle-o" ,"stop-circle" ,"stop-circle-o" ,"shopping-bag" ,"shopping-basket" ,"hashtag" ,"bluetooth" ,"bluetooth-b" ,"percent" ];
});