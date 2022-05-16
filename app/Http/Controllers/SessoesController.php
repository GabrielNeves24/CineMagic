<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessoesController extends Controller
{

        public function index()
     {
        $todasSessoes = DB::table('sessoes')
         ->where('data', '<', '2020-01-03');//getdate())

         //dd($todosFilmes);
         //$todosFilmes = Filme::all();
         return view('sessoes.index')->with('sessoes', $todasSessoes);
     }
}
