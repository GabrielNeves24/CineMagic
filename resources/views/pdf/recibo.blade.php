<!-- THIS EMAIL WAS BUILT AND TESTED WITH LITMUS http://litmus.com -->
<!-- IT WAS RELEASED UNDER THE MIT LICENSE https://opensource.org/licenses/MIT -->
{{ QrCode::generate('Make me into a QrCode')}}
<!DOCTYPE html>
<html>
   <head>
      <title>Compra Bilhetes CineMagic</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <style type="text/css">
         body{margin-top:20px;
         color: #2e323c;
         background: #f5f6fa;
         position: relative;
         height: 100%;
         }
         .invoice-container {
         padding: 1rem;
         }
         .invoice-container .invoice-header .invoice-logo {
         margin: 0.8rem 0 0 0;
         display: inline-block;
         font-size: 1.6rem;
         font-weight: 700;
         color: #2e323c;
         }
         .invoice-container .invoice-header .invoice-logo img {
         max-width: 130px;
         }
         .invoice-container .invoice-header address {
         font-size: 0.8rem;
         color: #9fa8b9;
         margin: 0;
         }
         .invoice-container .invoice-details {
         margin: 1rem 0 0 0;
         padding: 1rem;
         line-height: 180%;
         background: #f5f6fa;
         }
         .invoice-container .invoice-details .invoice-num {
         text-align: right;
         font-size: 0.8rem;
         }
         .invoice-container .invoice-body {
         padding: 1rem 0 0 0;
         }
         .invoice-container .invoice-footer {
         text-align: center;
         font-size: 0.7rem;
         margin: 5px 0 0 0;
         }
         .invoice-status {
         text-align: center;
         padding: 1rem;
         background: #ffffff;
         -webkit-border-radius: 4px;
         -moz-border-radius: 4px;
         border-radius: 4px;
         margin-bottom: 1rem;
         }
         .invoice-status h2.status {
         margin: 0 0 0.8rem 0;
         }
         .invoice-status h5.status-title {
         margin: 0 0 0.8rem 0;
         color: #9fa8b9;
         }
         .invoice-status p.status-type {
         margin: 0.5rem 0 0 0;
         padding: 0;
         line-height: 150%;
         }
         .invoice-status i {
         font-size: 1.5rem;
         margin: 0 0 1rem 0;
         display: inline-block;
         padding: 1rem;
         background: #f5f6fa;
         -webkit-border-radius: 50px;
         -moz-border-radius: 50px;
         border-radius: 50px;
         }
         .invoice-status .badge {
         text-transform: uppercase;
         }
         @media (max-width: 767px) {
         .invoice-container {
         padding: 1rem;
         }
         }
         .custom-table {
         border: 1px solid #e0e3ec;
         }
         .custom-table thead {
         background: #007ae1;
         }
         .custom-table thead th {
         border: 0;
         color: #ffffff;
         }
         .custom-table > tbody tr:hover {
         background: #fafafa;
         }
         .custom-table > tbody tr:nth-of-type(even) {
         background-color: #ffffff;
         }
         .custom-table > tbody td {
         border: 1px solid #e6e9f0;
         }
         .card {
         background: #ffffff;
         -webkit-border-radius: 5px;
         -moz-border-radius: 5px;
         border-radius: 5px;
         border: 0;
         margin-bottom: 1rem;
         }
         .text-success {
         color: #00bb42 !important;
         }
         .text-muted {
         color: #9fa8b9 !important;
         }
         .custom-actions-btns {
         margin: auto;
         display: flex;
         justify-content: flex-end;
         }
         .custom-actions-btns .btn {
         margin: .3rem 0 .3rem .3rem;
         }
      </style>
   </head>
   <body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
      <div style="border: 2px solid black">
         <div class="container">
            <div class="row gutters">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="card">
                     <div class="card-body p-0">
                        <div class="invoice-container">
                           <div class="invoice-header">
                              <!-- Row start -->
                              <div class="row gutters">
                                 <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <a href="index.html" class="invoice-logo">
                                    CineMagic.pt
                                    </a>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <address class="text-right">
                                       IPLeira, Eng Infomatica<br>
                                       Inet<br>
                                    </address>
                                 </div>
                              </div>
                              <!-- Row end -->
                              <!-- Row start -->
                              <div class="row gutters">
                                 <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details">
                                       <address>
                                          {{$recibo->nome_cliente}}<br>
                                          {{$recibo->nif}}
                                       </address>
                                    </div>
                                 </div>
                                 <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details">
                                       <div class="invoice-num">
                                          <div>Fatura-Recibo - #{{$recibo->id}}</div>
                                          <div>{{$recibo->data}}</div>
                                          <div>Tipo Pagamento:{{$recibo->tipo_pagamento}}</div>
                                          <div>Ref. Pgamento: {{$recibo->ref_pagamento}}</div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- Row end -->
                           </div>
                           <div class="invoice-body">
                              <!-- Row start -->
                              <div class="row gutters">
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="">
                                       <table class="table custom-table m-0">
                                          <thead>
                                             <tr>
                                                <th>Filme</th>
                                                <th>Data</th>
                                                <th>Hora</th>
                                                <th>Sub Total</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             @foreach($sessoes as $sessao)
                                             <tr>
                                                <td>
                                                   {{$sessao->Sessao->Filmes->titulo}}
                                                   <p class="m-0 text-muted">
                                                      Bilhete #{{$sessao->id}}, Sala: {{$sessao->Sessao->Salas->nome}}<br>
                                                      Fila: {{$sessao->Lugares->fila}} | Lugar: {{$sessao->Lugares->posicao}}<br>
                                                      Guarde este bilhete, serve como prova de compra do bilhete, apresentar na caixa
                                                   </p>
                                                </td>
                                                <td>{{$sessao->Sessao->horario_inicio}}</td>
                                                <td>{{$sessao->Sessao->data}}</td>
                                                <td>8.85€</td>
                                             </tr>
                                             @endforeach
                                             <tr>
                                                <td>&nbsp;</td>
                                                <td colspan="2">
                                                   <p>
                                                      IVA<br>
                                                   </p>
                                                   <h3 ><strong>Total</strong></h3>
                                                </td>
                                                <td>
                                                   <p>
                                                      {{(int) $recibo->iva}} %<br>
                                                   </p>
                                                   <h3 ><strong> {{$recibo->preco_total_com_iva}}</strong></h3>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                              <!-- Row end -->
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
   </body>
   </div>
</html>