@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1>{{$dadosPagina['titulo']}}</h1>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="#">{{$dadosPagina['titulo']}}</a></li>
</ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-6">
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lista as $L)
                                
                                <tr>
                                    <td>{{ $L->id }}</td>
                                    <td>{{ $L->name}}</a></td>
                                    <td>{{ $L->email }}</td>
                                    <td><a href="{{route( $dadosPagina['rota'] . 'edit', $L->id)}}" class="btn btn-warning btn-xs pull-right"> <i class="fa fa-trash-o"></i>
                                    </a>
                                    <form action="{{ route( $dadosPagina['rota'] . 'destroy', $L->id)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-danger btn-xs pull-right"><i class="fa fa-trash-o"></i> </button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2>Criar Novo Usuário </h2>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body" >
                    <form action="{{ route('user.store')  }}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input type="text" name="name" class="form-control" value="{{ $user['name'] }}"
                                    placeholder="{{ trans('adminlte::adminlte.full_name') }}">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input type="email" name="email" class="form-control" value="{{ $user['email'] }}"
                                    placeholder="{{ trans('adminlte::adminlte.email') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" name="password" class="form-control"
                                    placeholder="{{ trans('adminlte::adminlte.password') }}">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit"
                                class="btn btn-primary btn-block btn-flat"
                        >{{ trans('adminlte::adminlte.register') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop