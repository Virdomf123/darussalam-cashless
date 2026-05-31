<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada data 'locale' tersimpan di session, jika ada gunakan itu. 
        // Jika tidak ada, default-nya adalah bahasa Indonesia ('id')
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        } else {
            App::setLocale('id');
        }

        return $next($request);
    }
}