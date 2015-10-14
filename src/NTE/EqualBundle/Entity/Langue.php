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

/**
 * Langue
 *
 * @ORM\Table(name="equal_langue")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\LangueRepository")
 */
class Langue
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
     * @ORM\Column(name="locale", type="string", length=3)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="EqualApproches", mappedBy="langue")
     */
    private $approches;

    /**
     * @ORM\OneToMany(targetEntity="Pages", mappedBy="langue")
     */
    private $pages;

    /**
     * @ORM\OneToMany(targetEntity="EqualGlossaire", mappedBy="langue")
     */
    private $glossaire;

    /**
     * @ORM\OneToMany(targetEntity="EqualBiblio", mappedBy="langue")
     */
    private $biblio;


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
     * Set locale
     *
     * @param string $locale
     * @return Langue
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Langue
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
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->approches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->locale;
    }

    /**
     * Add pages
     *
     * @param \NTE\EqualBundle\Entity\Pages $pages
     * @return Langue
     */
    public function addPage(\NTE\EqualBundle\Entity\Pages $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \NTE\EqualBundle\Entity\Pages $pages
     */
    public function removePage(\NTE\EqualBundle\Entity\Pages $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }


    /**
     * Add approches
     *
     * @param \NTE\EqualBundle\Entity\EqualApproches $approches
     * @return Langue
     */
    public function addApproche(\NTE\EqualBundle\Entity\EqualApproches $approches)
    {
        $this->approches[] = $approches;
    
        return $this;
    }

    /**
     * Remove approches
     *
     * @param \NTE\EqualBundle\Entity\EqualApproches $approches
     */
    public function removeApproche(\NTE\EqualBundle\Entity\EqualApproches $approches)
    {
        $this->approches->removeElement($approches);
    }

    /**
     * Get approches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApproches()
    {
        return $this->approches;
    }

    /**
     * Add glossaire
     *
     * @param \NTE\EqualBundle\Entity\EqualGlossaire $glossaire
     * @return Langue
     */
    public function addGlossaire(\NTE\EqualBundle\Entity\EqualGlossaire $glossaire)
    {
        $this->glossaire[] = $glossaire;
    
        return $this;
    }

    /**
     * Remove glossaire
     *
     * @param \NTE\EqualBundle\Entity\EqualGlossaire $glossaire
     */
    public function removeGlossaire(\NTE\EqualBundle\Entity\EqualGlossaire $glossaire)
    {
        $this->glossaire->removeElement($glossaire);
    }

    /**
     * Get glossaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGlossaire()
    {
        return $this->glossaire;
    }

    /**
     * Add biblio
     *
     * @param \NTE\EqualBundle\Entity\EqualBiblio $biblio
     * @return Langue
     */
    public function addBiblio(\NTE\EqualBundle\Entity\EqualBiblio $biblio)
    {
        $this->biblio[] = $biblio;
    
        return $this;
    }

    /**
     * Remove biblio
     *
     * @param \NTE\EqualBundle\Entity\EqualBiblio $biblio
     */
    public function removeBiblio(\NTE\EqualBundle\Entity\EqualBiblio $biblio)
    {
        $this->biblio->removeElement($biblio);
    }

    /**
     * Get biblio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBiblio()
    {
        return $this->biblio;
    }
}
