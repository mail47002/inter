$(document).ready(function () {
	$(".add-question").click(function () {
		var type 	= $(this).attr('data-type');
		var id 		= Math.random();

		$('#questions').append( '<div class="question panel panel-default hide" data-id="'+id+'" data-type="'+type+'"><div class="panel-body"></div></div>' );
		$('#questions').append( $('.add-question-block[data-type="'+type+'"]').html() );
		$('#questions .well:last').attr('data-id', id);
	});

	$(document).on('click', '.add-question-cancel', function () {
		var id 		= $(this).parent().attr('data-id');

		$('#questions .question[data-id="'+id+'"]').remove();
		$(this).parent().slideUp(function () { $(this).remove(); });
	});

	$(document).on('click', '.add-question-confirm', function () {
		var id 		= $(this).parent().attr('data-id');
		var type 	= $('#questions .question[data-id="'+id+'"]').attr('data-type');

		$(this).parent().hide();

		$('#questions .question[data-id="'+id+'"]').removeClass('hide');
		$('#questions .question[data-id="'+id+'"] .panel-body').append('<p class="lead" style="margin-bottom: 0;">' + $(this).parent().find('#question-title').val() + '</p>');
		$('#questions .question[data-id="'+id+'"] .panel-body').append('<div class="variantai"></div>');

		if (type == 'radio') {
			$(this).parent().find('.variantai input').each(function () {
				$('#questions .question[data-id="'+id+'"] .variantai').append('<input type="radio" disabled name="'+id+'"> ' + $(this).val() + '<br>');
			});
		} else if (type == 'select') {
			$('#questions .question[data-id="'+id+'"] .variantai').append('<select disabled name="'+id+'"></select>');

			$(this).parent().find('.variantai input').each(function () {
				$('#questions .question[data-id="'+id+'"] .variantai select').append('<option value="">' + $(this).val() + '</option>');
			});
		}
	});

	$(document).on('click', ".add-more", function () {
		$(this).parents(".variantai").find(".option:last").after( $(this).parents(".variantai").find(".option:last").clone() );
		$(this).parents(".variantai").find(".option:last .add-more").remove();
		$(this).parents(".variantai").find(".option:last input").val('');
		$(this).parents(".variantai").find(".option:last .btn-spot").html('<button type="button" class="btn btn-default remove-it"><span class="glyphicon glyphicon-minus"></span> Pašalinti</button>');
	});

	$(document).on('click', ".remove-it", function () {
		$(this).parents(".option").remove();
	});
});