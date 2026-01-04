<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;

PhotoUploadAsset::register($this);
$this->title = $user["nickname"];
$text = strip_tags($share["text"]);
$this->title.= $text!=""?" ".mb_substr($text,0,160,'UTF-8'):' AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';


 $keywords = $keys!=""?" ".$keys:'Sosial şəbəkə, Chat, Tanışlıq, Mesaj, Əyləncə, Dost Tap, Paylaş, Azərbaycanda Tanışlıq';
$description = $text!=""?" ".mb_substr($text,0,200,'UTF-8'):'AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);

$this->registerMetaTag(['name' => 'description', 'content' => $description]);

$this->registerMetaTag(['property' => 'og:title', 'content' => htmlspecialchars_decode($this->title)]);

$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);

$this->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->getUrl()]);

$this->registerMetaTag(['property' => 'og:image', 'content' => 'http://alochat.com/images/alochat_logo.png']);

$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'Alochat.com']);
  ?>

