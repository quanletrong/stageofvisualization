/**
 * Author and copyright: Stefan Haack (https://shaack.com)
 * Repository: https://github.com/shaack/bootstrap-show-notification
 * License: MIT, see file 'LICENSE'
 */
;(function ($) {
    "use strict"

    function Notification(props) {
        // bootstrap 5
        this.props = {
            body: "", // put here the text, shown
            type: "primary", // the appearance
            duration: 5000, // duration till auto-hide, set to `0` to disable auto-hide
            minWidth: "500px",
            maxWidth: "500px", // the notification maxWidth
            shadow: "0 2px 6px rgba(0,0,0,0.2)", // the box-shadow
            zIndex: 1032,
            margin: "4.5rem", // the margin (above maxWidth)
            direction: "prepend", // or "append", the stack direction
            fontWeight: "400",
            isConfirm: true, //show button cancel and confirm: default true
            isIconClose: false, // show icon close: default false
        }
        this.containerId = "bootstrap-show-notification-container"
        for (let prop in props) {
            // noinspection JSUnfilteredForInLoop
            this.props[prop] = props[prop]
        }
        const cssClass = "alert alert-" + this.props.type + " alert-dismissible fade"
        this.id = "id-" + Math.random().toString(36).substr(2)
        this.template =
            "<div class='" + cssClass + "' role='alert' style='cursor: pointer;'>" + this.props.body +
            "<div style='display: flex; align-items: center; justify-content: flex-end'>" +
            (this.props.isConfirm ?
            ("<button type='button' class='date-range-btn-cancel text-danger date-range-button btn-hv-with-bankground' id='closeNotification'>Hủy</button>" +
            "<button type='button' class='date-range-btn-apply date-range-button btn-hv-with-bankground' id='confirmNotification' style='background-color: #3597DD'>Xác nhận</button>")
            : "") +
            "</div>" +
            (this.props.isIconClose ?
            ("<img src='images/v2022/icon/icon-filter-close.png' style='position: absolute; top: 16px; right: 12px; width: 12px; height: 12px; cursor: pointer;' id='iconCloseNotification'>")
            : "") +
            "</div>"

        this.$container = $("#" + this.containerId)
        if (!this.$container.length) {
            this.$container = $("<div id='" + this.containerId + "'></div>")
            $(document.body).append(this.$container)
            const css = "#" + this.containerId + " {" +
                "position: fixed;" +
                "right: calc( 50% - 250px );" +
                // "right: " + this.props.margin + ";" +
                "top: " + this.props.margin + ";" +
                "margin-left: " + this.props.margin + ";" +
                "z-index: " + this.props.zIndex + ";" +
                "}" +
                "#" + this.containerId + " .alert {" +
                "box-shadow: " + this.props.shadow + ";" +
                "min-width: " + this.props.minWidth + ";" +
                "max-width: " + this.props.maxWidth + ";" +
                "font-weight: " + this.props.fontWeight + ";" +
                "font-size: 15px; line-height: 25px; border-radius:6px; padding:10px 30px; text-align:left;" +
                "float: right; clear: right;" +
                "}" +
                // "@media screen and (min-width: 992px) and (max-width: 1199.98px) {" +
                // "#" + this.containerId + " {right: 2.5rem;}" +
                // "}" +
                // "@media screen and (min-width: 768px) and (max-width: 991.98px) {" +
                // "#" + this.containerId + " {right: 1.8rem;}" +
                // "}" +
                // "@media screen and (max-width: 767.98px) {" +
                // "#" + this.containerId + " {right: 0.9rem;}" +
                // "}" +
                "@media screen and (max-width: " + this.props.maxWidth + ") {" +
                "#" + this.containerId + " {max-width: 100%; width: 100%; right: 0; top: 0;}" +
                "#" + this.containerId + " .alert {margin-bottom: 0.25rem;width: auto;float: none;}" +
                "}"
            const head = document.head || document.getElementsByTagName('head')[0]
            const style = document.createElement('style')
            head.appendChild(style)
            style.appendChild(document.createTextNode(css))
        }
        //add background to alert type confirm
        if(this.props.isConfirm) {
            this.backgroundId = "background-show-notification";
            this.$background = $("#" + this.backgroundId);
            if (!this.$background.length) {
                this.background = "<div id='background-show-notification' class='d-none' style='height: 100%;width: 100%;background: rgba(0, 0, 0, 0.4);position: fixed;z-index: 1031;top: 0;'></div>";
                $(document.body).append(this.background);
            }
        }
        this.$element = this.showNotification()
    }
    Notification.prototype.showNotification = function () {
        const $notification = $(this.template);
        const duration = this.props.duration;
        const isAlertConfirm = this.props.isConfirm;
        let hideAlert;
        if (this.props.direction === "prepend") {
            this.$container.prepend($notification)
        } else {
            this.$container.append($notification)
        }
        $notification.addClass("show")
        if(this.props.duration && !this.props.isConfirm) {
            hideAlert = setTimeout(function () {
                $notification.alert("close")
            }, this.props.duration)
            this.$container.mouseover(function () {
                clearTimeout(hideAlert);
            });
            this.$container.mouseout(function () {
                hideAlert = setTimeout(function () {
                    $notification.alert("close")
                }, duration)
            })
        }
        if(this.props.isConfirm) {
            $("#background-show-notification").removeClass("d-none");
        }
        this.$container.find('#closeNotification').click(function () {
            $notification.alert("close")
            $notification.removeClass("show");
            if(isAlertConfirm) {
                $("#background-show-notification").addClass("d-none");
            }
        })
        this.$container.find("#confirmNotification").click(function () {
            $("#background-show-notification").addClass("d-none");
        });
        if(this.props.isIconClose) {
            this.$container.find('#iconCloseNotification').click(function () {
                $notification.alert("close")
                $notification.removeClass("show");
                if(isAlertConfirm) {
                    $("#background-show-notification").addClass("d-none");
                }
            })
        }
        return $notification
    }
    $.extend({
        showNotification: function (props) {
            return new Notification(props)
        }
    })
}(jQuery))