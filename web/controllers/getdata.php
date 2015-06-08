<?php

/* THIS FILE IS RESPONSIBLE FOR SENDING CALLBACK DATA TO MAIN APPLICATION */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$getdata = $app['controllers_factory'];

$getdata->post('/', function (Request $request) use ($app) {

            $output = $input = array();

            //build the query
            $sql = 'SELECT token, status FROM `events` WHERE 1=1 ';
            if ($organizationId = $request->request->get('organizationId', false)) {
                $sql .= ' AND organization_id = ?';
                $input[] = $organizationId;
            }
            if ($request->request->get('sync', false)) {
                $sql .= ' AND sync = 1';
            } else {
                $sql .= ' AND sync = 0';
            }
            if ($limit = $request->request->get('limit', false) && $offset = $request->request->get('offset', false)) {
                $sql .= ' LIMIT ?, ?';
                $input[] = $offset;
                $input[] = $limit;
            }
            $sql .= ' ORDER BY `dated` DESC';

            $result = $app['db']->executeQuery($sql, $input);
            //prepare output
            foreach ($result as $key => $record) {
                $output[$record['token']][] = $record['status'];
            }

            return new Response(json_encode($output));
        });

return $getdata;