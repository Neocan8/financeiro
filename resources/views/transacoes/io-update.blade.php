@extends('adminlte::page')
@section('title', 'Nova Entrada')
@section('content_header')
  <div class="row">
    <div class="col-xs-12 col-md-3">
      <h1 style="margin-top: 0;">
      {{$dadosPagina['titulo']}}                
      </h1>
    </div>
  </div>
  <ol class="breadcrumb">
    <li><a href="/home">Home</a></li>
    <li><a href="/entrada">{{$dadosPagina['caminho']}}</a></li>
    <li><a href="#">Editar</a></li>
  </ol>
@stop
@section('content')
          <div class="row">
              <div class="col-md-12">
                  <div class="box box-primary">
                <!-- /.box-header -->
                <!-- form start -->
                  <form role="form" action="{{ route('entrada.update', $entrada->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label for="data">Data</label>
                          <input type="date" class="form-control" value='{{$entrada->data}}' id="data" name="data">
                          </div>
                          <div class="form-group col-md-4">
                              <label for="conta_id">Conta</label>
                              <select name="conta_id" id="conta_id" class="form-control">
                                 @foreach ($contas as $c)
                                  <option value="{{$c->id}}" 
                                    @if ($c->id == $entrada->conta_id) 
                                      selected
                                    @endif>
                                  {{$c->nome}}</option>
                                 @endforeach
                              </select>
                            </div>
                          <div class="form-group col-md-5">
                            <label for="nome">Nome</label>
                          <input type="text" value='{{ $entrada->nome}}' class="form-control" id="nome" name="nome">
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="valor">Valor</label>
                                <input type="number" step=".01" class="form-control" value='{{ $entrada->valor}}'  id="valor" name="valor">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="parcela">Qtd. Parcelas</label>
                                <input type="text"  class="form-control" id="parcela" value='{{ $entrada->parcela}}' min=1 name="parcela" value=1>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="confirmado">Pagamento já efetuado</label>
                                <select name="confirmado" id="confirmado" class="form-control">
                                    <option value=0 @if ($entrada->confirmado == '0') selected @endif> NÃO </option>
                                    <option value=1 @if ($entrada->confirmado == '1') selected @endif> SIM </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="categoria_id">Categoria</label>
                                <select name="categoria_id" id="categoria_id" class="form-control">
                                   @foreach ($categorias as $cat)
                                    <option value="{{$cat->id}}" @if ($entrada->categoria_id == $cat->id) selected
                                      @endif>{{$cat->nome}}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="descricao">Descrição:</label>
                            <textarea name="descricao" id="descricao" rows=10 class="form-control"> {{$entrada->descricao}}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                                <div class="form-group col-md-12">
                                        <input type="submit" class="btn btn-primary" value="SALVAR">
                                </div>
                            </div>
                  <div class="box-footer">
                    @if (count($outrasParcelas) > 1)
                      <div class="row">
                          <div class="col-xs-12 col-md-6">
                            <h3 class="box-title">Demais Parcelas</h3>
                              <div class="table-responsive">
                                  <table class="table table-striped table-hover">
                                      <thead>
                                        <th>#</th>
                                        <th>Data</th>
                                        <th>Parcela</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Pago</th>
                                      </thead>
                                      <tbody>
                                            @foreach ($outrasParcelas as $p)
                                                
                                            <tr>
                                            <td>{{ $loop->index + 1}}</td>
                                              <td>{{ date('d/m/Y', strtotime($p->data)) }} </td>
                                              <td>{{$p->parcela}} </td>
                                              <td><a href=" {{route('entrada.update',$p->id)}}">{{$p->nome}} </a></td>
                                              <td>{{ number_format($p->valor, 2, ',', '.') }}</td>
                                              <td>@if($p->confirmado == 1)  SIM @else NÃO @endif </td>
                                            </tr>
                                            @endforeach
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                      @endif
                  </div>
                </form>
              </div>
              </div>
          </div>
        <!-- /.content-wrapper -->
@stop
