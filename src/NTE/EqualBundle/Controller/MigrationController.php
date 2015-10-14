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
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use NTE\EqualBundle\Entity\EqualApproches;
use NTE\EqualBundle\Entity\EqualDimensions;
use NTE\EqualBundle\Entity\EqualResultats;
use NTE\EqualBundle\Entity\EqualFeedbacks;
use NTE\EqualBundle\Entity\Pages;
use NTE\EqualBundle\Entity\Langue;
use Symfony\Component\HttpFoundation\Response;


class MigrationController extends Controller
{
	public function indexAction()
	{
	echo "PROD TEST";
	return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}

	public function tempAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}

		$queries[] = "CREATE TABLE IF NOT EXISTS equal_approches_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";
		$queries[] = "CREATE TABLE IF NOT EXISTS equal_dimensions_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";
		$queries[] = "CREATE TABLE IF NOT EXISTS equal_items_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";
		$queries[] = "CREATE TABLE IF NOT EXISTS equal_feedbacks_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";
		$queries[] = "CREATE TABLE IF NOT EXISTS equal_biblio_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";
		$queries[] = "CREATE TABLE IF NOT EXISTS equal_glossaire_temp (new_id INT NOT NULL, PRIMARY KEY(new_id), old_id INT NOT NULL, langue_id INT NOT NULL)";

		foreach ($queries as $q) {
			mysqli_query($link, $q) or die("Error: " . mysqli_error($link));
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function deleteAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}

		// --- Vider les tables
		$queries = array();
		$queries[] = "TRUNCATE equal_pages";
		$queries[] = "DELETE FROM equal_feedbacks";
		$queries[] = "DELETE FROM equal_items";
		$queries[] = "DELETE FROM equal_dimensions";
		$queries[] = "DELETE FROM equal_approches";
		$queries[] = "DELETE FROM equal_biblio";
		$queries[] = "DELETE FROM equal_glossaire";

//		$queries[] = "ALTER TABLE equal_pages AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_feedbacks AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_items AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_dimensions AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_approches AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_biblio AUTO_INCREMENT=1";
		$queries[] = "ALTER TABLE equal_glossaire AUTO_INCREMENT=1";

		$queries[] = "DELETE FROM equal_feedbacks_temp";
		$queries[] = "DELETE FROM equal_items_temp";
		$queries[] = "DELETE FROM equal_dimensions_temp";
		$queries[] = "DELETE FROM equal_approches_temp";
		$queries[] = "DELETE FROM equal_biblio_temp";
		$queries[] = "DELETE FROM equal_glossaire_temp";


		foreach ($queries as $q) {
			mysqli_query($link, $q) or die("Error(".$q."): " . mysqli_error($link));
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function appAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}

		// --- Approche
		echo "<br /><br />Approches<br />";
		$lang = array(1 => 'fr', 2 => 'de');

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_approches_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));

			while($row = mysqli_fetch_array($result))
			{
				$nom = mysqli_real_escape_string($link, $row['nom']);
				$query = "INSERT INTO equal_approches (nom, langue_id) 
								VALUES ('$nom', $lkey)";
				//echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_approches_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				//echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function dimAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');

		// --- Dimensions
		echo "<br /><br />Dimension<br />";

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_dimensions_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));

			while($row = mysqli_fetch_array($result))
			{
				$query_app1 = "SELECT id_approche FROM equal_rel_app_dim WHERE id_dimension = " . $row['id'];
				$result_app1 = mysqli_query($link, $query_app1) or die("Error: " . mysqli_error($link));
				$app_old_id = mysqli_fetch_array($result_app1);

				$query_app = "SELECT new_id FROM equal_approches_temp WHERE langue_id = " . $lkey . " AND old_id = " . $app_old_id[0];
				$result_app = mysqli_query($link, $query_app) or die("Error: " . mysqli_error($link));
				$app_new_id = mysqli_fetch_array($result_app);

				$nom = mysqli_real_escape_string($link, $row['nom']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Traitement du premier \r\n, ils ne seront pas convertis deux fois.
				$descr = str_replace($order, $replace, $row['description']);
				$descr = mysqli_real_escape_string($link, $descr);
				$query = "INSERT INTO equal_dimensions (nom, approche_id, description) 
								VALUES ('$nom', $app_new_id[0], '$descr')";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_dimensions_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function itemAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');

		// Items
		echo "<br /><br />Items<br />";

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_items_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));

			while($row = mysqli_fetch_array($result))
			{
				$query_dim1 = "SELECT id_dimension FROM equal_rel_dim_item WHERE id_item = " . $row['id'];
				$result_dim1 = mysqli_query($link, $query_dim1) or die("Error: " . mysqli_error($link));
				$dim_old_id = mysqli_fetch_array($result_dim1);

				$query_dim = "SELECT new_id FROM equal_dimensions_temp WHERE langue_id = " . $lkey . " AND old_id = " . $dim_old_id[0];
				$result_dim = mysqli_query($link, $query_dim) or die("Error: " . mysqli_error($link));
				$dim_new_id = mysqli_fetch_array($result_dim);

				$nom = mysqli_real_escape_string($link, $row['nom']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Traitement du premier \r\n, ils ne seront pas convertis deux fois.
				$descr = str_replace($order, $replace, $row['description']);
				$descr = mysqli_real_escape_string($link, $descr);
				$query = "INSERT INTO equal_items (nom, description, dimension_id) 
								VALUES ('$nom', '$descr', $dim_new_id[0])";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_items_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}

	public function feedAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');

		// --- Feedbacks
		echo "<br /><br />Feedbacks<br />";

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_feedbacks_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));


			while($row = mysqli_fetch_array($result))
			{
				$query_dim = "SELECT new_id FROM equal_dimensions_temp WHERE langue_id = " . $lkey . " AND old_id = " . $row['id_dimension'];
				$result_dim = mysqli_query($link, $query_dim) or die("Error: " . mysqli_error($link));
				$dim_new_id = mysqli_fetch_array($result_dim);

				$nom = mysqli_real_escape_string($link, $row['nom']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Traitement du premier \r\n, ils ne seront pas convertis deux fois.
				$descr = str_replace($order, $replace, $row['description']);
				$descr = mysqli_real_escape_string($link, $descr);
				$query = "INSERT INTO equal_feedbacks (dimension_id, nom, liminf, limsup, description) 
								VALUES ($dim_new_id[0], '$nom', $row[liminf], $row[limsup], '$descr')";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_feedbacks_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function biblioAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');

		// --- Biblio
		echo "<br /><br />Biblio<br />";

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_biblio_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));

			while($row = mysqli_fetch_array($result))
			{
				$title = mysqli_real_escape_string($link, $row['title']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Traitement du premier \r\n, ils ne seront pas convertis deux fois.
				$descr = str_replace($order, $replace, $row['description']);
				$descr = mysqli_real_escape_string($link, $descr);
				$query = "INSERT INTO equal_biblio (title, description, langue_id) 
								VALUES ('$title', '$descr', $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_biblio_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function gloAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');

		// --- Glossaire
		echo "<br /><br />Glossaire<br />";

		foreach ($lang as $lkey => $lval) {
			$query = "SELECT * FROM equal_glossaire_" . $lval;
			$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));


			while($row = mysqli_fetch_array($result))
			{
				$title = mysqli_real_escape_string($link, $row['title']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Traitement du premier \r\n, ils ne seront pas convertis deux fois.
				$descr = str_replace($order, $replace, $row['description']);
				$descr = mysqli_real_escape_string($link, $descr);
				$query = "INSERT INTO equal_glossaire (title, description, langue_id) 
								VALUES ('$title', '$descr', $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
				// Table de correspondance old/new id
				$last_id = mysqli_insert_id($link);
				$query = "INSERT INTO equal_glossaire_temp (new_id, old_id, langue_id) 
								VALUES ($last_id, $row[id], $lkey)";
				echo $query."<br/>";
				mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());
	}


	public function glourlAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');
		$glo = array(1 => 'glossaire', 2 => 'glossar');

		$tables = array(1 => 'equal_glossaire', 2 => 'equal_pages', 3 => 'equal_dimensions', 4 => 'equal_items', 5 => 'equal_feedbacks');
		$cols = array(1 => 'description', 2 => 'text', 3 => 'description', 4 => 'description', 5 => 'description');

		$base_link = '/equal/'; //ou eval


		// --- Glossaire
		echo "<br /><br />Glossaire Ancre<br />";

			foreach ($lang as $lkey => $lval) {
				$queries[1] = "SELECT id, description FROM equal_glossaire WHERE langue_id=".$lkey;
				$queries[2] = "SELECT id, text FROM equal_pages WHERE langue_id=".$lkey;
				$queries[3] = "SELECT d.id, d.description 
								FROM equal_dimensions d 
								INNER JOIN equal_approches a ON d.approche_id = a.id 
								WHERE a.langue_id=".$lkey;
				$queries[4] = "SELECT i.id, i.description 
								FROM equal_items i 
								LEFT JOIN (equal_dimensions d 
									INNER JOIN equal_approches a ON d.approche_id = a.id) 
									ON i.dimension_id = d.id 
								WHERE a.langue_id=".$lkey;
				$queries[5] = "SELECT f.id, f.description 
								FROM equal_feedbacks f 
								LEFT JOIN (equal_dimensions d 
									INNER JOIN equal_approches a ON d.approche_id = a.id) 
									ON f.dimension_id = d.id 
								WHERE a.langue_id=".$lkey;

				foreach ($tables as $tkey => $table) {
					$query2 = $queries[$tkey];
					$result2 = mysqli_query($link, $query2) or die("Error: " . mysqli_error($link));
					echo $query2;

					while($row = mysqli_fetch_array($result2))
					{
					// ** Replace glossaire.php#3A5 -> (glossaire|glossar)#A5
						$descr = $row[$cols[$tkey]];
						$pattern = '%glossaire.php#3([A-Z])(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);
						echo '<br />';

						foreach ($matches as $match)
						{
							$query3 = "SELECT new_id FROM equal_glossaire_temp WHERE old_id = " . $match[2] . " AND langue_id = " . $lkey;
							$result3 = mysqli_query($link, $query3) or die("Error: " . mysqli_error($link));
							$glo_new_id = mysqli_fetch_array($result3);
							$replacement = $glo[$lkey].'#'. $match[1] . $glo_new_id[0];
							$descr = preg_replace('%'.$match[0].'%', $replacement, $descr);
						}
						unset($matches);
						unset($glo_new_id);

					// ** Replace description.php?(dimension|item)=1 -> description/(dimension|item)/1
						$pattern = '%description\.php\?(dimension|item)=(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);
						echo '<br />';

						foreach ($matches as $match)
						{
							$query3 = "SELECT new_id FROM equal_" . $match[1] . "s_temp WHERE old_id = " . $match[2] . " AND langue_id = " . $lkey;
							$result3 = mysqli_query($link, $query3) or die("Error: " . mysqli_error($link));
							$glo_new_id = mysqli_fetch_array($result3);
							$replacement = 'description/'. $match[1] . '/' . $glo_new_id[0];
							$descr = preg_replace('%description.php\?'.$match[1].'='.$match[2].'%', $replacement, $descr);
							//$descr = preg_replace('%'.$match[0].'%', $replacement, $descr);
						}
						unset($matches);
						unset($glo_new_id);


					// ** Replace http://www.unifr.ch/didactic/eval/(glossaire|glossar)#A5  or only (glossaire|glossar)#A5
					// ** by /eval/(fr|de)/(glossaire|glossar)#A5
						if ($tkey != 1) {
                                                	$pattern = '%(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?(glossaire|glossar)#([A-Z])(\d+)%';
	                                                preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
	                                                print_r($matches);
	                                                echo '<br />';

	                                                foreach ($matches as $match)
	                                                {
	                                                        $replacement = $base_link.$lval.'/'.$match[3].'#'.$match[4].$match[5];
	                                                        $descr = preg_replace('%href=.{1,2}(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?'.$match[3].'#'.$match[4].$match[5].'%', 'href="'.$replacement, $descr);
	                                                }
	                                                unset($matches);
                                                }
/*
A ENLEVER !!!
						$pattern = '%(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?('.$base_link.$lval.')?\/(glossaire|glossar)#([A-Z])(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);
						echo '<br />';

						foreach ($matches as $match)
						{
							$replacement = $base_link.$lval.'/'.$match[4].'#'.$match[5].$match[6];
							$descr = preg_replace('%href=.{1,2}(http:\/\/)?(www\.unifr\.ch\/didactic\/eval)?('.$base_link.$lval.')?\/'.$match[4].'#'.$match[5].$match[6].'%', 'href="'.$replacement, $descr);
						}
						unset($matches);
						//print_r($matches4);
*/

						echo $descr;
						echo '<br /> ---- <br />';

						$query = "UPDATE ".$table." SET ".$cols[$tkey]." = '" . mysqli_real_escape_string($link, $descr) ."' WHERE id = $row[id]";
						echo $query."<br/>";


					// ** Replace http://www.unifr.ch/didactic/eval/description/(dimension|item)/5  or only description/(dimension|item)/5
					// ** by /eval/(fr|de)/description/(dimension|item)/5

						$pattern = '%(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?description\/(dimension|item)\/(\d+)%';
                                                preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
                                                print_r($matches);
                                                echo '<br />';

                                                foreach ($matches as $match)
                                                {
                                                        $replacement = $base_link.$lval.'/description/'. $match[3] .'/'.$match[4];
                                                        $descr = preg_replace('%href=.{1,2}(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?description\/'.$match[3].'\/'.$match[4].'%', 'href="'.$replacement, $descr);
                                                }
                                                unset($matches);
/*
A ENLEVER !!!
						$pattern = '%(http:\/\/)?(www\.unifr\.ch\/didactic\/eval)?('.$base_link.$lval.')?\/description\/(dimension|item)\/(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);
						echo '<br />';

						foreach ($matches as $match)
						{
							$replacement = $base_link.$lval.'/description/'. $match[4] .'/'.$match[5];
							$descr = preg_replace('%href=.{1,2}(http:\/\/)?(www\.unifr\.ch\/didactic\/eval)?('.$base_link.$lval.')?\/description\/'.$match[4].'\/'.$match[5].'%', 'href="'.$replacement, $descr);
						}
						unset($matches);
						//print_r($matches4);
*/

						echo $descr;
						echo '<br /> ---- <br />';

						$query = "UPDATE ".$table." SET ".$cols[$tkey]." = '" . mysqli_real_escape_string($link, $descr) ."' 
									WHERE id = $row[id]";
						echo $query."<br/>";
						//
						mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
					}
				}
			}
		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());

	}


	public function bibliourlAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}

		$lang = array(1 => 'fr', 2 => 'de');
		$biblio = array(1 => 'bibliographie', 2 => 'literatur');

		$tables = array(1 => 'equal_glossaire', 2 => 'equal_pages', 3 => 'equal_dimensions', 4 => 'equal_items', 5 => 'equal_feedbacks');
		$cols = array(1 => 'description', 2 => 'text', 3 => 'description', 4 => 'description', 5 => 'description');

		$base_link = '/equal/'; //ou eval

		// --- Bibliographie
		echo "<br /><br />Bibliographie Ancre<br />";

			foreach ($lang as $lkey => $lval) {
				$queries[1] = "SELECT id, description FROM equal_glossaire WHERE langue_id=".$lkey;
				$queries[2] = "SELECT id, text FROM equal_pages WHERE langue_id=".$lkey;
				$queries[3] = "SELECT d.id, d.description 
								FROM equal_dimensions d 
								INNER JOIN equal_approches a ON d.approche_id = a.id 
								WHERE a.langue_id=".$lkey;
				$queries[4] = "SELECT i.id, i.description 
								FROM equal_items i 
								LEFT JOIN (equal_dimensions d 
									INNER JOIN equal_approches a ON d.approche_id = a.id) 
									ON i.dimension_id = d.id 
								WHERE a.langue_id=".$lkey;
				$queries[5] = "SELECT f.id, f.description 
								FROM equal_feedbacks f 
								LEFT JOIN (equal_dimensions d 
									INNER JOIN equal_approches a ON d.approche_id = a.id) 
									ON f.dimension_id = d.id 
								WHERE a.langue_id=".$lkey;

				foreach ($tables as $tkey => $table) {
					$query2 = $queries[$tkey];
					$result2 = mysqli_query($link, $query2) or die("Error: " . mysqli_error($link));
					echo $query2;

					while($row = mysqli_fetch_array($result2))
					{
					// ** Replace biblio.php#2A5 -> (bibliographie|literatur)#A5
						$descr = $row[$cols[$tkey]];
						$pattern = '%biblio.php#2([A-Z])(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);
						echo '<br />';

						foreach ($matches as $match)
						{
							$query3 = "SELECT new_id FROM equal_biblio_temp WHERE old_id = " . $match[2] . " AND langue_id = " . $lkey;
							$result3 = mysqli_query($link, $query3) or die("Error: " . mysqli_error($link));
							$glo_new_id = mysqli_fetch_array($result3);
							$replacement = $biblio[$lkey].'#'. $match[1] . $glo_new_id[0];
							$descr = preg_replace('%'.$match[0].'%', $replacement, $descr);
						}
						unset($matches);
						unset($glo_new_id);


					// ** Replace http://www.unifr.ch/didactic/eval/(bibliographie|literatur)#A5  or only (bibliographie|literatur)#A5
					// ** by /eval/(fr|de)/(bibliographie|literatur)#A5
						$pattern = '%(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?(bibliographie|literatur)#([A-Z])(\d+)%';
						preg_match_all($pattern, $descr, $matches, PREG_SET_ORDER);
						print_r($matches);

						foreach ($matches as $match)
						{
							$replacement = $base_link.$lval.'/'.$match[3] .'#'.$match[4].$match[5];
							//$descr = preg_replace('%\"'.$match[0].'%', '"'.$replacement, $descr);
							$descr = preg_replace('%href=.{1,2}(http:\/\/)?(www\.unifr\.ch\/didactic\/eval\/)?'.$match[3].'#'.$match[4].$match[5].'%', 'href="'.$replacement, $descr);
						}
						unset($matches);

						$query = "UPDATE ".$table." SET ".$cols[$tkey]." = '" . mysqli_real_escape_string($link, $descr) ."' WHERE id = $row[id]";
						echo $query."<br/>";
						//
						mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
					}
				}
			}
		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());

	}


	public function pagesAction()
	{
		$link = mysqli_connect($this->container->getParameter('database_host'), 
			$this->container->getParameter('database_user'), 
			$this->container->getParameter('database_password'), 
			$this->container->getParameter('database_name'));
		/* Vérification de la connexion */
		if (mysqli_connect_errno()) {
		    printf("Échec de la connexion : %s\n", mysqli_connect_errno());
		    exit();
		}
		$lang = array(1 => 'fr', 2 => 'de');
		
		$pages = array(
			'index.php' => 1, 
			'index1.php' => 1, 
			'index2.php' => 2, 
			'index3.php' => 3, 
			'eval.php' => 2, 
			'evala.php' => 1, 
			'evalb.php' => 2, 
			'eval2.php' => 3, 
			'evalc.php' => 1, 
			'glossaire.php' => 4, 
			'biblio.php' => 5, 
			'impressum.php' => 6, 
			'contact.php' => 7);

		// --- Pages
		echo "<br /><br />Pages content<br />";
		$num = count($pages);
		$em = $this->getDoctrine()->getManager();

		

		foreach ($lang as $lkey => $lval) {
			$langue = $em->getRepository('NTEEqualBundle:Langue')
					  ->findOneBy(array('locale' => $lval))
			;
			include(__DIR__.'/variables_'.$lval.'_utf8.php');
			include(__DIR__.'/variables_'.$lval.'_org_utf8.php');
			for ($i=0; $i<$num; $i++) {
				// pages à créer à partir des fichiers php
		
				$positions = array(
					1, 
					1, 
					2, 
					3, 
					2, 
					1, 
					2, 
					3, 
					1, 
					4, 
					5, 
					6, 
					7);
				$tops = array(
					TRUE, 
					FALSE, 
					FALSE, 
					FALSE, 
					TRUE, 
					FALSE, 
					FALSE, 
					TRUE, 
					FALSE, 
					TRUE, 
					TRUE, 
					FALSE, 
					FALSE);
				$titles = array(
					$menu_home,
					$index_title, 
					$index_title, 
					$index_title, 
					$eval_title, 
					$eval_approcheA, 
					$eval_approcheB, 
					$eval_title2, 
					$eval_approcheC, 
					$menu_glossaire, 
					$menu_biblio, 
					$impressum_title, 
					$contact_title);
				$titles_left = array(
					$menu_home, 
					$menu_homeA, 
					$menu_homeB, 
					$menu_homeC, 
					$menu_evaluation, 
					$eval_approcheA, 
					$eval_approcheB, 
					$menu_evaluation2, 
					$eval_approcheC, 
					NULL, 
					NULL, 
					NULL, 
					NULL);
				$titles_top = array(
					$menu_home, 
					NULL, 
					NULL, 
					NULL, 
					$menu2_evaluation, 
					NULL, 
					NULL, 
					$menu2_evaluation2, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL);
				$subtitles = array(
					NULL, 
					$index_subtitle, 
					$index_subtitle, 
					$index_subtitle, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL, 
					NULL);
				$contents = array(
					'<h1><a href="/accueil" /><font color="#006699" >Pour un enseignement<br /><br /> supérieur sensible au genre</font></a></h1>
		      <br /><br />
		      <h1><a href="/home" /><font color="#006699" >Geschlechtergerechte<br /><br /> Hochschullehre</font></h1></a>', 
					'<p><strong>' . $index_par_title1 .'</strong><br />'. $index_par_title1_text .'</p>', 
					'<p><strong>' . $index_par_title2 .'</strong><br />'. $index_par_title2_text .'</p>',
					'<p><strong>' . $index_par_title3 .'</strong><br />'. $index_par_title3_text .'</p>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>N/A</P>', 
					'<p>' . $impressum_content . '</p><p>' . $impressum_content2a . ' Anne-Françoise Gilbert
													<br />'. $impressum_content2b . ' Nathalie Pasche
													<br />'. $impressum_content2c . ' François Jimenez
													<br />'. $impressum_content2d . ' Bernadette Charlier, Helene Füger</p>', 
					$contact_content);

				$page = new Pages();
				$page->setTitle($titles[$i]);
				$page->setSubtitle($subtitles[$i]);
				$page->setIntroduction(NULL);
				$page->setText($contents[$i]);
				$page->setLangue($langue);
				$page->setPosition($positions[$i]);
				$page->setTop($tops[$i]);
				$page->setApproche(NULL);
				$page->setTitleMenuLeft($titles_left[$i]);
				$page->setTitleMenuTop($titles_top[$i]);
				$page->setSpecial(NULL);

				$em->persist($page);
				$em->flush();
			}
		}

		return $this->render('NTEEqualBundle:Migration:index.html.twig', array());

	}

}
