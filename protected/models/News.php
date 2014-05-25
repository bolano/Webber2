<?php
class News extends EMongoDocument
    {
      public $title;
      public $url;
      public $summary;
      public $date;
      public $query;

      public $realname;
      public $company;

      public $pids;
 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'News';
      }
 
      public function rules()
      {
        return array(

        );
      }
 
      public function attributeLabels()
      {
        return array(
          'title'  => 'News Title',
          'URL'   => 'News URL',
          'summary'   => 'News Summary',
          'date'   => 'News Date',
          'query'   => 'Query words for this news',
        );
      }

      public function findNewsByRealname($realname)
      {
          $query = array(
            "realname" => $realname
            );

          return $this->findAllByAttributes($query);
      }

      function aasort(&$array, $key) {
          $sorter=array();
          $ret=array();
          reset($array);
          foreach ($array as $ii => $va) {
              $sorter[$ii]=$va[$key];
          }
          arsort($sorter);
          foreach ($sorter as $ii => $va) {
              $ret[$ii]=$array[$ii];
          }
          $array=$ret;
      }


      public function findNewsByRealnameList($realnameList)
      {
        $result = array();

        foreach($realnameList as $realname)
        {
          $l = $this->findNewsByRealname($realname);
          foreach($l as $news)
          {
            $result[] = $news;
          }
        }

        $this->aasort($result,"date");

        return $result;
      }

      public function findNewsByURL($urllist)
      {
        $result = array();

        foreach($urllist as $url)
        {
          $query = array(
            "url" => $url
            );

           $result[] = $this->findByAttributes($query);
        }

        $this->aasort($result,"date");

        return $result;
      }


    }
?>