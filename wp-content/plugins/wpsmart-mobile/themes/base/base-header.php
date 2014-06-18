<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset=UTF-8 />
	<meta name="viewport" content="initial-scale=1" />
	<title><?php echo wps_get_option( 'site_title' ) ?></title>

	<?php wps_enqueue_header(); wp_head(); ?>
	
	<script type="text/javascript">var $wpsmart = jQuery.noConflict();</script>

	<script type="text/javascript">
		var imgUrl = 'http://tax1on1.org/wp-content/uploads/Weibobanner/Tax1on1Wechat100x100.png';
		var lineLink = "http://" + window.location.host + window.location.pathname;
		var descContent = "";
		var shareTitle = "<?php echo $post->post_title ?>";
		var appid = '';

		function shareFriend() {
		    WeixinJSBridge.invoke('sendAppMessage',{
                            "appid": appid,
                            "img_url": imgUrl,
                            "img_width": "160",
                            "img_height": "160",
                            "link": lineLink,
                            "desc": descContent,
                            "title": shareTitle
                            }, function(res) {
                            _report('send_msg', res.err_msg);
                            })
		}
		
		function shareTimeline() {
		    WeixinJSBridge.invoke('shareTimeline',{
                            "img_url": imgUrl,
                            "img_width": "640",
                            "img_height": "640",
                            "link": lineLink,
                            "desc": descContent,
                            "title": shareTitle
                            }, function(res) {
                            _report('timeline', res.err_msg);
                            });
		}
		
		function shareWeibo() {
		    WeixinJSBridge.invoke('shareWeibo',{
                            "content": descContent,
                            "url": lineLink,
                            }, function(res) {
                            _report('weibo', res.err_msg);
                            });
		}


document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {

        WeixinJSBridge.on('menu:share:appmessage', function(argv){
            shareFriend();
            });

        WeixinJSBridge.on('menu:share:timeline', function(argv){
            shareTimeline();
            });

        WeixinJSBridge.on('menu:share:weibo', function(argv){
            shareWeibo();
            });
        }, false);
</script>


</head>

<body>