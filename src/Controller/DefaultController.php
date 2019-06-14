<?php
/**
 * Created by PhpStorm.
 * User: br4in
 * Date: 2019-05-22
 * Time: 20:03
 */

namespace App\Controller;
use App\Entity\Comment;
use App\Entity\History;
use App\Entity\Item;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Entity\Prediction;
use App\Entity\Reservation;
use App\Entity\Revenue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Menu;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use DateTime;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

class DefaultController extends AbstractController
{
    public function index(){
        if($this->isGranted("ROLE_ADMIN")){
            $em = $this->getDoctrine()->getManager();
            $comments = $em->getRepository(Comment::class)->findBy(array("canteen" => "Lehel"));
            return $this->render("lehel_dashboard.html.twig",['items' => $comments]);
        }
        if($this->isGranted("ROLE_USER"))
            return $this->redirectToRoute("todays_menu");
        return $this->render('404.html.twig');
    }

    public function lehelDashboard(){
        if(!$this->isGranted("ROLE_ADMIN"))
            return $this->render("404.html.twig");
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->findBy(array("canteen" => "Lehel"));
        return $this->render("lehel_dashboard.html.twig",['items' => $comments]);
    }

    public function getLehelDNOG($type){
        $em = $this->getDoctrine()->getManager();
        if($type == 1){
            $data = $em->getRepository(History::class)->findBy(array("canteen" => "Lehel"));
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item->getDate()->format('Y-m-d');
                $numbers[] = $item->getNumber();
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
        else if ($type == 2){
            $data = $em->getRepository(History::class)->getMonthlyDNOG("Lehel");
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item["gBmonth"]."/".$item['gBday'];
                $numbers[] = $item[1];
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
        else{
            $data = $em->getRepository(History::class)->getYearlyDNOG("Lehel");
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item["gBday"];
                $numbers[] = $item[1];
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
    }

    public function getGiesingDNOG($type){
        $em = $this->getDoctrine()->getManager();
        if($type == 1){
            $data = $em->getRepository(History::class)->findBy(array("canteen" => "Giesing"));
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item->getDate()->format('Y-m-d');
                $numbers[] = $item->getNumber();
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
        else if ($type == 2){
            $data = $em->getRepository(History::class)->getMonthlyDNOG("Giesing");
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item["gBmonth"]."/".$item['gBday'];
                $numbers[] = $item[1];
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
        else{
            $data = $em->getRepository(History::class)->getYearlyDNOG("Giesing");
            $labels = [];
            $numbers = [];
            foreach ($data as $item){
                $labels[] = $item["gBday"];
                $numbers[] = $item[1];
            }
            $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
            return new JsonResponse($to_return);
        }
    }

    public function getGuestsByMonthLehel(){
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(History::class)->getGuestsByMonth("Lehel");
        $labels = [];
        $numbers = [];
        foreach ($data as $item){
            $labels[] = date('F', mktime(0, 0, 0, $item["gBday"], 10));
            $numbers[] = $item[1];
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function getGuestsByDayOfWeekLehel(){
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(History::class)->getGuestsByDayOfWeek("Lehel");
        $labels = [];
        $numbers = [];
        foreach ($data as $item){
            $labels[] = date('l', mktime(0, 0, 0, 10, $item["gBday"]+1));
            $numbers[] = $item[1];
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function getGuestsByDayOfWeekGiesing(){
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(History::class)->getGuestsByDayOfWeek("Giesing");
        $labels = [];
        $numbers = [];
        foreach ($data as $item){
            $labels[] = date('l', mktime(0, 0, 0, 10, $item["gBday"]+1));
            $numbers[] = $item[1];
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function getGuestsByMonthGiesing(){
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository(History::class)->getGuestsByMonth("Giesing");
        $labels = [];
        $numbers = [];
        foreach ($data as $item){
            $labels[] = date('F', mktime(0, 0, 0, $item["gBday"], 10));
            $numbers[] = $item[1];
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function giesingDashboard(){
        if(!$this->isGranted("ROLE_ADMIN"))
            return $this->render("404.html.twig");
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->findBy(array("canteen" => "Giesing"));
        return $this->render("giesing_dashboard.html.twig",['items' => $comments]);
    }

    public function itemsView(){
        if(!$this->isGranted("ROLE_ADMIN"))
            return $this->render("404.html.twig");
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository(Item::class)->findAll();
        $in_menu1 = [];
        $in_menu2 = [];
        foreach ($items as $item){
            $Menu1 = $em->getRepository(Menu::class)->findOneBy(array('date' => new \DateTime(),'item' => $item,'canteen' => "Lehel"));
            $Menu2 = $em->getRepository(Menu::class)->findOneBy(array('date' => new \DateTime(),'item' => $item,'canteen' => "Giesing"));
            if($Menu1 != null)
                $in_menu1[] = true;
            else
                $in_menu1[] = false;

            if($Menu2 != null)
                $in_menu2[] = true;
            else
                $in_menu2[] = false;
        }
        return $this->render('view_items.html.twig',['items' => $items,'in_menu1' => $in_menu1,"in_menu2" => $in_menu2]);
    }

    public function getLehelPredictions(Request $request){
        $em = $this->getDoctrine()->getManager();
        $preds = $em->getRepository(Prediction::class)->findBy(array("canteen" => "Lehel"));
        $labels = [];
        $numbers = [];
        foreach ($preds as $item){
            $labels[] = $item->getDate()->format('Y-m-d');
            $numbers[] = $item->getNumber();
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function getGiesingPredictions(Request $request){
        $em = $this->getDoctrine()->getManager();
        $preds = $em->getRepository(Prediction::class)->findBy(array("canteen" => "Giesing"));
        $labels = [];
        $numbers = [];
        foreach ($preds as $item){
            $labels[] = $item->getDate()->format('Y-m-d');
            $numbers[] = $item->getNumber();
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => $labels, 'numbers' => $numbers), 'json');
        return new JsonResponse($to_return);
    }

    public function getLehelPredictionsPage(Request $request){
        if(!$this->isGranted("ROLE_ADMIN"))
            return $this->render("404.html.twig");
        return $this->render("lehel_predictions.html.twig");
    }

    public function getGiesingPredictionsPage(Request $request){
        if(!$this->isGranted("ROLE_ADMIN"))
            return $this->render("404.html.twig");
        return $this->render("giesing_predictions.html.twig");
    }

    public function TodaysMenu(Request $request){
        if($this->getUser() === null){
            dump('1');
            return $this->render('404.html.twig');
        }
        if($this->isGranted("ROLE_ADMIN")){
            dump('2');
            return $this->render("404.html.twig");
        }
        $em = $this->getDoctrine()->getManager();
        $Menu = $em->getRepository(Menu::class)->findBy(array("date" => new DateTime("now"),"canteen" => $this->getUser()->getCanteen()));
        $reservation = $em->getRepository(Reservation::class)->findBy(array("date" => new DateTime("now"),"user" => $this->getUser()));
        return $this->render("menu.html.twig",['menu' => $Menu,'appoin' => ($reservation != null)]);
    }

    public function reservation($type){
        if($type != 'cancel' && $type != 'apply')
            return $this->redirectToRoute("index");
        $em = $this->getDoctrine()->getManager();
        if($type == "cancel"){
            $app = $em->getRepository(Reservation::class)->findOneBy(array("user" => $this->getUser(),"date" => new DateTime("now")));
            if($app != null){
                $em->remove($app);
                $em->flush();
            }
        }
        else{
            $app = new Reservation();
            $app->setCanteen($this->getUser()->getCanteen());
            $app->setUser($this->getUser());
            $app->setDate(new DateTime("now"));
            $em->persist($app);
            $em->flush();
        }
        return $this->redirectToRoute("index");
    }

    public function getConfirmedVisitors($canteen){
        $em = $this->getDoctrine()->getManager();
        return new Response(sizeof($em->getRepository(Reservation::class)->findBy(array(
            'canteen' => $canteen,
                'date' => new DateTime("now")
            )))."");
    }

    public function getTotalServed($canteen){
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(History::class)->findBy(array(
            'canteen' => $canteen
        ));
        $sum = 0;
        foreach($res as $item)
            $sum += $item->getNumber();
        return new Response($sum."");
    }

    public function getTotalRevenue($canteen){
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(Revenue::class)->findBy(array(
            'canteen' => $canteen
        ));
        $sum = 0;
        foreach($res as $item)
            $sum += $item->getNumber();
        return new Response($sum."");
    }

    public function Predict(Request $request){
        $process = new Process(['python','lehel_predictor.py']);
        $process->run();

        $process = new Process(['python','giesing_predictor.py']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return new Response("");
    }

    public function PostComment(Request $request){
        if(!($this->isGranted("ROLE_USER") and !$this->isGranted("ROLE_ADMIN")))
            return $this->render("404.html.twig");
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setCanteen($this->getUser()->getCanteen());
        $comment->setDate(new DateTime("now"));
        $comment->setContent($request->get("content"));
        $process = new Process(['python','polarity.py','"'.$request->get("content").'"']);
        $process->run();
        $comment->setPolarity(str_replace(' ', '', $process->getOutput()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        return $this->redirectToRoute("todays_menu");
    }

    public function getGiesingPolarity(Request $request){
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->findBy(array("canteen" => "Giesing"));
        $p = $n = $ne = 0;
        foreach($comments as $c){
            if($c->getPolarity() == "Positive")
                $p++;
            else if($c->getPolarity() == "Negative")
                $n++;
            else
                $ne++;
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => ["Negative","Positive","Neutral"], 'numbers' => [$n,$p,$ne]), 'json');
        return new JsonResponse($to_return);
    }

    public function getLehelPolarity(Request $request){
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository(Comment::class)->findBy(array("canteen" => "Lehel"));
        $p = $n = $ne = 0;
        foreach($comments as $c){
            if($c->getPolarity() == "Positive")
                $p++;
            else if($c->getPolarity() == "Negative")
                $n++;
            else
                $ne++;
        }
        $to_return = $this->get('serializer')->serialize(array('labels' => ["Negative","Positive","Neutral"], 'numbers' => [$n,$p,$ne]), 'json');
        return new JsonResponse($to_return);
    }
}