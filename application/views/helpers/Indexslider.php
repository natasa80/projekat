<?php


class Zend_View_Helper_Indexslider extends Zend_View_Helper_Abstract
{
    public function indexslider(){
        
        ?>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                 
                
                <ol class="carousel-indicators">
                      
                    <?php 
                     $i = 0; 
                     foreach ($indexSlides as $index=>$slide) { 
                         ?>
                        <li data-target="/front/#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0 ) ? "active" : ""; ?>"></li>
                        <?php 
                        $i++;
                
                    } 
                    ?>
               
                </ol>
                 
                <!-- Wrapper for slides -->
              
                <div class="carousel-inner" role="listbox">
                     <?php 
                     $i = 0; 
                     foreach ($indexSlides as $index=>$slide) { 
                         ?>
                        <div class="item <?php echo ($i == 0 ) ? "active" : ""; ?>">
                            <img src="<?php echo $this->indexSlideImgUrl($slide); ?>" alt="<?php echo $this->escape($slide['title']); ?>"/>
                            <div class="carousel-caption">
                                <h3>DOG CAT PET CARE</h3>
                                <p class="normalText">We Take Good Care For</p>
                                <p class="normalText">Your Lovely Pet</p>
                                <p class="smallText">Those were the days. And we know Flipper lives in a world full of wonder </p>
                                <p class="smallText">flying there-under under the sea. The Love Boat soon will be making another</p>
                                <button class="btn btn-default purchaseLear">PURCHASE</button>
                                <button class="btn btn-default purchaseLear">LEARN MORE</button>
                            </div>
                        </div>
                        <?php 
                        $i++;
                
                    } 
                    ?>
                    
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="/front/#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="/front/#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
               
            </div>
        <?php
       
    }
  
}
?>


<div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="img/slide1.jpg" alt=""/>
                        <div class="carousel-caption">
                            <h3>DOG CAT PET CARE</h3>
                            <p class="normalText">We Take Good Care For</p>
                            <p class="normalText">Your Lovely Pet</p>
                            <p class="smallText">Those were the days. And we know Flipper lives in a world full of wonder </p>
                            <p class="smallText">flying there-under under the sea. The Love Boat soon will be making another</p>
                            <button class="btn btn-default purchaseLear">PURCHASE</button>
                            <button class="btn btn-default purchaseLear">LEARN MORE</button>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/slider-2.jpg" alt=""/>
                        <div class="carousel-caption">
                            <h3>DOG CAT PET CARE</h3>
                            <p class="normalText">We Take Good Care For</p>
                            <p class="normalText">Your Lovely Pet</p>
                            <p class="smallText">Those were the days. And we know Flipper lives in a world full of wonder </p>
                            <p class="smallText">flying there-under under the sea. The Love Boat soon will be making another</p>
                            <button class="btn btn-default purchaseLear">PURCHASE</button>
                            <button class="btn btn-default purchaseLear">LEARN MORE</button>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/slider-3.jpg" alt=""/>
                        <div class="carousel-caption">
                            <h3>DOG CAT PET CARE</h3>
                            <p class="normalText">We Take Good Care For</p>
                            <p class="normalText">Your Lovely Pet</p>
                            <p class="smallText">Those were the days. And we know Flipper lives in a world full of wonder </p>
                            <p class="smallText">flying there-under under the sea. The Love Boat soon will be making another</p>
                            <button class="btn btn-default purchaseLear">PURCHASE</button>
                            <button class="btn btn-default purchaseLear">LEARN MORE</button>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/slider-4.jpg" alt=""/>
                        <div class="carousel-caption">
                            <h3>DOG CAT PET CARE</h3>
                            <p class="normalText">We Take Good Care For</p>
                            <p class="normalText">Your Lovely Pet</p>
                            <p class="smallText">Those were the days. And we know Flipper lives in a world full of wonder </p>
                            <p class="smallText">flying there-under under the sea. The Love Boat soon will be making another</p>
                            <button class="btn btn-default purchaseLear">PURCHASE</button>
                            <button class="btn btn-default purchaseLear">LEARN MORE</button>
                        </div>
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <!-- CAROUSEL SLIDER STOP -->
