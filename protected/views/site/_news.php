<div class="news">
  <div class="title">
    <table>
      <tr>
        <td>
          <?php $this->widget('bootstrap.widgets.TbButton', array(
              'label'=>date('Y-M-d h:i:s', $news->date->sec),
              'type'=>'important', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
              'size'=>'small', // null, 'large', 'small' or 'mini'
          )); ?>
          <?php $this->widget('bootstrap.widgets.TbButton', array(
              'label'=>$news->title,
              'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
              'size'=>'small', // null, 'large', 'small' or 'mini'
              'url'=>$news->url,
              'htmlOptions'=>array('target'=>'_blank'),
          )); ?>
        </td>
        <td Style="text-align:right">
          <?php $this->widget('bootstrap.widgets.TbButton', array(
              'label'=>$news->company." ".$news->realname,
              'url'=>'index.php?r=site/person&pid='.$news->pids[0],
              'type'=>'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
              'size'=>'mini', // null, 'large', 'small' or 'mini'
          )); ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="content">
    <?php echo $news->summary; ?>
  </div>
  <br>
</div><!-- post -->