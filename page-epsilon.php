<?php 

class Epsilon_Lookup_Request extends SSO_Base_Request {
	
	protected $_endpoint = 'https://spc.gc.epsilon.com/prefcenter/external/getMemberinformation.aspx';
	
	public $response;
	
	
	public function __construct() {
		
		parent::__construct();
	}
	
	public static function factory() {
		 
		return new Epsilon_Lookup_Request;
	}
	
	public function query($email) {
		
		$xml = $this->_query('emailaddress', $email)
					->_url(false)
					->_execute();
						
		$xml = new SimpleXmlElement($xml);
		
		if(isset($xml->SUBSCRIPTIONS)) {
			
			return $xml->SUBSCRIPTIONS->SUBSCRIPTION;
		}
		
		return false;
	}
	
}


?>

<?php if(is_user_logged_in() && current_user_can('manage_options')): ?>
<html>
<head>
	<title>Epsilon Opt-In Query</title>
</head>
<body>
<h1>Epsilon Query</h1>
<form name="epsilon-query" id="epsilon-query" method="post">
	<label for="email">Enter User's Email:</label> 
	<input type="text" name="email" id="email" value="" /></br>
	<input type="submit" name="query" value="Submit" />
</form>
<div id="response">
<?php 
if(isset($_POST['query'])) {

	echo 'E-mail: ' . $_POST['email'] . '</br>';
	
	$response = Epsilon_Lookup_Request::factory()->query($_POST['email']);
	
	if($response) {
		
		switch($response->OPTTYPECODE){
			
			case 'MK':
				
				$store = 'MyKmart';
				
				break;
				
			case 'MS':
				
				$store = 'MySears';
				
				break;
			
		}
		
		echo 'Opted in to: <b>' . $store . '</b>.';
		
	} else {
		
		echo 'Not found. Not opted in to any list.';
	}
	
}
?>
</div>
</body>
</html>
<?php else:?>
	<h2>You do not have permission to view this page.</h2>
<?php endif;?>