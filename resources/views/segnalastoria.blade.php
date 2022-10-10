@extends('layout.base')
@section('header_pt1')
    @include('layout.header_pt1')
@endsection
@section('modal_1')
<div class="modal fade" tabindex="-1" id="modalTerminicondizioni" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-fullscreen-sm-down modal-animation-special">
        <div class="modal-content">
            <div class="modal-header bg-wheat">
                <h2 class="modal-title font-dark">Privacy Policy</h2>
                <button type="button" class="btn-close btn-close-white closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?=$privacy_policy->testoprivacy;?>
            </div>
            <div class="modal-footer">
                <button type="button" id="presovisionetermini" class="btn bg-success font-white closeModal" data-bs-dismiss="modal">Ho preso visione</button>
                <button type="button" class="btn bg-header font-white closeModal" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('additionaljs')
    @parent
    <script type="text/javascript" src="/js/form-control.js"></script>
    <script type="text/javascript" src="/js/terminiecondizioni.js"></script>    
@endsection
@section('content')
<div class="container-fluid pb-4 pt-3">
    <div class="row justify-content-start">
        <?php if(!Auth::check()){ ?>
        <div class="col-12 ps-4 pe-4 pt-1 pb-1">
            <div class="row">
                <div class="col-12 alert alert-warning">
                    <h4>ATTENZIONE!</h4>
                    <h5>Devi effettuare il <a style="text-decoration: underline;color: #842029 !important;" href="/login"><b>Log in o l&apos;autenticazione</b></a>  per poter segnalare una storia.</h5>
                </div> 
            </div>
        </div>
        <?php } ?>
        @if (session('formerrato'))
            <div class="col-12 p-4">
                <div class="row">
                    <div class="col-12 alert alert-danger">
                    {!!session('formerrato')!!}
                    </div> 
                </div>
            </div>
        @endif
        <div class="col-12 p-md-3 container-gen">
            <div class="container-submit rounded bg-wheat-transp p-3 border container-fluid shadow-personal-1">
                <!-- fomr sub IT -->
                <div class="container-form-sub container-form-sub-IT">
                  <div class="d-flex justify-content-between">
                    <div class="fs-4">SUBMISSION PAGE</div>
                    <div class="p-2 justify-content-end">
                      <a href="#!" class="switch-lang" data-language="EN" title="Switch language to English">
                        <img src="/images/bandiera_en.png" title="Switch language to English" width="40">
                      </a>
                    </div>
                  </div>
                  <h2>Inserite la vostra Storia di Zoonosi</h2>
                  <hr>
                  <form action="/crowdsourcing/submission" id="form-submitstory-IT" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate>
                      {{ csrf_field() }}
                      <input type="hidden" name="language" id="language-IT" value="IT" />
                    <div class="row">
                      <!-- prima colonna -->
                      <div class="col-12 col-sm-6">
                        <div class="mb-3 col-12">
                          <label for="titolo" class="form-label">Titolo<span class="text-required"> * </span></label>
                          <div class="input-group has-validation">
                              <input type="text" name="titolo" class="form-control" id="titolo" aria-describedby="titolo" value="<?=(isset($datapost['titolo']))?html_entity_decode($datapost['titolo']):'';?>" required>
                            <div class="invalid-feedback">
                              Campo obbligatorio.
                            </div>
                          </div>
                        </div>
                        <div class="mb-3 col-12">
                          <label for="zoonosi" class="form-label">Tipo di Zoonosi<span class="text-required"> * </span></label>
                          <div class="input-group has-validation">
                              <input type="text" name="zoonosi" class="form-control" id="zoonosi" aria-describedby="zoonosi" value="<?=(isset($datapost['zoonosi']))?html_entity_decode($datapost['zoonosi']):'';?>" required>
                            <div class="invalid-feedback">
                              Campo obbligatorio.
                            </div>
                          </div>
                        </div>
                        <div class="mb-3 col-12 col-sm-6">
                          <label for="annoambientazione" class="form-label">Anno di ambientazione<span class="text-required"> * </span></label>
                          <select class="form-select" id="annoambientazione" required name="annoambientazione">
                            <option disabled value="" <?=(!isset($datapost['annoambientazione']))?'selected="selected"':'';?>>Scegli l&apos;anno di ambientazione della storia</option>
                            <?php for($anno=1900;$anno<=date('Y');$anno++){ ?>
                            <option value=<?=$anno;?> <?=(isset($datapost['annoambientazione']) && $datapost['annoambientazione']==$anno)?'selected="selected"':'';?>><?=$anno;?></option>
                            <?php } ?>
                          </select>
                          <div class="invalid-feedback">
                            Anno di ambientazione obbligatorio.
                          </div>
                        </div>

                        <div class="mb-3 col-12">
                          <label for="descrizionebreve" class="form-label">Breve descrizione della storia<span class="text-required"> * </span></label>
                          <textarea class="form-select" rows="3" id="descrizionebreve" name="descrizionebreve" placeholder="Inserisci una breve descrizione della storia" required><?=(isset($datapost['descrizionebreve']))?html_entity_decode($datapost['descrizionebreve']):'';?></textarea>
                          <div class="invalid-feedback">
                            Inserisci una breve descrizione.
                          </div>
                        </div>
                      </div>
                      <!-- seconda colonna -->
                      <div class="col-12 col-sm-6">
                        <div class="mb-3 col-12">
                          <label for="ruolo" class="form-label">Indicare il proprio ruolo (se c&apos;&egrave;) nella storia</label>
                          <textarea class="form-select" rows="3" id="ruolo" name="ruolo" placeholder="Il proprio ruolo nella storia"><?=(isset($datapost['ruolo']))?html_entity_decode($datapost['ruolo']):'';?></textarea>
                        </div>
                        <div class="mb-3 col-12">
                          <label for="noteaggiuntive" class="form-label">Note aggiuntive</label>
                          <textarea class="form-select" rows="3" id="noteaggiuntive" name="noteaggiuntive" placeholder="Inserisci delle note aggiuntive che voresti comunicare prima che la storia venga pubblicata"><?=(isset($datapost['noteaggiuntive']))?html_entity_decode($datapost['noteaggiuntive']):'';?></textarea>
                        </div>
                      </div>
                    </div>  
                    <div class="row">
                        <div class="col-12 col-sm-7">
                            <div class="mb-3 col-12 col-sm-12">
                                <label class="form-check-label" for="filetesto">
                                  Inserisci file di testo (.doc, .docx, .rtf, .txt, .pdf) - (dimensione massima 10MB)
                                </label>
                                <input type="file" class="form-control" id="filetesto" aria-label="file example" name="filetesto">
                            </div>
                            <div class="mb-3 col-12 col-sm-12">
                                <label class="form-check-label" for="filevideo">
                                  Inserisci video (.mp4, .mov, .avi) - (max 2 video) - (dimensione massima 400MB ogni video)
                                </label>
                                <input type="file" class="form-control" id="filevideo" aria-label="file example" name="filevideo[]" multiple>
                            </div>
                            <div class="mb-3 col-12 col-sm-12">
                                <label class="form-check-label" for="fileimmagini">
                                  Inserisci immagini (.jpg, .png, .gif, .svg) - (max 10 immagini) - (dimensione massima 4,5MB ogni immagine)
                                </label>
                                <input type="file" class="form-control" id="fileimmagini" aria-label="file example" name="fileimmagini[]" multiple>
                            </div>
                            <div class="mb-3 col-12 col-sm-12">
                                <label class="form-check-label" for="fileaudio">
                                  Inserisci file audio (.pcm, .wav, .mp3, .ogg, .flac) - (dimensione massima 20MB)
                                </label>
                                <input type="file" class="form-control" id="fileaudio" aria-label="file example" name="fileaudio" multiple>
                            </div>
                        </div>
                        <div class="col-12 col-sm-5 border border-secondary rounded p-3">
                            <h4><i class="fa fa-question-circle-o"></i> Indicazione sui file</h4>
                            <div>
                                <strong>FILE DI TESTO</strong>
                                <p><strong>1</strong> file di testo ammesso con una grandezza massima di 10MB</p>
                            </div>
                            <div>
                                <strong>VIDEO</strong>
                                <p><strong>2</strong> file video ammessi con una grandezza massima di 400MB ogni video</p>
                            </div>
                            <div>
                                <strong>IMMAGINI</strong>
                                <p><strong>10</strong> immagini ammesse con una grandezza massima di 4.5MB ogni immagine</p>
                            </div>
                            <div>
                                <strong>AUDIO</strong>
                                <p><strong>1</strong> file audio ammesso con una grandezza massima di 20MB</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if(Auth::check()){ ?>
                    <div class="mb-3 col-12 mt-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="terminiecondizioni" required>
                        <label class="form-check-label fw-bold" for="terminiecondizioni">
                          Accetta <a href="#" onclick="apricondizioni();">termini e condizioni di utilizzo</a>
                        </label>
                        <div class="invalid-feedback">
                          Devi accettare per proseguire.
                        </div>
                      </div>  
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn bg-primary text-white ps-5 pe-5 text-center btn-showloader" title-loader="ATTENDERE..." text-loader="Il sistema sta memorizzando le informazioni<br />Non chiudere la finestra del browser...<br />(potrebbe volerci qualche minuto)">INVIA LA STORIA</button>
                    </div>
                    <?php } ?>
                  </form>
                </div>

                <!-- fomr sub EN -->
                <div class="container-form-sub container-form-sub-EN">
                    <div class="d-flex justify-content-between">
                      <div class="fs-4">SUBMISSION PAGE</div>
                      <div class="p-2 justify-content-end">
                        <a href="#!" class="switch-lang" data-language="IT" title="Cambia lingua in Italiano">
                          <img src="/images/bandiera_it.png" title="Cambia lingua in Italiano" width="40">
                        </a>
                      </div>
                    </div>
                    <h2>Enter your zoonosis story</h2>
                    <hr>
            <form action="/crowdsourcing/submission" id="form-submitstory-EN" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate>
                {{ csrf_field() }}
                <input type="hidden" name="language" id="language-EN" value="EN" />
                <div class="row">
                    <!-- prima colonna -->
                    <div class="col-12 col-sm-6">
                        <div class="mb-3 col-12">
                          <label for="titolo_en" class="form-label">Title<span class="text-required"> * </span></label>
                          <div class="input-group has-validation">
                            <input type="text" name="titolo_en" class="form-control" id="titolo_en" aria-describedby="titolo_en" value="<?=(isset($datapost['titolo_en']))?html_entity_decode($datapost['titolo_en']):'';?>" required>
                            <div class="invalid-feedback">
                              Required field.
                            </div>
                          </div>
                        </div>
                        <div class="mb-3 col-12">
                          <label for="zoonosi_en" class="form-label">Type of Zoonosis<span class="text-required"> * </span></label>
                          <div class="input-group has-validation">
                            <input type="text" name="zoonosi_en" class="form-control" id="zoonosi_en" aria-describedby="zoonosi_en" value="<?=(isset($datapost['zoonosi_en']))?html_entity_decode($datapost['zoonosi_en']):'';?>" required>
                            <div class="invalid-feedback">
                              Required field.
                            </div>
                          </div>
                        </div>
                        <div class="mb-3 col-12 col-sm-6">
                          <label for="annoambientazione_en" class="form-label">Year of setting<span class="text-required"> * </span></label>
                          <select class="form-select" id="annoambientazione_en" required name="annoambientazione_en">
                            <option disabled value="" <?=(!isset($datapost['annoambientazione_en']))?'selected="selected"':'';?>>Choose the year of setting for the story</option>
                            <?php for($anno=1900;$anno<=date('Y');$anno++){ ?>
                            <option value=<?=$anno;?> <?=(isset($datapost['annoambientazione']) && $datapost['annoambientazione']==$anno)?'selected="selected"':'';?>><?=$anno;?></option>
                            <?php } ?>
                          </select>
                          <div class="invalid-feedback">
                            Required year of setting.
                          </div>
                        </div>
                        <div class="mb-3 col-12">
                          <label for="descrizionebreve_en" class="form-label">Short description of the story<span class="text-required"> * </span></label>
                          <textarea class="form-select" rows="3" id="descrizionebreve_en" name="descrizionebreve_en" placeholder="Enter a short description of the story" required><?=(isset($datapost['descrizionebreve_en']))?html_entity_decode($datapost['descrizionebreve_en']):'';?></textarea>
                          <div class="invalid-feedback">
                            Enter a short description.
                          </div>
                        </div>
                    </div>
                    <!-- seconda colonna -->
                    <div class="col-12 col-sm-6">
                      <div class="mb-3 col-12">
                        <label for="ruolo_en" class="form-label">Indicate your role (if any) in the story</label>
                        <textarea class="form-select" rows="3" id="ruolo_en" name="ruolo_en" placeholder="Your role in history"><?=(isset($datapost['ruolo_en']))?html_entity_decode($datapost['ruolo_en']):'';?></textarea>
                      </div>
                      <div class="mb-3 col-12">
                        <label for="noteaggiuntive_en" class="form-label">Additional notes</label>
                        <textarea class="form-select" rows="3" id="noteaggiuntive_en" name="noteaggiuntive_en" placeholder="Enter any additional notes you would like to communicate before the story is published"><?=(isset($datapost['noteaggiuntive_en']))?html_entity_decode($datapost['noteaggiuntive_en']):'';?></textarea>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-7">
                        <div class="mb-3 col-12 col-sm-12">
                            <label class="form-check-label" for="filetesto_en">
                              Upload text file (.doc, .docx, .rtf, .txt, .pdf) - (maximum size 10MB)
                            </label>
                            <input type="file" class="form-control" id="filetesto_en" aria-label="file example" name="filetesto_en[]" multiple>
                        </div>
                        <div class="mb-3 col-12 col-sm-12">
                            <label class="form-check-label" for="filevideo_en">
                              Upload video files (.mp4, .mov, .avi) - (max 2 videos) - (maximum size 400MB each video)
                            </label>
                            <input type="file" class="form-control" id="filevideo_en" aria-label="file example" name="filevideo_en[]" multiple>
                        </div>
                        <div class="mb-3 col-12 col-sm-12">
                            <label class="form-check-label" for="fileimmagini_en">
                              Upload image files (.jpg, .png, .gif, .svg) - (max 10 images) - (maximum size 4,5MB each image)
                            </label>
                            <input type="file" class="form-control" id="fileimmagini_en" aria-label="file example" name="fileimmagini_en[]" multiple>
                        </div>
                        <div class="mb-3 col-12 col-sm-12">
                            <label class="form-check-label" for="fileaudio_en">
                              Upload audio file (.pcm, .wav, .mp3, .ogg, .flac) - (maximum size 20MB)
                            </label>
                            <input type="file" class="form-control" id="fileaudio_en" aria-label="file example" name="fileaudio_en[]" multiple>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5  border border-secondary rounded p-3">
                        <h4><i class="fa fa-question-circle-o"></i> Files indication</h4>
                        <div>
                            <strong>TEXT FILE</strong>
                            <p><strong>1</strong> text file allowed with a maximum size of 10MB</p>
                        </div>
                        <div>
                            <strong>VIDEOS</strong>
                            <p><strong>2</strong> video files allowed with a maximum size of 400MB each video</p>
                        </div>
                        <div>
                            <strong>IMAGES</strong>
                            <p><strong>10</strong> images allowed with a maximum size of 4.5MB each image</p>
                        </div>
                        <div>
                            <strong>AUDIO</strong>
                            <p><strong>1</strong> audio file allowed with a maximum size of 20MB</p>
                        </div>
                    </div>
                </div>
                <hr>
              <?php if(Auth::check()){ ?>
              <div class="mb-3 col-12 mt-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="terminiecondizioni_en" required>
                  <label class="form-check-label fw-bold" for="terminiecondizioni_en">
                    Accept <a href="#" onclick="apricondizioni();">terms and conditions of use</a>
                  </label>
                  <div class="invalid-feedback">
                    You have to accept to continue.
                  </div>
                </div>  
              </div>
              <div class="col-12 text-center">
                    <button type="submit" class="btn bg-primary text-white ps-5 pe-5 text-center btn-showloader">SEND THE STORY</button>
                </div>
              <?php } ?>
            </form>
          </div>
                <?php if(!Auth::check()){ ?>
                <div class="col-12 ps-4 pe-4 pt-1 pb-1">
                    <div class="row">
                        <div class="col-12 alert alert-warning">
                            <h4>ATTENZIONE!</h4>
                            <h5>Devi effettuare il <a style="text-decoration: underline;color: #842029 !important;" href="/login"><b>Log in o l&apos;autenticazione</b></a>  per poter segnalare una storia.</h5>
                        </div> 
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

@endsection
