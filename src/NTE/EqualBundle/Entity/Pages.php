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
 * Pages
 *
 * @ORM\Table(name="equal_pages")
 * @ORM\Entity(repositoryClass="NTE\EqualBundle\Entity\PagesRepository")
 */
class Pages
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="title_menu_left", type="string", length=255, nullable=true)
     */
    private $title_menu_left;

    /**
     * @var string
     *
     * @ORM\Column(name="title_menu_top", type="string", length=255, nullable=true)
     */
    private $title_menu_top;

    /**
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction", type="text", nullable=true)
     */
    private $introduction;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="Pages", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pages", mappedBy="parent")
     */
    private $children;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint")
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="Langue", inversedBy="pages")
     * @ORM\JoinColumn(name="langue_id", referencedColumnName="id", nullable=false)
     */
    private $langue;

    /**
     * @var boolean
     *
     * @ORM\Column(name="top", type="boolean")
     */
    private $top;

    /**
     * @ORM\OneToOne(targetEntity="EqualApproches")
     * @ORM\JoinColumn(name="approche_id", referencedColumnName="id", nullable=true)
     */
    private $approche;

    /**
     * @ORM\ManyToOne(targetEntity="SpecialPage", inversedBy="page_type")
     * @ORM\JoinColumn(name="special_id", referencedColumnName="id", nullable=true)
     */
    private $special;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_title", type="boolean", options={"default" = 1})
     */
    private $showTitle = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_breadcrumb", type="boolean", options={"default" = 1})
     */
    private $showBreadcrumb = true;


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
     * Set title
     *
     * @param string $title
     * @return Pages
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Pages
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     * @return Pages
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string 
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Pages
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set langue
     *
     * @param \NTE\EqualBundle\Entity\Langue $langue
     * @return Pages
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
    

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Pages
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set parent
     *
     * @param \NTE\EqualBundle\Entity\Pages $parent
     * @return Pages
     */
    public function setParent(\NTE\EqualBundle\Entity\Pages $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \NTE\EqualBundle\Entity\Pages 
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add children
     *
     * @param \NTE\EqualBundle\Entity\Pages $children
     * @return Pages
     */
    public function addChildren(\NTE\EqualBundle\Entity\Pages $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \NTE\EqualBundle\Entity\Pages $children
     */
    public function removeChildren(\NTE\EqualBundle\Entity\Pages $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set top
     *
     * @param boolean $top
     * @return Pages
     */
    public function setTop($top)
    {
        $this->top = $top;
    
        return $this;
    }

    /**
     * Get top
     *
     * @return boolean 
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set approche
     *
     * @param \NTE\EqualBundle\Entity\EqualApproches $approche
     * @return Pages
     */
    public function setApproche(\NTE\EqualBundle\Entity\EqualApproches $approche = null)
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
     * Set slug
     *
     * @param string $slug
     * @return Pages
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title_menu_left
     *
     * @param string $titleMenuLeft
     * @return Pages
     */
    public function setTitleMenuLeft($titleMenuLeft)
    {
        $this->title_menu_left = $titleMenuLeft;
    
        return $this;
    }

    /**
     * Get title_menu_left
     *
     * @return string 
     */
    public function getTitleMenuLeft()
    {
        return $this->title_menu_left;
    }

    /**
     * Set title_menu_top
     *
     * @param string $titleMenuTop
     * @return Pages
     */
    public function setTitleMenuTop($titleMenuTop)
    {
        $this->title_menu_top = $titleMenuTop;
    
        return $this;
    }

    /**
     * Get title_menu_top
     *
     * @return string 
     */
    public function getTitleMenuTop()
    {
        return $this->title_menu_top;
    }

    /**
     * Set special
     *
     * @param \NTE\EqualBundle\Entity\SpecialPage $special
     * @return Pages
     */
    public function setSpecial($special)
    {
        $this->special = $special;
    
        return $this;
    }

    /**
     * Get special
     *
     * @return \NTE\EqualBundle\Entity\SpecialPage 
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * Set showTitle
     *
     * @param boolean $showTitle
     * @return Pages
     */
    public function setShowTitle($showTitle)
    {
        $this->showTitle = $showTitle;
    
        return $this;
    }

    /**
     * Get showTitle
     *
     * @return boolean 
     */
    public function getShowTitle()
    {
        return $this->showTitle;
    }

    /**
     * Set showBreadcrumb
     *
     * @param boolean $showBreadcrumb
     * @return Pages
     */
    public function setShowBreadcrumb($showBreadcrumb)
    {
        $this->showBreadcrumb = $showBreadcrumb;
    
        return $this;
    }

    /**
     * Get showBreadcrumb
     *
     * @return boolean 
     */
    public function getShowBreadcrumb()
    {
        return $this->showBreadcrumb;
    }
}