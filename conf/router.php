<?php

# ---------------------------------
# File : router.php
# Creator : Jeremy Lardet
# Date : 18/08/2015
# ---------------------------------

require_once "route.php";

class Router {
    private static $instance;

    private $routes;

    # ---------------
    # function Router
    # Behaviour : Default constructor, initiate routes
    # Input : none
    # Output: none
    # ---------------
    public function Router() {
        $this->routes = array(

            // CategorieCategorie management (hierarchy)
            new Route("categorieCategoriesController", "create", "#^\/categories\/(?P<mother_id>\d*)\/sous-categories\/ajouter$#",
                "GET", array("mother_id")),
            new Route("categorieCategoriesController", "create", "#^\/categories\/(?P<mother_id>\d*)\/sous-categories\/ajouter$#",
                "POST", array("mother_id")),
            new Route("categorieCategoriesController", "destroy",
                "#^\/categories\/(?P<mother_id>\d*)\/sous-categories\/(?P<daughter_id>\d*)\/supprimer#", "GET", array("mother_id", "daughter_id")),

            // FicheCategorie management from categories scope views
            new Route("ficheCategoriesController", "create", "#^\/(?P<callback>categories)\/(?P<categorie_id>\d*)\/fiches\/ajouter$#",
                "GET", array("callback", "categorie_id")),
            new Route("ficheCategoriesController", "create", "#^\/(?P<callback>categories)\/(?P<categorie_id>\d*)\/fiches\/ajouter$#",
                "POST", array("callback", "categorie_id")),
            new Route("ficheCategoriesController", "destroy",
                "#^\/(?P<callback>categories)\/(?P<categorie_id>\d*)\/fiches\/(?P<fiche_id>\d*)\/supprimer$#", "GET",
                array("fiche_id", "categorie_id", "callback")),

            // FicheCategorie management from fiches scope views
            new Route("ficheCategoriesController", "create", "#^\/(?P<callback>fiches)\/(?P<fiche_id>\d*)\/categories\/ajouter$#",
                "GET", array("callback", "fiche_id")),
            new Route("ficheCategoriesController", "create", "#^\/(?P<callback>fiches)\/(?P<fiche_id>\d*)\/categories\/ajouter$#",
                "POST", array("callback", "fiche_id")),
            new Route("ficheCategoriesController", "destroy",
                "#^\/(?P<callback>fiches)\/(?P<fiche_id>\d*)\/categories\/(?P<categorie_id>\d*)\/supprimer$#", "GET",
                array("fiche_id", "categorie_id", "callback")),

            // Categories management
            new Route("categoriesController", 	"index", 	"#^\/categories$#", "GET"),
            new Route("categoriesController", 	"show", 	"#^\/categories\/(?P<categorie_id>\d)$#", "GET", array("categorie_id")),
            new Route("categoriesController", 	"create", 	"#^\/categories\/nouveau$#", "GET"),
            new Route("categoriesController", 	"create", 	"#^\/categories\/nouveau$#", "POST"),
            new Route("categoriesController", 	"update", 	"#^\/categories\/(?P<categorie_id>\d*)\/modifier$#", "GET",
                array("categorie_id")),
            new Route("categoriesController", 	"update", 	"#^\/categories\/(?P<categorie_id>\d*)\/modifier$#", "POST",
                array("categorie_id")),
            new Route("categoriesController", 	"destroy", 	"#^\/categories\/(?P<categorie_id>\d*)\/supprimer$#","GET",
                array("categorie_id")),

            // Fiches Management
            new Route("fichesController", 	"index", 	"#^\/fiches$#", "GET"),
            //new Route("fichesController", 	"show", 	"#^\/fiches\/(?P<fiche_id>\d)$#", "GET"   , array("fiche_id")),
            new Route("fichesController", 	"create", 	"#^\/fiches\/nouveau$#", "GET"),
            new Route("fichesController", 	"create", 	"#^\/fiches\/nouveau$#", "POST"),
            new Route("fichesController", 	"update", 	"#^\/fiches\/(?P<fiche_id>\d*)\/modifier$#", "GET"   , array("fiche_id")),
            new Route("fichesController", 	"update", 	"#^\/fiches\/(?P<fiche_id>\d*)\/modifier$#", "POST"  , array("fiche_id")),
            new Route("fichesController", 	"destroy", 	"#^\/fiches\/(?P<fiche_id>\d*)\/supprimer$#","GET"   , array("fiche_id")),

            new Route("defaultController", 	"index", "#^\/$#", "GET"),
        );
    }

    # ------------------------
    # function getRoute
    # Behaviour : Find the right route
    # Input : string formated as an url
    # Output: Route object or 404
    # ------------------------
    public function getRoute($url, $method) {
        $cpt = 0;
        $match = false;

        while($cpt < count($this->routes) && !$match) {
            $route = $this->routes[$cpt];
            $match = $route->matchPattern($url, $method);
            $cpt++;
        }

        if(!$match){
            http_response_code(404);
            include_once("../web/404.html");
            exit;
        }

        return $route;
    }

    # ---------------
    # function static getRouter
    # Behaviour : Get singleton instance, or instanciate it
    # Input : none
    # Output: Router instance
    # ---------------
    public static function getRouter(){
        if(is_null(self::$instance))
            self::$instance = new Router();
        return self::$instance;
    }
}

