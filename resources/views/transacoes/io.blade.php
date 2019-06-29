@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
<div class="row">
    <div class="col-xs-12 col-md-3">
        <h1 style="margin-top: 0;">
                {{$dadosPagina['titulo']}}                
            </h1>
            <a href="{{ route('entrada.create')}}" class="btn btn-success">Criar</a>
        </div>
        <div class="col-xs-12 col-md-6">
            <form action=" {{ route('entrada.periodo')}}"  method="POST" class="form-inline">
                @csrf
                <div class="form-group" style="margin-right: 15px;">
                    <label for="dataIni">Início</label>
                    <input type="date" name='dataIni' id='dataIni' class="form-control" value="{{$dadosPagina['dataIni']}}">
                </div>
                <div class="form-group">
                    <label for="dataFim">Fim</label>
                    <input type="date" name='dataFim' id='dataFim' class="form-control" value="{{$dadosPagina['dataFim']}}">
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"
                        aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <ol class="breadcrumb">
        <li><a href="/home">Home</a></li>
        <li><a href="#">{{$dadosPagina['caminho']}}</a></li>
    </ol>
@stop

@section('content')
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{$dadosPagina['subtituloEsquerda']}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Vencimento</th>
                                    <th>{{$dadosPagina['titulo']}}</th>
                                    <th>Parc.</th>
                                    <th>Valor</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contasAPagar as $CA)
                                    
                                    <tr>
                                        <td>{{ $CA->id }}</td>
                                        <td>{{ date('d/m/Y', strtotime($CA->data)) }}</td>
                                        <td><a href="{{ route('entrada.edit', $CA->id) }}"><b>{{ $CA->nome }}</b></a>
                                        </td>
                                        <td>{{ $CA->parcela}}</td>
                                        <td>{{ number_format($CA->valor,2,",",".") }}</td>
                                        <td>
                                            <a href="{{ route('pagar' , $CA->id)}}"
                                                    class="btn btn-success btn-xs pull-right"><i class="fa fa-arrow-right"></i>
                                                    Pagar</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <h2>Resta a {{ $dadosPagina['resta'] . " R$ ".  number_format($dadosPagina['restaPagar'],2,",",".") }}</h2>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{$dadosPagina['subtituloEsquerda']}}</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Vencimento</th>
                                            <th>{{ $dadosPagina['titulo'] }}</th>
                                            <th>Parc.</th>
                                            <th>Valor</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contasPagas as $CP)
                                        
                                        <tr>
                                            <td>{{$CP->id}}</td>
                                            <td>{{ date('d/m/Y', strtotime($CP->data)) }}</td>
                                            <td><a href="{{ route('entrada.edit', $CP->id) }}"><b>{{ $CP->nome }}</b></a>
                                            </td>
                                            <td>{{$CP->parcela}}</td>
                                            <td>{{ number_format($CP->valor,2,",",".") }}</td>
                                            <td>
                                                <a href="{{ route('estornar' , $CP->id)}}"
                                                        class="btn btn-danger btn-xs pull-right"><i class="fa fa-arrow-left"></i>
                                                    Estorno</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <h2>Total: R$ {{  number_format($dadosPagina['totalPagas'],2,",",".") }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="alert alert-danger">Total de {{$dadosPagina['titulo']}} {{ number_format($dadosPagina['totalAPagar'],2,",",".") }}</h5>
            </div>
        </div>
        
@stop