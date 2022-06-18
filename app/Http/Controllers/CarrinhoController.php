<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use App\Models\Sessao;
use App\Models\Lugares;
use App\Models\Recibo;
use App\Models\User;
use App\Models\Bilhetes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Services\Payment;
use App\Notifications\ReciboPago;
use App\Mail\EnvioRecibo;
use PDF;

class CarrinhoController extends Controller
{
    public function index(Request $request)
    {
        $cliente = auth()->user();
        //dd($cliente);
        return view('carrinho.index')
            ->with('cliente', $cliente)
            ->with('carrinho', session('carrinho') ?? []);
    }

    public function store_sessao(Request $request, Sessao $sessao)
    {
        
        $idlugar = $request->idlugar;

        $idCarrinho = $request->session()->increment('count');
        $countInt = $request->session()->increment('countInt');
        //$totalCarrinho = $request->session()->increment('totalCarrinho',5);
        $carrinho = $request->session()->get('carrinho', []);

        $qtd = $idCarrinho;
        $fila = ($carrinho[$idCarrinho]['fila']?? 0);
        $lugar = ($carrinho[$idCarrinho]['lugar']?? 0);
        $precoBilhete = Configuracao::find(1);
        $ivaBilhete = $precoBilhete->percentagem_iva;
        $fila=Lugares::where('id',$idlugar)->pluck('fila');
        $posicao=Lugares::where('id',$idlugar)->pluck('posicao');
        $precoSemIva= $precoBilhete->preco_bilhete_sem_iva;
        $bilheteComIva=$precoSemIva*(($ivaBilhete/100)+1);
        $total = $request->session()->increment('total', $bilheteComIva);

        //dd($total);
        
        $carrinho[$idCarrinho] = [
            'id' => $sessao->id,
            'filme' => $sessao->Filmes->titulo,
            'qtd' => $qtd,
            'data' => $sessao->data,
            'horario_inicio' => $sessao->horario_inicio,
            'sala_id' => $sessao->Salas->nome,
            'preco' => $precoBilhete->preco_bilhete_sem_iva,
            'iva' => $ivaBilhete,
            'idLugar' => $idlugar,
            'fila' => $fila[0],
            'lugar' => $posicao[0],
            'total' => $total,
        ];
        //dd($carrinho);
        $request->session()->put('carrinho', $carrinho);
        //$request->session()->increment('count', $incrementBy = 1);//incrementa 1 no [] do carrinho
        return redirect()->back()
            ->with('alert-msg', 'Foi adicionada uma nova sessão ao carrinho!')
            ->with('alert-type', 'success');
    }

    // public function update_sessao(Request $request, Sessao $sessao)
    // {
        
    //     $carrinho = $request->session()->get('carrinho', []);
    //     $qtd = $carrinho[$sessao->id]['qtd'] ?? 0;
    //     $total = $carrinho[$sessao->id]['total'] ?? 0;
    //     $qtd += $request->quantidade;
    //     $precoBilhete = Configuracao::find(1);
    //     //dd($precoTotalSessao);
    //     $total = $precoBilhete->preco_bilhete_sem_iva*$qtd;
    //     if ($request->quantidade < 0) {
    //         $msg = 'Foram removidas ';
    //     } elseif ($request->quantidade > 0) {
    //         $msg = 'Foram adicionadas ';
    //     }
    //     if ($qtd <= 0) {
    //         unset($carrinho[$sessao->id]);
    //         $msg = 'Foram removidas todas as sessões';
    //     } else {
    //         $carrinho[$sessao->id] = [
    //             'id' => $sessao->id,
    //             'filme' => $sessao->Filmes->titulo,
    //             'qtd' => $qtd,
    //             'data' => $sessao->data,
    //             'horario_inicio' => $sessao->horario_inicio,
    //             'sala_id' => $sessao->Salas->nome,
    //             'preco' => $precoBilhete->preco_bilhete_sem_iva,
    //             'iva' => $precoBilhete->percentagem_iva,
    //             'total' => $total,

    //         ];
    //     }
    //     $request->session()->put('carrinho', $carrinho);
    //     return back()
    //         ->with('alert-msg', $msg)
    //         ->with('alert-type', 'success');
    // }

    public function destroy_sessao(Request $request, Sessao $sessao)
    {
        $countInt = $request->session()->decrement('countInt');
        $precoBilhete = Configuracao::find(1);
        $ivaBilhete = $precoBilhete->percentagem_iva;
        $precoSemIva= $precoBilhete->preco_bilhete_sem_iva;
        $bilheteComIva=$precoSemIva*(($ivaBilhete/100)+1);
        $total = $request->session()->decrement('total', $bilheteComIva);
        $carrinho = $request->session()->get('carrinho', []);
        if (array_key_exists($request->eleminar, $carrinho)) {
            unset($carrinho[$request->eleminar]);
            $request->session()->put('carrinho', $carrinho);
            return back()
                ->with('alert-msg', 'Foram removida a Sessão')
                ->with('alert-type', 'success');
        }
        return back()
            ->with('alert-msg', 'A disciplina já não tinha inscrições no carrinho!')
            ->with('alert-type', 'warning');
    }

    public function store(Request $request, Sessao $sessao)
    {
        $currentTime = Carbon::now();
        $currentTime = $currentTime->toDateString();
        $userInfo = auth()->user();

        

        //se nao exister user logado
        if (auth()->user() === null) {
            return redirect()->route('carrinho.index')
            ->with('alert-msg', 'Sem Sessão iniciado, por favor, tente novamente')
            ->with('alert-type', 'danger');
        }
        // se nao existir sessoes no carrinho
        if ($request->session()->get('carrinho') === []) {
            return redirect()->route('carrinho.index')
            ->with('alert-msg', 'Carrinho vazio, por favor, adicione uma sessão')
            ->with('alert-type', 'danger');
        }
        //VISA
        if ($request->numerocard != null && $request->cvv != null) {
            //dd(Payment::payWithVisa($request->numerocard,$request->cvv));
            if(Payment::payWithVisa($request->numerocard,$request->cvv)){
                $metodoPagamento = 'VISA';
                $nomeCartao = $request->ccname;
                $ref_pagamento = $request->numerocard;
                $expirationCartao = $request->ccexpiration;
                $cvvCartao = $request->cvv;
                $nif = $request->nif;
            }else{
                return back()
                ->with('alert-msg', 'Dados Cartao Invalidos')
                ->with('alert-type', 'danger');
            }
        }
        //MBWAY
        if ($request->numTel != null) {
            if(Payment::payWithMBway($request->numTel)){
                $metodoPagamento = 'MBWAY';
                $ref_pagamento = $request->numTel;
                $nif = $request->nif;
            }else{
                return back()
                ->with('alert-msg', 'Numero Invalido')
                ->with('alert-type', 'danger');
            }

        }
        //PAYPAL
        if ($request->email != null ) {
            if(Payment::payWithPaypal($request->email)){
                $metodoPagamento = 'PAYPAL';
                $ref_pagamento = $request->email;
                $nif = $request->nif;
            }else{
                return back()
                ->with('alert-msg', 'Email Invalido')
                ->with('alert-type', 'danger');
            }
        }
        //Outros dados
        $precoBilhete = Configuracao::where('id', 1)->pluck('preco_bilhete_sem_iva');
        $ivaBilhete = Configuracao::where('id', 1)->pluck('percentagem_iva'); 
        $carro = $request->session()->get('carrinho');
        $count = $request->session()->get('count');    
        $quantidadeCarrinho = count($carro); 
        $precoTotalBilhetesSemIva = ($precoBilhete[0]*$quantidadeCarrinho);     
        $data = $request->session()->all(); // ver tudo da sessao 

        //dd($count);
        //---------------------Emitir Recibo--------------------
        //gravar pdf
        //guardar pdf em app\pdf_recibos com nome (1.pdf)
        //possivel implementacao string random
        $last = Recibo::orderBy('id', 'desc')->first();
        //$recibo = Recibo::where('cliente_id',$userInfo->id)->where('id',$last->id)->first();
        //dd($last->id+1);
        $novoIdRecibo = $last->id+1;

        $fileStorage = "http://projeto.test/recibos/bilhete/" . $novoIdRecibo . '.' . 'pdf' ;
        $fileName = $novoIdRecibo . '.' . 'pdf' ;
        

        $newRecibo = ([
            'cliente_id' => $userInfo->id,
            'data' => $currentTime,
            'preco_total_sem_iva' => $precoTotalBilhetesSemIva,
            'iva' => $ivaBilhete[0],
            'preco_total_com_iva' => round($precoTotalBilhetesSemIva*($ivaBilhete[0]/100 + 1),2),
            'nif' => $nif,
            'nome_cliente' => $userInfo->name,
            'tipo_pagamento' => $metodoPagamento,
            'ref_pagamento' => $ref_pagamento,
            'recibo_pdf_url' => $fileStorage,
        ]);
        $recibo = Recibo::create($newRecibo);
        
        
        //-----------------------END RECIBOS-------------------------
        //------------------------------------------------------
        //---------------------Emitir Bilhetes--------------------
        $ultimoRecibo = Recibo::orderBy('id','desc')->first();
        $ultimoIdRecibo = $ultimoRecibo->id;
        for ($i=1; $i <= $count ; $i++) {
            $newBilhete = ([
                'recibo_id' => $ultimoIdRecibo,
                'cliente_id' => $userInfo->id,
                'sessao_id' => $carro[$i]['id'] ?? 0,
                'lugar_id' => $carro[$i]['idLugar'] ?? 0,
                'preco_sem_iva' => $precoBilhete[0],
                'estado' => 'não usado',
            ]);
            //dd($newBilhete);

            if ($newBilhete['sessao_id'] <> 0) {
                $Bilhete = Bilhetes::create($newBilhete);
            }  
        }
        //-----------------------END Bilhetes------------------------

        $request->session()->forget('countInt');
        $request->session()->forget('total');
        $request->session()->forget('carrinho');
        
        //dd($recibo);
        // enviar por email
        $user = auth()->user();
        $recibo = Recibo::findOrFail($novoIdRecibo);
        $sessoes = Bilhetes::where('recibo_id',$recibo->id)->get();
        $pdf = pdf::loadView('pdf.recibo', compact('recibo', 'sessoes'));
        $path = storage_path('app\pdf_recibos');
        $pdf->save($path . '/' . $fileName);
        Mail::to($user)->send(new EnvioRecibo($recibo));
        
        return view('carrinho.carrinhoValidado')
                        ->with('recibo', $recibo)
                        ->with('alert-msg', 'Compra efetuado com Sucesso')
                        ->with('alert-type', 'Success');
    }

    public function carrinhoValidado(Request $request)
    {
        $user = auth()->user();
        $last = Recibo::where('cliente_id',$user)->last('id')->pluck('id');
        $recibo = Recibo::where('cliente_id',$user)->where('id',$last);
        
        //dd($recibos);
        return view('carrinho.carrinhoValidado', compact('recibo'));
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('countInt');
        $request->session()->forget('total');
        $request->session()->forget('count');
        $request->session()->forget('carrinho');
        return back()
            ->with('alert-msg', 'Carrinho foi limpo!')
            ->with('alert-type', 'Sucess');
    }
}