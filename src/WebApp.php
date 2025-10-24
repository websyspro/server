<?php

namespace Websyspro\Server;

use Websyspro\Commons\DataList;
use Websyspro\DynamicSql\Core\DataByFn;
use Websyspro\Elements\Document;
use Websyspro\Elements\Dom;
use Websyspro\Server\Shareds\ItemController;
use Websyspro\Server\Shareds\StructureControllers;
use Websyspro\Server\Shareds\StructureModuleControllers;
use Websyspro\Server\Shareds\StructureRoute;
use Websyspro\Server\Shareds\StructureView;

class WebApp
{
  public Response $response;
  public readonly Request $request;

  public string|null $requestUri;
  public string|null $controller;
  public array|null $endpoint;

  public StructureControllers $structureControllersPublics;
  public StructureControllers $structureControllersPrivates;

  public function __construct(
    public StructureView $structureView,
    public DataList $publics,
    public DataList $privates
  ){
    $this->runServer();
  }

  public function runServer(
  ): void {
    $this->initialPutEnvs();
    $this->initialStructureProps();
    $this->initialStructureControllers();
    $this->initialServer();
  }

  public function initialPutEnvs(
  ): void {
    if(defined("rootdir")){
      $envfile = DataList::create(
        file(rootdir . DIRECTORY_SEPARATOR . ".env")
      );

      $envfile
        ->where(fn(string $line) => preg_match("#^(\#|;)#", $line) === 0)
        ->where(fn(string $line) => empty(trim($line)) === false)
        ->mapper(fn(string $line) => explode("=", $line))
        ->mapper(
          function(array $line){
            [$key, $val] = $line;

            putenv(sprintf(
              "%s=%s", trim($key), trim($val, " \t\n\r\0\x0B\"'")
            ));
          });
    }
  }
  
  private function initialStructureProps(
  ): void {
    $this->request = new Request();
  }

  private function initialStructureControllers(
  ): void {
    if($this->publics->exist()){
      $this->structureControllersPublics = new StructureControllers($this->publics);
    }

    if($this->privates->exist()){
      $this->structureControllersPrivates = new StructureControllers($this->privates);
    }
  }

  private function hasControllerInRequestURI(
  ): bool {
    return (
      is_null($this->request->controller) === false && 
      empty($this->request->controller) === false
    );  
  }

  private function hasEndpointInRequestURI(
  ): bool {
    return (
      is_null($this->request->endpoint) === false && 
      sizeof($this->request->endpoint) !== 0
    );
  }

  private function initialServer(
  ): void {
    if($this->hasControllerInRequestURI() === false){
      /** TODO while not controllers **/
    }

    /** Check is exists controllers publics **/
    if(isset($this->structureControllersPublics)){
      $this->structureControllersPublics->controllers->where(
        fn(ItemController $controller) => $controller->getName() === $this->request->controller
      );

      /** Load is exists endpoints **/
      $this->initialEndpointInServer($this->structureControllersPublics);
    }

    /** Check is exists controllers privates **/
    if(isset($this->structureControllersPrivates)){
      $this->structureControllersPrivates->controllers->where(
        fn(ItemController $controller) => $controller->getName() === $this->request->controller
      );
      
      /** Load is exists endpoints **/
      $this->initialEndpointInServer($this->structureControllersPrivates);
    }

  }

  public function initialEndpointInServer(
    StructureControllers $structureControllers
  ): void {
    if($this->hasEndpointInRequestURI() === false){
      /** TODO while not endpoint in controllers **/
    }

    /** Check is endpint exists in controller **/
    $structureControllers->controllers->where(
      fn(ItemController $controller) => $controller->routes->where(
        fn(StructureRoute $structureRoute) => $structureRoute->isEndpoint(
          DataList::create($this->request->endpoint), $this->request->method
        )
      )
    );

    if($structureControllers->controllers->exist()){
      if($structureControllers->controllers->first()->routes instanceof DataList){
        if($structureControllers->controllers->first()->routes->exist() === false){
          /** TODO Show route not found **/
          if(isset($this->structureView->page404)){
            $this->viewBase(
              viewBase: $this->structureView->page404,
              viewHtml: ""
            );
          }
        } else {
          $viewHtml = $structureControllers->controllers->first()->routes->first()->executeHtml(
            $this->request, $structureControllers->controllers->first()->middlewares
          );

          /** Render page home **/
          if(isset($this->structureView->home)){
            $this->viewBase(
              viewBase: $this->structureView->home,
              viewHtml: $viewHtml
            );
          }
        }
      }
    }
  }

  public function viewBase(
    string $viewBase,
    string $viewHtml
  ): void {
    Document::render([
      Dom::docType([
        "html"
      ]),
      Dom::html([ 
        "lang" => "pt"
      ], [
        Dom::head([], [
          Dom::title([], [
            "PixGO"
          ])
        ]),
        Dom::body([], [
          (new $viewBase())
            ->render($viewHtml)
        ])
      ])
    ]);
  }

  public static function view(
    string $login,
    string $home,
    string $page404,    
  ): StructureView {
    return new StructureView(
      login: $login,
      home: $home,
      page404: $page404
    );
  }

  public static function publics(
    array $publics    
  ): DataList {
    return DataList::create($publics);
  }  

  public static function privates(
    array $privates    
  ): DataList {
    return DataList::create($privates);
  }  
  
  public static function render(
    StructureView $structureView,
    DataList $publics,
    DataList $privates,
  ): WebApp {
    return new static(
      structureView: $structureView,
      publics: $publics,
      privates: $privates
    );
  }  
}