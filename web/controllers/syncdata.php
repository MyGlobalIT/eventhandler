<?php

/* THIS FILE IS RESPONSIBLE FOR SYNC DATA IN MINI APPLICATION */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$syncdata = $app['controllers_factory'];

$syncdata->post('/', function (Request $request) use ($app) {

            $dated = new \DateTime();
            $affectedRows = 0;

            //build the query
            $sql = 'UPDATE `events` SET `sync` = 1, `sync_date` = "' . $dated->format('Y-m-d H:i:s') . '"';
            if ($tokens = $request->request->get('tokens', false)) {
                $sql .= ' WHERE `token` IN (?)';
                $affectedRows = $app['db']->executeUpdate($sql, array($tokens), array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY));
            }
            return new Response(json_encode(array('rows_updated' => $affectedRows)));
        });

return $syncdata;