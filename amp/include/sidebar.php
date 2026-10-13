<?php if(!defined('access')){ echo "Hacking ?"; exit(); } ?>
<div class="panel panel-default panel-stili">
<div class="panel-heading">
<h3 class="panel-title"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> En Çok Okunanlar</h3>
</div>
<div class="panel-body">
<ul class="media-list">
<?php
$populerSor = $conn->prepare("select * from blog where durum=? order by hit desc limit 5");
$populerSor->execute(array('Aktif'));
while($populer = $populerSor->fetch()){
?>			
<li class="media">
<div class="media-left">
<div class="yan-cerceve"><a href="yazi/<?php echo $populer['seo']; ?>/<?php echo $populer['id']; ?>.html"><amp-img src="blogresim/<?php echo $populer['resim']; ?>" alt="<?php echo $populer['baslik']; ?>" width="60" height="60" layout="fixed" class="yan-resim"></amp-img></a></div>
</div>
<div class="media-body">
<h5 class="media-heading">
<a href="yazi/<?php echo $populer['seo']; ?>/<?php echo $populer['id']; ?>.html"><?php echo $populer['baslik']; ?></a>
<br><small><?php echo turkcetarih('j F Y',$populer['yil'].'-'.$populer['tarih']); ?></small>
</h5>
<p class="small"><?php $metin = mb_substr($populer['aciklama'],0,150).'... '; echo strip_tags($metin); ?></p>
</div>
</li>
<?php } ?>					
</ul>
<a href="./makaleler.html" class="btn btn-danger btn-block">Tüm İlanlar »</a>
</div>
</div>
<div class="panel panel-default  panel-stili">
<div class="panel-heading">
<h3 class="panel-title"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> KATEGORİLER</h3>
</div>
<div class="list-group">
<?php
$katSor = $conn->prepare("select * from kategori order by baslik asc");
$katSor->execute();
while($kategori = $katSor->fetch()){
?>
<a class="list-group-item" href="kategori/<?php echo $kategori['seo']; ?>/<?php echo $kategori['id']; ?>"><?php echo $kategori['baslik']; ?></a>
<?php } ?>
</div>
</div>