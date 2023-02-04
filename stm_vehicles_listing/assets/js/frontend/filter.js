if (typeof (STMListings) == 'undefined') {
    var STMListings = {};
}

(function ($) {
    "use strict";

    function Filter(form) {
        this.form = form;
        this.ajax_action = ($(this.form).data('action')) ? $(this.form).data('action') : 'listings-result';
        this.init();
    }

    Filter.prototype.init = function () {
        $(this.form).on("submit", $.proxy(this.submit, this));
        this.getTarget().on('click', 'a.page-numbers', $.proxy(this.paginationClick, this));
    };

    Filter.prototype.submit = function (event) {
        event.preventDefault();

        var data = [],
            url = $(this.form).attr('action'),
            sign = url.indexOf('?') < 0 ? '?' : '&';

        $.each($(this.form).serializeArray(), function (i, field) {
            if (field.value != '') {
                if (field.name == 'stm_lat' || field.name == 'stm_lng') {
                    if (field.value != 0) {
                        data.push(field.name + '=' + field.value)
                    }
                } else {
                    data.push(field.name + '=' + field.value)
                }
            }
        });

        url = url + sign + data.join('&');

        this.performAjax(url);
    };

    Filter.prototype.paginationClick = function (event) {
        event.preventDefault();
        var stmTarget = $(event.target).closest('a').attr('href');
        this.performAjax(stmTarget);
    };

    Filter.prototype.pushState = function (url) {
        window.history.pushState('', '', decodeURI(url));
    };

    Filter.prototype.performAjax = function (url) {
        $.ajax({
            url: url,
            dataType: 'json',
            context: this,
            data: 'ajax_action=' + this.ajax_action,
            beforeSend: this.ajaxBefore,
            success: this.ajaxSuccess,
            complete: this.ajaxComplete
        });
    };

    Filter.prototype.ajaxBefore = function () {
        this.getTarget().addClass('stm-loading');
    };

    Filter.prototype.ajaxSuccess = function (res) {
        this.getTarget().html(res.html);
        this.disableOptions(res);
        if (res.url) {
            this.pushState(res.url);
        }
    };

    Filter.prototype.ajaxComplete = function () {
        this.getTarget().removeClass('stm-loading');
    };

    Filter.prototype.disableOptions = function (res) {
        if (typeof res.options != 'undefined') {
            $.each(res.options, function (key, options) {
                $('select[name=' + key + '] > option', this.form).each(function () {
                    var slug = $(this).val();
                    if (options.hasOwnProperty(slug)) {
                        $(this).prop('disabled', options[slug].disabled);
                    }
                });
            });
        }
    };

    Filter.prototype.getTarget = function () {
        var target = $(this.form).data('target');
        if (!target) {
            target = '#listings-result';
        }
        return $(target);
    };

    STMListings.Filter = Filter;

    $(function () {
        $('form[data-trigger=filter]').each(function () {
            $(this).data('Filter', new Filter(this));
        });
    });


})(jQuery);