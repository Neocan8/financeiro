<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header" ">
                <h1>
                    Cadastro de Novas Contas / Saída
                </h1>
                <ol class="breadcrumb">
                  <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li><a href="/admin/{$tipo}/contas">Contas</a></li>
                  <li class="active"><a href="#">Edição</a></li>
                </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
              <div class="col-md-12">
                  <div class="box box-primary">
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="/admin/contas/{$tipo}/criar" method="post">
                  <input type="hidden" name="entrada" value={$entrada}>
                        <div class="form-row">
                          <div class="form-group col-md-2">
                            <label for="data_pgt">Vencimento</label>
                            <input type="date" class="form-control" id="data_pgt" name="data_pgt" ">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="idcategory">Categoria</label>
                              <select name="idcategory" id="idcategory" class="form-control">
                                  {loop="$categorias"}
                                    <option value="{$value.idcategory}">{$value.descategory}</option>
                                  {/loop}
                              </select>
                            </div>
                          <div class="form-group col-md-7">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao">
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="valor">Valor</label>
                                <input type="number" step=".01" class="form-control"  id="valor" name="valor" ">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="num_parcela">Parcela</label>
                                <input type="text"  class="form-control" id="num_parcela" min=1 name="num_parcela" value=1>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="pago">Pago</label>
                                <select name="pago" id="pago" class="form-control">
                                    <option value=0 > NÃO </option>
                                    <option value=1 > SIM </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="idcaixa">Caixa</label>
                                <select name="idcaixa" id="idcaixa" class="form-control">
                                    {loop="$caixas"}
                                        <option value="{$value.idcaixa}">{$value.caixa}</option>
                                    {/loop}
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="obs">Observações:</label>
                                <textarea name="obs" id="obs" rows=10 class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                                <div class="form-group col-md-12">
                                        <input type="submit" class="btn btn-primary" value="SALVAR">
                                </div>
                            </div>
                  <div class="box-footer">
                  </div>
                </form>
              </div>
              </div>
          </div>
        </section>
        <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->