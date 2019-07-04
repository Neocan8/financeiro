@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1>Centro de Custos</h1>
<a href="{{ route("centrodecusto.create" )}}" class="btn btn-success">Criar</a>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="#">Centro de Custos</a></li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <form role="form" action=" {{ route('centrodecusto.store')}}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nome">Nome</label>
                            <input type="text" step=".01" class="form-control"  id="nome" value="{{old('nome')}}" name="nome">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="descricao">Descrição:</label>
                            <input type='text' name="descricao" value="{{ old('descricao')}}" id="descricao"  class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <input type="submit" class="btn btn-primary" value="SALVAR">
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2>Lista </h2>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($centrodecustos as $C)
                                
                                <tr>
                                    <td>{{ $C->id }}</td>
                                    <td>{{ $C->nome }}</td>
                                    <td>{{ $C->descricao}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop