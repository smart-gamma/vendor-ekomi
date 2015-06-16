<?php

namespace Gamma\TrustedShop\TrustedShopBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Localdev\FrameworkExtraBundle\Services\LoggerService;

/**
 * Ekomi service
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
class EkomiManager extends LoggerService
{
    /**
     * Trusted Shop API
     *
     * @var Api
     */
    protected $api;
    
    /**
     * Initializes a new instance of the Images class.
     *
     * @param ContainerInterface $container Service container
     * @param Logger             $logger    Logger
     * @param Api                $api       Panthermedia API
     */
    public function __construct(ContainerInterface $container, Logger $logger = null, Api $api)
    {
        parent::__construct($container, $logger);
        $this->api = $api;
    }

    /**
     * Get trusted shop review aggregation
     */
    public function getReviewAggregation()
    {
        try {
            $review = $this->api->reviewAggregation();
            return $review;
        } catch (\Exception $ex) {
            $this->getLogger()->addError($ex);
        }
    }
    
    /**
     * Get trusted shop reviews 
     */
    public function getReviews()
    {
        try {
            $reviews = $this->api->reviews();
            return $reviews;
        } catch (\Exception $ex) {
            $this->getLogger()->addError($ex);
        }
    }    
}
