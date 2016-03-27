/**
 * Created by Tu on 30/03/2015.
 * Modified 03 Apr 2015
 */

;(function(){
	/**
	 * @author ThimPress
	 * @package LearnPress/Javascript
	 * @version 1.0
	 */
	;
	if (typeof window.LearnPress == 'undefined') {
		window.LearnPress = {};
	}
	var $ = jQuery,
		$doc = $(document);


	function uniqueValue (prefix, more_entropy) {
		if (typeof prefix === 'undefined') {
			prefix = '';
		}

		var retId;
		var formatSeed = function (seed, reqWidth) {
			seed = parseInt(seed, 10)
				.toString(16); // to hex str
			if (reqWidth < seed.length) { // so long we split
				return seed.slice(seed.length - reqWidth);
			}
			if (reqWidth > seed.length) { // so short we pad
				return Array(1 + (reqWidth - seed.length))
						.join('0') + seed;
			}
			return seed;
		};

		// BEGIN REDUNDANT
		if (!this.php_js) {
			this.php_js = {};
		}
		// END REDUNDANT
		if (!this.php_js.uniqidSeed) { // init seed with big random int
			this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
		}
		this.php_js.uniqidSeed++;

		retId = prefix; // start with prefix, add current milliseconds hex string
		retId += formatSeed(parseInt(new Date()
				.getTime() / 1000, 10), 8);
		retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
		if (more_entropy) {
			// for more entropy we add a float lower to 10
			retId += (Math.random() * 10)
				.toFixed(8)
				.toString();
		}

		return retId;
	}

	$.fn.fitSizeWithText = function (opts) {

		return $.each(this, function () {
			if ($(this).data('fitSizeWithText')) return this
			var options = $.extend({
					x   : 30,
					auto: false
				}, opts || {}),
				$this = $(this),
				text = null,
				css = {
					visibility: 'hidden',
					padding   : $this.css('padding'),
					fontSize  : $this.css('font-size'),
					fontWeight: $this.css('font-weight'),
					fontStyle : $this.css('font-style'),
					fontFamily: $this.css('font-family')
				},
				width = 0;

			function calculate() {
				text = $this.val ? $this.val() : $this.text();
				var checker = $('<span />').css(css).html(text).appendTo($(document.body));
				width = checker.outerWidth() + options.x;
				checker.remove();
				$this.width(width);
			}

			calculate();
			if (options.auto) {
				$this.on('keyup', function () {
					calculate()
				})
			}
			return $this.data('fitSizeWithText', 1);
		});

	}
	$.fn.outerHTML = function () {

		// IE, Chrome & Safari will comply with the non-standard outerHTML, all others (FF) will have a fall-back for cloning
		return (!this.length) ? this : (this[0].outerHTML || (function (el) {
			var div = document.createElement('div');
			div.appendChild(el.cloneNode(true));
			var contents = div.innerHTML;
			div = null;
			return contents;
		})(this[0]));

	}
	function onRemoveQuestion(question) {
		var $select = $('#lpr-quiz-question-select-existing'),
			$option = $('<option value="' + question.id + '">' + question.text + '</option>', $select);
		$select.append($option);
	}

	function updateAnswers($element) {
		$rows = $('.lpr-question-option tbody', $element).children();
		if ($rows.length == 1) {
			$rows.addClass('lpr-disabled');
		} else {
			$rows.filter(function () {
				if ( ( $('.lpr-answer-text', this).val() + '' ).length == 0) {
					$(this).addClass('lpr-disabled');
				} else {
					$(this).removeClass('lpr-disabled');
				}
			})
		}
		$element.trigger('lpr_update_item_index')
	}

	function inputKeyEvent(evt) {
		var $input = $(this),
			$wrap = $input.closest('.lpr-question');

		if (evt.type == 'focusin') {
			$input.data('value', $input.val());
		} else if (evt.type == 'focusout') {
			if ($input.val().length == 0) {
				var $row = $input.closest('tr');
				if (!$row.is(':last-child')) $row.remove();
			}
			return false;
		} else if (evt.type == 'keydown') {
			var $row = $input.closest('tr'),
				$rows = $row.parent().children(),
				index = $rows.index($row);

			switch (evt.keyCode) {
				default:
					if ($row.is(':last-child') && $input.val().length) {
						$('.lpr-button-add-answer', $wrap).trigger('click');
						$('.lpr-question-answer tr:last input:last', $wrap).val('')
						$input.focus();
					}
					break;
				case 13: // enter
					if ($input.val().length) {
						var $nextrow = $row.next();
						if (!$nextrow.get(0)) {
							$('.lpr-button-add-answer', $wrap).trigger('click')
							$nextrow = $row.next();
							$('input.lpr-answer-text', $nextrow).val('')
						}
						$('input.lpr-answer-text', $nextrow).focus();
					}
					evt.preventDefault();
					return false;
				case 27: // esc
					$input.val($input.data('value'));
					$input.closest('.lpr-question').focus();
					evt.preventDefault();
					break;
				case 9: // tab

					$('input.lpr-answer-text', $row.next()).focus();
					evt.preventDefault();
					return false;
				case 8: // back space
				case 46: // delete
					if ($input.val().length == 0) {
						$newrow = $row.prev();

						if ($rows.length > 1) {
							try {
								$row.remove();
							} catch (ex) {
							}
						}
						if (!$newrow.get(0)) {
							$newrow = $rows.last();
						}
						$newrow.find('input.lpr-answer-text').focus();
						updateAnswers($wrap)
						evt.preventDefault();
						return false;
					}
			}
		}
	}

	LearnPress.Question = {
		addOption  : function (question_id) {
			var args = {
					question_id: question_id,
					text       : 'New option',
					value      : uniqueValue()
				},
				$container = $('#learn-press-question-'+question_id),
				tmpl = wp.template( $container.attr('data-type')+'-question-answer'),
				$newOption = $(tmpl(args)),
				$list = $container.find('.lpr-question-answer tbody');
			//if( LearnPress.Hook.applyFilters('before_add_question_option', $newOption, args ) !== false ) {
				$list.append($newOption);
			//	LearnPress.Hook.doAction('question_option_added', $newOption, args );
			//}
			return $newOption;
		},
		removeOption: function(theOption){
			var $theOption = null;
			if($.type( theOption ) == 'integer' ){
				$theOption = $('lp-list-option-'+theOption);
			}else{
				$theOption = $(theOption);
			}
			//if( LearnPress.Hook.applyFilters('before_remove_question_option', true, $theOption) !== false ) {
				$theOption.remove();
			//	LearnPress.Hook.doAction('question_option_removed', $theOption);
			//}
		},
		addQuestion: function (args) {
			args = $.extend({
				id  : 0,
				type: null,
				name: null
			}, args);
			if (!args.id && !args.type) {
				alert('ERROR');
				return;
			}
			var post_data = $.extend({
				action: 'learnpress_add_question'
			}, args);

			post_data = LearnPress.Hook.applyFilters( 'LearnPress.add_question_post_data', post_data );

			$.ajax({
				url     : LearnPress_Settings.ajax,
				dataType: 'html',
				type    : 'post',
				data    : post_data,
				success : function (response) {
					response = LearnPress.parseJSON(response);
					var $newQuestion = $(response.html);
					$('#learn-press-list-questions').append($newQuestion);
					LearnPress.Question._hideQuestion( args.id )
					LearnPress.Hook.doAction( 'learn_press_add_quiz_question', $newQuestion, args);
				}
			});
		},
		_hideQuestion: function(question){
			if($.type( question ) == 'number' ) {
				question = $('#learn-press-dropdown-questions .question a[data-id="' + question + '"]').parent()
			}
			$(question).addClass('added');
		},
		_showQuestion: function(question){
			if($.type( question ) == 'number' ) {
				question = $('#learn-press-dropdown-questions .question a[data-id="' + question + '"]').parent()
			}
			$(question).removeClass('added');
		},
		init: function(){
			$('.lpr-question').each(function(){
				makeAnswersSortable($(this))
			});

			$(document).on('keydown keypress focus blur', '.lpr-answer-text', inputKeyEvent)
				.on('focus', function () {
					$(this).addClass('selected')
				})
				.on('blur', function () {
					$(this).removeClass('selected')
				})
				.attr('tabindex', 0);
		}
	};
	function makeAnswersSortable( $q ){
		if( typeof $.fn.sortable == 'undefined' ) return;
		$($q).find('.lpr-question-option tbody').sortable({
			handle: '.lpr-sortable-handle',
			axis: 'y',
			start: function(e, ui){
				var $heads = ui.item.parent().closest('table').find('tr > th');
				ui.item.children().each(function(i){
					$(this).css({
						width: $heads.eq(i).width()
					});
				})
				var $this = $(this),
					cols = $this.find('tr:first').children().length;
				$this.find('.ui-sortable-placeholder td:gt(0)').remove();
				$this.find('.ui-sortable-placeholder td:eq(0)').attr('colspan', cols)
			},
			stop: function(){

			}
		});
	}
	function updateHiddenQuestions(hidden){
		if( hidden == undefined ) {
			hidden = [];
			var len = $('.quiz-question-content').each(function () {
				if ($(this).is(':hidden')) {
					hidden.push($('.learn-press-question', this).attr('data-id'));
				}
			}).length;
			if( hidden.length == 0 ){
				$('.questions-toggle a[data-action="collapse"]')
					.show()
					.siblings('a[data-action="expand"]')
					.hide();
			}else if( hidden.length == len ){
				$('.questions-toggle a[data-action="collapse"]')
					.hide()
					.siblings('a[data-action="expand"]')
					.show();
			}
		}

		$.ajax({
			url: LearnPress_Settings.ajax,
			data: {
				action: 'learnpress_update_quiz_question_state',
				quiz_id: $('#post_ID').val(),
				hidden: hidden
			},
			success: function(){

			}
		});
		return hidden;
	}
	var $doc = $(document),
		$body = $(document.body);

	function _ready() {
		$body = $(document.body);
		LearnPress.Question.init();
		questionActions();
		$('#learn-press-toggle-questions').on('click', function () {
			$(this).siblings('ul').toggle();
		});

		$doc.on('click', '#learn-press-dropdown-questions ul li a', function (e) {
			e.preventDefault();
			LearnPress.Question.addQuestion({id: $(this).data('id')});
			$(this).closest('ul').hide();
		});

		$('#learn-press-button-add-question').on('click', function () {
			LearnPress.Question.addQuestion({name: $('#learn-press-question-name').val(), type: 'true_or_false'});
		});



		$doc.on('click', '.add-question-option-button', function () {
			var question_id = $(this).attr('data-id'),
				$newOption = null,
				$nullOptions = $(this).closest('.lpr-question').find('.lpr-question-answer tr.lpr-disabled');
			if( $nullOptions.length == 0 ) {
				$newOption = LearnPress.Question.addOption(question_id);
				if ($newOption) {
					$newOption.find('input.lpr-answer-text').focus();
				}
			}else{
				$nullOptions.first().find('.lpr-answer-text').focus();
			}
		}).on('click', '.lpr-remove-answer', function () {
			var $option = $(this).closest('tr');
			if($option.hasClass('lpr-disabled')){
				return;
			}
			LearnPress.Question.removeOption( $option );
		}).on('change', '.lpr-question-types', function(){
			var questionId = $(this).closest('.lpr-question').attr('data-id'),
				from = $(this).data('type'),
				to = this.value;
			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'html',
				data: {
					action: 'learnpress_convert_question_type',
					question_id: questionId,
					from: from,
					to: to
				},
				success: function(response){
					response = LearnPress.parse_json(response);
					var $newOptions = $(response);
					$('#learn-press-question-'+questionId).replaceWith($newOptions);
					makeAnswersSortable( $newOptions );
				}
			});
		}).on( 'keyup', '.lpr-answer-text', function(e){
			var $input = $(this),
				$row = $input.closest('tr'),
				$container = $input.closest('.lpr-question');
			if( $input.val().length == 0 ){
				$row.addClass( 'lpr-disabled');
			}else{
				$row.removeClass( 'lpr-disabled');
				if(!$container.find('.lpr-question-answer tr.lpr-disabled').length) {
					LearnPress.Question.addOption($container.attr('data-id'));
				}
			}
		}).on('click', '.questions-toggle a', function(e){
			e.preventDefault();
			var action = $(this).attr('data-action');
			switch (action){
				case 'expand':
					var $items = $('.quiz-question'),
						len = $items.length, i = 0;
					$(this)
						.hide()
						.siblings('a[data-action="collapse"]')
						.show();
					$items
						.removeClass('is-hidden')
						.find('.quiz-question-content').slideDown(function(){
							if(++i == len){
								updateHiddenQuestions([]);
							}
						});
					$items.find('a[data-action="collapse"]').show();
					$items.find('a[data-action="expand"]').hide();
					break;
				case 'collapse':
					var $items = $('.quiz-question'),
						len = $items.length, i = 0,
						hidden = [];
					$(this)
						.hide()
						.siblings('a[data-action="expand"]')
						.show();
					$items
						.addClass('is-hidden')
						.find('.quiz-question-content').slideUp(function(){
							hidden.push($('.learn-press-question', this).attr('data-id'));
							if(++i == len){
								updateHiddenQuestions(hidden);
							}
						});
					$items.find('a[data-action="collapse"]').hide();
					$items.find('a[data-action="expand"]').show();
					break;
			}
		}).on('click', '.quiz-question-actions a', function(e){
			var action = $(this).attr('data-action');

			switch (action){
				case 'expand':
					$(this)
						.hide()
						.siblings('a[data-action="collapse"]')
						.show()
						.closest('.quiz-question')
						.removeClass('is-hidden')
						.find('.quiz-question-content').slideDown(function(){
							if( updateHiddenQuestions().length == 0 ){

							}
						});
					break;
				case 'collapse':
					$(this)
						.hide()
						.siblings('a[data-action="expand"]')
						.show()
						.closest('.quiz-question')
						.addClass('is-hidden')
						.find('.quiz-question-content').slideUp(function(){
							updateHiddenQuestions();
						});
					break;
				case 'remove':
					LearnPress.MessageBox.show( 'Do you want to remove this question from quiz?', {
						buttons: 'yesNo',
						data: $(this).closest('.quiz-question'),
						onYes: function(data){
							var $question = $(data);
							LearnPress.Question._showQuestion( parseInt( $question.find('.learn-press-question').attr('data-id') ) );
							$question.remove();
						}
					})
					break;
				case 'edit':
					LearnPress.MessageBox.show('<iframe src="'+$(this).attr('href')+'" />');

			}
			if( action ){
				e.preventDefault();
			}
		});

		function questionActions() {
			$doc.on('click', '.lpr-question-head a', function (evt) {
				var $link = $(this);
				if ($link.attr('href')) return true;
				evt.preventDefault();
				var action = $link.data('action'),
					$wrap = $link.closest('.lpr-question');
				switch (action) {
					case 'remove':
						if (!confirm('Remove?')) return;
						onRemoveQuestion({
							id  : $wrap.data('id'),
							text: $('.lpr-question-title input', $wrap).val()
						});
						var data = {
							action     : 'lpr_quiz_question_remove',
							question_id: $wrap.data('id'),
							quiz_id    : learn_press_quiz_id
						}
						$.post(ajaxurl, data, function () {

						})
						$wrap.remove();
						break;
					case 'collapse':
					case 'expand':
						var $input = $('input.lpr-question-toggle', $wrap);
						if (!$input.get(0)) {
							$input = $('<input class="lpr-question-toggle" type="hidden" name="lpr_question[' + $wrap.data('id') + '][toggle]" value="" />')
							$input.appendTo($wrap);
						}
						$input.val(action == 'collapse' ? 0 : 1);
						$link.hide();
						if (action == 'collapse') {
							$link.siblings('a[data-action="expand"]').show();
							$('.lpr-question-content', $wrap).slideUp();//addClass('hide-if-js');
						} else {
							$link.siblings('a[data-action="collapse"]').show();
							$('.lpr-question-content', $wrap).slideDown();//removeClass('hide-if-js');
						}
						break;
				}
			});


			$doc.on('add_new_question_type', function () {
				if (!$(this).val()) return;


				var args = $(this).prev().offset(),
					type = $(this).val();
				args.type = $(this).val();


				$(this).val('').prev().find('.select2-chosen').html($('option:selected', this).html());
				var $form = showFormQuickAddQuestion(args);
				$form.css("top", args.top - $form.outerHeight() - 5);
				$('input', $form).val('').focus();
			});

			$('#lpr-quiz-question-select-existing').change(function () {
				addExistingQuestion(this.value)
			})
			if ($.fn.select2) {
				$('.lpr-select2').select2();
			}
		}

		function addExistingQuestion(id, args) {
			var data = {
				action     : 'lpr_quiz_question_add',
				quiz_id    : learn_press_quiz_id,
				question_id: id
			};
			$.post(ajaxurl, data, function (res) {
				res = LearnPress.parse_json(res);
				if (res.success) {
					var $question = $(res.html),
						$select = $('#lpr-quiz-question-select-existing');
					$('#lpr-quiz-questions').append($question);
					makeAnswersSortable($question);
					lprHook.doAction('lpr_admin_quiz_question_html', $question, res.type);
					$('option[value="' + id + '"]', $select).remove();
					$('.lpr-question-title input', $question).fitSizeWithText({auto: true})
					$select
						.val('').prev().find('.select2-chosen').html($('option:selected', $select).html());
					args && args.success && args.success();
				} else {
					alert(res.msg)
				}
			}, 'html');
		}

		function addNewQuestion(args) {
			//var type = $('#lpr-quiz-question-type').val();
			args = $.extend({
				type   : null,
				text   : null,
				success: false
			}, args || {});
			if (!args.type) {
				// warning
				return;
			}
			var data = {
				action : 'lpr_quiz_question_add',
				quiz_id: learn_press_quiz_id,
				type   : args.type,
				text   : args.text
			};
			$.post(ajaxurl, data, function (res) {
				res = LearnPress.parse_json(res);
				console.log(res);
				if (res && res.success) {
					var $question = $(res.html)
					$('#lpr-quiz-questions').append($question);
					makeAnswersSortable($question);
					lprHook.doAction('lpr_admin_quiz_question_html', $question, args.type);
					$('.lpr-question-title input', $question).fitSizeWithText({auto: true})
					args.success && args.success();
				}
			}, 'html');
		}

		function showFormQuickAddQuestion(args) {
			args = $.extend({
				top : undefined,
				left: undefined,
				type: null
			}, args || {})


			var $form = $('#lpr-form-quick-add-question');

			if (!$form.get(0)) {
				$form = $(wp.template('form-quick-add-question')()).appendTo($body).hide();
				$('button', $form).click(function () {
					var action = $(this).data('action'),
						$input = $('input', $form),
						args = $form.data('data');
					switch (action) {
						case 'add':
							if (!$input.val()) {
								$input.css("border-color", "#FF0000");
								return;
							}
							$form.addClass('working');

							addNewQuestion({
								type   : $('select', $form).val(),
								text   : $input.val(),
								success: function () {
									$.lprHideBlock();
									$form.removeClass('working');
									$input.css("border-color", "");
								}
							});
							break;
						case 'cancel':
							$.lprHideBlock();
							$input.css("border-color", "");
							break;
					}
				});
				$('input', $form).on('keyup', function (evt) {
					if (evt.keyCode == 13) {
						$(this).siblings('button[data-action="add"]').trigger('click');
					} else if (evt.keyCode == 27) {
						$(this).siblings('button[data-action="cancel"]').trigger('click');
					}
				});
			}


			$form.data('data', args);

			$.lprShowBlock($form);
			return $form.css(args).show();
		}
		if( typeof $.fn.sortable != 'undefined' ) {

			$('#lpr-quiz-questions').sortable({
				handle: '.lpr-question-head',
				axis  : 'y',
				start : function (evt, ui) {
					$('.lpr-question-content', ui.item).css("display", "none");
					$(ui.item).css("height", "")
					$('.ui-sortable-placeholder', this).height($(ui.item).height());
				},
				stop  : function (evt, ui) {
					$('.lpr-question-content', ui.item).css("display", "");
				}
			});
		}
			var $button_add_new_question_type = $('#lpr-add-new-question-type');
			$('button:first', $button_add_new_question_type).click(function () {
				var type = $(this).attr('data-type'),
					args = $.extend({type: type}, $(this).offset())
				$form = showFormQuickAddQuestion(args);

				$form.css("top", args.top - $form.outerHeight() - 5);
				$('select', $form).val(type);
				$('input', $form).val('').focus();

				$button_add_new_question_type.data('bg') && $button_add_new_question_type.data('bg').trigger('click')
			}).hover(function () {
				$button_add_new_question_type.data('bg') && $button_add_new_question_type.data('bg').trigger('click')
			});
			$('#lpr-add-new-question-type .dropdown-toggle').hover(function () {
				var $this = $(this);
				if ($this.hasClass('hovering')) return
				$this.addClass('hovering')
				var bg = $('<div />').css({
					position: 'fixed',
					top     : 0,
					left    : 0,
					right   : 0,
					bottom  : 0,
					zIndex  : 1000
				}).appendTo($(document.body));
				bg.on('mouseenter click', function () {
					$(this).remove();
					$button_add_new_question_type.css("z-index", "");
					dropdown.hide();
					$this.removeClass('hovering')
				});
				$button_add_new_question_type.data('bg', bg)
				var dropdown = $this.siblings('.dropdown-menu').show();
				$button_add_new_question_type.css({
					zIndex: 1010
				})
			});
			$('.dropdown-menu a', $button_add_new_question_type).click(function (e) {
				e.preventDefault();
				$('button:first', $button_add_new_question_type).attr('data-type', $(this).attr('rel')).trigger('click')
			})

			$(document).on('change', '.lpr-question-head > select', function () {
				var $select = $(this),
					$container = $select.closest('.lpr-question');
				old_type = $select.attr('data-type'),
					new_type = $select.val(),
					$qq = $('.lpr-question', $container);
				$container.block_ui();
				$select.attr('data-type', new_type);
				$container.removeClass('lpr-question-' + old_type.replace(/_/g, '-')).addClass('lpr-question-' + new_type.replace(/_/g, '-'));
				var data = {
					action     : 'lpr_load_question_settings',
					question_id: $container.data('id'),
					type       : new_type
				};
				data = lprHook.applyFilters('lpr_admin_load_question_settings_args', data, new_type, $container, old_type);
				$.post(ajaxurl, data,
					function (res) {
						$new = $(res);
						$container.replaceWith($new);
						lprHook.doAction('lpr_admin_question_html', $new, new_type, old_type);
						$container.unblock_ui();
					}
					, 'text');
			}).on('focusin', '.lpr-question-title input', function () {
				$(this).removeClass('inactive');
			}).on('focusout', '.lpr-question-title input', function () {
				$(this).addClass('inactive');
			});

			$('.lpr-question-title input').fitSizeWithText({auto: true});
			$doc.on('click', '.lpr-questions-toggle a', function (evt) {
				evt.preventDefault();
				var $button = $(this),
					action = $button.data('action');
				$('.lpr-question .lpr-question-head a[data-action="' + action + '"]').trigger('click');
				$button.hide().siblings('a[data-action="expand"], a[data-action="collapse"]').show();
			});

			if ($('.lpr-question .lpr-question-content:visible').length) {
				$('.lpr-questions-toggle a[data-action="collapse"]').show().siblings('a[data-action="expand"]').hide();
			} else {
				$('.lpr-questions-toggle a[data-action="expand"]').show().siblings('a[data-action="collapse"]').hide();
			}

	}
	$(document).ready(_ready);

})();
