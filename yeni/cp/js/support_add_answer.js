// JavaScript Document
$(document).ready(function(e) {   
	$('.answer').on('change', function(){

		var supp_answer = $(this).val();
		var supp_id = $(this).attr('supp_id');
		
		$.ajax({ 
			type:'POST',
			url:'ajax/support_add_answer.php',
			data:'supp_id='+supp_id +'&supp_answer='+supp_answer,
			success:function(data)
			{
				// alert(data);
				$("#"+supp_id).show();
				if(data==1){ var result = 'OK';$("#"+supp_id).css('color','green' );}else{  var result = 'Error';$("#"+supp_id).css('color','red' );}
				$("#"+supp_id).text(result);
				$("#"+supp_id).fadeOut(3000);
			}
			
		});
		
   });
});