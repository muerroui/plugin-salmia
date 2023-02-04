(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Vue.component('multilisting_repeater', {
  props: ['fields', 'field_label', 'field_name', 'field_id', 'field_value', 'field_data'],
  data: function data() {
    return {
      repeater: [],
      repeater_values: {},
      disable_scroll: false
    };
  },
  template: "\n    <div class=\"wpcfto_generic_field wpcfto_generic_field_repeater wpcfto-repeater unflex_fields\">\n\n        <wpcfto_fields_aside_before :fields=\"fields\" :field_label=\"field_label\"></wpcfto_fields_aside_before>\n        \n        <div class=\"wpcfto-field-content\">\n\n            <div v-for=\"(area, area_key) in repeater\" class=\"wpcfto-repeater-single\" :class=\"'wpcfto-repeater_' + field_name + '_' + area_key \">\n    \n                <div class=\"wpcfto_group_title ml_collapse_title\" :data-ml-collapse=\"area_key \" v-html=\"area.label + ' (' + area.slug + ')'\"></div>\n    \n                <div class=\"repeater_inner ml_collapse_inner\" :id=\"'ml_collapse_inner_' + area_key \">\n    \n                    <div class=\"wpcfto-repeater-field\" v-for=\"(field, field_name_inner) in fields.fields\">\n                    \n                        <component :is=\"'wpcfto_' + field.type\"\n                                   :fields=\"field\"\n                                   :field_name=\"field_name + '_' + area_key + '_' + field_name_inner\"\n                                   :field_label=\"field.label\"\n                                   :field_value=\"getFieldValue(area_key, field, field_name_inner)\"\n                                   :field_data=\"field\"\n                                   :field_native_name=\"field_name\"\n                                   :field_native_name_inner=\"field_name_inner\"\n                                   @wpcfto-get-value=\"$set(repeater[area_key], field_name_inner, $event)\">\n                        </component>\n    \n                    </div>\n    \n                </div>\n    \n                <span class=\"wpcfto-repeater-single-delete\" @click=\"removeArea(area_key)\">\n                    <i class=\"fa fa-trash-alt\"></i>Delete\n                </span>\n    \n            </div>\n    \n            <div v-if=\"repeater && repeater.length > 0\" class=\"separator\"></div>\n    \n            <div class=\"addArea\" @click=\"addArea\">\n                <i class=\"fa fa-plus-circle\"></i>\n                <span v-html=\"addLabel()\"></span>\n            </div>\n        \n        </div>\n        \n        <wpcfto_fields_aside_after :fields=\"fields\"></wpcfto_fields_aside_after>\n\n    </div>\n    ",
  mounted: function mounted() {
    var _this = this;

    if (typeof _this.field_value === 'string' && WpcftoIsJsonString(_this.field_value)) {
      _this.field_value = JSON.parse(_this.field_value);
    }

    if (typeof _this.field_value !== 'undefined' && typeof _this.field_value !== 'string') {
      _this.$set(_this, 'repeater_values', _this.field_value);

      _this.repeater_values.forEach(function () {
        _this.repeater.push({});
      });
    }

    if (typeof _this.field_data !== 'undefined' && typeof _this.field_data['disable_scroll'] !== 'undefined') _this.disable_scroll = true;
  },
  methods: {
    addArea: function addArea() {
      this.repeater.push({
        closed_tab: true
      });

      if (!this.disable_scroll) {
        var el = 'wpcfto-repeater_' + this.field_name + '_' + (this.repeater.length - 1);
        Vue.nextTick(function () {
          if (typeof jQuery !== 'undefined') {
            var $ = jQuery;
            $([document.documentElement, document.body]).animate({
              scrollTop: $("." + el).offset().top - 40
            }, 400);
          }
        });
      }
    },
    toggleArea: function toggleArea(area) {
      var currentState = typeof area['closed_tab'] !== 'undefined' ? area['closed_tab'] : false;
      this.$set(area, 'closed_tab', !currentState);
    },
    removeArea: function removeArea(areaIndex) {
      if (confirm('Do your really want to delete this field?')) {
        this.repeater.splice(areaIndex, 1);
      }
    },
    getFieldValue: function getFieldValue(key, field, field_name) {
      if (typeof this.repeater_values === 'undefined') return field.value;
      if (typeof this.repeater_values[key] === 'undefined') return field.value;
      if (typeof this.repeater_values[key][field_name] === 'undefined') return field.value;
      return this.repeater_values[key][field_name];
    },
    addLabel: function addLabel() {
      if (typeof this.field_data['load_labels'] !== 'undefined' && this.field_data['load_labels']['add_label'] !== 'undefined') {
        return this.field_data['load_labels']['add_label'];
      }

      return 'Add ' + this['field_label'];
    }
  },
  watch: {
    repeater: {
      deep: true,
      handler: function handler(repeater) {
        this.$emit('wpcfto-get-value', repeater);
      }
    }
  }
});

(function ($) {
  $(window).on('load', function () {
    setTimeout(function(){
      $('.ml_collapse_inner').hide();

      $('.ml_collapse_title').on('click touchend', function(e) {
        var id = $(this).data('ml-collapse');

        if($(this).hasClass('ml_open')) {
          $('#ml_collapse_inner_' + id).hide(500);
          $(this).removeClass('ml_open');
          $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
          $('#ml_collapse_inner_' + id).show(500);
          $(this).addClass('ml_open');
          $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        
      });

      $('.wpcfto-box-repeater.multilisting_repeater .ml_collapse_title').append(' <i class="fas fa-chevron-down"></i>');

      // you can't change slug once saved
      $('[field_native_name_inner="slug"]').find('input').prop('disabled', true);

      // validate slug - only english letters, numbers and a hyphen is allowed
      $(document).on('keyup', '[field_native_name_inner="slug"] input', function(){
        var slug = $(this).val();
        var regexp = /^\s*([0-9a-zA-Z\-]*)\s*$/;
  
        if( !regexp.test(slug) ) {
          $(this).css('border-color', '#ff0000');
        } else {
          $(this).css('border-color', '#bec5cb');
        }
        
      });
    }, 2000);
  });
})(jQuery);

//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImZha2VfNGJmYmE5MmEuanMiXSwibmFtZXMiOlsiVnVlIiwiY29tcG9uZW50IiwicHJvcHMiLCJkYXRhIiwicmVwZWF0ZXIiLCJyZXBlYXRlcl92YWx1ZXMiLCJkaXNhYmxlX3Njcm9sbCIsInRlbXBsYXRlIiwibW91bnRlZCIsIl90aGlzIiwiZmllbGRfdmFsdWUiLCJXcGNmdG9Jc0pzb25TdHJpbmciLCJKU09OIiwicGFyc2UiLCIkc2V0IiwiZm9yRWFjaCIsInB1c2giLCJmaWVsZF9kYXRhIiwibWV0aG9kcyIsImFkZEFyZWEiLCJjbG9zZWRfdGFiIiwiZWwiLCJmaWVsZF9uYW1lIiwibGVuZ3RoIiwibmV4dFRpY2siLCJqUXVlcnkiLCIkIiwiZG9jdW1lbnQiLCJkb2N1bWVudEVsZW1lbnQiLCJib2R5IiwiYW5pbWF0ZSIsInNjcm9sbFRvcCIsIm9mZnNldCIsInRvcCIsInRvZ2dsZUFyZWEiLCJhcmVhIiwiY3VycmVudFN0YXRlIiwicmVtb3ZlQXJlYSIsImFyZWFJbmRleCIsImNvbmZpcm0iLCJzcGxpY2UiLCJnZXRGaWVsZFZhbHVlIiwia2V5IiwiZmllbGQiLCJ2YWx1ZSIsImFkZExhYmVsIiwid2F0Y2giLCJkZWVwIiwiaGFuZGxlciIsIiRlbWl0Il0sIm1hcHBpbmdzIjoiQUFBQTs7QUFFQUEsR0FBRyxDQUFDQyxTQUFKLENBQWMsaUJBQWQsRUFBaUM7QUFDL0JDLEVBQUFBLEtBQUssRUFBRSxDQUFDLFFBQUQsRUFBVyxhQUFYLEVBQTBCLFlBQTFCLEVBQXdDLFVBQXhDLEVBQW9ELGFBQXBELEVBQW1FLFlBQW5FLENBRHdCO0FBRS9CQyxFQUFBQSxJQUFJLEVBQUUsU0FBU0EsSUFBVCxHQUFnQjtBQUNwQixXQUFPO0FBQ0xDLE1BQUFBLFFBQVEsRUFBRSxFQURMO0FBRUxDLE1BQUFBLGVBQWUsRUFBRSxFQUZaO0FBR0xDLE1BQUFBLGNBQWMsRUFBRTtBQUhYLEtBQVA7QUFLRCxHQVI4QjtBQVMvQkMsRUFBQUEsUUFBUSxFQUFFLHlvRUFUcUI7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QUFPc0MsS0FBSyxDQUFDQyxLQUFiO0FBQ2pELFVBQUksT0FBTyxLQUFLdkMsZUFBTCxDQUFxQnFDLEdBQXJCLENBQVAsS0FBcUMsV0FBekMsRUFBc0QsT0FBT0MsS0FBSyxDQUFDQyxLQUFiO0FBQ3RELFVBQUksT0FBTyxLQUFLdkMsZUFBTCxDQUFxQnFDLEdBQXJCLEVBQTBCcEIsVUFBMUIsQ0FBUCxLQUFpRCxXQUFyRCxFQUFrRSxPQUFPcUIsS0FBSyxDQUFDQyxLQUFiO0FBQ2xFLGFBQU8sS0FBS3ZDLGVBQUwsQ0FBcUJxQyxHQUFyQixFQUEwQnBCLFVBQTFCLENBQVA7QUFDRCxLQWhDTTtBQWlDUHVCLElBQUFBLFFBQVEsRUFBRSxTQUFTQSxRQUFULEdBQW9CO0FBQzVCLFVBQUksT0FBTyxLQUFLNUIsVUFBTCxDQUFnQixhQUFoQixDQUFQLEtBQTBDLFdBQTFDLElBQXlELEtBQUtBLFVBQUwsQ0FBZ0IsYUFBaEIsRUFBK0IsV0FBL0IsTUFBZ0QsV0FBN0csRUFBMEg7QUFDeEgsZUFBTyxLQUFLQSxVQUFMLENBQWdCLGFBQWhCLEVBQStCLFdBQS9CLENBQVA7QUFDRDs7QUFFRCxhQUFPLFNBQVMsS0FBSyxhQUFMLENBQWhCO0FBQ0Q7QUF2Q00sR0EzQnNCO0FBb0UvQjZCLEVBQUFBLEtBQUssRUFBRTtBQUNMMUMsSUFBQUEsUUFBUSxFQUFFO0FBQ1IyQyxNQUFBQSxJQUFJLEVBQUUsSUFERTtBQUVSQyxNQUFBQSxPQUFPLEVBQUUsU0FBU0EsT0FBVCxDQUFpQjVDLFFBQWpCLEVBQTJCO0FBQ2xDLGFBQUs2QyxLQUFMLENBQVcsa0JBQVgsRUFBK0I3QyxRQUEvQjtBQUNEO0FBSk87QUFETDtBQXBFd0IsQ0FBakMiLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcblxuVnVlLmNvbXBvbmVudCgnd3BjZnRvX3JlcGVhdGVyJywge1xuICBwcm9wczogWydmaWVsZHMnLCAnZmllbGRfbGFiZWwnLCAnZmllbGRfbmFtZScsICdmaWVsZF9pZCcsICdmaWVsZF92YWx1ZScsICdmaWVsZF9kYXRhJ10sXG4gIGRhdGE6IGZ1bmN0aW9uIGRhdGEoKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIHJlcGVhdGVyOiBbXSxcbiAgICAgIHJlcGVhdGVyX3ZhbHVlczoge30sXG4gICAgICBkaXNhYmxlX3Njcm9sbDogZmFsc2VcbiAgICB9O1xuICB9LFxuICB0ZW1wbGF0ZTogXCJcXG4gICAgPGRpdiBjbGFzcz1cXFwid3BjZnRvX2dlbmVyaWNfZmllbGQgd3BjZnRvX2dlbmVyaWNfZmllbGRfcmVwZWF0ZXIgd3BjZnRvLXJlcGVhdGVyIHVuZmxleF9maWVsZHNcXFwiPlxcblxcbiAgICAgICAgPHdwY2Z0b19maWVsZHNfYXNpZGVfYmVmb3JlIDpmaWVsZHM9XFxcImZpZWxkc1xcXCIgOmZpZWxkX2xhYmVsPVxcXCJmaWVsZF9sYWJlbFxcXCI+PC93cGNmdG9fZmllbGRzX2FzaWRlX2JlZm9yZT5cXG4gICAgICAgIFxcbiAgICAgICAgPGRpdiBjbGFzcz1cXFwid3BjZnRvLWZpZWxkLWNvbnRlbnRcXFwiPlxcblxcbiAgICAgICAgICAgIDxkaXYgdi1mb3I9XFxcIihhcmVhLCBhcmVhX2tleSkgaW4gcmVwZWF0ZXJcXFwiIGNsYXNzPVxcXCJ3cGNmdG8tcmVwZWF0ZXItc2luZ2xlXFxcIiA6Y2xhc3M9XFxcIid3cGNmdG8tcmVwZWF0ZXJfJyArIGZpZWxkX25hbWUgKyAnXycgKyBhcmVhX2tleSBcXFwiPlxcbiAgICBcXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cXFwid3BjZnRvX2dyb3VwX3RpdGxlXFxcIiB2LWh0bWw9XFxcImZpZWxkX2xhYmVsICsgJyAjJyArIChhcmVhX2tleSArIDEpXFxcIj48L2Rpdj5cXG4gICAgXFxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XFxcInJlcGVhdGVyX2lubmVyXFxcIj5cXG4gICAgXFxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVxcXCJ3cGNmdG8tcmVwZWF0ZXItZmllbGRcXFwiIHYtZm9yPVxcXCIoZmllbGQsIGZpZWxkX25hbWVfaW5uZXIpIGluIGZpZWxkcy5maWVsZHNcXFwiPlxcbiAgICAgICAgICAgICAgICAgICAgXFxuICAgICAgICAgICAgICAgICAgICAgICAgPGNvbXBvbmVudCA6aXM9XFxcIid3cGNmdG9fJyArIGZpZWxkLnR5cGVcXFwiXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA6ZmllbGRzPVxcXCJmaWVsZFxcXCJcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDpmaWVsZF9uYW1lPVxcXCJmaWVsZF9uYW1lICsgJ18nICsgYXJlYV9rZXkgKyAnXycgKyBmaWVsZF9uYW1lX2lubmVyXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgOmZpZWxkX2xhYmVsPVxcXCJmaWVsZC5sYWJlbFxcXCJcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDpmaWVsZF92YWx1ZT1cXFwiZ2V0RmllbGRWYWx1ZShhcmVhX2tleSwgZmllbGQsIGZpZWxkX25hbWVfaW5uZXIpXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgOmZpZWxkX2RhdGE9XFxcImZpZWxkXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgOmZpZWxkX25hdGl2ZV9uYW1lPVxcXCJmaWVsZF9uYW1lXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgOmZpZWxkX25hdGl2ZV9uYW1lX2lubmVyPVxcXCJmaWVsZF9uYW1lX2lubmVyXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgQHdwY2Z0by1nZXQtdmFsdWU9XFxcIiRzZXQocmVwZWF0ZXJbYXJlYV9rZXldLCBmaWVsZF9uYW1lX2lubmVyLCAkZXZlbnQpXFxcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2NvbXBvbmVudD5cXG4gICAgXFxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgXFxuICAgICAgICAgICAgICAgIDwvZGl2PlxcbiAgICBcXG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XFxcIndwY2Z0by1yZXBlYXRlci1zaW5nbGUtZGVsZXRlXFxcIiBAY2xpY2s9XFxcInJlbW92ZUFyZWEoYXJlYV9rZXkpXFxcIj5cXG4gICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzPVxcXCJmYSBmYS10cmFzaC1hbHRcXFwiPjwvaT5EZWxldGVcXG4gICAgICAgICAgICAgICAgPC9zcGFuPlxcbiAgICBcXG4gICAgICAgICAgICA8L2Rpdj5cXG4gICAgXFxuICAgICAgICAgICAgPGRpdiB2LWlmPVxcXCJyZXBlYXRlciAmJiByZXBlYXRlci5sZW5ndGggPiAwXFxcIiBjbGFzcz1cXFwic2VwYXJhdG9yXFxcIj48L2Rpdj5cXG4gICAgXFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cXFwiYWRkQXJlYVxcXCIgQGNsaWNrPVxcXCJhZGRBcmVhXFxcIj5cXG4gICAgICAgICAgICAgICAgPGkgY2xhc3M9XFxcImZhIGZhLXBsdXMtY2lyY2xlXFxcIj48L2k+XFxuICAgICAgICAgICAgICAgIDxzcGFuIHYtaHRtbD1cXFwiYWRkTGFiZWwoKVxcXCI+PC9zcGFuPlxcbiAgICAgICAgICAgIDwvZGl2PlxcbiAgICAgICAgXFxuICAgICAgICA8L2Rpdj5cXG4gICAgICAgIFxcbiAgICAgICAgPHdwY2Z0b19maWVsZHNfYXNpZGVfYWZ0ZXIgOmZpZWxkcz1cXFwiZmllbGRzXFxcIj48L3dwY2Z0b19maWVsZHNfYXNpZGVfYWZ0ZXI+XFxuXFxuICAgIDwvZGl2PlxcbiAgICBcIixcbiAgbW91bnRlZDogZnVuY3Rpb24gbW91bnRlZCgpIHtcbiAgICB2YXIgX3RoaXMgPSB0aGlzO1xuXG4gICAgaWYgKHR5cGVvZiBfdGhpcy5maWVsZF92YWx1ZSA9PT0gJ3N0cmluZycgJiYgV3BjZnRvSXNKc29uU3RyaW5nKF90aGlzLmZpZWxkX3ZhbHVlKSkge1xuICAgICAgX3RoaXMuZmllbGRfdmFsdWUgPSBKU09OLnBhcnNlKF90aGlzLmZpZWxkX3ZhbHVlKTtcbiAgICB9XG5cbiAgICBpZiAodHlwZW9mIF90aGlzLmZpZWxkX3ZhbHVlICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgX3RoaXMuZmllbGRfdmFsdWUgIT09ICdzdHJpbmcnKSB7XG4gICAgICBfdGhpcy4kc2V0KF90aGlzLCAncmVwZWF0ZXJfdmFsdWVzJywgX3RoaXMuZmllbGRfdmFsdWUpO1xuXG4gICAgICBfdGhpcy5yZXBlYXRlcl92YWx1ZXMuZm9yRWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgIF90aGlzLnJlcGVhdGVyLnB1c2goe30pO1xuICAgICAgfSk7XG4gICAgfVxuXG4gICAgaWYgKHR5cGVvZiBfdGhpcy5maWVsZF9kYXRhICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgX3RoaXMuZmllbGRfZGF0YVsnZGlzYWJsZV9zY3JvbGwnXSAhPT0gJ3VuZGVmaW5lZCcpIF90aGlzLmRpc2FibGVfc2Nyb2xsID0gdHJ1ZTtcbiAgfSxcbiAgbWV0aG9kczoge1xuICAgIGFkZEFyZWE6IGZ1bmN0aW9uIGFkZEFyZWEoKSB7XG4gICAgICB0aGlzLnJlcGVhdGVyLnB1c2goe1xuICAgICAgICBjbG9zZWRfdGFiOiB0cnVlXG4gICAgICB9KTtcblxuICAgICAgaWYgKCF0aGlzLmRpc2FibGVfc2Nyb2xsKSB7XG4gICAgICAgIHZhciBlbCA9ICd3cGNmdG8tcmVwZWF0ZXJfJyArIHRoaXMuZmllbGRfbmFtZSArICdfJyArICh0aGlzLnJlcGVhdGVyLmxlbmd0aCAtIDEpO1xuICAgICAgICBWdWUubmV4dFRpY2soZnVuY3Rpb24gKCkge1xuICAgICAgICAgIGlmICh0eXBlb2YgalF1ZXJ5ICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgdmFyICQgPSBqUXVlcnk7XG4gICAgICAgICAgICAkKFtkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQsIGRvY3VtZW50LmJvZHldKS5hbmltYXRlKHtcbiAgICAgICAgICAgICAgc2Nyb2xsVG9wOiAkKFwiLlwiICsgZWwpLm9mZnNldCgpLnRvcCAtIDQwXG4gICAgICAgICAgICB9LCA0MDApO1xuICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICB9XG4gICAgfSxcbiAgICB0b2dnbGVBcmVhOiBmdW5jdGlvbiB0b2dnbGVBcmVhKGFyZWEpIHtcbiAgICAgIHZhciBjdXJyZW50U3RhdGUgPSB0eXBlb2YgYXJlYVsnY2xvc2VkX3RhYiddICE9PSAndW5kZWZpbmVkJyA/IGFyZWFbJ2Nsb3NlZF90YWInXSA6IGZhbHNlO1xuICAgICAgdGhpcy4kc2V0KGFyZWEsICdjbG9zZWRfdGFiJywgIWN1cnJlbnRTdGF0ZSk7XG4gICAgfSxcbiAgICByZW1vdmVBcmVhOiBmdW5jdGlvbiByZW1vdmVBcmVhKGFyZWFJbmRleCkge1xuICAgICAgaWYgKGNvbmZpcm0oJ0RvIHlvdXIgcmVhbGx5IHdhbnQgdG8gZGVsZXRlIHRoaXMgZmllbGQ/JykpIHtcbiAgICAgICAgdGhpcy5yZXBlYXRlci5zcGxpY2UoYXJlYUluZGV4LCAxKTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGdldEZpZWxkVmFsdWU6IGZ1bmN0aW9uIGdldEZpZWxkVmFsdWUoa2V5LCBmaWVsZCwgZmllbGRfbmFtZSkge1xuICAgICAgaWYgKHR5cGVvZiB0aGlzLnJlcGVhdGVyX3ZhbHVlcyA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybiBmaWVsZC52YWx1ZTtcbiAgICAgIGlmICh0eXBlb2YgdGhpcy5yZXBlYXRlcl92YWx1ZXNba2V5XSA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybiBmaWVsZC52YWx1ZTtcbiAgICAgIGlmICh0eXBlb2YgdGhpcy5yZXBlYXRlcl92YWx1ZXNba2V5XVtmaWVsZF9uYW1lXSA9PT0gJ3VuZGVmaW5lZCcpIHJldHVybiBmaWVsZC52YWx1ZTtcbiAgICAgIHJldHVybiB0aGlzLnJlcGVhdGVyX3ZhbHVlc1trZXldW2ZpZWxkX25hbWVdO1xuICAgIH0sXG4gICAgYWRkTGFiZWw6IGZ1bmN0aW9uIGFkZExhYmVsKCkge1xuICAgICAgaWYgKHR5cGVvZiB0aGlzLmZpZWxkX2RhdGFbJ2xvYWRfbGFiZWxzJ10gIT09ICd1bmRlZmluZWQnICYmIHRoaXMuZmllbGRfZGF0YVsnbG9hZF9sYWJlbHMnXVsnYWRkX2xhYmVsJ10gIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmZpZWxkX2RhdGFbJ2xvYWRfbGFiZWxzJ11bJ2FkZF9sYWJlbCddO1xuICAgICAgfVxuXG4gICAgICByZXR1cm4gJ0FkZCAnICsgdGhpc1snZmllbGRfbGFiZWwnXTtcbiAgICB9XG4gIH0sXG4gIHdhdGNoOiB7XG4gICAgcmVwZWF0ZXI6IHtcbiAgICAgIGRlZXA6IHRydWUsXG4gICAgICBoYW5kbGVyOiBmdW5jdGlvbiBoYW5kbGVyKHJlcGVhdGVyKSB7XG4gICAgICAgIHRoaXMuJGVtaXQoJ3dwY2Z0by1nZXQtdmFsdWUnLCByZXBlYXRlcik7XG4gICAgICB9XG4gICAgfVxuICB9XG59KTsiXX0=
},{}]},{},[1])