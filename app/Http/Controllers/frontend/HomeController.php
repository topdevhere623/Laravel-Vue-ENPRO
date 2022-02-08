<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы

// главный контроллер
class HomeController extends Controller
{
    // вывод главной страницы
    public function index(Request $request)
    {
        //открыть вьшку
        return view('frontend.home.index');
    }
}

