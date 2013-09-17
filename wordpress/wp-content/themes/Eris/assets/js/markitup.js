jQuery(document).ready(function($) { 
   		$('#forums-topic-reply-link a').on('click', function(){

   			container = $(this).closest('.reply');
   			
   			$.markItUp({target:"#bbp_reply_content", openWith:'[quote]', closeWith:'[/quote]' });
   		});
    	
   });