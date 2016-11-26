<?php
use App\Lib\Auth,
App\Lib\Response,
App\Lib\GeneralFunction,
App\Validation\wifiValidation,
App\Middleware\AuthMiddleware,
App\Middleware\AuthCookieMiddleware,
App\Middleware\AuditMiddleware;



$app->group('/wifi/', function () {

  $this->get('list', function ($request, $response, $args) {
    $token = $request->getAttribute('token');
    $type_petition = $request->getAttribute('type_petition');
    $audit = $request->getAttribute('audit');
    $id= Auth::GetData($token)->id;
    $listWifi = $this->controller->wifi->getDataByIdUser($id,$audit);
    if($type_petition == "html"){
      return $this->view->render($response, 'modules/wifi/templates/listwifi.twig',[
        'wifis' => $listWifi
      ]);
    }else{
     return $response->withHeader('Content-type', 'application/json')
     ->write(
       json_encode($listWifi)
       );
   }

 })->add(new AuditMiddleware($this))->add(new AuthMiddleware($this));



  $this->post('register', function ($req, $res, $args) {
    $files = $req->getUploadedFiles();

    $expected_fields = array('name','process','mac');

    $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

    $r = wifiValidation::Validate($data);


    $data['audit'] = $req->getAttribute('audit');

    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
      ->withStatus(422)
      ->write(json_encode($r));
    }

    return $res->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->wifi->register($data,$files))
     ); 

   })->add(new AuditMiddleware($this))->add(new AuthMiddleware($this));


  $this->post('update', function ($req, $res, $args) {
    $files = $req->getUploadedFiles();

    $expected_fields = array('name','process','id');

    $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

    $r = wifiValidation::Validate($data,true);

    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
      ->withStatus(422)
      ->write(json_encode($r->errors));
    }

    return $res->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->wifi->update($data,$files))
     ); 

  });


  $this->put('crack', function ($req, $res, $args) {

    $expected_fields = array('id','password','error');

    $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

    $r = wifiValidation::ValidateCrack($data);

    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
      ->withStatus(422)
      ->write(json_encode($r->errors));
    }

    return $res->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->wifi->activeCrack($data,$files))
     ); 

  });



  $this->put('activeprocess', function ($req, $res, $args) {
   
    $token = $req->getAttribute('token');
    $id_user= Auth::GetData($token)->id;

    $expected_fields = array('id');

    $data = GeneralFunction::createNullData($req->getParsedBody(),$expected_fields);

    $r = wifiValidation::ValidateProcess($data);

    if(!$r->response){
      return $res->withHeader('Content-type', 'application/json')
      ->withStatus(422)
      ->write(json_encode($r));
    }

    return $res->withHeader('Content-type', 'application/json')
    ->write(
     json_encode($this->controller->wifi->activeProcess($data,$id_user))
     ); 

 })->add(new AuthMiddleware($this));



  $this->get('getelementprocess', function ($req, $res, $args) {

   return $res->withHeader('Content-type', 'application/json')
   ->write(
     json_encode($this->controller->wifi->getelementprocess())
     ); 

 });





});