//JavaScript Document
$(document).ready(function(){
    	load('action=ajax&page=1'); //Load by default first page on load
    	
});

$(document).on('click','.pagin a', function(){
	load('action=ajax&page='+$(this).attr('id')); //Load data when click on pagination
})

function load(str) {
	$("#loader").fadeIn('slow');
	$('#data').load('index.php?'+str,function() {
		$("#loader").fadeOut('slow');
	});
}

//Select All with checkbox functionality


//Functionality for single or multiple delete
$(document).on('click','.delall,.delrow',function(e){
	var id;
	if($(this).hasClass('delall')) {
		e.preventDefault();
		id = $('.selrow:checked').map(function(){
				return $(this).val();
			}).get();
	} else {
		id = $(this).parents('tr').find('input:hidden').val();
	}
	if($('.selrow:checked').length == 0 && $(this).hasClass('delall')) {
		alert('Please select atleast one row');
	} else if(confirm('Do you really want to delete')) {
		load('action=delete&page=1&id='+id);
	}
	e.stopImmediatePropagation();
});

function reload(x)
{

    load('action=ajax&page='+x);


};

function emptyRows()
{

$("tr").each(function () {
                var totalTDs = $(this).find("td").length;
                var emptyTDS = 0;

                $(this).find("td").each(function () {
                    if ($(this).text().trim() == "") {
                        emptyTDS += 1;
                    };
                });

                if (emptyTDS == totalTDs) {
                    $(this).remove();
                }
            });
}


//Save updated data in database
function savedata() {
	if($('.modified').length > 0) {
		$('.modified').each(function(e){
			$tr = $(this);
			$tr.find('input:text').hide();
			$.post('index.php?'+$tr.find(':input').serialize()+'&action=update',function() {});
			$tr.find('span').show(function(){
				$(this).text($(this).next('input').val()).next('input').remove();
			});
			$tr.css('background-color','#F5E6DA').removeClass('modified').find('img').show();
			window.location.reload();
		});
	}
}

//Save data when click anywhere on page body
$(document).on('click','html',function(e){
	if(! $(e.target).is(':input')) {
		savedata();
	}
	e.stopImmediatePropagation();
});


$(document).on('click','.task',function(e){
    var id
    if($(e.target).css('text-decoration') == 'none')
         {
            id = $(this).parents('tr').find('input:hidden').val();
            load('action=setDone&id='+id);
      }
      else {
            id = $(this).parents('tr').find('input:hidden').val();
            load('action=setUndone&id='+id);
      }
});

//Show input boxes in row when click on update icon
$(document).on('click','.updrow',function(e){
	$(this).hide();
	$tr = $(this).parents('tr');
	$tr.addClass('modified');
	$tr.css('background-color','#686C70');
	$tr.find('span').each(function(){
		$(this).hide(function(){
			$(this).after('<input name="'+$(this).attr('class')+'" value="'+$(this).text()+'" maxlength="35"/>');
		});
	});
	e.stopImmediatePropagation();
});
