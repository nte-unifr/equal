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
 * EqualFeedbacks
 *
 * @ORM\Table(name="equal_feedbacks")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\EqualFeedbacksRepository")
 */
class EqualFeedbacks
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
     * @var integer
     * @ORM\ManyToOne(targetEntity="EqualDimensions", inversedBy="feedbacks", cascade={"persist"})
     * @ORM\JoinColumn(name="dimension_id", referencedColumnName="id", nullable=false)
     */
    private $dimension;
     
    /**
     * @var float
     *
     * @ORM\Column(name="liminf", type="float", precision=10, scale=0, nullable=false)
     */
    private $liminf;

    /**
     * @var float
     *
     * @ORM\Column(name="limsup", type="float", precision=10, scale=0, nullable=false)
     */
    private $limsup;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;



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
     * @return EqualFeedbacks
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
     * Set liminf
     *
     * @param float $liminf
     * @return EqualFeedbacks
     */
    public function setLiminf($liminf)
    {
        $this->liminf = $liminf;

        return $this;
    }

    /**
     * Get liminf
     *
     * @return float 
     */
    public function getLiminf()
    {
        return $this->liminf;
    }

    /**
     * Set limsup
     *
     * @param float $limsup
     * @return EqualFeedbacks
     */
    public function setLimsup($limsup)
    {
        $this->limsup = $limsup;

        return $this;
    }

    /**
     * Get limsup
     *
     * @return float 
     */
    public function getLimsup()
    {
        return $this->limsup;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return EqualFeedbacks
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
     * Set dimension
     *
     * @param \NTE\EqualBundle\Entity\EqualDimensions $dimension
     * @return EqualFeedbacks
     */
    public function setDimension(\NTE\EqualBundle\Entity\EqualDimensions $dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return \NTE\EqualBundle\Entity\EqualDimensions 
     */
    public function getDimension()
    {
        return $this->dimension;
    }
}
