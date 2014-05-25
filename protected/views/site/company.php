<style type="text/css">
td {
  vertical-align: top;
}
</style>

<table>
	<tr>
		<td width="20%">

<?php 

	$companyNameItem = array();
	foreach($companys as $company)
	{
		$companyNameItem[] = array('label'=>$company['company'], 'url'=>'index.php?r=site/company&cid='.(string)$company['_id']);
	}



	$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>$companyNameItem,
	)); 

?>
		</td>
		<td valign='top'>
<?php

$formCompany=array();
$formCompany['company'] = $curCompany['company'];
$formCompany['baike_url'] = $curCompany['baike_url'];
$formCompany['baike_title'] = $curCompany['baike_title'];
$formCompany['baike_abstract'] = $curCompany['baike_abstract'];

if(array_key_exists('baike_keywords', $curCompany) && $curCompany['baike_keywords']!=null)
{
	$formCompany['baike_keywords'] = implode($curCompany['baike_keywords'],",");
}

if(array_key_exists('baike_tags', $curCompany) && $curCompany['baike_tags']!=null)
	$formCompany['baike_tags'] = implode($curCompany['baike_tags'],",");


$this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$formCompany,
    'attributes'=>array(
        array('name'=>'company', 'label'=>'Company Name'),
        array('name'=>'baike_url', 'label'=>'Baike URL', 'type'=>'url'),
        array('name'=>'baike_title', 'label'=>'Baike Title'),
        array('name'=>'baike_abstract', 'label'=>'Baike Abstract'),
        array('name'=>'baike_keywords', 'label'=>'Baike Keywords'),
        array('name'=>'baike_tags', 'label'=>'Baike Tags'),
    ),
));


//persons
$personArray = array();
foreach($persons as $person)
{
	$personArray[] = array(
		'id'=>$person->id,
		'realname'=>$person->realname,
		'division'=>$person->division,
		'position'=>$person->position,
		'address'=>$person->address,
		);
}

$gridDataProvider = new CArrayDataProvider($persons);

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'realname', 'header'=>'Real Name'),
        array('name'=>'division', 'header'=>'Division'),
        array('name'=>'position', 'header'=>'Position'),
        array('name'=>'address', 'header'=>'Address'),
    ),
));

?>


		</td>
	</tr>
</table>