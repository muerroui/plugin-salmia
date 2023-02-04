(function ($) {
    "use strict";
    $(document).ready(function () {
        var MMRepeaterModel = Backbone.Model.extend({
            defaults: {
                idAttribute: "_id",
                icoId: '',
                icoName: '',
                icoValue: '',
                textId: '',
                textName: '',
                textValue: '',
                plusPosition: 0,
                minusPosition: 0
            }
        });


        $(".mega-repeater-box").each(function () {
            var postId = $(this).data('id');
            var appId = '#app' + postId;

            var MMRepeaterCollection = Backbone.Collection.extend({
                model: MMRepeaterModel
            });

            var MMRepeater = new MMRepeaterCollection;

            Backbone.ajax({
                url: ajaxurl,
                type: "POST",
                data: {action: 'stm_mm_get_menu_data', postId: postId},
                dataType: 'json',
                success: function (data) {
                    if(data.icons != null) {
                        for (var q = 0; q < Object.keys(data.icons).length; q++) {
                            var repeater = new MMRepeaterModel({
                                icoId: 'edit-menu-item-stm_menu_icon_repeater-' + postId,
                                icoName: 'menu-item-stm_menu_icon_repeater[' + postId + '][]',
                                icoValue: data.icons[q],
                                textId: 'edit-menu-item-stm_mega_text_repeater-' + postId,
                                textName: 'menu-item-stm_mega_text_repeater[' + postId + '][]',
                                textValue: data.text[q],
                                plusId: 'mm-plus-' + postId,
                                minusId: 'mm-minus-' + postId,
                                plusPosition: q,
                                minusPosition: q
                            });
                            MMRepeater.add(repeater);
                        }
                    } else {
                        var repeater = new MMRepeaterModel({
                            icoId: 'edit-menu-item-stm_menu_icon_repeater-' + postId,
                            icoName: 'menu-item-stm_menu_icon_repeater[' + postId + '][]',
                            icoValue: '',
                            textId: 'edit-menu-item-stm_mega_text_repeater-' + postId,
                            textName: 'menu-item-stm_mega_text_repeater[' + postId + '][]',
                            textValue: '',
                            plusId: 'mm-plus-' + postId,
                            minusId: 'mm-minus-' + postId,
                            plusPosition: 0,
                            minusPosition: 0
                        });

                        MMRepeater.add(repeater);
                    }
                }
            });


            var MMRepeatersView = Backbone.View.extend({
                el: appId,

                initialize: function() {
                    MMRepeater.on('add', this.render, this);
                    MMRepeater.on('remove', this.render, this);
                },

                render: function () {
                    this.$el.html('');

                    MMRepeater.each(function(model) {
                        var repeater = new MMRepeaterView({
                            model: model
                        });

                        this.$el.append(repeater.render().el);

                        var $elementRepeater = $('.menu-item').find('.edit-menu-item-stm_menu_icon_repeater');
                        stm_init_fontpicker($elementRepeater);

                    }.bind(this));

                    return this;
                }
            });

            var MMRepeaterView = Backbone.View.extend({

                template: _.template($('#repItem').html()),
                events: {
                    "click .mm-plus": "clickPlus",
                    "click .mm-minus": "clickMinus",
                    "change input.edit-menu-item-stm_menu_icon_repeater": "changeIcon",
                    "change input.edit-menu-item-stm_menu_text_repeater": "changeText"
                },

                changeText: function (evt) {
                    this.model.set({'textValue': $(evt.currentTarget).val()});
                },

                changeIcon: function (evt) {
                    this.model.set({'icoValue': $(evt.currentTarget).val()})
                },

                clickPlus: function (e) {
                    $(e.currentTarget).parent().addClass("has-value");
                    var repeater = new MMRepeaterModel({
                        icoId: 'edit-menu-item-stm_menu_icon_repeater-' + postId,
                        icoName: 'menu-item-stm_menu_icon_repeater[' + postId + '][]',
                        icoValue: '',
                        textId: 'edit-menu-item-stm_mega_text_repeater-' + postId,
                        textName: 'menu-item-stm_mega_text_repeater[' + postId + '][]',
                        textValue: '',
                        plusId: 'mm-plus-' + postId,
                        minusId: 'mm-minus-' + postId,
                        plusPosition: MMRepeater.length,
                        minusPosition: MMRepeater.length
                    });

                    MMRepeater.push(repeater);
                },

                clickMinus: function (e) {
                    console.log(this.model.cid);
                    var id = this.model.cid;
                    if(MMRepeater.length > 1) MMRepeater.remove(MMRepeater.get(this.model.cid));
                },

                render: function () {
                    this.$el.html(this.template(this.model.attributes));
                    return this;
                }
            });

            var app = new MMRepeatersView;
        });
    });

    function stm_init_fontpicker($el) {
        $el.fontIconPicker({
            source: stmIconsSet,
            emptyIcon: true,
            hasSearch: true,
            theme: 'fip-inverted'
        });

        $('.submit-add-to-menu').on('click', function(){
            $el.fontIconPicker({
                source: stmIconsSet,
                emptyIcon: true,
                hasSearch: true,
                theme: 'fip-inverted'
            });
        })
    }
})(jQuery);
