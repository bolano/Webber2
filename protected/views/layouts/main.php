<?php /* @var $this Controller */ 
	Yii::app()->bootstrap->register();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<style>
	  body {
	    padding-top: 60px;
	  }
	  @media (max-width: 980px) {
	    body {
	      padding-top: 0;
	    }
	  }
	</style>
</head>

<body>

<div class="container" id="page">

	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
		    'type'=>'inverse', // null or 'inverse'
		    'brand'=>'Webber',
		    'brandUrl'=>'#',
		    'collapse'=>true, // requires bootstrap-responsive.css
		    'items'=>array(
		        array(
		            'class'=>'bootstrap.widgets.TbMenu',
		            'items'=>array(
		                array('label'=>'Home', 'url'=>array('/site/index'), 'active'=>true),
		                array('label'=>'News', 'url'=>array('/site/news'), 'visible'=>!Yii::app()->user->isGuest),
		                array('label'=>'Graph', 'url'=>array('/site/graph'), 'visible'=>!Yii::app()->user->isGuest),
		                array('label'=>'Company', 'url'=>array('/site/company'), 'visible'=>!Yii::app()->user->isGuest),
		                array('label'=>'Map', 'url'=>array('/site/map'), 'visible'=>!Yii::app()->user->isGuest),
		                array('label'=>'Person', 'url'=>array('/site/person'), 'visible'=>!Yii::app()->user->isGuest),
		            ),
		        ),
		        //'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		        array(
		            'class'=>'bootstrap.widgets.TbMenu',
		            'htmlOptions'=>array('class'=>'pull-right'),
		            'items'=>array(
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
		            ),
		        ),
		    ),
		)); 

	 	echo $content;

	?>


</div><!-- page -->

</body>
</html>
