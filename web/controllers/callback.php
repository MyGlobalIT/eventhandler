<?php
/*THIS FILE IS RESPONSIBLE FOR HANDLING CALLBACKS FROM SENDGRID AND TWILIO*/

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$callback = $app['controllers_factory'];

$callback->post('/', function (Request $request) use ($app) {

    $oid = $request->request->get('oid', false);
    $params = json_decode($request->getContent());
    //twilio not sending in JSON format
    if ($oid) {
        $temp = $request->getContent().'&oid='.$oid;
        parse_str($temp, $tempArr);
        $params[] = $tempArr;
    }
    $response = json_encode($params);
    $sampleResponse = $response.'--'.print_r($request->request->all(), true)
            .'--'.print_r($request->headers->all(), true).'---'.print_r($_SERVER, true);
    
    $sql = 'INSERT INTO `events` (`event_data`) VALUES( ? )';

    $app['db']->executeQuery($sql, array($sampleResponse));
    return $sampleResponse;
    //return $app->json($post, 201);
});

return $callback;