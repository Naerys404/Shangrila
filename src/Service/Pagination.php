<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination {


    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    private $twig;
    private $route;

    private $templatePath;


    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath){

        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        //dump($this->route);die;
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
       
    }

    public function display(){
        //appelle le moteur twig et on précise quel template on veut utiliser
        $this->twig->display($this->templatePath, [
            //option nécessaires à l'affichage des données
            //variables : route / page / pages
            'page'=>$this->currentPage,
            'pages'=>$this->getPages(),
            'route'=>$this->route

        ]);
    }

   

    // 1- utiliser la pagination à partir de n'importe quelle entité => on devra préciser l'entité concernée
    public function setEntityClass($entityClass){

        //ma donnée entityClass = donnée qui va m'être envoyée (on crée un setter et un getter)
        $this->entityClass = $entityClass;
        return $this;

    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    // 2 - quelle est la limite ?

    public function getLimit(){
        return $this->limit;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    // 3 - sur quelle page je me trouve actuellement

    public function getPage(){
        return $this->currentPage;
    }

    public function setPage($page){
        $this->currentPage = $page;
        return $this;
    }

    
    //On récupère les données de chaque entité (toutes les données accessibles par les getters ) tout en contraignant l'affichage par page
    public function getData(){

        if(empty($this->entityClass)){
            throw new \Exception("setEntityClass n'a pas été renseigné dans le controller correspondant.");
        }

        //calcul l'offset ( à partir de quel élément on affiche les resultats)
        $offset =  $this->currentPage * $this->limit - $this->limit;

        //on demande au repository de trouver les éléments
        //on va chercher le bon repository
        $repo = $this->manager->getRepository($this->entityClass);

        //on construit notre requete

        $data = $repo->findBy([],[], $this->limit, $offset);

        return $data;
    }
    
    //4 - on cherche le nombre de pages au total
    public function getPages(){

        $repo = $this->manager->getRepository($this->entityClass);
        
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }
    

    public function getRoute(){
       return $this->route;
       
    }

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;
        return $this;
    }
    
}