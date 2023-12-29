<?php
namespace App\Twig;


use Twig\TwigTest;
use Twig\TwigFilter;
use App\Entity\Livre;
use App\Entity\Abonne;
use App\Repository\AbonneRepository;
use Twig\TwigFunction;
use App\Repository\LivreRepository;
use Twig\Extension\AbstractExtension;
/**
 * Pour ajouter des fonctionalités a Twig il faut creer une classe qui herite de Twig\Extension\AbstractExtension
 * 
 */
class Extension extends AbstractExtension{

    private LivreRepository $livreRepository;
    private AbonneRepository $abonneRepository;


    public function __construct(LivreRepository $livreRepository, AbonneRepository $abonneRepository) {
        $this->livreRepository = $livreRepository;
        $this->abonneRepository = $abonneRepository;
    }

    public function disponible(Livre $livre) : bool{
        $livresDisponibles = $this->livreRepository->findLivresDisponibles();
        return in_array($livre,$livresDisponibles);
    }


    
    public function autorisation(Abonne $abonne){
        $abonneRole = $abonne->getRoles();
        $roleCorrespondance = array(
            "ROLE_ADMIN" => "Administrateur",
            "ROLE_BIBLIO" => "Bibliothécaire",
            "ROLE_LECTEUR" => "Lecteur",
            "ROLE_USER" => "Abonné",
        );
        $listeFinale="";
        foreach($abonneRole as $key => $val){
            $listeFinale .= ( !$listeFinale ? "" : ",") . $roleCorrespondance[$val];
        }


        return $listeFinale;
        // return $roleCorrespondance[$abonneRole];

            
    }


    public function exit(){
        exit;
    }

/**
 * Pour ajouter une fonction ou un filtre ou un test on va utiliser la methode getFunctions ( ou getFiltres ou GetTests)
 * Pour que twig aille le referencer. Cette methode va retourner un array d'objet de la classe 
 * TwigFunction ( ou TwigFilter ou TwigTest)
 * Les arguments des constructeurs de ces classes sont 
 * 1 Nom de la fonction a utiliser dans les fichiers twig
 * 2 le methode a utiliser (type callable)
 * [$this,nomDeLaMethodeDeLObjetThis]
 * 
 */
    public function getFunctions(){

        return [
            new TwigFunction("dispo", [$this,"disponible"]),
            new TwigFunction("die", [$this,"exit"])
        ];
    }



    public function getFilters(){
        return [
            new TwigFilter("dispo", [$this,"disponible"]),
            new TwigFilter("autorisations", [$this,"autorisation"]),
        ];
    }

    public function getTests(){
        return [
            new TwigTest("dispo", [$this,"disponible"])
        ];
    }





}


