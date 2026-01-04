<?

		$pop_ad_banner = '<iframe id="iframetest" src="http://cinemobi.club/subscribe/?ref=mobilink" width="100%" height="500px" style="border:none; height:500px;"></iframe>';
		$style = '<style>#close-link-ad{position:absolute; width:100%; height:100%; top:0; left:0;} #mobilink-pop {position: fixed; padding: 4px; background-color: #fff; max-width: 400px; max-height: 800px; margin: auto; left: 5%; right: 5%; top: 35px;border-radius: 4px;z-index: 10;box-shadow: 0 0 0 9999px rgba(0,0,0,0.5)} #mobilink-pop-info a:link {color: #2A5DB0; font-size: 12px; text-decoration: none;} #mobilink-pop-info a:visited { color: #2A5DB0; font-size: 12px; text-decoration: none;} #mobilink-pop-info a:hover {color: #2A5DB0; font-size: 12px;text-decoration: underline;} #mobilink-pop-info a:active { color: #2A5DB0; font-size: 12px; text-decoration: none;}</style>';

		
?>
<script>
if (window.isset == undefined) {
	document.write('<?=$style;?><div id="close-link-ad" onclick="closeLinkAd()"></div><div id="mobilink-pop"><div id="mobilink-pop-info" style="position: absolute; right: 0; text-align: center; font: 11px Tahoma; line-height: 1; color: #4b4b4b; background: #fff; padding: 0px 1px 2px 3px; border: 1px solid #dadada; border-bottom-left-radius: 5px; border-top: 0;"><span id="mobilink-reklami">Mobilink.az reklamı<br/></span><span id="countdown">3 san. sonra keç</span></div><?=$pop_ad_banner;?></div>');
	
	setTimeout(function (){
	   document.getElementById("mobilink-pop").style.display="none";
	   document.getElementById("close-link-ad").style.display="none";
	}, 200000);

	function closeLinkAd() {
		document.getElementById("mobilink-pop").style.display="none !important";
		document.getElementById("close-link-ad").style.display="none !important";
	}

	(window.isset = function runPop() {
		var activePop = 1;
		var timeLeft = 3,
			cinterval;
		var timeDec = function (){
			timeLeft--;
			document.getElementById('countdown').innerHTML = timeLeft+' san. sonra keç';
			if(timeLeft === 0){
				clearInterval(cinterval);
				document.getElementById('countdown').innerHTML = '<a onclick="closeLinkAd();" href="#"><img style="float: right;" src="http://mobilink.az/img/close-32.png" alt="Bağla" width="32px" height="32px"/></a> ';
				document.getElementById('mobilink-reklami').innerHTML = '';
			}
		};
		cinterval = setInterval(timeDec, 1000);
	})();
	}

</script>
	
