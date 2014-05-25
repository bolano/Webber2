<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Print the news of friends.
	 */
	public function actionNews()
	{
		$friendIDList=Relation::model()->findFriendsByID(Yii::app()->user->id);

	    $newsURLList=NewsIndex::model()->findNewsURLByIDList($friendIDList);

	    $newsList = News::model()->findNewsByURL($newsURLList);

	    //page
	    $pageNum = Yii::app()->request->getQuery('page');
	    if($pageNum==null)
	    	$pageNum = 1;

	    $newsPerPage = 10;
	    $itemCount = count($newsList);
	    $pageSize = $newsPerPage;

		$pages =new CPagination($itemCount);
	    $pages->setPageSize($pageSize);

	    $this->render('news',array(
                        'newsList'=>array_slice($newsList, $newsPerPage*($pageNum-1), $newsPerPage),
                        'pages'=>$pages,
                ));
	}

	/**
	 * Print the graph of the user.
	 */
	public function actionGraph()
	{

		$graphData = array('nodes' => array(), 'links' => array() );

		$personIndex = array();

		//user
        $me = Person::model()->getPersonByID(Yii::app()->user->id);
        $graphData['nodes'][] = array("name"=>$me->realname, 
                                     "group"=>0, 
                                     "title"=>$me->company.",".$me->division.",".$me->position, 
                                     "id"=>Yii::app()->user->id
                                     );
        $personIndex[$me->id] = 0;


        //friends
        $index = 1;
        $friendIDList=Relation::model()->findFriendsByID(Yii::app()->user->id);
        $friends = Person::model()->getPersonByIDList($friendIDList);
        foreach($friends as $friend)
        {
            $graphData['nodes'][] = array("name"=>$friend->realname, 
                         "group"=>0, 
                         "title"=>$friend->company.",".$friend->division.",".$friend->position, 
                         "id"=>$friend->id
                         );

            $personIndex[$friend->id] = $index;

            $index = $index+1;
        }

        //links
        $linkType = Yii::app()->request->getQuery('linktype');

        if($linkType==NULL)
        {
        	$linkType = 0;
        }

        switch($linkType)
        {
        	//default
        	case 0:
        		//mapping to real connections
        		$graphData['links'] = Relation::model()->genFriendLinkData(Yii::app()->user->id, $personIndex);
        		break;
        	case 1:
        		//mapping to company connections
        		//
        		$graphData['links'] = CompanyLink::model()->genFriendLinkData(Yii::app()->user->id, $personIndex);
        		break;
        	case 2:
        		$graphData['links'] = NewsIndex::model()->genFriendLinkData(Yii::app()->user->id, $personIndex);
        		break;
        	default:
        		break;
        }


	    $this->render('graph',array(
                        'graphData'=>$graphData,
                ));
	}

	public function actionCompany()
	{
		//get all the company name and id

		$companys = Company::model()->getAllCompany();

		//links
        $companyID = Yii::app()->request->getQuery('cid');
        //default the company of the user
        $curCompany;
        if($companyID ==null)
        {
        	$user = Person::model()->getPersonByID(Yii::app()->user->id);
        	$companyName = $user['company'];

        	$curCompany = Company::model()->getCompanyByName($companyName);
    	}
    	else
    	{
    		$curCompany = Company::model()->getCompanyByID($companyID);
    	}

    	//get the person of this company
    	$persons = Person::model()->getCompanyPerson($curCompany);

		$this->render('company', array(
					'companys'=>$companys,
					'curCompany'=>$curCompany,
					'persons'=>$persons,
				)
			);
	}

	public function actionMap()
	{

		$friendIDList=Relation::model()->findFriendsByID(Yii::app()->user->id);
        $friends = Person::model()->getPersonByIDList($friendIDList);

        $friendLocData = array();
        foreach($friends as $friend)
        {
        	$friendLocData[] = array("lat"=>$friend->addressLat,"lng"=>$friend->addressLng,"name"=>$friend->realname,"info"=>$friend->company);
        }

        $hotAreaData = Person::model()->getFriendHotArea($friendIDList);

		$this->render('map', array(
				'friendLocData'=>$friendLocData,
				'hotAreaData'=>$hotAreaData,
			)
		);
	}

	public function actionPerson()
	{

        //pid
        $pid = Yii::app()->request->getQuery('pid');

        if($pid==NULL)
        {
        	$pid = Yii::app()->user->id;
        }

        $person =  Person::model()->getPersonByID($pid);

        //links
        $graphData = array('nodes' => array(), 'links' => array() );

		$personIndex = array();

		//user
        $graphData['nodes'][] = array("name"=>$person->realname, 
                                     "group"=>0, 
                                     "title"=>$person->company.",".$person->division.",".$person->position, 
                                     "id"=>$person->id
                                     );
        $personIndex[$person->id] = 0;


        //friends
        $index = 1;
        $friendIDList=Relation::model()->findFriendsByID($person->id);
        $friends = Person::model()->getPersonByIDList($friendIDList);
        foreach($friends as $friend)
        {
            $graphData['nodes'][] = array("name"=>$friend->realname, 
                         "group"=>0, 
                         "title"=>$friend->company.",".$friend->division.",".$friend->position, 
                         "id"=>$friend->id
                         );

            $personIndex[$friend->id] = $index;

            $index = $index+1;
        }

        $graphData['links'] = Relation::model()->genFriendLinkData($person->id, $personIndex);

		$this->render('person', array(
				'person'=>$person,
				'graphData'=>$graphData,
			)
		);
	}
}