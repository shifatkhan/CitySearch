<?php
/**
 * This is a city object containing info about a city
 *
 * @author Shifat Khan
 */
class City {
    private $city;
    private $country;
    private $population;
    
    /**
     * Three param constructor.
     * 
     * @param String $city
     * @param String $country
     * @param Int $population
     */
    function __construct($city = '', $country = '', $population = '') {
        $this->city = $city;
        $this->country = $country;
        $this->population = $population;
    }
    
    /**
     * Returns the city name
     * @return String
     */
    function getCity(){
        return $this->city;
    }
    
    /**
     * Returns the Country name
     * @return Strin
     */
    function getCountry(){
        return $this->country;
    }
    
    /**
     * Returns the population number
     * @return Int
     */
    function getPopulation(){
        return $this->population;
    }
    
    /**
     * Set the city name
     * @param String $city
     */
    function setCity($city = ''){
        $this->city = $city;
    }
    
    /**
     * Set the Country name
     * @param String $country
     */
    function setCountry($country = ''){
        $this->country = $country;
    }
    
    /**
     * Set the population
     * @param String $population
     */
    function setPopulation($population = ''){
        $this->population = $population;
    }
    
}
