(function($) {
	'use strict'

	$(document).ready(function ($) {
		
		$('.stm_edit_item').on('click',function(e){
			e.preventDefault();
			var edit_item_id = $(this).attr('data-id');
			var edit_item_name = $(this).attr('data-name');
			var edit_item_plural = $(this).attr('data-plural');
			var edit_item_slug = $(this).attr('data-slug');
			var edit_item_numeric = $(this).attr('data-numeric');
			var edit_item_slider = $(this).attr('data-slider');
			var edit_item_use_on_listing = $(this).attr('data-use-on-listing');
			var edit_item_use_on_car_listing = $(this).attr('data-use-on-car-listing');
			var edit_item_use_on_car_archive_listing = $(this).attr('data-use-on-car-archive-listing');
			var edit_item_use_on_single_car_page = $(this).attr('data-use-on-single-car-page');
			var edit_item_use_on_filter = $(this).attr('data-use-on-car-filter');
			var edit_item_use_on_tabs = $(this).attr('data-use-on-tabs');
			var edit_item_use_on_modern_filter = $(this).attr('data-use-on-car-modern-filter');
			var edit_item_use_on_modern_filter_view_images = $(this).attr('data-use-on-car-modern-filter-view-images');
			var edit_item_use_on_filter_links = $(this).attr('data-use-on-car-filter-links');
			var use_on_directory_filter_title = $(this).attr('data-use-on-directory-filter-title');
			var data_number_field_affix = $(this).attr('data-number-field-affix');
			var edit_font = $(this).attr('data-font');
			var listing_rows = $(this).attr('data-listing-rows-numbers');
			var listing_taxonomy_parent = $(this).attr('data-use-listing_taxonomy_parent');
			var enable_checkbox_button = $(this).attr('data-enable-checkbox-button');
			var use_in_footer_search = $(this).attr('data-use-in-footer-search');

			$('#listing_taxonomy_parent').val(listing_taxonomy_parent);
			$('#listing_cols_per_row').val(listing_rows);

			$('#stm_edit_item_single_name').val( edit_item_name);
			$('#stm_edit_item_plural_name').val( edit_item_plural);
			$('#stm_edit_item_slug').val( edit_item_slug);

			$('#stm_number_field_affix').val(data_number_field_affix);
			$('#stm_old_slug').val(edit_item_slug);
			$('.stm_edit_item_wrap #stm_edit_item_id').val(edit_item_id);

			if(edit_item_numeric == 1) {
				$('#stm_edit_item_numeric').prop('checked', true);
			} else {
				$('#stm_edit_item_numeric').prop('checked', false);
			}

			if(edit_item_slider == 1) {
				$('#stm_edit_item_slider').prop('checked', true);
			} else {
				$('#stm_edit_item_slider').prop('checked', false);
			}

			if(edit_item_use_on_listing == 1) {
				$('#use_on_single_listing_page').prop('checked', true);
			} else {
				$('#use_on_single_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_listing == 1) {
				$('#use_on_car_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_listing == 1) {
				$('#use_on_car_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_archive_listing == 1) {
				$('#use_on_car_archive_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_archive_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_single_car_page == 1) {
				$('#use_on_single_car_page').prop('checked', true);
			} else {
				$('#use_on_single_car_page').prop('checked', false);
			}

			if(edit_item_use_on_filter == 1) {
				$('#use_on_car_filter').prop('checked', true);
			} else {
				$('#use_on_car_filter').prop('checked', false);
			}

			if(edit_item_use_on_tabs == 1) {
				$('#use_on_tabs').prop('checked', true);
			} else {
				$('#use_on_tabs').prop('checked', false);
			}

			if(edit_item_use_on_modern_filter == 1) {
				$('#use_on_car_modern_filter').prop('checked', true);
			} else {
				$('#use_on_car_modern_filter').prop('checked', false);
			}

			if(edit_item_use_on_modern_filter_view_images == 1) {
				$('#use_on_car_modern_filter_view_images').prop('checked', true);
			} else {
				$('#use_on_car_modern_filter_view_images').prop('checked', false);
			}

			if(edit_item_use_on_filter_links == 1) {
				$('#use_on_car_filter_links').prop('checked', true);
			} else {
				$('#use_on_car_filter_links').prop('checked', false);
			}

			if(use_on_directory_filter_title == 1) {
				$('#use_on_directory_filter_title').prop('checked', true);
			} else {
				$('#use_on_directory_filter_title').prop('checked', false);
			}

			if(typeof edit_font != 'undefined') {
				$('#stm-edit-picked-font-icon').val(edit_font);
				$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview').html('<i class="' + edit_font + '"></i>')
			} else {
				$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview i').remove();
			}

			if(enable_checkbox_button == 1) {
				$('#enable_checkbox_button').prop('checked', true);
			} else {
				$('#enable_checkbox_button').prop('checked', false);
			}

			if(use_in_footer_search == 1) {
				$('#use_in_footer_search').prop('checked', true);
			} else {
				$('#use_in_footer_search').prop('checked', false);
			}

			$('.stm_edit_item_wrap').slideDown();
			
			$('.stm-new-filter-category').slideUp();
		});

		$('.stm_delete_item').on('click', function(e){
			var confirm_delete = confirm('Are you sure?');
			if(!confirm_delete){
				e.preventDefault();
			}
		});

		$('.stm_close_edit_item').on('click', function(e){
			e.preventDefault();
			$('.stm_edit_item_wrap').slideUp();
			$('.stm-new-filter-category').slideDown();
		});

		//Sort
		$(function() {
			$( ".stm-ui-sortable" ).sortable();
			$( ".stm-ui-sortable" ).disableSelection();
		});

		$(".stm-ui-sortable").sortable({
			update: function (event, ui) {
				var r = $(this).sortable("toArray");
				$('#stm_new_posts_order').val(r);
			}
		}).disableSelection();

		$('.stm_theme_pick_font').on('click',function(e){
			e.preventDefault();
			$(this).closest('.stm_theme_font_pack_holder').find('.stm_theme_icon_font_table').slideToggle();
		});

		$('.stm-new-filter-category .stm-pick-icon').on('click',function(e){
			e.preventDefault();
			var font = $(this).find('i').attr('class');
			$('.stm-new-filter-category #stm-picked-font-icon').val(font);
			$('.stm-new-filter-category .stm_theme_cat_chosen_icon_preview').html('<i class="' + font + '"></i>')
		});

		$('.stm_edit_item_wrap .stm-pick-icon').on('click',function(e){
			e.preventDefault();
			var font = $(this).find('i').attr('class');
			$('.stm_edit_item_wrap #stm-edit-picked-font-icon').val(font);
			$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview').html('<i class="' + font + '"></i>')
		});
		
		
		$(".source .item").draggable({ 
			revert: "invalid", appendTo: 'body', helper: 'clone',
        	start: function(ev, ui){ 
	        	ui.helper.width($(this).width()); 
	        }
    	});

	    $(".target .empty").droppable({
		    tolerance: 'pointer', 
		    hoverClass: 'highlight', 
		    drop: function(ev, ui){
	            var item = ui.draggable;
	            if (!ui.draggable.closest('.empty').length) item = item.clone().draggable();
	            this.innerHTML = '';                               
	            item.css({ top: 0, left: 0 }).appendTo(this);
				$(item).closest('.target-unit').find('input').val(ui.draggable[0].dataset.key);
	        },
	        out: function(ev, ui) {
		        var item = ui.draggable;
		        $(item).closest('.target-unit').find('input').val('');
	        },
	        activate: function(ev, ui) {
	        },
	        create: function(ev, ui) {
	        },
	        deactivate: function(ev, ui) {
	        },
	        over: function(ev, ui) {
	        }
	    });
	    
	    
	    
	
	    $(".target").on('click', '.closer', function(){
	        var item = $(this).closest('.item');
	        item.fadeTo(200, 0, function(){ item.remove(); })
			$(this).closest('.target-unit').find('input').val('');
	    });

		jQuery('.stm-multiselect').multiSelect({
			'keepOrder' : true
		});

		$('.stm_vehicles_listing_icons .inner .stm_font_nav a').on('click',function(e){
			e.preventDefault();
			$('.stm_vehicles_listing_icons .inner .stm_font_nav a').removeClass('active');
			$(this).addClass('active');
			var tabId = $(this).attr('href');
			$('.stm_theme_font').removeClass('active');
			$(tabId).addClass('active');
		});

		/*Open/Delete icons*/
        $(document).on('click', '.stm_vehicles_listing_icon .stm_delete_icon', function(e){
            $(this).closest('.stm_form_wrapper_icon').find('input[name="font"]').val('');

            $(this).closest('.stm_form_wrapper_icon').find('i').removeAttr('class');
            $(this).closest('.stm_form_wrapper_icon').find('img').removeAttr('class');
            $(this).closest('.stm_form_wrapper_icon').find('img').addClass('stm-default-icon_');
            $(this).closest('.stm_vehicles_listing_icon').removeClass('stm_icon_given');

            e.preventDefault();
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

		var currentTarget = '';
		$(document).on('click', '.stm_vehicles_listing_icon', function(e){
			e.preventDefault();
			$('.stm_vehicles_listing_icons').addClass('visible');
			currentTarget = $(this).closest('.stm_vehicles_listing_option_meta');

			var currentVal = '.' + currentTarget.find('input[name="font"]').val().replace(' ', '.');
            if(currentVal === '.') {
                return;
            }
			$('.stm-listings-pick-icon').removeClass('chosen');
			$('.stm_vehicles_listing_icons ' + currentVal).closest('.stm-listings-pick-icon').addClass('chosen');
		});

		$('.stm_vehicles_listing_icons .inner td.stm-listings-pick-icon i').on('click', function(){
			var stmClass = $(this).attr('class').replace(' big_icon', '');
			currentTarget.find('input[name="font"]').val(stmClass);
			currentTarget.find('.icon i').attr('class', stmClass);

			currentTarget.find('.stm_vehicles_listing_icon').addClass('stm_icon_given');

			stm_listings_close_icons();
		});

		$('.stm_vehicles_listing_icons .overlay').on('click', function(){
			$('.stm_vehicles_listing_icons').removeClass('visible');
		});

		function stm_listings_close_icons() {
			$('.stm_vehicles_listing_icons').removeClass('visible');
		}

		/*Open edit*/
		$(document).on('click', '.stm_listings_settings_head td:not(.manage)', function(){
			var $headTr = $(this).closest('tr');
			var dataTr = $headTr.attr('data-tr');

			$headTr.toggleClass('active');
			$('.stm_listings_settings_tr[data-tr="' + dataTr + '"] .stm_vehicles_listing_option_meta').slideToggle('fast');
		});

		$('[data-depended]').each(function(){
			var $stmThis = $(this);
			var stmDependedName = 'input[name="' + $(this).data('slug') + '"]';

			var $stmDepended = $(this).closest('.stm_vehicles_listing_row_options').find(stmDependedName);

			stmHideUseless($stmDepended, $stmThis);

			$(document).on('change', $stmDepended, function(){
				stmHideUseless($stmDepended, $stmThis);
			});

		});

		function stmHideUseless(elementDepended, stm_this) {
			var compareMethod = stm_this.data('type');

			var depType = $(elementDepended).attr('type');

			var depValue = '';

			if(depType == 'checkbox') {
				depValue = elementDepended.prop('checked');
			} else {
				depValue = elementDepended.val();
			}

			if(compareMethod === 'not_empty') {
				if(depValue) {
					stm_this.show();
				} else {
					stm_this.hide();
				}
			}
		}

		$('.stm_vehicles_listing_option_meta input').each(function(){
			var $stmThis = $(this);
			stm_vehicles_listing_highlight($stmThis);

			$(document).on('keyup', $stmThis, function(){
				stm_vehicles_listing_highlight($stmThis);
			});

			$(document).on('change', $stmThis, function(){
				stm_vehicles_listing_highlight($stmThis);
			});

            $stmThis.focus(function(){
                $stmThis.closest('.stm_form_wrapper').addClass('highlighted');
            })

		});

		function stm_vehicles_listing_highlight($stmThis) {
			var checkVal = '';
			if($stmThis.attr('type') == 'checkbox') {
				checkVal = $stmThis.prop('checked');
			} else {
				checkVal = $stmThis.val();
			}

			if(checkVal) {
				$stmThis.closest('.stm_form_wrapper').addClass('highlighted');
			} else {
				$stmThis.closest('.stm_form_wrapper').removeClass('highlighted');
			}
		}

		/*Actions buttons categories*/
		$(document).on('click', '.stm_vehicles_listing_row_actions a[href="#cancel"]', function() {
			var $headTr = $(this).closest('tr');
			var dataTr = $headTr.attr('data-tr');

			$('.stm_listings_settings_head[data-tr="' + dataTr + '"]').toggleClass('active');
			$('.stm_listings_settings_tr[data-tr="' + dataTr + '"] .stm_vehicles_listing_option_meta').slideToggle('fast');
		});

		$(document).on('click', '.stm_vehicles_listing_row_actions a[href="#delete"]', function(e) {

			var numberTr = $(this).closest('tr').attr('data-tr');
			var $stmOptions = $(this).closest('.stm_vehicles_listing_option_meta');

			var confirm_delete = confirm('Are you sure?');
			if(!confirm_delete){
				e.preventDefault();
				return;
			}

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: 'number=' + numberTr + '&action=stm_listings_delete_single_option_row&security=' + deleteSingleOpt,
				context: this,
				beforeSend: function () {
					$stmOptions.addClass('loading');
				},
				success: function (data) {
					$stmOptions.removeClass('loading');
					stmVehiclesListingDeleteOption(numberTr);
				}
			});
		});

		$(document).on('click', '.stm_vehicles_listing_row_actions a[href="#save"]', function() {
			var $stmForm = $(this).closest('form');
			var $stmOptions = $(this).closest('.stm_vehicles_listing_option_meta');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: $stmForm.serialize() + '&action=stm_listings_save_single_option_row&security=' + saveSingleOpt,
				context: this,
				beforeSend: function () {
					$stmOptions.addClass('loading');
				},
				success: function (data) {
					$stmOptions.removeClass('loading');
				}
			});
		});

		function stmVehiclesListingDeleteOption(number) {

			$('.stm_listings_settings_head[data-tr="' + number + '"], ' +
				'.stm_listings_settings_tr[data-tr="' + number + '"]').remove();
		}

		function stmVehiclesListingSortOptions() {
			var number = 0;
			$('.stm_listings_settings_head').each(function(){
				$(this).attr('data-tr', number);
				number ++;
			});

			number = 0;

			$('.listing_categories_edit .stm_listings_settings_tr').each(function(){
				$(this).attr('data-tr', number);
				$(this).find('input[name="stm_vehicle_listing_row_position"]').val(number);
				number ++;
			});
		}

		/*Dragging options*/
		var stmSortableSelector = $(".stm_vehicles_listing_categories .stm_vehicles_listing_content table.listing_categories_edit tbody");
		$(stmSortableSelector).sortable({
			items: ".stm_listings_settings_head"
		});
		$(stmSortableSelector).disableSelection();

		$(stmSortableSelector).sortable({
			stop: function (event, ui) {

				var newOrder = $(this).sortable("toArray", {attribute: 'data-tr'});

				$('.listing_categories_edit .stm_listings_settings_tr').each(function(){
					var currentNum = $(this).attr('data-tr');

					$(this).detach().insertAfter($('.stm_listings_settings_head[data-tr="' + currentNum + '"]'));

				});

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: 'order=' + newOrder.join(',') + '&action=stm_listings_save_option_order&security=' + saveOpt,
					context: this,
					beforeSend: function () {
						$('.stm_vehicles_listing_categories .stm_vehicles_listing_content').addClass('loading');
					},
					success: function (data) {
						$('.stm_vehicles_listing_categories .stm_vehicles_listing_content').removeClass('loading');
						stmVehiclesListingSortOptions();
					}
				});
			}
		}).disableSelection();

        $('.listing_categories_add_new a[href="#cancel"]').on('click', function(){
            $('.stm_vehicles_listings_add_new_row').removeClass('active');

            $('.stm_vehicles_add_new .listing_categories_add_new .stm_vehicles_listing_option_meta').slideToggle();
        });

		$('.stm_vehicles_listings_add_new_row').on('click', function(){
			$(this).toggleClass('active');

			$('.stm_vehicles_add_new .listing_categories_add_new .stm_vehicles_listing_option_meta').slideToggle();
		});

		$(document).on('click', '.stm_vehicles_add_new .listing_categories_add_new .stm_vehicles_listing_option_meta ' +
            '.stm_vehicles_listing_row_actions a[href="#add_new"]', function(){
			var $stmForm = $(this).closest('form');
			var $stmtable = $(this).closest('.stm_vehicles_add_new');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: $stmForm.serialize() + '&action=stm_listings_add_new_option&security=' + addOpt,
				context: this,
				beforeSend: function () {
                    $('.listing_categories_add_new .stm_response_message').text();
                    $('.listing_categories_add_new .stm_response_message').slideUp();
					$stmtable.addClass('loading');
				},
				success: function (data) {
					$stmtable.removeClass('loading');
                    if(data.message) {
                        $('.listing_categories_add_new .stm_response_message').text(data.message);
                        $('.listing_categories_add_new .stm_response_message').slideDown();
                    }
					if(data.option) {
						stm_listings_append_option(data.option);
					}
				}
			});
		});

		function stm_listings_append_option(option) {
			var trHead = '<tr class="stm_listings_settings_head" data-tr="' + option.key + '">' +
				'<td class="highlited">' + option.name + '</td> ' +
				'<td>' + option.plural + '</td> ' +
				'<td>' + option.slug + '</td> ' +
				'<td>' + option.numeric + '</td> ' +
				'<td class="manage"><i class="fas fa-list-ul" data-url="' + option.link + '"></i></td> ' +
				'<td><i class="fas fa-pencil-alt"></i></td> ' +
				'</tr>';

			var tableHead = '.listing_categories_edit tbody';
			var $tableHead = $(tableHead);

			$tableHead.append(trHead);
			var $newTrSettings = $('.listing_categories_add_new tr').clone();
			$newTrSettings.appendTo(tableHead);
			$newTrSettings.attr('data-tr', option.key);
			$newTrSettings.find('.stm_vehicles_listing_option_meta').hide();

			$('.listing_categories_add_new form').trigger('reset');

            $newTrSettings.find('.stm_response_message').text();
            $newTrSettings.find('.stm_response_message').hide();

		}

		$(document).on('click', '.stm_listings_settings_head i.fa-list-ul', function(){
			var stmUrl = $(this).attr('data-url');
			var win = window.open(stmUrl, '_blank');
			if (win) {
				win.focus();
			} else {
				window.location(stmUrl);
			}
		});

		$(document).on('click', '.stm-has-preview-image a[data-image]', function(e){
			e.preventDefault();
			var image = $(this).attr('data-image');
			$('.image-preview').addClass('visible').append('<img src="' + image + '" />');
		});

		$(document).on('click', '.image-preview .overlay', function(){
			$('.image-preview').removeClass('visible').find('img').remove();
		});

		var noFileLabel = $('.stm_admin_listings_fake .fake_text').text();

		$('.stm_admin_listings_fake input').on('change', function(e){

			var file = $(this)[0].files[0];
			if(typeof file == 'undefined') {
				$('.stm_admin_listings_fake .fake_text').text(noFileLabel);
				$('.stm_admin_listings_fake').removeClass('active');
				$('.stm_vehicles_listing_categories .stm_import_export .export_settings form button').attr('disabled', 'disabled');
			} else {
				$('.stm_admin_listings_fake .fake_text').text(file.name);
				$('.stm_admin_listings_fake').addClass('active');
				$('.stm_vehicles_listing_categories .stm_import_export .export_settings form button').removeAttr('disabled');
			}
		});

	});
	
}(jQuery));