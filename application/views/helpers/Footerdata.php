<?php


class Zend_View_Helper_Footerdata extends Zend_View_Helper_Abstract
{
    public function footerdata(){
        
        ?>
            <div class="media media1">
                            <div class="media-left blogMedia1">
                                <a href="#">
                                    <span class="media-object fa fa-map-o"></span>
                                </a>
                            </div>
                            <div class="media-body">
                                <p class="media-heading">Washington Square Park,</p>
                                NY, United States
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
                                + 124 45 76 678 
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
                                mail@woodsman.com
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <h4 class="text-uppercase footerh4padd">working time</h4>
                        <ul class="list-unstyled">
                            <li>MONDAY</li>
                            <li>TUESDAY</li>
                            <li>WEDNESDAY</li>
                            <li>THURSDAY</li>
                            <li>FRIDAY</li>
                            <li>WEEK END</li>
                            <li>EMERGENCY UNIT</li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">

                        <ul class="list-unstyled openTime">
                            <li>08:00 am  -  10:00 pm</li>
                            <li>8:00 am  -  10:00 pm</li>
                            <li>8:00 am  -  10:00 pm</li>
                            <li>8:00 am  -  10:00 pm</li>
                            <li>8:00 am  -  10:00 pm</li>
                            <li>CLOSED</li>
                            <li class="yellowOpen">OPEN ANY TIME</li>
                        </ul>
                    </div>
        <?php
       
    }
  
}