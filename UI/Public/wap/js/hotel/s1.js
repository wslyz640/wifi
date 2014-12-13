(function (window, $, undefined) {
   function createHandler($easycall) {
        var list_count = $easycall.children().length;
        if (list_count > 0) {
            var $doc = $(document),
            fn_showEasyCall = function () {
                $easycall.css('zIndex', $handler.css('zIndex') - 1).fadeIn();
                $EasyCall_MaskLayer.css('zIndex', $easycall.css('zIndex') - 1).show();
                $Handler_MaskLayer.hide();
                $handler.hide();
            },
            fn_hideEasyCall = function () {
                $easycall.fadeOut(function () {
                    $EasyCall_MaskLayer.hide();
                    $handler.show();
                });
            };

            $easycall.bind('tap', fn_hideEasyCall);

            var $handler = $('.wb_easycall-handler').bind('vmousedown', function (e) {
                var $this = $(this);
                $this.addClass('active');
                startX = e.screenX;
                startY = e.screenY;
                startLeft = parseInt($this.css('left'));
                startTop = parseInt($this.css('top'));
                $doc.bind('vmousemove', fn_move);
                $doc.bind('vmouseup', fn_up);
                fn_showEasyCall();
            });

            var $Handler_MaskLayer = $('<div class="ui-popup-screen"></div>').hide().appendTo($handler.parent());
            var $EasyCall_MaskLayer = $('<div class="ui-popup-screen"></div>').hide().appendTo($handler.parent());

            var diff = 0;

            var fn_fix = function (diff) {
                if (diff > 0) {
                    $handler.animate({
                        'left': $doc.width() - $handler.width()
                    }, 220, 'swing', function () {
                        $Handler_MaskLayer.hide();
                    });
                }
                else {
                    $handler.animate({
                        'left': 0
                    }, 220, 'swing', function () {
                        $Handler_MaskLayer.hide();
                    });
                }
            };

            var fn_move = function (e) {
                $Handler_MaskLayer.css('zIndex', $handler.css('zIndex') - 2).show();
                diff = e.screenX - startX;
                $handler.css({
                    'left': startLeft + e.screenX - startX,
                });

                if (Math.abs(diff) > 20) {
                    fn_up();
                }
            };
            var fn_up = function (e) {
                $doc.unbind('vmousemove', fn_move);
                $doc.unbind('vmouseup', fn_up);
                fn_fix(diff);
            };

            var startX = 0, startY = 0, startLeft = 0, startTop = 0;
        }
    }

    function initHander($handler, $easycall, $Handler_MaskLayer, $EasyCall_MaskLayer) {
        var $doc = $(document), startX = 0, startY = 0, startLeft = 0, startTop = 0, flgDraging = false, diff = 0,
            //自动停靠
            fn_fix = function (diff) {
                if (diff > 0) {
                    $handler.animate({
                        'left': $doc.width() - $handler.width()
                    }, 220, 'swing', fn_fix_end);
                }
                else {
                    $handler.animate({
                        'left': 0
                    }, 220, 'swing', fn_fix_end);
                }
            },
            fn_fix_end = function () {
                flgDraging = false;
                $Handler_MaskLayer.hide();
            },
            //拖动进行
            fn_running = function (e) {
                diff = e.screenX - startX;
                $Handler_MaskLayer.show();
                $handler.css({
                    'left': startLeft + diff,
                    'top': startTop + (e.screenY - startY),
                });

                if (Math.abs(diff) > 100) {
                    fn_end();
                }
                return false;
            },
            //拖动结束
            fn_end = function (e) {
                $handler.removeClass('active');
                $doc.unbind('vmousemove', fn_running);
                $doc.unbind('vmouseup', fn_end);
                var pos = $handler.offset();
                $handler.css({
                    'position': 'fixed',
                    'left': pos.left-$(document).scrollLeft(),
                    'top': pos.top - $(document).scrollTop(),
                });


                fn_fix(diff);
                flgDraging = false;
            },

            //拖动开始
            fn_start = function (e) {
                var $this = $(this), pos = $this.offset();
                $this.addClass('active').css({
                    'position': 'absolute',
                    'left': pos.left,
                    'top': pos.top,
                });
                startX = e.screenX;
                startY = e.screenY;
                startLeft = parseInt($this.css('left'));
                startTop = parseInt($this.css('top'));
                $doc.bind('vmousemove', fn_running);
                $doc.bind('vmouseup', fn_end);
                flgDraging = true;
            },

            //点击handler，显示快捷按钮列表
            fn_handle_tap = function () {
                if (!flgDraging) {
                    $EasyCall_MaskLayer.show();
                    $easycall.fadeIn();
                }
                return false;
            };

        var list = $easycall.children('[wb_type]'), list_count = list.length, firstItem = $(list[0]);

        //1.设置$handler图标，用第一作为图标
        //$handler.css('background', $('.icon', firstItem).css('background'));
        var $icon = $('.icon', firstItem);
        $handler.css({
            'background-image': $icon.css('background-image'),
            'background-repeat': $icon.css('background-repeat'),
            'background-position': $icon.css('background-position')
        });

        //2.拖动逻辑
        $handler.bind('vmousedown', fn_start);
        //3.点击逻辑
        if (list_count === 1) {//只有一个快捷拨号，则不出现列表，直接点击操作
            $handler.append('<a style="display:block;position:absolute;width:100%;height:100%;top:0;left:0;" href="' + firstItem.attr('wb_link') + '">&nbsp;</a>');
        } else {//2个和2个以上，显示快捷列表面板
            $EasyCall_MaskLayer.bind('tap', function () {
                $easycall.fadeOut(function () {
                    $EasyCall_MaskLayer.hide();
                });
            });

            $easycall.bind('tap', function () {
                $easycall.fadeOut(function () {
                    $EasyCall_MaskLayer.hide();
                });
            });

            $handler.bind('tap', fn_handle_tap);
        }
    }


    $.widget('mobile.wb_easycall', $.mobile.widget, {
        options: {

        },
        _create: function () {
            var self = this,
			$el = self.element,
            list = $el.children(),
            list_count = list.length,
            $parent = $el.parent(),
            $handler,
            $Handler_MaskLayer,
            $EasyCall_MaskLayer;

            $el.css('zIndex',9000).append('<div class="bg"></div>').addClass('wb_easycall ' + 'child' + list_count).hide();

            list.each(function (index, item) {
                var $item = $(item);
                    $item.addClass('wb_easycall-item no' + (index + 1) + ' ' + item.getAttribute('wb_type'))
                    .html('<a href="' + item.getAttribute('wb_link') + '"><div class="icon"></div><div class="text">' + $item.html() + '</div></a>');
            });

            if (list_count > 0) {
                $handler = $('<div class="wb_easycall-handler"></div>').css('zIndex', 8000).appendTo($parent);
                $Handler_MaskLayer = $('<div class="ui-popup-screen"></div>').css('zIndex', 7999).hide().appendTo($handler.parent());
                $EasyCall_MaskLayer = $('<div class="ui-popup-screen"></div>').css('zIndex', 8999).hide().appendTo($handler.parent());

                initHander($handler, $el, $Handler_MaskLayer, $EasyCall_MaskLayer);
            }
        }
    });

    $(document).bind('pagecreate', function (e) {
        return $(':jqmData(role=\'wb_easycall\')', e.target).wb_easycall();
    });
})(window, jQuery, undefined);