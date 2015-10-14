<?php

/**
 * Copyright 2014 Centre NTE <http://nte.unifr.ch>, Université de Fribourg, Suisse
 * 
 * This file is part of Equal+.
 *
 * Equal+ is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Equal+ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Equal+.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace NTE\EqualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SpecialPage
 *
 * @ORM\Table(name="equal_special_page")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\SpecialPageRepository")
 */
class SpecialPage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Pages", mappedBy="special")
     */
    private $page_type;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return SpecialPage
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function __toString()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->page_type = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add page_type
     *
     * @param \NTE\EqualBundle\Entity\Pages $pageType
     * @return SpecialPage
     */
    public function addPageType(\NTE\EqualBundle\Entity\Pages $pageType)
    {
        $this->page_type[] = $pageType;
    
        return $this;
    }

    /**
     * Remove page_type
     *
     * @param \NTE\EqualBundle\Entity\Pages $pageType
     */
    public function removePageType(\NTE\EqualBundle\Entity\Pages $pageType)
    {
        $this->page_type->removeElement($pageType);
    }

    /**
     * Get page_type
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPageType()
    {
        return $this->page_type;
    }
}
