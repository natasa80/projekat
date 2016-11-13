<?php

class Zend_View_Helper_Footerdata extends Zend_View_Helper_Abstract {

    public function footerdata() {

        $cmsShopData = new Application_Model_DbTable_CmsShopData();


        $shopData = $cmsShopData->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsShopData::STATUS_ENABLED
            ),
            'limit' => 1
        ));


        $cmsWorkingHours = new Application_Model_DbTable_CmsWorkingHours ();
        $workingHours = $cmsWorkingHours->search(array(
            'orders' => array(
                'order_number' => 'desc'
            )
        ));
        ?>


        <?php foreach ($shopData as $shopInfo) { ?>
            <h4 class="text-uppercase text-left footerh4padd">about us</h4>
            <p class="text-left"><?php echo $this->view->escape($shopInfo['about_us']); ?></p>
            <div class="media media1">
                <div class="media-left blogMedia1">
                    <a href="#">
                        <span class="media-object fa fa-map-o"></span>
                    </a>
                </div>
                <div class="media-body">
                    <p class="media-heading"><?php echo $this->view->escape($shopInfo['address']); ?></p>
            <?php echo $this->view->escape($shopInfo['city']); ?>
                </div>
            </div>

            <div class="media media1">
                <div class="media-left blogMedia1">
                    <a href="#">
                        <span class="media-object fa fa-phone"></span>
                    </a>
                </div>
                <div class="media-body">
                    <p class="media-heading">Customer Support :</p>
            <?php echo $this->view->escape($shopInfo['phone']); ?>
                </div>
            </div>

            <div class="media media1">
                <div class="media-left blogMedia1">
                    <a href="#">
                        <span class="media-object fa fa-envelope-o"></span>
                    </a>
                </div>
                <div class="media-body">
                    <p class="media-heading">Email:</p>
            <?php echo $this->view->escape($shopInfo['email']); ?>
                </div>
            </div>
            </div>
        <?php } ?>


        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">
            <h4 class="text-uppercase footerh4padd">working time</h4>

            <ul class="list-unstyled">
        <?php foreach ($workingHours as $workingHour) { ?>
                    <li><?php echo $this->view->escape($workingHour['day']); ?></li>

        <?php } ?>
            </ul>

        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">

            <ul class="list-unstyled openTime">
        <?php foreach ($workingHours as $workingHour) { ?>

                    <li><?php echo $this->view->escape($workingHour['time']); ?></li>

        <?php } ?>
            </ul>
        </div>

       
           <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="footerSubscriber">
                            <div class="footerSocial">
                            <?php foreach ($shopData as $shopInfo) { ?>
                            <a class="aSocial" target="_blank" href="<?php echo $this->view->escape($shopInfo['facebook']); ?>"><span class="fa fa-facebook"></span></a>
                            <a class="aSocial" target="_blank" href="<?php echo $this->view->escape($shopInfo['google_plus']); ?>"><span class="fa fa-google-plus"></span></a>
                            <a class="aSocial" target="_blank" href="#"><span class="fa fa-tumblr"></span></a>
                            <a class="aSocial" target="_blank" href="#"><span class="fa fa-dribbble"></span></a>
                            <a class="aSocial" target="_blank" href="https://rs.linkedin.com/in/natasa-lukic-930081123"<?php echo $this->view->escape($shopInfo['linkedin']); ?>"><span class="fa fa-linkedin"></span></a>
                            <a class="aSocial" target="_blank" href="<?php echo $this->view->escape($shopInfo['twitter']); ?>"><span class="fa fa-twitter"></span></a>
                            <?php }?>
                        </div> 

                        </div>
                       
                    </div>
            
            <?php
    }

}
