<?php  define('access',"False"); 
require_once("../include/connect.php"); require_once("fonksiyon.php");
$tag = htmlspecialchars($_GET["etiket"], ENT_QUOTES, 'utf-8');
$TagSql = $conn->prepare("SELECT * FROM etiketler WHERE seo = ?");  
$TagSql->execute(array($tag));
while ( $etiket = $TagSql->fetch() ){
$BlogSql = $conn->prepare("SELECT * FROM blog WHERE anahtar LIKE ?");
$BlogSql->execute(['%' . $etiket['baslik'] . '%']);
}
?>
<!doctype html>
<html lang="tr-TR" amp>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<title><?php echo $EtiketBaslik; ?> | <?php echo $SiteBilgi["logoyazi"]; ?></title>
<link rel="canonical" href="<?php echo $SiteBilgi["siteurl"]; ?>etiket/<?php echo $tag; ?>.html"/>
<link rel='stylesheet' id='google-fonts-css'  href='https://fonts.googleapis.com/css?family=Quicksand:400,500,700&amp;subset=latin-ext' type='text/css' media='all' />
<script type='text/javascript' async src='https://cdn.ampproject.org/v0.js'></script>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<script type='text/javascript' custom-element="amp-sidebar" async src='https://cdn.ampproject.org/v0/amp-sidebar-0.1.js'></script>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
<noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>	
<style amp-custom>
button,hr,input{overflow:visible}audio,canvas,progress,video{display:inline-block}progress,sub,sup{vertical-align:baseline}[type=checkbox],[type=radio],legend{box-sizing:border-box;padding:0}html{font-family:sans-serif;line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,main,menu,nav,section{display:block}h1{font-size:1em;margin:.67em 0}figure{margin:1em 40px}hr{box-sizing:content-box;height:0}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}a:active,a:hover{outline-width:0}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:bolder}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:ButtonText dotted 1px}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{color:inherit;display:table;max-width:100%;white-space:normal}textarea{overflow:auto}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}[hidden],template{display:none}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
.ilan-liste{padding:12px 0;border-bottom:1px solid #e6e6e6;min-height:125px}.ilan-liste:last-child{border-bottom:none;padding-bottom:0}.ilan-liste .post-thumbnail{display:inline-block;float:right;margin-left:15px}.ilan-liste .post-thumbnail .amp-image-tag.amp-image-tag.amp-image-tag{width:100px}.ilan-liste.have-thumbnail a.post-read-more{position:absolute;bottom:18px}.ilan-liste .post-title{max-height:55px;overflow:hidden}.post-thumbnail {
width:100px;
}
.pagination{line-height:28px;margin:0 0 30px;height:30px}.pagination .nav-links{text-align:center;padding:0;font-family:Quicksand,sans-serif;font-weight:400;font-size:14px}.pagination .nav-links .page-numbers,.pagination h2,.pagination>.screen-reader-text{display:none}.pagination .nav-links .page-numbers.current,.pagination .nav-links .page-numbers.next,.pagination .nav-links .page-numbers.prev{display:inline-block}.pagination .nav-links .page-numbers.next,.pagination .nav-links .page-numbers.prev{padding:0 18px;border:1px solid #d7d7d7;color:#494949;font-family:Quicksand,sans-serif;font-weight:500;font-size:13px;display:inline-block;text-decoration:none;background:#f7f7f7;border-radius:33px}.pagination .nav-links .page-numbers.prev{float:left}.pagination .nav-links .page-numbers.next{float:right}.pagination .nav-links .page-numbers.prev .fa{margin-right:7px}.pagination .nav-links .page-numbers.next .fa{margin-left:7px}.pagination .nav-links .page-numbers.current:first-child{float:left}.pagination .newer{float:right}.pagination .older{float:left}
.clearfix:after,.clearfix:before{display:table;content:' ';clear:both}.clearfix{zoom:1}
.wrap{padding:2%;height:100%;}
.wrap img{max-width:100%}
body.body{background:#ffff80;font-family:Quicksand,sans-serif;font-weight:400;color:#363636;line-height:1.44;font-size:15px}.istanbul-amp-wrapper{max-width:780px;margin:0 auto}.amp-image-tag,amp-video{max-width:100%}blockquote,p{margin:0 0 15px}.bold,b,strong{font-weight:700}a{-webkit-transition:all .4s ease;-moz-transition:all .4s ease;-o-transition:all .4s ease;transition:all .4s ease}amp-video{height:auto}.strong-label,blockquote{color:#000;font-family:Quicksand,sans-serif;font-weight:500;line-height:1.5}blockquote{border:1px solid #e2e2e2;border-width:1px 0;padding:15px 15px 15px 60px;text-align:left;position:relative;clear:both}blockquote p:last-child{margin-bottom:0}blockquote:before{content:"\f10e";font:normal normal normal 14px/1 FontAwesome;color:#d3d3d3;font-size:28px;position:absolute;left:12px;top:17px}.button,.comments-pagination a,.pagination a{padding:6px 15px;border:1px solid #d7d7d7;background:#faf9f9;color:#494949;font-family:Quicksand,sans-serif;font-weight:500;font-size:13px;display:inline-block;text-decoration:none;border-radius:33px}.pagination a{padding:0 15px}.comments-pagination{margin:10px 0;color:#adadad;font-size:small}.comments-pagination a{margin-right:5px;padding:5px 15px}.img-holder{position:relative;width:80px;background:center center no-repeat #eee;display:inline-block;float:left;margin-right:15px;margin-bottom:15px;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover}.img-holder:before{display:block;content:" ";width:100%;padding-top:70%}.content-holder{position:absolute;bottom:16px;left:16px;right:16px}.h1,.h2,.h3,.h4,.h5,.h6,.heading-typo,h1,h2,h3,h4,h5,h6{font-family:Quicksand,sans-serif;font-weight:500;margin:15px 0 7px}.heading{font-size:16px;font-weight:700;margin:10px 0}.site-header{height:52px;width:100%;position:relative;margin:0;color:#fff}.site-header .branding{display:block;text-align:center;font-size:20px;font-weight:400;text-decoration:none;font-family:Quicksand,sans-serif;color:#fff;position:absolute;top:0;width:100%;padding:10px 55px;z-index:9;height:52px;line-height:32px}.site-header .branding .amp-image-tag{display:inline-block}.sticky-nav .site-header{position:fixed;left:0;right:0;top:0;z-index:999;display:block}body.sticky-nav{padding-top:52px}.site-header .navbar-search,.site-header .navbar-toggle{color:#fff;font-weight:400;font-size:18px;position:absolute;top:0;z-index:99;border:none;background:rgba(0,0,0,.1);height:52px;line-height:50px;margin:0;padding:0;width:52px;text-align:center;outline:0;cursor:pointer;-webkit-transition:all .6s ease;-moz-transition:all .6s ease;-o-transition:all .6s ease;transition:all .6s ease}.site-header .navbar-search:hover,.site-header .navbar-toggle:hover{background:rgba(0,0,0,.1)}.site-header .navbar-search:focus,.site-header .navbar-toggle:focus{background:rgba(0,0,0,.2)}.site-header .navbar-toggle{font-size:21px;left:0}.site-header .navbar-search{font-size:18px;right:0;line-height:48px}.istanbul-amp-footer{margin:0}.istanbul-amp-footer.sticky-footer{position:fixed;bottom:0;left:0;right:0}.istanbul-amp-footer-nav{border-top:1px solid rgba(0,0,0,.1);background:#fff;padding:14px 15px}.istanbul-amp-copyright{padding:17px 10px;text-align:center;font-family:Quicksand,sans-serif;font-weight:400;color:#494949;border-top:1px solid rgba(0,0,0,.1);font-size:13px}.footer-navigation{list-style:none;margin:0;padding:0;text-align:center}.footer-navigation li{display:inline-block;margin:0 8px 5px}.footer-navigation li li,.footer-navigation ul{display:none}.footer-navigation a{text-decoration:none;color:#494949;font-family:Quicksand,sans-serif;font-weight:300;font-size:14px}.footer-navigation .fa{margin-right:5px}.istanbul-amp-main-link{display:block;text-align:center;font-weight:700;margin:6px 0 12px}.istanbul-amp-main-link a{color:#fff;text-decoration:none;padding:0 15px;display:inline-block;border:1px solid rgba(0,0,0,.08);border-radius:33px;line-height:26px;font-size:12px;font-weight:400}.istanbul-amp-main-link a .fa{margin-right:5px}.carousel{overflow:hidden}.carousel .carousel-item,.carousel .img-holder{width:205px;float:none;margin:0}.carousel .carousel-item{margin-right:20px;margin-bottom:20px;overflow:hidden;line-height:0}.carousel .carousel-item:last-child{margin-right:0}.carousel .content-holder{position:relative;bottom:auto;right:auto;top:auto;left:auto;background:#f8f8f8;border:1px solid #e2e2e2;border-top-width:0;float:left;white-space:normal;padding:15px;height:100px}.carousel .content-holder h3{margin:0;height:64px;overflow:hidden;position:relative}.carousel .content-holder a{line-height:20px;font-size:15px;color:#000;text-decoration:none}.comment-header{margin-bottom:14px}.comment .comment-content p:last-child,.comment-header:last-child{margin-bottom:0}.comment-list{margin:0}.comment-list,.comment-list ul{list-style:none;padding:0}.comment-list ul ul{padding:0 0 0 30px}.comment-list .comment{position:relative;margin-top:14px;padding-top:14px;border-top:1px solid #f3f3f3}.comment-list>.comment:first-child{margin-top:0}.comment-list li.comment:after{clear:both;content:' ';display:block}.comment .comment-avatar img{border-radius:50%}.comment .column-1{float:left;width:55px}.comment .column-2{padding-left:75px}.comment .comment-content{color:#838383;margin-top:8px;line-height:1.57;font-size:14px}.comment .comment-author{font-size:14px;font-weight:700;font-style:normal}.comment .comment-published{margin-left:10px;font-size:12px;color:#a2a2a2;font-style:italic}.comment .comment-footer .fa,.comment .comment-footer a{font-size:14px;text-decoration:none}.comment .comment-footer a+a{margin-left:10px}.comments-wrapper .button.add-comment{color:#555}.bs-shortcode{margin:0 0 30px}.bs-shortcode .section-heading{margin:0 0 13px}.bs-shortcode .section-heading .other-link{display:none}
.istanbul-amp-sidebar{background:#fff;max-width:350px;min-width:270px;padding-bottom:30px}.istanbul-amp-sidebar .sidebar-container{width:100%}.istanbul-amp-sidebar .close-sidebar{font-size:25px;border:none;color:#fff;position:absolute;top:10px;right:0px;background:0 0;width:32px;height:32px;line-height:32px;text-align:center;padding:0;outline:0;-webkit-transition:all .6s ease;-moz-transition:all .6s ease;-o-transition:all .6s ease;transition:all .6s ease;cursor:pointer}.istanbul-amp-sidebar .close-sidebar:hover{background:rgba(0,0,0,.1)}.sidebar-brand{color:#fff;padding: 0px 50px 10px 5px;text-align:left;font-family:Quicksand,sans-serif;line-height:2}.sidebar-brand .logo .amp-image-tag{display:inline-block;margin:0}.sidebar-brand.type-text{padding-top:12px}.sidebar-brand .brand-name{font-weight:500;font-size:18px}.sidebar-brand .brand-description{font-weight:400;font-size:14px;line-height:1.4;margin-top:4px}.istanbul-amp-sidebar .amp-menu{margin-top:2px}.istanbul-amp-sidebar .social-list-wrapper{margin:17px 0 0}.sidebar-footer{border-top:1px solid rgba(0,0,0,.09);font-family:Quicksand,sans-serif;font-size:13px;padding:20px 15px;margin-top:15px;color:#848484;line-height:1.7}.amp-menu div{padding:0}.amp-menu h6,.amp-menu section{-webkit-transition:all .3s ease;-moz-transition:all .3s ease;-o-transition:all .3s ease;transition:all .3s ease}.amp-menu h6[aria-expanded=false]{background:0 0;border:none}.amp-menu section[expanded]{color:#363636;background:#eee;background:rgba(0,0,0,.08);padding-left:10px}.amp-menu section[expanded]>h6{margin-left:-10px;background:0 0}.amp-menu a,.amp-menu h6{color:inherit;font-size:1.3rem;font-weight:300;padding:0;border:none}.amp-menu h6 a{padding:0;margin:0}

.amp-menu a,.amp-menu span{color: #000000;
    padding: 10px 5px 10px 15px;
    display: block;
    position: relative;
    -webkit-transition: all ease-in-out .22s;
    transition: all ease-in-out .22s;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    font-family: Quicksand,sans-serif;}

.amp-menu a:hover,.amp-menu span:hover{background:rgba(0,0,0,.06)}.amp-menu span:hover>a{background:0 0}.amp-menu span span{padding:0;margin:0;display:inline-block}.amp-menu span span:after{display:none}.amp-menu h6 span:after{position:absolute;right:0;top:0;font-family:FontAwesome;font-size:12px;line-height:38px;content:'\f0dd';color:#363636;-webkit-transition:all .4s ease;-moz-transition:all .4s ease;-o-transition:all .4s ease;transition:all .4s ease;width:40px;text-align:center}.amp-menu section[expanded]>h6 span:after{-webkit-transform:rotate(180deg);tranform:rotate(180deg);top:4px}.amp-menu .menu-item:not(.menu-item-has-children){padding:0}.amp-menu span.menu-item-deep.menu-item-deep{padding:0 0 0 25px}.amp-menu i{font-size:14px;margin-right:5px;margin-top:-3px}
.ilan-liste .post-title,.ilan-liste a.post-read-more{font-family:Quicksand,sans-serif;font-weight:500}.ilan-liste .post-title a,.ilan-liste a.post-read-more{color:#363636;text-decoration:none}
.ilan-liste{position:relative}.ilan-liste .post-title{color:#363636;font-size:15px;line-height:1.3;margin:0 0 10px}.ilan-liste .post-meta{margin-top:15px;font-size:12px}.ilan-liste .post-meta .post-date .fa{margin-right:3px}.ilan-liste a.post-read-more{font-size:12px;background:#f9f9f9;border:1px solid #d8d8d8;padding:0 13px;border-radius:33px;display:inline-block;line-height:24px}.ilan-liste a.post-read-more .fa{margin-left:3px}.ilan-liste a.post-read-more:hover{border-color:transparent;color:#fff}.ilan-liste .post-excerpt{color:#333;font-family:Quicksand,sans-serif;font-weight:500;font-size:14px;line-height:1.5}.ilan-liste .post-excerpt p:last-child{margin-bottom:0}
.ilan-liste-2{padding:12px 0;border-bottom:1px solid #ddd;}
.ilan-liste-2:last-child{border-bottom:none;padding-bottom:0}
.ilan-liste-2 .post-thumbnail{margin-bottom:4px;}
.ilan-liste-2 .post-thumbnail amp-img{max-width:100%; float:left; margin-right:7px;}.ilan-liste-2 .post-title{font-size:18px;margin:0 0 10px}
.ilan-liste-2 a.post-read-more{float:right}
.blog-liste{margin-bottom:30px;margin-top:1px;padding:5px;}
.margintop20{margin-top:20px;}
/*
* => Theme Color
*/
.ilan-liste a.post-read-more:hover,
.post-terms.cats .term-type,
.post-terms a:hover,
.search-form .search-submit,
.istanbul-amp-main-link a,
.sidebar-brand,
.site-header{
background:<?php echo $AmpBilgi['amp_headerarkarenk']; ?>;
}
.single-post .post-meta a,
.entry-content ul.bs-shortcode-list li:before,
a{
color: #000000;
}
/*
* => Other Colors
*/
body.body {
background:<?php echo $SiteBilgi['renk_arka']; ?>;
}
.istanbul-amp-wrapper {
border:1px solid <?php echo $AmpBilgi['amp_headerarkarenk']; ?>;
color: #363636;
}
.istanbul-amp-footer {
background:#f3f3f3;
}
.istanbul-amp-footer-nav {
background:#ffffff;
}
a.ilanver{background:#057b66;}
a.ilanver:hover{background:#333;}
a.ilanver{color:#fff;}
a.ilanver:hover{color:#fff;}
.yan-cerceve{
width:100px;
height:100px;
background:#ddd;
float:left;
margin:0;
border:2px solid #ddd;
border-radius:3px; margin-right:7px;
}
amp-img.yan-resim {
object-fit: contain;
max-width:100%;
max-height:100%;
}

.text-center{text-align:center;}
hr{border:0.5px solid #ddd;}
.text-small{font-size:11px;}
.sitebaslik{padding:4px;background:<?php echo $AmpBilgi['amp_baslikarkarenk']; ?>;color:<?php echo $AmpBilgi['amp_baslikyazirenk']; ?>;}
.sitebaslik h1{margin:10px 0;font-size:15px;}
.sitebaslik{margin:10px 0;font-size:15px;}

.listelevip img{
border: 0px; 
border-color: #000;
margin-left: 0px;
margin-bottom: 0px;
}
.vipliste {
max-width: 100%;
margin-top:10px;
}
.listv {
display: inline-block;
width: 86px;
}
.platin{height:auto; width:100%;border:2px solid transparent;float:left;margin:2px 0;}
.gold{height:auto; width:25%;border:2px solid transparent;float:left;margin:2px 0;}
.vip{height:auto; width:50%;border:2px solid transparent;float:left;margin:2px 0;}
.vipliste2 {
width:99%;
margin: auto;
}
.vipliste2 small{ font-size:8px;}
.vipilan{background:#ddd;text-align:center;}
.vipilan span{color:#000; font-size:20px; padding:5px; text-align:center;}
.kategorialtyazi {
font-weight: 400;
font-size: 85%;
}
.kategorialtyazi a{
font-weight: 600;
}

</style>

<script type="application/ld+json">
{"@context":"https://schema.org",
"@type":"NewsArticle",
"headline":"<?php echo $SiteBilgi["siteadi"]; ?>",
"url":"<?php echo $SiteBilgi["siteurl"]; ?>",
"datePublished":"2020-02-02T12:45:30+00:00",
"dateModified":"<?php echo date('Y-m-d'); ?>T<?php echo date('H:i:s'); ?>+00:00",
"mainEntityOfPage":{"@type":"WebPage","@id":"<?php echo $SiteBilgi["siteurl"]; ?>"},
"publisher":{"@type":"Organization",
"name":"<?php echo $EtiketBaslik; ?>",
"logo":{"@type":"ImageObject",
"url":"<?php echo $SiteBilgi["ampurl"]; ?>resim/esc.png",
"height":302,"width":182}},
"author":{"@type":"Person",
"name":"<?php echo $SiteBilgi["logoyazi"]; ?>"},
"image":{"@type":"ImageObject",
"url":"<?php echo $SiteBilgi["ampurl"]; ?>resim/esc.png",
"width":696,
"height":302}}
</script>
</head>
<body class="home blog body">
<div class="istanbul-amp-wrapper">
<?php require_once('menu.php'); ?>

<div class="wrap">
<div class="text-center sitebaslik"><h1><?php echo $EtiketBaslik; ?></h1></div>
<div class="blog-liste">
<?php while($Blog = $BlogSql->fetch()){ ?>
<article class="ilan-liste ilan-liste-2 clearfix">
<h3 class="post-title">
<a href="<?php echo $YeniAdres; ?>/yazi-<?php echo $Blog['seo']; ?>-<?php echo $Blog['id']; ?>.html"><?php echo $Blog['baslik']; ?></a>
</h3>
<div class="post-thumbnail">
<a href="<?php echo $YeniAdres; ?>/yazi-<?php echo $Blog['seo']; ?>-<?php echo $Blog['id']; ?>.html">
<div class="yan-cerceve"><amp-img src="../blogresim/<?php echo $Blog['resim']; ?>" alt="<?php echo $Blog['baslik']; ?>" height="100" width="100" class="yan-resim"></amp-img></div>
</a>
</div>
<div class="post-excerpt">
<p><?php $metin = mb_substr($Blog['aciklama'],0,500).'...'; echo strip_tags($metin); ?></p>
<a class="post-read-more" href="<?php echo $YeniAdres; ?>/yazi-<?php echo $Blog['seo']; ?>-<?php echo $Blog['id']; ?>.html">Devamı <i class="fa fa-arrow-right" aria-hidden="true"></i>
</a>
</div>
</article>
<?php } ?>
</div>
<footer class="istanbul-amp-footer ">
<div class="istanbul-amp-copyright"><?php echo $SiteBilgi["copyright"]; ?></div>
</footer>
<amp-analytics type="googleanalytics">
<script type="application/json">
{
"vars": {
"account": "<?php echo $SiteBilgi["analytics"]; ?>"
},
"triggers": {
"trackPageview": {
"on": "visible",
"request": "pageview"
}
}
}
</script>
</amp-analytics>
</div>
</div>
</body>
</html>