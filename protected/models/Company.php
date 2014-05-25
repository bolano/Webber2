<?php
class Company extends EMongoDocument
    {
      public $company;
      public $baike_url;
      public $baike_title;
      public $baike_tags;
      public $baike_pics;
      public $baike_keywords;
      public $baike_abstract;

 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'Company';
      }
 
      public function rules()
      {
        return array(
        );
      }
 
      public function attributeLabels()
      {
        return array(
        );
      }

      public function getAllCompany()
      {

          $allcompany = $this->findAll();

          $this->aasort($allcompany,'company');

          return $allcompany;
      }

      public function getAllCompanyName()
      {
          $result = array();

          $allcompany = $this->findAll();

          foreach($allcompany as $company)
          {
            //var_dump($allcompany);
            $result[] = $company['company'];
          }

          return $result;
      }

      public function getCompanyByName($companyName)
      {
        $query = array(
          'company'=>$companyName,
          );

        $company = $this->findByAttributes($query);

        return $company;
      }

      public function getCompanyByID($companyID)
      {
        $query = array(
          '_id'=>new MongoId($companyID),
          );

        $company = $this->findByAttributes($query);

        return $company;
      }

      

      function aasort(&$array, $key) {
          $sorter=array();
          $ret=array();
          reset($array);
          foreach ($array as $ii => $va) {
              $sorter[$ii]=$va[$key];
          }
          asort($sorter);
          foreach ($sorter as $ii => $va) {
              $ret[$ii]=$array[$ii];
          }
          $array=$ret;
      }

    }
?>