@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1>{{$dadosPagina['titulo']}}</h1>
<a href="{{ route( $dadosPagina['rota'] . 'create' )}}" class="btn btn-success">Criar</a>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="#">{{$dadosPagina['titulo']}}</a></li>
</ol>
@stop

@section('content')
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
                                <th>Email</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lista as $L)
                                
                                <tr>
                                    <td>{{ $L->id }}</td>
                                    <td><a href="{{route( $dadosPagina['rota']' . edit', $L->id)}}">{{ $L->nome}}</a></td>
                                    <td>{{ $L->email }}</td>
                                    <td><a href="{{ route( $dadosPagina['rota']' . destroy', $L->id)}}"
                                        class="btn btn-danger btn-xs pull-right"><i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $lista->links() }}
                </div>
            </div>
        </div>
    </div>
@stop