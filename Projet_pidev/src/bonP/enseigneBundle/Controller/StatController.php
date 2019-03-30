<?php
/**
 * Created by PhpStorm.
 * User: Fethi Ouerfelli
 * Date: 19/04/2018
 * Time: 11:55
 */

namespace bonP\enseigneBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class StatController extends Controller
{public function indexAction( )
{
    $pieArray=array( array("enseigne", "evenement"));

    $em=$this->getDoctrine()->getManager();
    $enseignes=$em->getRepository("bonPenseigneBundle:Enseigne")->findAll();

    foreach ( $enseignes as $e)
    {
        $en=$em->getRepository("bonPenseigneBundle:Evenement")->findBy(array("enseigne"=>$e->getId()));

        array_push($pieArray, array($e->getNom(),count($en) ));

    }

    var_dump($pieArray);
    $pieChart = new PieChart();
    $pieChart->getData()->setArrayToDataTable($pieArray);
    $pieChart->getOptions()->setTitle('nombre Evenement % enseignes');
    $pieChart->getOptions()->setHeight(500);
    $pieChart->getOptions()->setWidth(500);
    $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
    $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
// 2
    $pieArray1=array( array("enseigne", "menu"));

    $em=$this->getDoctrine()->getManager();
    $enseignes=$em->getRepository("bonPenseigneBundle:Enseigne")->findAll();

    foreach ( $enseignes as $e)
    {
        $en=$em->getRepository("bonPenseigneBundle:Menu")->findBy(array("enseigne"=>$e->getId()));

        array_push($pieArray1, array($e->getNom(),count($en) ));

    }

    $pieChart1 = new PieChart();
    $pieChart1->getData()->setArrayToDataTable($pieArray1);
    $pieChart1->getOptions()->setTitle('nombre Menu % enseignes');
    $pieChart1->getOptions()->setHeight(500);
    $pieChart1->getOptions()->setWidth(500);
    $pieChart1->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart1->getOptions()->getTitleTextStyle()->setColor('#009900');
    $pieChart1->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart1->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart1->getOptions()->getTitleTextStyle()->setFontSize(20);

    // 3
    $pieArray2=array( array("categorie", "enseigne"));

    $cats=$em->getRepository("planBundle:Categorie")->findAll();

    foreach ( $cats as $e)
    {
        $en=$em->getRepository("bonPenseigneBundle:Enseigne")->findBy(array("categorie"=>$e->getId()));

        array_push($pieArray2, array($e->getNom(),count($en) ));

    }

    $pieChart2 = new PieChart();
    $pieChart2->getData()->setArrayToDataTable($pieArray2);
    $pieChart2->getOptions()->setTitle('nombre Enseigne % Menu');
    $pieChart2->getOptions()->setHeight(500);
    $pieChart2->getOptions()->setWidth(500);
    $pieChart2->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart2->getOptions()->getTitleTextStyle()->setColor('#009900');
    $pieChart2->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart2->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart2->getOptions()->getTitleTextStyle()->setFontSize(20);




    return $this->render('@bonPenseigne/Default/statistique.html.twig', array('piechart' => $pieChart,'piechart1' => $pieChart1,'piechart2' => $pieChart2));


}

}