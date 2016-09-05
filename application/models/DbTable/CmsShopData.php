 <?php

class Application_Model_DbTable_CmsShopData extends Zend_Db_Table_Abstract {

    
    protected $_name = 'cms_shop_data';

    public function getInfos() {

        $select = $this->select();
        
      return $row = $this->fetchAll($select)->toArray();
       
//       if (empty($row)){
//           return false;
//       }else {
//           return $row;
//       }
        
    }

    /**
     * 
     * @param int $id
     * @param array $user Associative array with keys as column names and values as column new values
     */
    public function updateData($informations) {

        if (isset($informations['id'])) {

            unset($informations['id']);
        }
        $this->update($informations);
    }

    
    public function insertData($informations) {


        $select = $this->select();

        $id = $this->insert($informations);

        return $id;
    }
    
    public function getDataById($id) {

        $select = $this->select();
        $select->where('id =?', $id);

        $row = $this->fetchRow($select);

        if ($row instanceof Zend_Db_Table_Row) {

            return $row->toArray();
        } else {
            //row not found
            return null;
        }
    }


    
    
   

}
