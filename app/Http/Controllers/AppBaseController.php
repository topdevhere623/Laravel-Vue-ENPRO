<?php

namespace App\Http\Controllers;

use Response;

/**
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $meta = null)
    {
        return Response::json(self::makeResponse($message, $result, $meta));
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

    public static function makeResponse($message, $data, $meta = null)
    {
         $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
            'last_page' =>  0
        ];

        if ((! empty($meta)) && array_key_exists('pagination', $meta)) {
            $meta['pagination']['current_page'] = (int)$meta['pagination']['page'];
            $meta['pagination']['first_page_url'] = request()->url() . '?page=1';
            if (($meta['pagination']['total'] > $meta['pagination']['size'] * ($meta['pagination']['page']-1)) && ($meta['pagination']['total'] <= $meta['pagination']['size'] * $meta['pagination']['page']))
            {
                $from = ($meta['pagination']['size'] * ($meta['pagination']['page']-1))+1;
            } else {
                if ($meta['pagination']['total'] > $meta['pagination']['size'] * $meta['pagination']['page']) {
                    $from = $meta['pagination']['size'] * ($meta['pagination']['page']-1) +1;
                } else {
                    $from = 0;
                }
            }
            $meta['pagination']['from'] = $from;
            $meta['pagination']['last_page'] = (int)ceil($meta['pagination']['total'] / (($meta['pagination']['size'] > 0) ? $meta['pagination']['size'] : $meta['pagination']['total']));
            $meta['pagination']['last_page_url'] = ($meta['pagination']['last_page'] > 0) ? request()->url() . '?page=' . $meta['pagination']['last_page'] : null;
            $meta['pagination']['next_page_url'] = (($meta['pagination']['page'] + 1) > $meta['pagination']['last_page']) ? null : request()->url() . '?page=' . ($meta['pagination']['page'] + 1);
            $meta['pagination']['path'] = request()->url();
            $meta['pagination']['per_page'] = (int)$meta['pagination']['size'];
            $meta['pagination']['prev_page_url'] = (($meta['pagination']['page'] - 1) > 0) ? request()->url() . '?page=' . ($meta['pagination']['page'] - 1) : null;
            if (($meta['pagination']['total'] > $meta['pagination']['size'] * ($meta['pagination']['page']-1)) && ($meta['pagination']['total'] <= $meta['pagination']['size'] * $meta['pagination']['page']))
            {
                $to = $meta['pagination']['total'];
            } else {
                if ($meta['pagination']['total'] > $meta['pagination']['size'] * $meta['pagination']['page']) {
                    $to = $meta['pagination']['size'] * $meta['pagination']['page'];
                } else {
                    $to = 0;
                }
            }
            $meta['pagination']['to'] = $to;
            unset($meta['pagination']['size']);
            unset($meta['pagination']['page']);
        }

        if (! empty($meta)) {
            $response['meta'] = $meta;
        }
        return $response;
    }

    public function getDefaultValuesIds ($model, &$values)
    {
        $defaultValues = self::DEFAUL_VALUES;
        foreach ($defaultValues as $relation => &$defaultValue) {
            $relatedModel = $model->$relation->getRelated()->where('value', '=', $defaultValue)->findOrFail();
        }
    }
}
