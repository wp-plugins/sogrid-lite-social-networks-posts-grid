jQuery(document).ready(function($){
	$(".imapper-checkbox-span").disableSelection();
	$(document).on('click','.imapper-checkbox-on',function(){
		if($(this).hasClass('my_disabled'))return;
		
		$(this).removeClass('inactive');
		$(this).siblings('.imapper-checkbox-off').addClass('inactive');
		$(this).siblings('[type=checkbox]').attr('checked','checked');
	});

	$(document).on('click','.imapper-checkbox-off',function(){
		if($(this).hasClass('my_disabled'))return;
		
		$(this).removeClass('inactive');
		$(this).siblings('.imapper-checkbox-on').addClass('inactive');
		$(this).siblings('[type=checkbox]').removeAttr('checked');
	});
	$(document).on('click','.wrap.imapper-admin-wrapper select',function(){
		//console.log($(this));
		// var select = $(this).parent();
		$(this).siblings('span').text($(this).children(":selected").text());
	});
	
	
});
