<?php
header("Content-type: text/css");
include "../include/connect.php";
$SiteBilgi = $conn->query("SELECT * FROM ayarlar")->fetch(PDO::FETCH_ASSOC);

$arkaplan		= $SiteBilgi['renk_arka'];
$headerarka		= $SiteBilgi['renk_headerarka'];
$headeryazi		= $SiteBilgi['renk_headeryazi'];
$paketbaslik	= $SiteBilgi['renk_paketbaslik'];
$paketyazi		= $SiteBilgi['renk_paketyazi'];
?>
.navbar-default .navbar-brand{
    background: <?php echo $SiteBilgi['renk_headerarka']; ?>;
    }
html {
  font-size: 10px;

  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}
body {
  font-family: "Quicksand", Helvetica, Arial, sans-serif;
  font-size: 14px;
  line-height: 1.42857143;
  color: #333;
  background-color: <?php echo $arkaplan; ?>;
  /*background:url(<?php echo $SiteBilgi['siteurl']; ?>img/arka.png) repeat center top fixed <?php echo $arkaplan; ?>;*/
  
}
/*body{
background: linear-gradient(90deg, #e6ec8f, #e3ee53, #ffd800, #ffb600, #ff9400, #ff7300, #ff4600, #e6ec8f, #e3ee53 ,#ffd800, #ffb600, #ff9400, #ff7300, #ff4600);
background-size: 1000% 1000%;
animation: BackgroundGradient 30s ease infinite;}
@keyframes BackgroundGradient {
0% {background-position: 0% 50%;}
50% {background-position: 100% 50%;}
100% {background-position: 0% 50%;}
} */

::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
	border-radius: 0px;
	background-color: #dddddd;
}

::-webkit-scrollbar
{
	width: 8px;
	background-color: #dddddd;
}

::-webkit-scrollbar-thumb
{
	border-radius: 0px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #732344;
}

.temizle{ clear:both; }
.slogan{font-size:30px;}
.ilanhatti{font-size:24px;}
.m-bottom{margin-bottom:8px;}
.text-white, .text-white:hover {color: #fff;}

.VitrinUst:hover{
	opacity:1;
	background:#732344;
	border:1px solid #732344;
	-webkit-transition: all 0.6s ease-in-out;
-moz-transition: all 0.6s ease-in-out;
transition: all 0.6s ease-in-out;
}

.ilan-karti.efekt:after {
content: '';
position: absolute;
top: 0;
left: 1.5%;
width: 0;
height: 100%;
background: rgba(255, 255, 255, 1);
}
.ilan-karti.efekt:hover:after {
width: 97%;
background-color: rgba(255,255,255,0.3);
-webkit-transition: all 0.6s ease-in-out;
-moz-transition: all 0.6s ease-in-out;
transition: all 0.6s ease-in-out;
z-index:-9;
}
.ilan-karti:active:after {
  opacity: 1; 
}

.navbar-header a {
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 3px;
  display: inline-block;
  position: relative;
  -webkit-mask-image: linear-gradient(-75deg, rgba(0,0,0,.6) 30%, #000 50%, rgba(0,0,0,.6) 70%);
  -webkit-mask-size: 200%;
  animation: shine 2s linear infinite;
}

@keyframes shine {
  from { -webkit-mask-position: 150%; }
  to { -webkit-mask-position: -50%; }
}

.vipilan{background:<?php echo $paketbaslik; ?>;text-align:center;margin-top:5px;margin-bottom:5px;padding:5px;text-align:center;}
.vipilan span{color:<?php echo $paketyazi; ?>; font-size:15px; padding:5px; font-weight:500; text-align:center;}
.vipilan span.glyphicon{color:#fff; font-size:12px; padding:2px; font-weight:400; text-align:center;}

.cerceve{
	width:110px;
	height:110px;
	float:left;
	margin-top:10px;
}

img.resim {
    object-fit: contain;
	width:100%;
	height:100%;
	border: 2px solid #ddd;
	padding:1px;
}

.yan-cerceve{
	width:60px;
	height:60px;
	float:left;
	margin:0;
	border-radius:3px;
}

img.yan-resim {
    object-fit: contain;
	width:100%;
	height:100%;
	border: 1px solid #ccc;
}

@media only screen and (max-width: 414px) {
.cerceve{
	width:90px;
	height:90px;
	float:left;
	margin-top:10px;
}

img.resim {
    object-fit: contain;
	width:100%;
	height:100%;
	border: 1px solid #ddd;
	padding:1px;
}

.yan-cerceve{
	width:45px;
	height:45px;
	float:left;
	margin:0;
}

img.yan-resim {
    object-fit: contain;
	width:100%;
	height:100%;
	border: 1px solid #ccc;
}
}

h4.media-heading a {color:#ce0056; font-size: 1.6rem;  font-weight: 600;}

.panel-default > .panel-heading {
  color: <?php echo $headeryazi; ?>;
    background-color: <?php echo $headerarka; ?>;
    border-color: #fff;
}

@media (min-width:320px) and (max-width:768px) {
   
}


a.devami{
	border:1px solid #999;
	border-radius:3px;
	padding:3px;
	text-decoration:none;
	font-size:11px;
	background:#fff;
	color:#333;
}

a.devami:hover{
	border:1px solid #999;
	text-decoration:none;
	color:#eee;
	background:#333;
}



.modelonceki{
	float:left; margin-left:22px;
}

.modelsonraki{
	float:right; margin-right:22px;
}



#footer{
	color: #777;
}

.thumb-contenido{
    
    margin-bottom:4%;
    margin-left: 0px;
    padding-left: 0px;

}

/* CSS Test begin */
.comment-box {
    margin-top: 30px;
}
/* CSS Test end */

.comment-box img {
    width: 50px;
    height: 50px;
}
.comment-box .media-left {
    padding-right: 10px;
    width: 75px;
}
.comment-box .media-body p {
    border: 1px solid #ddd;
    padding: 10px;
	
}
.comment-box .media-body .media p {
    margin-bottom: 0;
}
.comment-box .media-heading {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 7px 25px;
    position: relative;
    margin-bottom: -1px;
}
.comment-box .media-heading:before {
    content: "";
    width: 10px;
    height: 10px;
    background-color: #f1f1f1;
    border: 0px solid #ddd;
    border-width: 0px 0 0 0px;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    position: absolute;
    top: 10px;
    left: 4px;
}

.Header_Alani{
	color: <?php echo $headeryazi; ?>; 
	background-color: <?php echo $headerarka; ?>; 
	text-align:center; 
	margin-top: 50px;
    margin-bottom: 10px;
    min-height: 50px;
    padding: 5px;
	text-shadow:0px 0px 0px #333;
	font-family: 'Quicksand', sans-serif;
	
	
}


.block-title{color: #48453d; margin-bottom:0px; font-size:18px; margin-top: 0px; font-weight:500; text-transform:capitalize;}
.item-content-block{padding:20px; border-top:2px solid #f6f6f2; background-color:#FFF; display:block;margin-bottom:20px;}

.tags button{background-color:#777; padding:10px; color:#fff; display:inline-block; font-size:11px; text-transform:capitalize; line-height:4px; border-radius:2px; border:0px; margin-bottom:5px; margin-right:2px; text-decoration:none;}

.tags button:hover{background-color:#333;color:#fff;}


/*----------------------
Product Card Styles 
----------------------*/
.panel.panel-stili {
    background: #fff;
	color:#333;
	
}
.panelTop {
	
}

.panelTop h3 a{
	color:#000;
	font-weight:500;
	font-size:0.75em;
	text-decoration:none;
}

.panelTop p{
	color:#000;
	font-weight:500;
	
}

.panelBottom {
    border-radius: 3px;
	padding:5px;
	margin-top: 10px;
	background:#fff;
	color:#eee;
	
}


.panelBottom span{
	color:#333;
	font-weight:300;
	font-size:11px;
	
}



/* Title Divider
============================================ */
.title-divider {
  margin: 0 auto;
  max-width: 300px;
  margin-bottom: 20px;
  overflow: hidden;
  padding: 10px 0;
}
.hr-divider {
  border-bottom: 1px solid #333;
  position: relative;
  float: left;
  bottom: -4px;
}
.icon-separator {
  float: left;
  text-align: center;
  margin-top: -7px;
  font-size: 24px;
  color: #222;
  padding: 0;
}
.heading-divider {
  margin-bottom: 40px;
  margin-top: 30px;
  display: flex;
}
.heading-divider .title {
  flex-grow: 0;
  -webkit-flex-grow: 0;
  margin: 0 5px 0 0;
  line-height: 1px;
}
.heading-divider .line-separator {
  border-bottom: 1px solid #52f75f;
  border-top: 1px solid #52f75f;
  flex-grow: 1;
  -webkit-flex-grow: 1;
  height: 6px;
  position: relative;
}







.transition {
  transition: .3s cubic-bezier(.3, 0, 0, 1.3)
}
.three .transition {
  transition: .7s cubic-bezier(.3, 0, 0, 1.3)
}
.five .transition {
  transition: .5s cubic-bezier(.3, 0, 0, 1.3)
}


.kapsayici{
	
	background-position: center center;
	overflow: hidden;
    background-size: cover;
	
	
}

 @-webkit-keyframes social-show {
 from {
margin-top: 140px;
}
to {
  margin-top: 0px;
}
}
 @-moz-keyframes social-show {
 from {
margin-top: 140px;
}
to {
  margin-top: 0px;
}
}
 @-o-keyframes social-show {
 from {
margin-top: 140px;
}
to {
  margin-top: 0px;
}
}
 @keyframes social-show {
 from {
margin-top: 140px;
}
to {
  margin-top: 0px;
}
}




.go-top {
  display: block;
  width: 30px;
  height: 30px;
  line-height: 35px;
  text-align: center;
  font-size: 15px;
  position: fixed;
  bottom: -40px;
  right: 10px;
  -webkit-transition: all 1s ease;
  -moz-transition: all 1s ease;
  -o-transition: all 1s ease;
  transition: all 1s ease;
  background-color: #333;
  color: #FFFFFF;
  text-decoration: none;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  cursor:pointer;

}

.go-top.show { bottom: 10px;cursor:pointer; }

.go-top:hover {
  background-color: #434a54;
  color: #FFFFFF;
  cursor:pointer;
}