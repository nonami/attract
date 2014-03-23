$(document).ready(function() {
    /*
    $('.dd_status').click(function() {
        // Only call notifications when opening the dropdown
        if(!$(this).hasClass('open')) {
           console.log('open');
           $(this).children('.dropdown-menu').html('<li><a href="#">Option</a></li>');
        }
    });
    */
    
});

/**
 * While task specific scripts will be in their respective templates,
 * Scripts that cut across the application will reside here..
 */

//String formatting
if (!String.prototype.format) {
    String.prototype.format = function() {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function(match, number) {
            return typeof args[number] !== 'undefined'
                    ? args[number]
                    : match
                    ;
        });
    };
}

function Attract() {
    this.WARNING = "warning";
    this.INFO = "info";
    this.SUCCESS = "success";
    this.ERROR = "error";
    this.TOAST_CONTAINER = "#toast_container";

    this.toastNotifyDefaults = {duration: 10000, align: 'right', type: this.INFO};
    this.toastNotify = function(options) {
        var optionsType = typeof (options);
        if (optionsType === 'object') {
            if (options.hasOwnProperty('message')) {
                options = this.mergeOptions(options);
                var alertClass = this.getAlertClass(options.type);
                var cont_id = this.TOAST_CONTAINER;
                var content = '<div class="attract-toast alert alert-{0}" style="float: {1}">{2}</div>'.format(alertClass, options.align, options.message);
                $(cont_id).html(content).fadeIn();
                var removeToast = setTimeout(function() {
                    $(cont_id).fadeOut('slow', function() {
                        $(this).html('');
                    });
                }, options.duration);
            } else {
                console.log("No message was passed in to options");
            }
        } else {
            console.log(options);
            console.log("Invalid object type passed to method");
        }
    };

    this.getAlertClass = function(type) {
        switch (type) {
            case this.ERROR:
                return "danger";
            case this.INFO:
                return "info";
            case this.SUCCESS:
                return "success";
            case this.WARNING:
                return "warning";
            default:
                return "info";
        }
    };

    this.mergeOptions = function(options) {
        var defaults = this.toastNotifyDefaults;
        for (var i in options) {
            defaults[i] = options[i];
        }
        return defaults;
    };
}


var attract = new Attract();
