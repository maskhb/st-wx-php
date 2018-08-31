// JavaScript Document dialog
/**
 *(**************************** 通用对话框*************************
 */
(function(){
    var elemDialog, elemOverlay, elemContent, elemTitle,
        inited = false,
        body = document.compatMode && document.compatMode !== 'BackCompat' ?
            document.documentElement : document.body,
        cssFixed;

    function init(){
        if (!inited){
            createOverlay();
            createDialog();
            inited = true;
        }
    }

    function createOverlay(){
        if (!elemOverlay){
            elemOverlay = $('<div class="box_overlay"></div>');
            $('body').append(elemOverlay);
        }
    }
    function createDialog(){
        if (!elemDialog){
            elemDialog = $('<div class="dialog">'+
                '<div class="dialog_content"></div>'+
                '</div>');
            elemContent = $('.dialog_content', elemDialog);
            $('body').append(elemDialog);
            elemDialog.fadeIn(300)
        }
    }
    function open(){
        elemDialog.fadeIn();
        elemOverlay.fadeIn();
//		$('select').hide();
    }
    function close(){
        // elemDialog.fadeOut();
        // if(elemOverlay)elemOverlay.fadeOut();
        elemDialog.hide();
        if(elemOverlay)elemOverlay.hide();
        elemContent.empty();
//		$('select').show();
    }

    function setHtml(html){
        elemContent.html(html);
    }
    var Dialog = {
        loading:function(){
            this.open("<p class='dialog_loading'></p>");
        },
        toast:function(){
            var toastTips = "";
            if(arguments[0]!=null)toastTips = arguments[0];
            this.open("<p class='dialog_toast'>"+toastTips+"</p>");
            setTimeout(function(){
                $.Dialog.close();
            },2000)
        },
        success:function(){
            var successTips = "操作成功!";
            if(arguments[0]!=null)successTips = arguments[0];
            this.open("<p class='dialog_success'>"+successTips+"</p>");
            setTimeout(function(){
                $.Dialog.close();
            },2000)
        },
        fail:function(){
            var failTips = "操作失败!";
            if(arguments[0]!=null)failTips = arguments[0];
            this.open("<p class='dialog_fail'>"+failTips+"</p>");
            setTimeout(function(){
                $.Dialog.close();
            },2000)
        },
        confirm:function (confirmTips, callback, options) {
            var cancelText='取消',confirmText = '确认',confirmClose = true;
            if(options){
                if('confirmText' in options){
                    confirmText = options.confirmText;
                }
                if('cancelText' in options){
                    cancelText = options.cancelText;
                }
                if('confirmClose' in options){
                    confirmClose = options.confirmClose;
                }
            }
            if(arguments[0]!=null) confirmTips = arguments[0];
            this.open("<div class=\"dialog_confirm\">\n    <div class=\"confirm_contents\">\n        "+confirmTips+"\n    </div>\n    <div class=\"dialog_buttons\">\n        <button class=\"button_cancel\">"+cancelText+"</button>\n        <button class=\"button_confirm\">"+confirmText+"</button> \n    </div>\n</div>");
            elemContent.find(".button_cancel").click(function (e) {
                if(callback != undefined){
                    callback(false);
                }
                $.Dialog.close();
            });
            elemContent.find(".button_confirm").click(function (e) {
                if(callback != undefined){
                    callback(true);
                }
                if(confirmClose){
                    $.Dialog.close();
                }

            });
        },
        open: function(html){
            init();
            setHtml(html);
            open();
            elemContent.find(".dialog-close-btn").click(function () {
                $.Dialog.close();
            });
        },
        close: close
    };
    $.extend({Dialog: Dialog});
})();