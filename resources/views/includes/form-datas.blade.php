<form action=" {{ route($dadosPagina['rota'] . "periodo")}}"  method="POST" class="form-inline">
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