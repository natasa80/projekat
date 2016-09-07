<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initRouter() {
		//ensure that database is configured
		$this->bootstrap('db');
		
		$sitemapPageTypes = array(
			
			'StaticPage' => array(
				'title' => 'Static Page',
				'subtypes' => array(
					// 0 means unlimited number
					'StaticPage' => 0
				)
			),
			
			'PhotoGalleriesPage' => array(
				'title' => 'Photo Galleries Page',
				'subtypes' => array(
					
				)
			),
                    'ServicesPage' => array(
				'title' => 'Services Page',
				'subtypes' => array(
					
				)
			),
                    'AboutUsPage' => array(
				'title' => 'About Us Page',
				'subtypes' => array(
					
				)
			),
                    'ShopPage' => array(
				'title' => 'Shop Page',
				'subtypes' => array(
					
				)
			),
                    'ContactPage' => array(
				'title' => 'Contact Page',
				'subtypes' => array(
					
				)
			),
                    'BlogPage' => array(
				'title' => 'Blog Page',
				'subtypes' => array(
					
				)
			),
                    'PetPage' => array(
				'title' => 'Pet Page',
				'subtypes' => array(
					// 0 means unlimited number
					'StaticPage' => 0
				)
			),
		);
		
		$rootSitemapPageTypes = array(
			'StaticPage' => 0,
			'PhotoGalleriesPage' => 1,
                        'ShopPage' => 1,
                        'ServicesPage' => 1,
                        'AboutUsPage' => 1,
                        'ContactPage' => 1,
                        'BlogPage' => 1,
                        'PetPage' => 0,
                          
		);
		
		Zend_Registry::set('sitemapPageTypes', $sitemapPageTypes);
		Zend_Registry::set('rootSitemapPageTypes', $rootSitemapPageTypes);
		
		$router = Zend_Controller_Front::getInstance()->getRouter();
		
		$router instanceof Zend_Controller_Router_Rewrite;
                
               
        $sitmapPagesMap = Application_Model_DbTable_CmsSitemapPages::getSitemapPagesMap();
		
		foreach ($sitmapPagesMap as $sitemapPageId => $sitemapPageMap) {
			
			if ($sitemapPageMap['type'] == 'StaticPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'staticpage',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
                        //ABOUT US ROUTE
                        if ($sitemapPageMap['type'] == 'AboutUsPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'aboutus',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                                
                                 $router->addRoute('member-route', new Zend_Controller_Router_Route(
                                  $sitemapPageMap['url'] . '/member/:id/:member_slug', array(
                                 'controller' => 'aboutus',
                                 'action' => 'member',
                                 'member_slug' => ''
                                    )
                            ));
                        }
                        
                        //SERVICES ROUTE
                        if ($sitemapPageMap['type'] == 'ServicesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'services',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                                
                                $router->addRoute('service-route', new Zend_Controller_Router_Route(
                                $sitemapPageMap['url'] . '/:id/:service_slug', array(
                                 'controller' => 'services',
                                 'action' => 'service',
                                 'service_slug' => ''
                                    )
                                ));
                        }
                        
                        //SHOP ROUTE
                         if ($sitemapPageMap['type'] == 'ShopPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'shop',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                                
                                $router->addRoute('product-route', new Zend_Controller_Router_Route(
                                $sitemapPageMap['url'] . '/:id/:product_slug', array(
                                 'controller' => 'shop',
                                 'action' => 'product',
                                 'product_slug' => ''
                                    )
                                ));
			}
                        
                         //Contact ROUTE
                        if ($sitemapPageMap['type'] == 'ContactPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'contact',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
			
			
			if ($sitemapPageMap['type'] == 'PhotoGalleriesPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'photogalleries',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
				
				$router->addRoute('photo-gallery-route', new Zend_Controller_Router_Route(
					$sitemapPageMap['url'] . '/:id/:photo_gallery_slug',
					array(
						'controller' => 'photogalleries',
						'action' => 'gallery',
						'sitemap_page_id' => $sitemapPageId
					)
				));
			}
                        
                        //BLOG ROUTE
                         if ($sitemapPageMap['type'] == 'BlogPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'blog',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                                
                                $router->addRoute('blog-route', new Zend_Controller_Router_Route(
                                $sitemapPageMap['url'] . '/:id/:blog_slug', array(
                                 'controller' => 'blog',
                                 'action' => 'post',
                                 'blog_slug' => ''
                                    )
                                ));
			}
                        
                        //Categories page
                        if ($sitemapPageMap['type'] == 'PetPage') {
				
				$router->addRoute('static-page-route-' . $sitemapPageId, new Zend_Controller_Router_Route_Static(
					$sitemapPageMap['url'],
					array(
						'controller' => 'categories',
						'action' => 'index',
						'sitemap_page_id' => $sitemapPageId
					)
				));
                                
                                $router->addRoute('service-route', new Zend_Controller_Router_Route(
                                $sitemapPageMap['url'] . '/:id/:pet_slug', array(
                                 'controller' => 'pets',
                                 'action' => 'pet',
                                 'service_slug' => ''
                                    )
                                ));
                        }
                  
                }
	}
}

