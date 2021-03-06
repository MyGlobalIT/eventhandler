<?php

/* THIS FILE IS RESPONSIBLE FOR HANDLING CALLBACKS FROM SENDGRID AND TWILIO */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$callback = $app['controllers_factory'];

$callback->post('/', function (Request $request) use ($app) {

            $sync = 0;
            $organizationId = $origin = $status = $token = '';
            $dated = new \DateTime();

            $oid = $request->query->get('oid', false);
            $params = json_decode($request->getContent());

            //twilio not sending in JSON format
            if ($oid) {
                $temp = $request->getContent() . '&oid=' . $oid;
                parse_str($temp, $tempArr);
                $params[] = $tempArr;
                $origin = 'twilio';
                $status = $tempArr['SmsStatus'];
                $token = $tempArr['SmsSid'];
                $organizationId = $oid;
            } else {
                $origin = 'sendgrid';
                $status = $params[0]->event;
                $token = $params[0]->token;
                $temp = explode('_', $token);
                $organizationId = $temp[0];
            }
            $response = json_encode($params);
            if (!is_null($params)) {
                //insert into table
                $sql = 'INSERT INTO `events` (`data`, `dated`, `status`, `sync`, `origin`, 
            `token`, `organization_id`)
            VALUES( ?, ?, ?, ?, ?, ?, ? )';
                $input = array($response, $dated->format('Y-m-d H:i:s'), $status, $sync,
                    $origin, $token, $organizationId);
                $app['db']->executeQuery($sql, $input);
            }

            return new Response($response);
        });

return $callback;