<?php
class NewsIndex extends EMongoDocument
    {
      public $pid;
      public $urls;
 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'NewsIndex';
      }
 
      public function rules()
      {
        return array(

        );
      }
 

      //gen the links based on news
      public function genFriendLinkData($id, $id2index)
      {
         $result = array();

         //get the pid to urls map
         $urlMap = array();
         foreach($id2index as $id =>$index)
         {
            $query = array(
            "pid" => $id
            );

            $personNews = $this->findByAttributes($query);

            $urlMap[$id] = $personNews['urls'];
         }

         //check if news set of two persons are overlapped

         $added = array();
         foreach($id2index as $pid1 => $index1)
         {
            foreach($id2index as $pid2 => $index2)
            {
              if($pid1==$pid2)
                continue;
              else
              {
                $news1 = $urlMap[$pid1];
                $news2 = $urlMap[$pid2];

                if(!is_array($news1) or !is_array($news2))
                  continue;

                $both = array_intersect($news1, $news2);

                if(count($both)>0)
                {
                  $result[] = array("source"=>$index1, "target"=>$index2, "length"=>((float)count($news1)-(float)count($both))*10/(float)count($news1), "c"=>'red');
                  if($index1==0)
                  {
                    $added[] = $index2;
                  }
                }
              }
            }
         }

         //add user to neighbor
         foreach($id2index as $pid => $index)
         {
            if($index!=0 and !in_array($pid, $added))
            {
              $result[] = array("source"=>0, "target"=>$index, "length"=>rand(10,20), "c"=>'blue');
            }
         }

         return $result;
      }

      public function findNewsURLByIDList($IDList)
      {
        $result = array();

        foreach($IDList as $id)
        {
          $query = array(
            "pid" => $id
            );

          $personNews = $this->findByAttributes($query);


          if($personNews!=NULL)
            $result = array_merge($result, $personNews['urls']);
        }

        return $result;
      }

    }
?>