<?php
error_reporting(0);
ini_set('display_errors', 'Off');

if(substr($_SERVER['HTTP_USER_AGENT'], 0 , 7) == "SamSunf") die();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

if($meta_keywords == '') $meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj, Paylaş';
if($meta_description == '') $meta_description = 'Sayt ve ya Android, Iphone mobil tetbiqlerimiz vasitesi ile daxil olaraq yeni dostlar qazan, pulsuz mesajlaş, paylaş ve tanış ol!';

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="az">
<head>
<link rel="shortcut icon" href="http://m.alo.az/img/favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, width=device-width, height=device-height, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="keywords" content="<?=$meta_keywords;?>" />
<meta name="description" content="<?=$meta_description;?>" />
<style type="text/css">
img{
	border: 0;
}
body {
	background: #f9f9f9; 
	font: 16px Arial;
	color: #212121;
	margin: 0;
}
a:link, a:visited {
	font: 16px tahoma;
	text-decoration: none;
	#color: #1285FF;
	color: #0369d6;
}
a:hover, a:active {
	text-decoration: underline;
	color: #363636;	
}
div.header{
	height: 30px;
}
div.logo{
	padding: 0 0 0 4px;
	margin: 0px;
	display: block;
}

div.mnav{
	#background-image:url("/chat/img/bg/nav_orange.gif");
	#background-repeat: repeat-x;
	background-color: #5483c6;
	height: 24px;
	color: #fff;
	padding: 6px 0 0 8px;
	font: 14px tahoma;
	line-height: 18px;
}
div.mnav a:link, div.mnav a:visited {
	font: 14px tahoma;
	text-decoration: none;
	color: #fff;	
}
div.mnav a:hover, div.mnav a:active {
	text-decoration: underline;
	font: 14px tahoma;
	color: #fff;	
}

div.graynav{
	background: #ebebeb;
	color: #363636;
	padding: 3px 0 0 10px;
	margin: 3px 0;
	height: 20px;
}
div.graynav a:link, div.graynav a:visited {
	font: 14px tahoma;
	text-decoration: none;
	color: #E04B05;	
}
div.graynav a:hover, div.graynav a:active {
	text-decoration: underline;
	color: #363636;	
}
div.layer{
	padding: 0px 8px 0 8px;
	margin: 8px 0;
}
.placenum {
	background:#AA68AD; color:#FFF; padding:0px 3px; margin: 0 1px; font-size: 12px;
}
div.counter{
	padding: 0 10px 0 0;
	margin: 8px 0;
	text-align: right;
}
div.pagetitle{
	background: #82ca9c;
	color: #363636;
	padding: 3px 0 0 10px;
	margin: 3px 0;
	height: 20px;
}

div.ad_middle{
	text-align: center;
	padding: 2px;
}
.menu{
border-top : 1px solid #dfdfdf; 
background-color : #fff;
margin: 2px 0px;
padding: 3px 0px 3px 3px;
}
.links{ 
background-color: #ebebeb;
margin: 0px 0px;
padding: 6px;
}
.links a:link, .links a:visited {
	font: 14px tahoma;
	text-decoration: none;
	color: #E04B05;	
}
.links a:hover, div.graynav a:active {
	text-decoration: underline;
	color: #000;	
}

div.pageNav{
	text-align:center;margin: .4em;
}

div.pageNav a:link, div.pageNav a:visited, div.pageNav a:active{
	top:0px;background-color:#4F7F9F; color:#fff;
	border-radius: 2px;padding:.3em .6em; background-color:#ebebeb;color:#000;
}
div.pageNav span{
	border-radius: 2px;padding:.3em .6em;background-color:#5483c6; color:#fff;
}

#pageButon{
	#border-radius: 2px;padding:.3em .6em; background-color:#ebebeb;color:#000;
}
#pageButon_off{
	border-radius: 2px;padding:.3em .6em;background-color:#5483c6; color:#fff;
}

	a.button { text-decoration: none; color: #fff;}
	a.button {
		padding: 3px 10px;
		font-family: Verdana, Arial; font-size: 13px !important;
		background: #777 url(/img/button.png) repeat-x bottom;
		border: none;
		color: #fff;
	}
	a.button:hover {
		background-position: 0 center;
		color: #fff;
	}
	a.button:active {
		background-position: 0 top;
		position: relative;
		top: 1px;
		padding: 3px 10px;
		color: #fff;
	}
	a.button { background-color: #5483c6;}

a.button2 {
  font-weight: 700;
  color: white;
  text-decoration: none;
  padding: 2px;
  border-radius: 3px;
  background: rgb(64,199,129);
  box-shadow: 0 -3px rgb(53,167,110) inset;
  transition: 0.2s;
} 
a.button2:hover { background: rgb(53, 167, 110); }
a.button2:active {
  background: rgb(33,147,90);
  box-shadow: 0 3px rgb(33,147,90) inset;
}

.submitButton{
	border:		0px solid #6191d5;
	padding:		3px 10px !important;
	font-size:		14px !important;
	background-color:	#5483c6;
	color:			#ffffff;
}
.submitButton:hover {
	background-color:	#6191d5;
}

a.authButton{
	color: #fff;
	padding:		2px 10px ;
	font-size:		14px;
	background-color:	#5483c6;
	
}
a.authButton:hover {
	background-color:	#6191d5;
	text-decoration: none;
}


table.ozunutanit {
	width: 170px;
	cellpadding: 2;
}
table.ozunutanit th {
	background-color:#8e8e8e;
	color:#FFF;
	border-radius:3px;
}
table.ozunutanit tr {
	align: center;
}

table.ozunutanit td {
	background: #f6f4f4;
	text-align: center;
}
table.ozunutanit th a:link, table.ozunutanit th a:visited {
	font: 14px tahoma;
	text-decoration: none;
	color: #fff;	
}

table.ozunutanit th a:hover, table.ozunutanit th a:active {
	text-decoration: underline;
	font: 14px tahoma;
	color: #fff;	
}

.notif{
	text-shadow:0 1px #fff;
	margin-top:5px;
	padding:6px;
	border-radius:4px;
	border:1px solid #ccc color:#932;
	background-color:#faee8d;
	margin-right: 8px;
	margin-bottom: 5px;
}

.bubbledLeft,.bubbledRight{
	margin-top: 3px;
	padding: 1px 5px 1px 5px;
	#max-width: 60%;
	float:left;
	clear: both;
	margin-top:10px;
	margin-right:8px;
	border-radius:4px;
}

.bubbledLeft{
	background-color: #9EE6FF;
	border: 1px solid #9EE6FF;
	color: #343434;
	#border-radius: 5px;
	#box-shadow: 1px 0px 6px gray;
	float:left;
	
}
.bubbledRight{
	background-color: #E6E6EB;
	border: 1px solid #E6E6EB;
	color: #343434;
	#border-radius: 5px;
	#box-shadow: 1px 0px 6px gray;
	float:right;
}

.support{
	text-shadow:0 1px #fff;
	margin-top:5px;
	padding:6px;
	border-radius:4px;
	border:1px solid #ccc;
	margin-right: 8px;
	margin-bottom: 15px;
}
.support hr{
	border: 0;
	background-color: #dadada;
	height: 1px;
}

td.info_params{
	padding:5px;
	border-bottom:1px solid #E3E2E2;
	color: #247CAF;
}
td.info_value{
	padding:5px;
	border-bottom:1px solid #E3E2E2;
	max-width:300px;
}

a.button_gray{
	color: white;
	font-size: 13px;
	padding: 1px;
	padding-left: 5px;
	padding-right: 5px;
	border-radius: 2px;
	background: #626262;
}

.content{
	text-shadow:0 1px #fff;
	border-bottom:1px solid #ccc;
	margin:5px,0px,5px,0px;
	padding-top:6px;
	padding-bottom:10px;
	word-wrap:
	break-word;
}

.terms-note{
	 font-size:14px;
}

.terms-note a:link, .terms-note a:visited, .terms-note a:hover, .terms-note a:active {
    font-size:14px;
}

#small-size{
	 font-size:14px;
}

#small-size a:link, #small-size a:visited, #small-size a:hover, #small-size a:active {
    font-size:14px;
}
#play {
    background: url('http://cdn1.iconfinder.com/data/icons/iconslandplayer/PNG/64x64/CircleBlue/Play1Pressed.png') center center no-repeat;
    margin: -240px 10px 0 0;
    height: 140px;
    position: relative;
    z-index: 10;
}

@media screen and (max-width: 480px) {
    .video-container {
    position: relative;
    padding-bottom: 56.25%;
    padding-top: 35px;
    height: 0;
    overflow: hidden;
	}

	.video-container iframe {
		position: absolute;
		top:0;
		left: 0;
		width: 99%;
		height: 100%;
	}
}

a.play-button-link {
	position:relative;
	display:inline-block;
	line-height:0px;
}
 
a.play-button-link img.play-button {	 
  position: absolute;
	bottom: 30%;
	left: 55%;
	z-index: 10;
	margin: 0 0 0 -50px;
	opacity:1; 
	display:inline-block; 
}

a.play-button-link img.play-button2 {	 
  position: absolute;
	bottom: 25%;
	left: 87%;
	z-index: 10;
	margin: 0 0 0 -50px;
	opacity:1; 
	display:inline-block; 
	width:30px;
	height:30px;
}
form#azercell-checkout> * {
    margin: 4px 0px;
    padding: 4px;
}



</style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60236086-1', 'auto');
  ga('send', 'pageview');

</script>
<?
echo '<title>'.$title.'</title>';
?>
</head> 
<body>