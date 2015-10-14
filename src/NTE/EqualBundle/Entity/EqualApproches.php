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
 * EqualApproches
 *
 * @ORM\Table(name="equal_approches")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\EqualApprochesRepository")
 */
class EqualApproches
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Langue", inversedBy="approches")
     * @ORM\JoinColumn(name="langue_id", referencedColumnName="id", nullable=false)
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity="EqualDimensions", mappedBy="approche")
     */
    private $dimensions;


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
     * @return EqualApproches
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
     * Set description
     *
     * @param string $description
     * @return EqualApproches
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dimensions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set langue
     *
     * @param \NTE\EqualBundle\Entity\Langue $langue
     * @return EqualApproches
     */
    public function setLangue(\NTE\EqualBundle\Entity\Langue $langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return \NTE\EqualBundle\Entity\Langue 
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Add dimensions
     *
     * @param \NTE\EqualBundle\Entity\EqualDimensions $dimensions
     * @return EqualApproches
     */
    public function addDimension(\NTE\EqualBundle\Entity\EqualDimensions $dimensions)
    {
        

        $dimensions->setApproche($this);
        $this->dimensions[] = $dimensions;

        return $this;
    }

    /**
     * Remove dimensions
     *
     * @param \NTE\EqualBundle\Entity\EqualDimensions $dimensions
     */
    public function removeDimension(\NTE\EqualBundle\Entity\EqualDimensions $dimensions)
    {
        $this->dimensions->removeElement($dimensions);
    }

    /**
     * Get dimensions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
