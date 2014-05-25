<?php
class Relation extends EMongoDocument
    {
      public $from_person;
      public $to_person;
 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'Relation';
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

      public function findFriendsByID($id)
      {
          $result = array();

            $query = array(
            "from_person" => $id,
            );

          $l1 = $this->findAllByAttributes($query);

          foreach($l1 as $r)
          {
            //duplicate is found
            if(!in_array($r['to_person'], $result))
              $result[] = $r['to_person'];
          }

          

          $query = array(
            "to_person" => $id,
            );

          $l2 = $this->findAllByAttributes($query);

          foreach($l2 as $r)
          {
            $result[] = $r['from_person'];
          }

          return $result;
      }

      public function genFriendLinkData($id, $id2index)
      {

        $links = array();

        //
        foreach($id2index as $id =>$index )
        {
          $links[] = array("source"=>0, "target"=>$index, "length"=>rand(10,20), "c"=>'blue');
        }

        //TODO: add links between friends

        return $links;
        

      }
    }
?>