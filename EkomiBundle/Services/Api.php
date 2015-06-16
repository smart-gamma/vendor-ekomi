<?php

namespace Gamma\Ekomi\EkomiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\CacheProvider;

use Localdev\FrameworkExtraBundle\Services\LoggerService;


/**
 * Interface to the ekomi api
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
class Api extends LoggerService
{
    const REVIEW_PROVIDER = 'Ekomi';
    
    /**
     * Host
     *
     * @var string
     */
    protected $host = null;
   
    /**
     * Ekomi Interface Id 
     *
     * @var string
     */
    protected $interface_id;

    /**
     * Ekomi Interface password 
     *
     * @var string
     */
    protected $interface_pw;

    /**
     * Type of result
     *
     * @var string
     */
    protected $type;
    
    /**
     * Max rank point
     *
     * @var int
     */
    protected $maxRank = 5;

    /**
     * Cache driver
     *
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    protected $cache;

    /**
     * Cache time out (default 12 hours)
     *
     * @var int
     */
    protected $cacheTimeOut = 43200;
    
    /**
     * {@inheritDoc}
     */
    public function __construct(ContainerInterface $container,CacheProvider $cache)
    {
        parent::__construct($container);
        
        $this->scheme = $container->getParameter('gamma.ekomi.config.scheme');
        $this->host = $container->getParameter('gamma.ekomi.config.host');
        $this->path = $container->getParameter('gamma.ekomi.config.path');
        $this->interface_id = $container->getParameter('gamma.ekomi.config.interface_id');
        $this->interface_pw = $container->getParameter('gamma.ekomi.config.interface_pw');
        $this->type = $container->getParameter('gamma.ekomi.config.type');
        $this->maxRank = $container->getParameter('gamma.ekomi.config.max_rank');
        $this->cacheTimeOut = $container->getParameter('gamma.ekomi.config.cache_timeout');
        
        $this->cache = $cache;
        $this->container = $container;
    }

    /**
     * Load aggregated reviews data
     *
     * @return array
     */
    public function reviewAggregation()
    {
        $data = array();
        $apiUrl = $this->scheme.'://'.$this->host.'/'.$this->path;
        $string = $this->call($apiUrl);
        /*
        if ($xml = simplexml_load_string($string)) {
            $xPath = "/shop/ratings/result[@name='average']";
            $data['averageRank'] = (float) $xml -> xpath($xPath)[0];
            $data['maxRank'] = $this->maxRank;
            $data['votes'] = $xml->ratings["amount"];
            $data['shopName'] = $xml->name;
            $data['ReviewProvider'] = self::REVIEW_PROVIDER;
        }
        */
        return $data;
    }

    /**
     * Load aggregated reviews data
     *
     * @return array
     */
    public function reviews()
    {
        $reviews = array();
        $apiUrl = $this->scheme.'://'.$this->host.'/'.$this->path;
        $lines = $this->call($apiUrl);
        print_r($lines);die;
        if (sizeof($lines) > 0) {               
            $i = 0;
            foreach($lines as $option) {
                $reviews[$i]['rating'] = (int)$option->rating[0];
                $reviews[$i]['comment'] = trim((string)$option->comment);
                $reviews[$i]['date'] = (string)$option->date;
                $reviews[$i]['provider'] = self::REVIEW_PROVIDER;
                $i++;
            }
        }

        return $reviews;
    }
    
    /**
     * Request to api
     * @param type $apiUrl
     * @return string
     */
    private function call($apiUrl)
    {
        $id = $this->getCachePath($apiUrl);

        if ($this->cache->contains($id)) {
            $output = $this->cache->fetch($id);  
        } else {    
            $output = file($apiUrl);           
            $this->cache->save($id, $output, $this->cacheTimeOut);
        } 
        
        return $output;
    }
    
    /**
     * Get cache storage id
     * @param type $apiUrl
     * @return string
     */
    private function getCachePath($apiUrl)
    {
        return self::REVIEW_PROVIDER.'_'.md5($apiUrl);
    }
}
