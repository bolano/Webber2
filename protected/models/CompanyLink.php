<?php
class CompanyLink extends EMongoDocument
    {
      public $fromPerson;
      public $toPerson;
      public $length;
 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'CompanyLink';
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

      public function genFriendLinkData($id, $id2index)
      {
        $result = array();

        //add links between user and its friends
        $query = array(
            "fromPerson" => $id,
            );

        $toPersonList = $this->findAllByAttributes($query);

        $addedPerson = array();
        foreach($toPersonList as $person)
        {
            $result[] = array("source"=>0, "target"=>$id2index[$person["toPerson"]], "length"=>$person["length"], "c"=>'blue');
            $addedPerson[] = $person["toPerson"];
        }

        //not in companylink, add the longest link
        
        foreach($id2index as $pid => $index)
        {
            //use random value to make sparse
            if(!in_array($pid, $addedPerson))
              $result[] = array("source"=>0, "target"=>$index, "length"=>rand(10,20), "c"=>'blue');
        }

        //add the link between friends
        foreach($id2index as $pid => $index)
        {
            $query = array(
              "fromPerson" => $pid,
            );

            $toPersonList = $this->findAllByAttributes($query);

            foreach($toPersonList as $person)
            {
              if(array_key_exists($person["toPerson"], $id2index))
              {
                $result[] = array("source"=>$index, "target"=>$id2index[$person["toPerson"]], "length"=>$person["length"], "c"=>'red');
              }
            }
        }

        return $result;
      }
  }
?>