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

namespace NTE\EqualBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NTE\EqualBundle\Form\EqualDimensionsType;
use NTE\EqualBundle\Form\EqualApprochesType;
use NTE\EqualBundle\Entity\EqualApproches;
use NTE\EqualBundle\Entity\EqualDimensions;
use NTE\EqualBundle\Entity\EqualResultats;
use NTE\EqualBundle\Entity\EqualFeedbacks;
use NTE\EqualBundle\Entity\Pages;
use NTE\EqualBundle\Entity\Langue;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

$session = new Session();

class DefaultController extends Controller
{
	public function createIdAction($length) {
		$chars = "234567890";
		$i = 0;
		$testID = "";

		while ($i <= $length) {
			$testID .= mt_rand(0, strlen($chars));
			$i++;
		}

		return $testID;
	}


	public function indexAction()
	{
		return $this->render('NTEEqualBundle:Default:index.html.twig', array());
	}

	public function glossaireAction()
	{
		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		$glossaire = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualGlossaire')
					 ->getGlossaire($langue);

		$alpha = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualGlossaire')
					 ->getGlossaireAlpha($langue);

		return $this->render('NTEEqualBundle:Default:biblio-glossaire.html.twig', array(
			'entries' => $glossaire,
			'alpha' => $alpha
		));
	}

	public function biblioAction()
	{
		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		$biblio = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualBiblio')
					 ->getBiblio($langue);

		$alpha = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualBiblio')
					 ->getBiblioAlpha($langue);

		return $this->render('NTEEqualBundle:Default:biblio-glossaire.html.twig', array(
			'entries' => $biblio,
			'alpha' => $alpha
		));
	}

	public function descrDimAction($dim_id)
	{
		$entry = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualDimensions')
					 ->findOneById($dim_id);
		$approche = $entry->getApproche()->getNom();

		return $this->render('NTEEqualBundle:Default:description.html.twig', array(
			'entry' => $entry,
			'approche' => $approche
		));
	}

	public function descrItemAction($item_id)
	{
		$entry = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('NTEEqualBundle:EqualItems')
					 ->findOneById($item_id);
		$approche = $entry->getDimension()->getApproche()->getNom();

		return $this->render('NTEEqualBundle:Default:description.html.twig', array(
			'entry' => $entry,
			'approche' => $approche
		));
	}

	public function approcheAction($approche_id)
	{
		$session = $this->getRequest()->getSession();
		$request = $this->getRequest();

		// -- Récupération des dimensions en fonction de l'ID de l'approche
		$dim = $this->getDoctrine()
						->getManager()
						->getRepository('NTEEqualBundle:EqualDimensions')
						->findBy(array('approche' => $approche_id))
		;

		// -- Initialisation des sessions
		$session->set('id_approche', $approche_id);
		$session->set('action', 'eval');

		if ($session->has('dimension_number'))
		{
			$session->remove('dimension_number');
		}

		if (!$session->has('id_candidat') || $session->has('id_candidat'))
		{
			$candidat = self::createIdAction(8);
			$session->set('id_candidat', $candidat);
		}
		if ($session->has('nbItems'))
		{
			$session->remove('nbItems');
		}
		if ($session->has('response'))
		{
			$session->remove('response');
		}
		if ($session->has('arrayDimension'))
		{
			$session->remove('arrayDimension');
		}


		return $this->render('NTEEqualBundle:Default:approche.html.twig', array(
			'dim' => $dim
		));
	}

	public function dimensionAction()
	{
		$session = $this->getRequest()->getSession();
		$request = $this->getRequest();

		/* - Redirection si aucune dimension choisie (fait avec jQuery à la place -> voir approche template)
		if ($request->request->has('formDimSubmit') && !$request->request->has('equalapprochestype')) {
			$previousUrl = $request->headers->get('referer');

			return $this->redirect($previousUrl);
		}
		*/

		// -- Initialisation des sessions et des variables
		$action = $session->get('action');
		$id_approche = $session->get('id_approche');
		$id_candidat = $session->get('id_candidat');
		$shown = false;
		$begin = true;
		$dim_counter = 0;

		// Page "fictive" pour afficher breadcrumb lors de l'évaluation et résultat
		$page_eval = $this->getDoctrine()
			   ->getManager()
			   ->getRepository('NTEEqualBundle:Pages')
			   ->findOneBy(array('approche' => $id_approche))
		;

		if (!$request->request->has('begin'))
		{
			$begin = false;
		}

		if ($request->request->has('dim_counter'))
		{
			$dim_counter = $request->request->get('dim_counter');
		}


		$cA = $this->get('translator')->trans('enonceA');
		$cB = $this->get('translator')->trans('enonceB');
		$cC = $this->get('translator')->trans('enonceC');
		$cD = $this->get('translator')->trans('enonceD');
		$cE = $this->get('translator')->trans('enonceE');
		$choices = array(5 => $cA, 4 => $cB, 2 => $cC, 1 => $cD, 99 => $cE);
		$nbItems = 0;


		// On récupère les dimensions sélectionnées par l'utilisateur
		if ($begin)
		{
			$selectedDim = $request->request->get('equalapprochestype');
			$session->set('arrayDimension', $selectedDim['dimensions']);
			$begin = false;
		}

		$arrayDimension = $session->get('arrayDimension');
		$dimension_number = count($session->get('arrayDimension'));

		// On affiche les items correspondant à la dimension (itération sur l'array de session)
		if ($dim_counter < $dimension_number)
		{

			$item = $this->getDoctrine()
					   ->getManager()
					   ->getRepository('NTEEqualBundle:EqualDimensions')
					   ->getDimCheckbox($arrayDimension[$dim_counter])
			;

			$dimItem = $this->getDoctrine()
					   ->getManager()
					   ->getRepository('NTEEqualBundle:EqualDimensions')
					   ->findOneById($arrayDimension[$dim_counter])
			;

			$my_items = $this->getDoctrine()
					   ->getManager()
					   ->getRepository('NTEEqualBundle:EqualItems')
					   ->getItemsFromDim($arrayDimension[$dim_counter])
			;

			$nbItems = count($my_items);

			$form = $this->createForm(new EqualDimensionsType, $item);

			if ($request->getMethod() == 'POST') {
				$form->bind($request);
			}
		}

		if ($request->request->has('nbItems') && $request->request->has('step'))
		{
			$nbItems = $request->request->get('nbItems');
			$response = array();
			for($i=0; $i<$nbItems; $i++)
			{
				if ($request->request->has('response'. $i))
				{
					$response[$i] = $request->request->get('response'. $i);
				}
			}

			if (isset($response)){
				$session->set('response', $response);
			}
		}


		// -- FIN du questionnaire => enregistrement des données dans le bd
		if ($request->request->has('step'))
		{
			$nbItems = $request->request->get('nbItems');
			$id_dimension = $arrayDimension[$dim_counter-1];

			// on attrape les réponses
			for ($i=0; $i<$nbItems; $i++)
			{
				$tt1 = 'response'.$i;
				$tt2 = 'item'.$i;
				$response = $request->request->get($tt1);
				$id_item = $request->request->get($tt2);

				// Enregistrement des réponses
				$res = new EqualResultats();
				$res->setIdEtudiant($session->get('id_candidat'));
				$res->setIdApproche($session->get('id_approche'));
				$res->setIdDimension($id_dimension);
				$res->setIdItem($id_item);
				$res->setResultat($response);
				$res->setDate(new \DateTime());

				$em = $this->getDoctrine()->getManager();
				$em->persist($res);
				$em->flush();

			}
		}

		// -- FIN du questionnaire => affichage des réponses
		if ($dim_counter < $dimension_number)
		{
			return $this->render('NTEEqualBundle:Default:evaluation.html.twig', array(
				'form' => $form->createView(),
				'dimItem' => $dimItem,
				'items' => $my_items,
				'choices' => $choices,
				'dim' => $arrayDimension[$dim_counter],
				'dim_counter' => $dim_counter,
				'dimension_number' => $dimension_number,
				'nbItems' => count($my_items),
				'page_eval' => $page_eval
			));
		} else
		{
			$session->set('action', 'register');

			/*
			return $this->forward('NTEEqualBundle:Default:result', array(
				'id_candidat' => $session->get('id_candidat')
			));
			*/
			return $this->redirect($this->generateUrl('equal_result',
				array('approche_id' => $id_approche)
			));
		}
	}


	public function resultAction()
	{
		$session = $this->getRequest()->getSession();
		$request = $this->getRequest();

		// Page "fictive" pour afficher breadcrumb lors de l'évaluation et résultat
		$id_approche = $session->get('id_approche');
		$page_eval = $this->getDoctrine()
			   ->getManager()
			   ->getRepository('NTEEqualBundle:Pages')
			   ->findOneBy(array('approche' => $id_approche))
		;

		$id_candidat = $session->get('id_candidat');
		$points2 = 0;
		$counter2 = 1;
		$temp = true;
		$cat_temp = 0;
		$cA = $this->get('translator')->trans('enonceA');
		$cB = $this->get('translator')->trans('enonceB');
		$cC = $this->get('translator')->trans('enonceC');
		$cD = $this->get('translator')->trans('enonceD');
		$cE = $this->get('translator')->trans('enonceE');
		$choices = array(5 => $cA, 4 => $cB, 2 => $cC, 1 => $cD, 99 => $cE);

		$res = $this->getDoctrine()
				  ->getManager()
				  ->getRepository('NTEEqualBundle:EqualResultats')
				  ->findBy(array('idEtudiant' => $id_candidat))
		;

		// --- FEEDBACK
		foreach ($res as $r)
		{
			$check = 0;
			$nb_question = 0;
			$points = 0;
			$resultat_final = '';

			$arrayDimension = $session->get('arrayDimension');

			$res2 = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:EqualResultats')
					  ->findBy(array(
						'idEtudiant' => $id_candidat,
						'idDimension' => $r->getIdDimension()))
			;

			$dim = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:EqualDimensions')
					  ->find($r->getIdDimension())
			;

			foreach ($res2 as $r2)
			{
				if ($r2->getResultat() != 0 && $r2->getResultat() != 99)
				{
					$nb_question++;
					$points = $points + $r2->getResultat();
				}

				if ($r2->getResultat() == 99)
				{
					$check++;
				}
			}

			if ($r->getIdDimension() != $cat_temp)
			{
				if ($check >= 2) {
					if (isset($check2)) {
						$check2 .= "mais il y a au moins deux réponses <b>\"Ne s'applique pas\"</b> donc points = 99<br />";
					} else {
						$check2 = "mais il y a au moins deux réponses <b>\"Ne s'applique pas\"</b> donc points = 99<br />";
					}
				} else {
					$check2 = '';
				}

				$nb_question == 0 ? $moy = $points : $moy = $points/$nb_question;
				$resultat_final .= "<strong>". $dim->getNom() ."</strong><br />";
				$resultat_final .= "Score : ".$points." Point(s) " .$check2. "<br />";
				$resultat_final .= "Moyenne : ".$moy."<br />";

				$dimNom[$dim->getId()] = $dim->getNom();
				$score[] = $points;
				$checkPoints[] = $check2;
				$moyenne[] = $moy;
				$dimApproche = $dim->getApproche()->getNom();

				if ($check >= 2) {
					$points2 = 99;
				} else {
					$points2 = $points;
				}

				$feed = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:EqualFeedbacks')
					  ->getFeed($dim->getId(), $points2, $nb_question)
				;

				foreach ($feed as $f) {
					$resultat_final .= "<font color=\"navy\"><strong>Feedback </strong></font>:<br /> ". $f->getDescription() ."<br /><br /><br />";
					$feedDescr[] = $f->getDescription();
				}
			}

			$cat_temp = $r->getIdDimension();
			//echo $resultat_final;
		}

		// --- DETAILS des réponses
		foreach ($res as $r)
		{
			$item = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:EqualItems')
					  ->find($r->getIdItem())
			;

			$itemNom[$r->getIdDimension()][$item->getId()] = $item->getNom();
			$responseNom[$r->getIdDimension()][$item->getId()] = $choices[$r->getResultat()];
		}

		$session->remove('arrayDimension');
		//$session->remove('id_approche');
		$session->remove('action');
		$session->remove('dimension_number');
		$session->remove('id_candidat');
		$session->remove('nbItems');
		$session->remove('response');


		return $this->render('NTEEqualBundle:Default:result.html.twig', array(
			'dim' => $dimNom,
			'score' => $score,
			'check' => $checkPoints,
			'moyenne' => $moyenne,
			'feed' => $feedDescr,
			'item' => $itemNom,
			'response' => $responseNom,
			'dimApproche' => $dimApproche,
			'page_eval' => $page_eval
		));
	}


	public function generatePageAction($page_id, $slug)
	{
		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		// --- Generate the page
		if ($page_id != 0) {
			$currentPage = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('NTEEqualBundle:Pages')
						  ->find($page_id)
			;
		} elseif ($slug == '_') {
			//get the first element of menu
			$currentPage = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Pages')
					  ->findOneBy(array('parent' => NULL, 'langue' => $langue), array('position' => 'ASC'), $limit = 1)
			;
		} else {
			$currentPage = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Pages')
					  ->findOneBy(array('slug' => $slug))
			;
		}

		if (!$currentPage) {
			throw new HttpException(404, "Page not found");
		}

		return $this->render('NTEEqualBundle:Default:page.html.twig', array (
			'currentPage' => $currentPage
		));
	}

	public function generatePage2Action($app_id)
	{
		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		// --- Generate the page (from the ID approche)
		$currentPage = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Pages')
					  ->findOneBy(array('langue' => $langue, 'approche' => $app_id))
		;

		if (!$currentPage) {
			throw new HttpException(404, "Page not found");
		}

		return $this->render('NTEEqualBundle:Default:page.html.twig', array (
			'currentPage' => $currentPage
		));
	}

	public function generateMenuAction($top)
	{
		$mTop = $top;

		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		// --- Generate menu items
		$mItems = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Pages')
					  ->findBy(array('langue' => $langue), array('position' => 'ASC'))
		;

		return $this->render('NTEEqualBundle:Default:menu.html.twig', array (
			'mItems' => $mItems,
			'mTop' => $mTop
		));
	}


	public function generateMenuRespAction($top)
	{
		$mTop = $top;

		// -- Language choice
		$lang = $this->getRequest()->getLocale();
		$langue = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Langue')
					  ->findBy(array('locale' => strtolower($lang)))
		;
		// Default language (fr) if not found above
		if (!$langue) {
			$langue = 1;
		}

		// --- Generate menu items
		$mItems = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:Pages')
					  ->findBy(array('langue' => $langue), array('position' => 'ASC'))
		;

		return $this->render('NTEEqualBundle:Default:menuresp.html.twig', array (
			'mItems' => $mItems,
			'mTop' => $mTop
		));
	}


	public function allfeedsAction()
	{
		$feeds = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('NTEEqualBundle:EqualFeedbacks')
					  ->findAll()
		;

		return $this->render('NTEEqualBundle:Default:allfeeds.html.twig', array (
			'feeds' => $feeds
		));
	}

}
