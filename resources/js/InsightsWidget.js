/**
 * Insights Widget
 */

(function($) {

Craft.FacebookInsightsWidget = Garnish.Base.extend(
{
    $widget: null,
    $body: null,
    $infos: null,
    $counts: null,
    $spinner: null,
    $error: null,

    init: function(widgetId)
    {
        this.$widget = $('#'+widgetId);
        this.$body = $('.body', this.$widget);
        this.$infos = $('.infos', this.$widget);
        this.$counts = $('.counts', this.$widget);

        this.$spinner = $('<div class="spinner hidden" />').appendTo(this.$body);
        this.$error = $('<div class="error hidden" />').appendTo(this.$body);

        this.sendRequest();

        this.$widget.data('widget').on('destroy', $.proxy(this, 'destroy'));

        Craft.FacebookInsightsWidget.instances.push(this);
    },

    sendRequest: function()
    {
        this.$spinner.removeClass('hidden');

        Craft.postActionRequest('facebook/reports/getInsightsReport', {}, $.proxy(function(response, textStatus)
        {
            this.$spinner.addClass('hidden');

            if(textStatus == 'success' && typeof(response.error) == 'undefined')
            {
                // infos

                if(response.supportedObject)
                {
                    var object = response.object;

                    var $infoLink = $('<a target="_blank" href="https://www.facebook.com/'+object.id+'/">'+object.name+'</a>');

                    $infoLink.appendTo(this.$infos);


                    // counts

                    $.each(response.counts, $.proxy(function(key, count)
                    {
                        var $count = $('<div class="count" />'),
                            $label = $('<div class="label light" />').appendTo($count),
                            $value = $('<div class="value" />').appendTo($count);

                        $label.html(count.label);
                        $value.html(count.value);

                        $count.appendTo(this.$counts);

                    }, this));
                }
                else
                {
                    var $message = $('<p class="light">'+response.message+'</p>');
                    $message.appendTo(this.$infos);
                }
            }
            else
            {
                var msg = 'An unknown error occured.';

                if(typeof(response) != 'undefined' && response && typeof(response.error) != 'undefined')
                {
                    msg = response.error;
                }

                this.$error.html(msg);
                this.$error.removeClass('hidden');
            }

        }, this));
    },

    destroy: function()
    {
        Craft.FacebookInsightsWidget.instances.splice($.inArray(this, Craft.FacebookInsightsWidget.instances), 1);
        this.base();
    }
}, {
    instances: []
});

})(jQuery);
