@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection
@section('content')
<div class="row justify-content-start">
    <div class="col-12 p-3">
      <div class="container-submit rounded p-3 border container-fluid ">
        <!-- fomr sub IT -->
        <div class="container-form-sub container-form-sub-IT">
          <div class="d-flex justify-content-between">
            <div class="fs-4">SEARCHING PAGE</div>
          </div>
          <h2>Ricerca utilizzando i criteri sottostanti</h2>
          <hr>
          <form id="search-story" action="{{route('listStories')}}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
              {{ csrf_field() }}
              <div class="container-search p-2 rounded mt-3">
                  <div class="row form-group av-search-row p-2">
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select id="tiporicerca1" name="tiporicerca1" class="form-control">
                              <option value="Autore" selected="selected">Autore
                              </option>
                              <option value="Titolo">Titolo
                              </option>
                              <option value="Abstract">Abstract
                              </option>
                              <option value="Testo">Testo
                              </option>
                          </select>
                      </div>
                      <div class="col-12 col-md-8 av-search-row-el">
                          <div class="input-group">
                              <input class="form-control" type="text" tabindex="2" name="valorericerca1" id="valorericerca1" size="80">
                          </div>
                      </div>
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select name="valoreoperazione1" id="valoreoperazione1" class="form-control">
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                              <option value="AND_NOT">AND NOT</option>
                              <option value="EXACT">"Frase Esatta"</option>
                          </select>
                      </div>
                  </div>
                  <div class="row form-group av-search-row p-2">
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select id="tiporicerca2" name="tiporicerca2" class="form-control">
                              <option value="Autore">Autore
                              </option>
                              <option value="Titolo" selected="selected">Titolo
                              </option>
                              <option value="Abstract">Abstract
                              </option>
                              <option value="Testo">Testo
                              </option>
                          </select>
                      </div>
                      <div class="col-12 col-md-8 av-search-row-el">
                          <div class="input-group">
                              <input class="form-control" type="text" tabindex="2" name="valorericerca2" id="valorericerca2" size="80">
                          </div>
                      </div>
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select name="valoreoperazione2" id="valoreoperazione2" class="form-control">
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                              <option value="AND_NOT">AND NOT</option>
                              <option value="EXACT">"Frase Esatta"</option>
                          </select>
                      </div>
                  </div>
                  <div class="row form-group av-search-row p-2">
                      <div class="col-12 col-md-2 av-search-row-el">
                          
                          <select id="tiporicerca3" name="tiporicerca3" class="form-control">
                              <option value="Autore">Autore
                              </option>
                              <option value="Titolo">Titolo
                              </option>
                              <option value="Abstract" selected="selected">Abstract
                              </option>
                              <option value="Testo">Testo
                              </option>
                          </select>
                      </div>
                      <div class="col-12 col-md-8 av-search-row-el">
                          
                          <div class="input-group">
                              <input class="form-control" type="text" tabindex="2" name="valorericerca3" id="valorericerca3" size="80">
                          </div>
                      </div>
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select name="valoreoperazione3" id="valoreoperazione3" class="form-control">
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                              <option value="AND_NOT">AND NOT</option>
                              <option value="EXACT">"Frase Esatta"</option>
                          </select>
                      </div>
                  </div>
                  <div class="row form-group av-search-row p-2">
                      <div class="col-12 col-md-2 av-search-row-el">
                          <select id="tiporicerca4" name="tiporicerca4" class="form-control">
                              <option value="Autore">Autore
                              </option>
                              <option value="Titolo">Titolo
                              </option>
                              <option value="Abstract">Abstract
                              </option>
                              <option value="Testo" selected="selected">Testo
                              </option>
                          </select>
                      </div>
                      <div class="col-12 col-md-8 av-search-row-el">
                          <div class="input-group">
                              <input class="form-control" type="text" tabindex="2" name="valorericerca4" id="valorericerca4" size="80">
                          </div>
                      </div>
                      <div class="col-12 col-md-2 av-search-row-el">
                         
                          <select name="valoreoperazione4" id="valoreoperazione4" class="form-control">
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                              <option value="AND_NOT">AND NOT</option>
                              <option value="EXACT">"Frase Esatta"</option>
                          </select>
                      </div>
                  </div>
                  <div class="row form-group av-search-row pt-2">
                      <div class="col-12 col-sm-3 av-search-row-el">
                          <label for="data_dal">Data evento dal</label>
                          <div class="input-group">
                              <input class="form-control date-picker" type="text" tabindex="2" name="data_dal" id="data_dal" placeholder="Clicca per selezionare una data" />
                          </div>
                      </div>
                      <div class="col-12 col-sm-3 av-search-row-el">
                          <label for="data_al">Data evento al</label>
                          <div class="input-group">
                              <input class="form-control date-picker" type="text" tabindex="2" name="data_al" id="data_al" placeholder="Clicca per selezionare una data" />
                          </div>
                      </div>
                      <div class="col-12 col-sm-3 av-search-row-el">
                          <label for="zoonosi">Zoonosi</label>
                          <select name="zoonosi" id="zoonosi" class="select form-control">
                              <option value="" selected="selected">Tutte</option>
                              @foreach($zoonosi as $z)    
                                  <option value="{{$z->zid}}">{{$z->nome}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-12 col-sm-3 av-search-row-el">
                          <label for="paese">Paese ambientazione</label>
                          <select name="paese" id="paese" class="form-control" multiple="multiple">
                              <option value="IT" selected="selected">Italia</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="container-search p-2 rounded mt-3">
                  <fieldset>
                      <input class="btn btn-success" type="submit" value="Avvia la ricerca" /> <a href="#!" title="Annulla" class="btn btn-secondary">Annulla</a>
                  </fieldset>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection
