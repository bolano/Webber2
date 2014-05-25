<?php
$this->widget('CLinkPager', array(
    'pages'=>$pages,
));
 
$this->widget('CListPager', array(
    'pages'=>$pages,
));?>


<?php foreach($newsList as $news): ?>
<?php $this->renderPartial('_news',array(
        'news'=>$news,
)); ?>
<?php endforeach; ?>

