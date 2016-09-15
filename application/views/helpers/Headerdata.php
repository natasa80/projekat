<?php

class Zend_View_Helper_Headerdata extends Zend_View_Helper_Abstract {

    public function headerdata() {

        $cmsShopData = new Application_Model_DbTable_CmsShopData();


        $shopData = $cmsShopData->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsShopData::STATUS_ENABLED
            ),
            'limit' => 1
        ));
        $shopData = !empty($shopData) ? $shopData[0] : null;

        $cmsWorkingHours = new Application_Model_DbTable_CmsWorkingHours ();
        $workingHours = $cmsWorkingHours->search(array(
            'orders' => array(
                'order_number' => 'desc'
            )
        ));
        
    

    ?>
    
    
    
    
    
   
                    <!-- MODAL STOP -->
                    <div class="container clearfix headerMain">
                <section class="headerTop">
                    <!-- MODAL START -->
                    <button type="button" class="btn btn-info btn-lg modalButton" data-toggle="modal" data-target="#myModal"><span class="fa fa-arrow-circle-down"></span></button>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Hi,Im modal!</h4>
                                </div>
                                <div class="modal-body">
                                    <p>I can be very useful.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                        <span>Leave A Message: </span>
                    <a class="mail" href="mailto:<?php echo $this->view->escape($shopData['email']); ?>"><?php echo $this->view->escape($shopData['email']); ?></a>
                </section>
                <section class="headerSocial">
                    <a class="twitter" 
                       target="_blank"
                       href="<?php echo $this->view->escape($shopData['twitter']); ?>"><span class="fa fa-twitter social"></span></a>
                    <a class="facebook"
                       target="_blank"
                       href="<?php echo $this->view->escape($shopData['facebook']); ?>"><span class="fa fa-facebook social"></span></a>
                    <a class="googlePlus" 
                       target="_blank"
                       href="<?php echo $this->view->escape($shopData['google_plus']); ?>"><span class="fa fa-google-plus social"></span></a>
<!--                    <a class="dribbble" href=""><span class="fa fa-dribbble social"></span></a>-->
                    <a class="linkedIn"
                       target="_blank"
                       href="<?php echo $this->view->escape($shopData['linkedin']); ?>"><span class="fa fa-linkedin social"></span></a>
                </section>

            </div>
    
   
    <div class="headerMid">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <article>
                                <a href="<?php echo $this->view->url(array('controller' => 'index', 'action' => 'index'), 'default', true);?>"><img class="img-responsive Logo" src="<?php echo $this->view->logoImgUrl($shopData); ?>" alt="Logo"/></a>
                            </article>
                        </div>
                        <div class="col-md-6 location">
                            <div class="row">
                                <div class="col-md-4">
                                    <article>
                                        <div class="media">
                                            <a class="media-left" href="#">
                                                <span class="fa fa-map-marker"></span>
                                            </a>
                                            <div class="media-body">

                                                <p class="media-heading">Our Location</p>
                                                <p><?php echo $this->view->escape($shopData['address']." ". $shopData['city'] ); ?></p>
                                            </div>
                                        </div>
                                    </article>
                                </div>

                                <div class="col-md-4">
                                    <article>
                                        <div class="media">
                                            <a class="media-left" href="#">
                                                <span class="fa fa-hourglass-half"></span>
                                            </a>
                                            <div class="media-body">

                                                <p class="media-heading">Mon - Sat</p>
                                                <p>8 am - 10 pm</p>
                                            </div>
                                        </div>
                                    </article>
                                </div>

                                <div class="col-md-4">
                                    <article>
                                        <div class="media">
                                            <a class="media-left" href="#">
                                                <span class="fa fa-mobile"></span>
                                            </a>
                                            <div class="media-body">

                                                <p class="media-heading">Call Us</p>
                                                <p><?php echo $this->view->escape($shopData['phone']); ?></p>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
<!--                        <div class="col-md-2">
                            <article>
                                <a class="shoppingCart" href="#"><span class="fa fa-shopping-basket"></span><span class="badge">10</span></a>
                            </article>
                        </div>-->
                    </div>
                </div>
            </div>
    <?php
}
}
