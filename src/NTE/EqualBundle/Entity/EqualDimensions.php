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
 * EqualDimensions
 *
 * @ORM\Table(name="equal_dimensions")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\EqualDimensionsRepository")
 */
class EqualDimensions
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="EqualApproches", inversedBy="dimensions")
     * @ORM\JoinColumn(name="approche_id", referencedColumnName="id", nullable=false)
     */
    private $approche;

    /**
     * @ORM\OneToMany(targetEntity="EqualItems", mappedBy="dimension", cascade={"persist"})
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="EqualFeedbacks", mappedBy="dimension", cascade={"persist"})
     */
    private $feedbacks;



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
     * @return EqualDimensions
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
     * @return EqualDimensions
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
     * Set approche
     *
     * @param \NTE\EqualBundle\Entity\EqualApproches $approche
     * @return EqualDimensions
     */
    public function setApproche(\NTE\EqualBundle\Entity\EqualApproches $approche)
    {
        $this->approche = $approche;

        return $this;
    }

    /**
     * Get approche
     *
     * @return \NTE\EqualBundle\Entity\EqualApproches 
     */
    public function getApproche()
    {
        return $this->approche;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->feedbacks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add items
     *
     * @param \NTE\EqualBundle\Entity\EqualItems $items
     * @return EqualDimensions
     */
    public function addItem(\NTE\EqualBundle\Entity\EqualItems $items)
    {
        $items->setDimension($this);
        $this->items[] = $items;

        

        return $this;
    }

    /**
     * Remove items
     *
     * @param \NTE\EqualBundle\Entity\EqualItems $items
     */
    public function removeItem(\NTE\EqualBundle\Entity\EqualItems $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * Add feedbacks
     *
     * @param \NTE\EqualBundle\Entity\EqualFeedbacks $feedbacks
     * @return EqualDimensions
     */
    public function addFeedback(\NTE\EqualBundle\Entity\EqualFeedbacks $feedbacks)
    {
        $this->feedbacks[] = $feedbacks;

        return $this;
    }

    /**
     * Remove feedbacks
     *
     * @param \NTE\EqualBundle\Entity\EqualFeedbacks $feedbacks
     */
    public function removeFeedback(\NTE\EqualBundle\Entity\EqualFeedbacks $feedbacks)
    {
        $this->feedbacks->removeElement($feedbacks);
    }

    /**
     * Get feedbacks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }
}
