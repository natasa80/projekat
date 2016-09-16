<?php

class Zend_View_Helper_TopMenuHtml extends Zend_View_Helper_Abstract {

    public function topMenuHtml() {

        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();

        $topMenuSitemapPages = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
                'parent_id' => 0,
                'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC'
            )
        ));



        //resetovanje placeholdera
        $this->view->placeholder('topMenuHtml')->exchangeArray(array());
        $this->view->placeholder('topMenuHtml')->captureStart();
        ?>


        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li>
               <a href="<?php echo $this->view->baseUrl('/'); ?>">HOME <span class="sr-only">(current)</span></a>
              </li>
           

        <?php foreach ($topMenuSitemapPages as $sitemapPage) { ?>

                <li>
                    <a href="<?php echo $this->view->sitemapPageUrl($sitemapPage['id']); ?>">
            <?php echo $this->view->escape($sitemapPage['short_title']); ?>
                    </a>
                </li>

        <?php } ?>

        </ul>
        </div>



        <?php
        $this->view->placeholder('topMenuHtml')->captureEnd();
        return $this->view->placeholder('topMenuHtml')->toString();
    }

}


