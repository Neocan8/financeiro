@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
    <h1>Saldo</h1>

    <ol class="breadcrumb">
        <li><a href="">Dashboard</a></li>
        <li><a href="">Saldo</a></li>
    </ol>
@stop

@section('content')
@foreach ($centrodecustos as $c)
    <h2> {{ $c->nome }}</h2>

    <div class="row">
        @foreach ($c->conta as $conta)
            <div class="col-xs-12 col-md-4">
                <div class="box">
                    <div class="box-header">
                        <h4> {{ $conta->nome}}</h4>
                        <a href="\contas\transacao\depositar\{{ $conta->id}}" class="btn btn-xs btn-primary"><i class="fa fa-cart-plus" aria-hidden="true"> Depósito</i></a>
                        
                        @if ($amount > 0)
                        <a href="\contas\transacao\sacar\{{ $conta->id}}" class="btn btn-xs btn-danger"><i class="fa fa-cart-plus" aria-hidden="true"> Saque</i></a>
                        @endif
                        
                        @if ($amount > 0)
                        <a href="{{ route('contas.transfer') }}" class="btn btn-xs btn-info"><i class="fa fa-exchange" aria-hidden="true"> Transferir</i></a>
                        @endif
                    </div>
                    <div class="box-body">
                        @include('includes.alerts')
                        @if ($conta->saldo > 0)
                            <div class="small-box bg-green">
                        @else
                            <div class="small-box bg-red">
                        @endif
                            <div class="inner">
                                <h3>R$ {{ number_format($conta->saldo, 2, ',','.') }}</h3>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            <a href="#" class="small-box-footer">Ver Histórico<i class ="fa fa-arrow-up"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> {{-- row --}} 
@endforeach

@stop