 <?php

class Application_Model_DbTable_CmsShopData extends Zend_Db_Table_Abstract {

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected $_name = 'cms_shop_data';

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

    public function updateData($id, $info) {

        //izbegavamo da se promeni id usera, brise se iz niza ukoliko je setovan
        if (isset($info['id'])) {

            unset($info['id']);
        }
        $this->update($info, 'id = ' . $id);
    }

    /**
     * 
     * @param array $logo
     * @return int The new ID of new logo (autoincrement)
     */
    public function insertData($info) {


        $select = $this->select();

        //Sort rows by order_number DESC and fets=ch row from the top
        //with biggest order number
        $select->order('order_number DESC');

        $dataWithBiggestOrderNumber = $this->fetchRow($select);



        if ($dataWithBiggestOrderNumber instanceof Zend_Db_Table_Row) {

            $logo['order_number'] = $dataWithBiggestOrderNumber['order_number'] + 1;
        } else {
            //table was empty, we are inserting first logo
            $logo['order_number'] = 1;
        }

        $id = $this->insert($info);

        return $id;
    }

    /**
     * 
     * @param int $id ID of logo to delete
     */
    public function deleteData($id) {

        $logoPhotoFilePath = PUBLIC_PATH . '/uploads/logos/' . $id . '.jpg';

        if (is_file($logoPhotoFilePath)) {
            unlink($logoPhotoFilePath);
        }
        
        
        $info = $this->getDataById($id);

        $this->update(array(
            'order_number' => new Zend_Db_Expr('order_number -1')
                ), 'order_number > ' . $info['order_number']);


        $this->delete('id = ' . $id);
    }

    /**
     * 
     * @param nt $id ID of logo to enable
     */
    public function disableData($id) {

        $this->update(array(
            'status' => self::STATUS_DISABLED
                ), 'id = ' . $id);
    }

    /**
     * 
     * @param nt $id ID of logo to enable
     */
    public function enableData($id) {

        $this->update(array(
            'status' => self::STATUS_ENABLED
                ), 'id = ' . $id);
    }

    public function updateDataOfOrder($sortedIds) {

        foreach ($sortedIds as $orderNumber => $id) {

            $this->update(array(
                'order_number' => $orderNumber + 1 // +1 because it starts from 0
                    ), 'id = ' . $id);
        }
    }
    
   
     /**
     * Array $parameters is keeping search parameters.
     * Array $parameters  must be in following format:
     *      array(
     *          'filters' => array(
     *              'status' => 1,
     *                'id' => (1, 3, 8)
     *           ),
     *           'orders' => array(
     *                'username' => 'ASC' //key is column, asc-> ORDER BY ASC
     *                 'first_name' => 'DESC' // key is column, desc-> ORDER BY DESC
     *          ),
     *          'limit' => 50, //limit result set to 50 rows
     *          'page' => 3 // start from page 3. If no limit is set, page ignored
     * )
     * @param array $parameters Asoc array with keys "filters", "orders", "limit" and "page"
     */
    public function search(array $parameters = array()) {//ovo znaci da ne mora parametar da se prosledi jer je navedeno da je array
        $select = $this->select();



        if (isset($parameters['filters'])) {

            $filters = $parameters['filters'];

            $this->processFilters($filters, $select);
        }



        if (isset($parameters['orders'])) {


            $orders = $parameters['orders'];

            foreach ($orders as $field => $orderDirection) {

                switch ($field) {

                    case 'id': 
                    case 'about_us':
                    case 'address':
                    case 'phone':
                    case 'email':
                    case 'order_number':

                        if ($orderDirection === 'DESC') {

                            $select->order($field . ' DESC');
                        } else {
                            $select->order($field);
                        }
                        break;
                }
            }
        }



        //ovde se ispituje i uslov za page
        if (isset($parameters['limit'])) {

            if (isset($parameters['page'])) {
                // page is set do limit by page
                $select->limitPage($parameters['page'], $parameters['limit']);
            } else {
                //[age is not set, just do regular limit 
                $select->limit($parameters['limit']);
            }
        }

            //da proverimo koji nam se query izvrsava
            //die($select->assemble());
        //ovde dobijamo niz sa upitom
        return $this->fetchAll($select)->toArray();
    }

    /**
     * 
     * @param array $filters See function search $parameters ['filters']
     * return int Count rows that match $filters
     */
    public function count(array $filters = array()) {

        $select = $this->select();


        $this->processFilters($filters, $select);


        // reset previously set columns for 
        $select->reset('columns');
        // set one column/field to fetch and it is COUNT function
        $select->from($this->_name, 'COUNT(*) as total');

        $row = $this->fetchRow($select);

        return $row['total'];
    }

    /**
     * Fill $select object with WHERE conditions
     * @param array $filters
     * @param Zend_Db_Select $select
     */
    protected function processFilters(array $filters, Zend_Db_Select $select) {

        // $select object will be modified outside this function
        // objects are always passed by reference

        foreach ($filters as $field => $value) {

            switch ($field) {

                case 'id':
                case 'address':
                case 'email':

                    if (is_array($value)) {
                        $select->where($field . ' IN (?)', $value);
                    } else {
                        $select->where($field . ' = ?', $value);
                    }


                    break;

               


                case 'address_search':

                    $select->where('first_name LIKE ?', '%' . $value . '%');
                    break;

                case 'about_us_search':
                    $select->where('last_name LIKE ?', '%' . $value . '%');
                    break;

                case 'email_search':

                    $select->where('email_search LIKE ?', '%' . $value . '%');
                    break;
                
                case 'id_exclude':
                    
                    if (is_array($value)){
                        $select->where('id NOT IN (?)', $value);
                    }else {
                        $select->where('id != ?', $value);
                    }
                    break;
                    
                
            }
        }
    }


}
