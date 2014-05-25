<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php $this->widget('bootstrap.widgets.TbCarousel', array(
    'items'=>array(
        array('image'=>'images/1.jpg', 'label'=>'', 'caption'=>'The “Social Graph” behind Facebook'),
        array('image'=>'images/2.jpg', 'label'=>'', 'caption'=>'Human Brain has between 10-100 billion neurons.'),
    ),
)); ?>