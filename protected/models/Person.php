<?php
class Person extends EMongoDocument
    {
      public $realname;
      public $id;
      public $company;
      public $division;
      public $position;

      public $address;
      public $addressLat;
      public $addressLng;

      public $baike_link;
 
      // This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }
 
      // This method is required!
      public function getCollectionName()
      {
        return 'Person';
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

      public function getPersonByID($pid)
      {
          $query = array(
                "id" => $pid,
                );

          $person = $this->findByAttributes($query);

          return $person;
      }

      public function getPersonByIDList($pidList)
      {
        $result = array();

        foreach($pidList as $id)
        {
          $query = array(
              "id" => $id,
              );

          $person = $this->findByAttributes($query);

           if($person!=null)
           {
              $result[] = $person;
           }
        }

        return $result;
      }

      public function getRealnameByID($personIDList)
      {
          $result = array();

          foreach($personIDList as $id)
          {
            $query = array(
              "id" => $id,
              );

             $person = $this->findByAttributes($query);

             if($person!=null)
             {
                $result[] = $person->realname;
             }
               
          }

          return $result;
      }

      public function getCompanyNameByPersonID($personID)
      {
        
      }

      public function getCompanyPerson($company)
      {
          $query = array(
            "company" => $company->company,
          );

          $persons = $this->findAllByAttributes($query);

          return $persons;
      }

      public function getFriendHotArea($friendIDList)
      {

        $friends = $this->getPersonByIDList($friendIDList);

        //产生聚合点和计数
        $aggre_data = array();
        foreach($friends as $friend)
        {

            if(!array_key_exists('addressLat',$friend))
              continue;
            //取两位精度
            $lat = round($friend['addressLat'],3);
            $lng = round($friend['addressLng'],3);

            if(!array_key_exists(strval($lat),$aggre_data))
            {
                $aggre_data[strval($lat)] = array();
            }

            if(!array_key_exists(strval($lng),$aggre_data[strval($lat)]))
            {
                $aggre_data[strval($lat)][strval($lng)] = 1;
            }
            else
            {
                $aggre_data[strval($lat)][strval($lng)] += 1;
            }
        }

        $result = array();
        foreach($aggre_data as $lat=> $lngList)
        {
          foreach($lngList as $lng =>$count)
          {
            $result[] = array("lat"=>$lat, "lng"=>$lng, "count"=>$count);
          }
        }

        return $result;
      }
    }
?>