@extends('adminlte::page')

@section('title', 'Home Dashboard')

@section('content_header')
    <h1>Nova Conta</h1>

    <ol class="breadcrumb">
        <li><a href="">Dashboard</a></li>
        <li><a href="{{ route('conta.index')}}">Contas</a></li>
        <li><a href="">Editar</a></li>
    </ol>
@stop

@section('content')
<div class="box">
        <div class="box-header">
        <h3>Editar Conta {{$conta->nome}}</h3>
        </div>
        <div class="box-body">    
            @include('includes.alerts')     

            <form action="{{ route('conta.update', $conta->id)}}" method="post">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="">Centro de Custo</label>
                    <select name="centrodecusto_id" id="" class="form-control">
                        @foreach ($centrodecustos as $ct)
                            <option value="{{ $ct->id }}" @if ($ct->id == $conta->centrodecusto->id) selected
                                
                            @endif>{{ $ct->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control"  value="{{$conta->nome}}" placeholder="Dê um nome a sua nova conta">
                </div>
                <div class="form-group">
                    <label for="descricao"> Descrição</label>
                    <textarea class="form-control" name="descricao" id="descricao" cols="30" rows="10"> {{$conta->nome}}</textarea>
                </div>

                <div class="form-group">
                    <button class="btn btn-success" type="submit">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
@stop