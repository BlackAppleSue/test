<!--
<?php

session_start();
/*
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphUser.php' );
require_once( 'Facebook/GraphSessionInfo.php' );
 */
require_once( 'autoload.php' );
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;


$app_id = '286418341549663'; $app_secret = '78230ff0d25e2e7817004f32c4af27a4';
FacebookSession::setDefaultApplication($app_id, $app_secret);
$redirect_url = "https://kids-dream.phd.com.tw/";
$helper = new FacebookRedirectLoginHelper($redirect_url);

try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {
} catch (Exception $ex) {
}

if (isset($session)) {
    $access_token = $session->getToken();
    $appsecret_proof = hash_hmac('sha256', $access_token, $app_secret);
    $request = new FacebookRequest($session, 'GET', '/me', array("appsecret_proof" =>  $appsecret_proof));
    $response = $request->execute();
    $graphObject = $response->getGraphObject();

   //echo print_r($graphObject, 1);
} else {
    //echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}


//$data = file_get_contents('http://www.gamer.com.tw/'); print_r( $data );
//echo 'aaaa';
?>
-->

<!DOCTYPE html>
<html>
<head>
<title>Kids Dream</title>
<meta charset="UTF-8">
</head>
<body>
<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	  $(".login_button").hide();
	  $(".share-btn").show();
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
	  FB.login();
      document.getElementById('status').innerHTML = '請登入 ' +
        ' Facebook.';
		$(document).ready(function(){
			$(".login_button").show();
			$(".share-btn").hide();
		});
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '286418341549663',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/zh_TW/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
		console.log(response);
		$.ajax({
		  type: "POST",
		  url: "db.php",
		  data: { data: response , type:'insert'}
		  //type: 'json'
		})
		  .done(function( msg ) {
			msg = JSON.parse(msg);
			//alert( "Data Saved: " + msg );
			alert(msg.msg);
			$(".share").html('你的分享次數為:'+msg.share);
		  });		
    });
  }
  
  
function fb_share() {
 FB.init({
    appId      : '286418341549663',
    xfbml      : true,
    version    : 'v2.1'
  });
  FB.ui( {
    method: 'feed',
    name: "兒童之家 (夢想起飛)",
    link: "http://www.poweroflove.com.tw/tab_project.aspx?projectid=4B1080",
    picture: "http://www.poweroflove.com.tw/FileUpload/projects/2013/20130930_03032115_399_img.jpg",
    caption: "為改變衛生福利部中區兒童之家孩子因1.學業成就低落、2.自信心低落、3.缺乏改變動力、4.缺乏生涯規劃等因素而造成其發展困境，因此，本會（中華民國雲水蘭若文教協會）2014年將以「夢想起飛－中區兒童之家追夢輔導計劃」，透過補救教學課程提升其的學習成就，藉由「潛能開發」與「夢想啟發」講座，提升其學習興趣與動力，並為其自立生活做好生涯規劃。"
}, function( response ) {
    if ( response !== null && typeof response.post_id !== 'undefined' ) {
        console.log( response );
		$.ajax({
		  type: "POST",
		  url: "db.php",
		  data: { data: response , type:'share'}
		  //type: 'json'
		})
		  .done(function( msg ) {
			msg = JSON.parse(msg);
			//alert( "Data Saved: " + msg );
			alert(msg.msg);
			document.location.href="https://kids-dream.phd.com.tw/";
			//$(".share").html('你的分享次數為:'+msg.share);
		  });
    }
});


}
$(document).ready(function(){
  $('.share-btn').on( 'click', fb_share );
});
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<div class="login_button">
<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>
</div>
<div id="status">
</div>
<button class='btn btn-primary share-btn'>分享</button>
<div class="share"></div>
</body>
</html>

