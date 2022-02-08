<?php

namespace App\Http\Controllers;

use Response;

/**
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class BaseController extends Controller
{
    public function sendResponse($result, $message, $pagination = null)
    {
        return Response::json(self::makeResponse($message, $result, $pagination));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error
        ], $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public static function makeResponse($message, $data, $pagination = null)
    {
         $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];

        if (! empty($pagination)) {
            $pagesCount = ceil($pagination['total'] / (($pagination['size'] > 0) ? $pagination['size'] : $pagination['total']));
            $response['meta']['pagination'] = [
                'page' => (int)$pagination['page'],
                'size' => (int)$pagination['size'],
                'last_page' => (int)$pagesCount,
                'total' => (int)$pagination['total'],
            ];
        }
        return $response;
    }
}
