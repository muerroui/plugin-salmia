(function($) {
	"use strict"
	if(typeof stm_listings != 'undefined' && stm_listings.listings.length > 0) {
		stm_listings.listings.forEach(function(list, index){
			// Save New Row
			$(document).on('click',
				'.stm_vehicles_add_new .listing_categories_add_new .stm_vehicles_listing_option_meta ' +
				'.stm_vehicles_listing_row_actions a[href="#add_new_'+list.slug+'"]', function () {
				var $stmForm = $(this).closest('form');
				var $stmtable = $(this).closest('.stm_vehicles_add_new');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: $stmForm.serialize() + '&action=stm_'+list.slug+'_add_new_option',
					context: this,
					beforeSend: function () {
						$('.listing_categories_add_new .stm_response_message').text();
						$('.listing_categories_add_new .stm_response_message').slideUp();
						$stmtable.addClass('loading');
					},
					success: function (data) {
						$stmtable.removeClass('loading');
						if (data.message) {
							$('.listing_categories_add_new .stm_response_message').text(data.message);
							$('.listing_categories_add_new .stm_response_message').slideDown();
						}
						if (data.option) {
							stm_post_type_append_option(list, data.option);
						}
					}
				});
			});

			// Delete Row
			$(document).on('click', '.stm_vehicles_listing_row_actions a[href="#delete_'+list.slug+'"]', function(e) {

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
					data: 'number=' + numberTr + '&action=stm_'+list.slug+'_delete_option_row',
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

			// Save Row
			$(document).on('click', '.stm_vehicles_listing_row_actions a[href="#save_'+list.slug+'"]', function() {
				var $stmForm = $(this).closest('form');
				var $stmOptions = $(this).closest('.stm_vehicles_listing_option_meta');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: $stmForm.serialize() + '&action=stm_'+list.slug+'_save_option_row',
					context: this,
					beforeSend: function () {
						$stmOptions.addClass('loading');
					},
					success: function (data) {
						$stmOptions.removeClass('loading');
					}
				});
			});

			var stmSortableSelectorM = $(".stm_vehicles_listing_categories .stm_vehicles_listing_content table."+list.slug+"_categories_edit tbody");

			$(stmSortableSelectorM).sortable({
				items: ".stm_listings_settings_head"
			});
			$(stmSortableSelectorM).disableSelection();

			$(stmSortableSelectorM).sortable({
				stop: function (event, ui) {

					var newOrder = $(this).sortable("toArray", {attribute: 'data-tr'});

					$('.'+list.slug+'_categories_edit .stm_listings_settings_tr').each(function(){
						var currentNum = $(this).attr('data-tr');

						$(this).detach().insertAfter($('.stm_listings_settings_head[data-tr="' + currentNum + '"]'));

					});

					$.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: 'order=' + newOrder.join(',') + '&action=stm_'+list.slug+'_save_option_order',
						context: this,
						beforeSend: function () {
							$('.stm_vehicles_listing_categories .stm_vehicles_listing_content').addClass('loading');
						},
						success: function (data) {
							$('.stm_vehicles_listing_categories .stm_vehicles_listing_content').removeClass('loading');
							stmVehiclesListingSortOptions(list);
						},
						error: function (jqXHR, exception) {
							var msg = '';
							if (jqXHR.status === 0) {
								msg = 'Not connect.\n Verify Network.';
							} else if (jqXHR.status === 404) {
								msg = 'Requested page not found. [404]';
							} else if (jqXHR.status === 500) {
								msg = 'Internal Server Error [500].';
							} else if (exception === 'parsererror') {
								msg = 'Requested JSON parse failed.';
							} else if (exception === 'timeout') {
								msg = 'Time out error.';
							} else if (exception === 'abort') {
								msg = 'Ajax request aborted.';
							} else {
								msg = 'Uncaught Error.\n' + jqXHR.responseText;
							}
							console.warn(msg);
						}
					});
				}
			}).disableSelection();
		});
	}

	function stmVehiclesListingSortOptions(list) {
		var number = 0;
		$('.stm_listings_settings_head').each(function(){
			$(this).attr('data-tr', number);
			number ++;
		});

		number = 0;

		$('.'+list.slug+'_categories_edit .stm_listings_settings_tr').each(function(){
			$(this).attr('data-tr', number);
			$(this).find('input[name="stm_vehicle_listing_row_position"]').val(number);
			number ++;
		});
	}

	function stmVehiclesListingDeleteOption(number) {

		$('.stm_listings_settings_head[data-tr="' + number + '"], ' +
			'.stm_listings_settings_tr[data-tr="' + number + '"]').remove();
	}

	function stm_post_type_append_option(list, option) {
		var trHead = '<tr class="stm_listings_settings_head" data-tr="' + option.key + '">' +
			'<td class="highlited">' + option.name + '</td> ' +
			'<td>' + option.plural + '</td> ' +
			'<td>' + option.slug + '</td> ' +
			'<td>' + option.numeric + '</td> ' +
			'<td class="manage"><i class="fas fa-list-ul" data-url="' + option.link + '"></i></td> ' +
			'<td><i class="fas fa-pencil-alt"></i></td> ' +
			'</tr>';

		var tableHead = '.'+list.slug+'_categories_edit tbody';
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

	if(typeof STMListings != 'undefined') {
		STMListings.Filter.prototype.performAjax = function (url) {
			let type = $('form[data-trigger="filter"]').find('input[name="posttype"]').val();
			$.ajax({
				url: url,
				dataType: 'json',
				context: this,
				data: 'ajax_action=' + this.ajax_action + '&posttype='+type,
				beforeSend: this.ajaxBefore,
				success: this.ajaxSuccess,
				complete: this.ajaxComplete
			});

			STMListings.Filter.prototype.ajax_action = STMListings.Filter.prototype.ajax_action + '&posttype=' + type;
		};
	}

	$('.multilisting-search-tabs-wrap .nav-item').on('click', function(e){
		$('.multilisting-search-tabs-wrap .nav-item .nav-link').removeClass('active');
		
		if($(this).find('.nav-link:first').hasClass('active')) {
			$(this).find('.nav-link:first').removeClass('active');
		} else {
			$(this).find('.nav-link:first').addClass('active');
		}
	});
	
}(jQuery));
