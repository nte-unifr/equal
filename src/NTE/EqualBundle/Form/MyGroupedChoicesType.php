<?php

namespace NTE\EqualBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MyGroupedChoicesType extends AbstractType
{
	private $groupedChoices;

	public function __construct(array $groupedChoices)
	{
		$this->groupedChoices = $groupedChoices;
	}

	public function getDefaultOptions(array $options)
	{
		return array('choices' => $this->groupedChoices;
	}

	public function getParent(array $options)
	{
		return 'choice';
	}

	public function getName()
	{
		return 'my_grouped_choices';
	}
}