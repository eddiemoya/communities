  var rpxURLPrefix= "https://signin.shld.net/";
  
  Use http://www.sears.com/shc/s/EchoBack?storeId=10153&config=GlobalConfig&catalogId=12605&prop=RPX_GOOGLE_URL&propStore=10153
  Chaneg the property param to get the values of different params not defined in this script.
  
  $(document).ready(function () {
 
 	$('.loginLogo').unbind().click(function(){
 	var _this = $(this);
 	if(_this.hasClass('thirdPartyAol')){
		$('#thirdPartyLogin').hide();
		$('.prov_box').show();
		$('#wa_signin').unbind().click(function(){
			var aol = $('.user_id').val();
			if(aol != ''){
			var jrURL = _fnCallJRfromLoginModal(_this,aol);
			//trackAcceptOrDenyFlow(service);
			openChildPopup(jrURL);
			}							
		});
	}
	else{
		var jrURL = _fnCallJRfromLoginModal(_this,'');
		//trackAcceptOrDenyFlow(service);
		openChildPopup(jrURL);
																				
		}
 	}); 
 	
 	
 	//FB Connect Question
	$('#loginWrapper #thirdPartyLogin .questionMark').toggle(function(){
		var TTTop = $(this).offset().top;
		var TTLeft = $(this).offset().left;
			$('#fbTTContent').css({'top':TTTop+'px','left':'320px'}).show();
	},function(){
		$('#fbTTContent').hide();
});
});



function fnCallJRfromLoginModal(_obj,aolName){
	var _this = _obj;
	var jr_url;
	var sHost=window.location.host;
	var currentWindowLocation = window.location;
	
	var token_url=window.location.protocol+'//'+sHost+'/shc/s/FacebookConnectReg?'+
	      'storeId=${WCParam.storeId}&catalogId=${WCParam.catalogId}&langId=-1&shcapiBypassSSO=true&fromPage=ModalLogon&fbOpenIDLogonFlow=true';

	if(_this.hasClass('thirdPartyYahoo')){
		token_url= token_url + "&fromOIDProvider=Yahoo";
		jr_url = '${rpxURLPrefix}' + '${rpxYahooUrl}' + escape(token_url) ;
	}
	if(_this.hasClass('thirdPartyGoogle')){
	    token_url= token_url + "&fromOIDProvider=Google";
		jr_url = '${rpxURLPrefix}' + '${rpxGoogleUrl}' + escape(token_url) ;
	}
	if(_this.hasClass('thirdPartyAol')){
		token_url= token_url + "&fromOIDProvider=Aol";
		jr_url =  '${rpxURLPrefix}' + '${rpxAOLUrl}'+aolName+'&token_url='+ escape(token_url);
	}
	if(_this.hasClass('thirdPartyMyspace')){
		token_url= token_url + "&fromOIDProvider=Myspace";
		jr_url =  '${rpxURLPrefix}' + '${rpxMyspaceUrl}' + escape(token_url) ;
	}
	if(_this.hasClass('thirdPartyTwitter')){
		token_url= token_url + "&fromOIDProvider=Twitter";
		jr_url =  '${rpxURLPrefix}' + '${rpxTwitterUrl}' + escape(token_url);
	}

	if(_this.hasClass('thirdPartyFacebook')){
	
	var fbPerms = {publish_stream:1,offline_access:1,user_activities:1,friends_activities:1,user_birthday:1,
	friends_birthday:1,user_events:1,friends_events:1,user_interests:1,friends_interests:1,user_likes:1,
	friends_likes:1,email:1,user_location:1,friends_location:1,user_hometown:1,friends_hometown:1};
	function fb_perms(){var ss='';for(i in fbPerms){if(ss.length!=0)ss+=',';ss+=i;}return ss;}
	token_url= token_url + "&fromOIDProvider=FacebookConnect"; 
	jr_url = "${rpxFacebookConnectUrl}token_url="+escape(token_url)+"&ext_perm="+fb_perms();

	}

	try
   {
	var mywindow=window.open(jr_url,'Login', 'resizable=0,height=600,width=600,left=200,top=100');
	mywindow.moveTo(200, 100);
  } catch(err){}
}




function openChildPopup(jr_url){
 
 try
   {
    //if(typeof console != 'undefined' && console ) console.log('window.open : jr_url : ' + jr_url);
	if(jr_url.indexOf("www.google.com") > -1){
		mywindow =window.open(jr_url,'Login', 'resizable=0,height=600,width=1200,left=200,top=100');
	}else {
 	mywindow =window.open(jr_url,'Login', 'resizable=0,height=600,width=600,left=200,top=100');
	}
 	
 	/* commenting out below code for ECOM-232941
 	mywindow.moveTo(200, 100);
 	*/
 	
 	
  } catch(err){}
   
  }
