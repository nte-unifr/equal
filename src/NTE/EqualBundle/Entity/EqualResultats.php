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
 * EqualResultats
 *
 * @ORM\Table(name="equal_resultat")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\EqualResultatsRepository")
 */
class EqualResultats
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
     * @var integer
     *
     * @ORM\Column(name="id_etudiant", type="integer", nullable=false)
     */
    private $idEtudiant;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_approche", type="integer", nullable=false)
     */
    private $idApproche;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_dimension", type="integer", nullable=false)
     */
    private $idDimension;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_item", type="integer", nullable=false)
     */
    private $idItem;

    /**
     * @var integer
     *
     * @ORM\Column(name="resultat", type="integer", nullable=false)
     */
    private $resultat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;



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
     * Set idEtudiant
     *
     * @param integer $idEtudiant
     * @return EqualResultats
     */
    public function setIdEtudiant($idEtudiant)
    {
        $this->idEtudiant = $idEtudiant;

        return $this;
    }

    /**
     * Get idEtudiant
     *
     * @return integer 
     */
    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }

    /**
     * Set idApproche
     *
     * @param integer $idApproche
     * @return EqualResultats
     */
    public function setIdApproche($idApproche)
    {
        $this->idApproche = $idApproche;

        return $this;
    }

    /**
     * Get idApproche
     *
     * @return integer 
     */
    public function getIdApproche()
    {
        return $this->idApproche;
    }

    /**
     * Set idDimension
     *
     * @param integer $idDimension
     * @return EqualResultats
     */
    public function setIdDimension($idDimension)
    {
        $this->idDimension = $idDimension;

        return $this;
    }

    /**
     * Get idDimension
     *
     * @return integer 
     */
    public function getIdDimension()
    {
        return $this->idDimension;
    }

    /**
     * Set idItem
     *
     * @param integer $idItem
     * @return EqualResultats
     */
    public function setIdItem($idItem)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get idItem
     *
     * @return integer 
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set resultat
     *
     * @param integer $resultat
     * @return EqualResultats
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }

    /**
     * Get resultat
     *
     * @return integer 
     */
    public function getResultat()
    {
        return $this->resultat;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return EqualResultats
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
