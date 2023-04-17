<footer class="text-center text-lg-start bg-footer" id="includefooter">
    <div class="container p-4 pb-0">
        <div class="row">
            <!-- column 1 -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3 text-center">
                <a class="text-decoration-none" href="https://plos.org/" target="_blank">
                    <img src="/images/open_access_logo.png" alt="Open Access Logo" style="width: 100%;max-width: 30px;" />
                    <p class="pt-3 text-uppercase mb-4 font-weight-bold text-white">Copyright<br />Open Access</p>
                </a>
            </div>
            <hr class="w-100 clearfix d-md-none" />
            <!-- column 2 -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3 text-center"> 
                <?php if(isset($settings) && isset($settings['logo_footer_column_2']) && $settings['logo_footer_column_2']->valueconfig!=''){?>
                    <a class="text-white" target="_blank" href="<?php if(isset($settings) && isset($settings['link_logo_footer_column_2'])){?><?=$settings['link_logo_footer_column_2']->valueconfig;?><?php }else echo '#'; ?>" title="<?php if(isset($settings) && isset($settings['link_logo_footer_column_2'])){?><?=$settings['link_logo_footer_column_2']->valueconfig;?><?php }else echo ''; ?>" style="white-space: nowrap;">
                        <img class="d-inline-block" src="/images/<?=$settings['logo_footer_column_2']->valueconfig;?>" style="max-width: 50px;" title="<?php if(isset($settings) && isset($settings['link_logo_footer_column_2'])){?><?=$settings['link_logo_footer_column_2']->valueconfig;?><?php }else echo ''; ?>" alt="<?php if(isset($settings) && isset($settings['link_logo_footer_column_2'])){?><?=$settings['link_logo_footer_column_2']->valueconfig;?><?php }else echo ''; ?>" />
                        <?php if(isset($settings) && isset($settings['text_logo_footer_column_2'])){?>
                            <br /><br /><h6 class="text-wrap"><?=html_entity_decode($settings['text_logo_footer_column_2']->valueconfig,ENT_QUOTES,'utf-8');?></h6>
                        <?php } ?>
                    </a>
                <?php } ?>
                <?php if(isset($settings) && isset($settings['show_developer_footer']) && $settings['show_developer_footer']->valueconfig==1){?>    
                    <h6 class="mt-5"><b>Sviluppo: <a class="text-white" href="{{route('developmentBy')}}">Eros Rivosecchi</a></b></h6>
                <?php } ?>
            </div>
            <hr class="w-100 clearfix d-md-none" />
            <!-- column 3 -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3 text-center">
                <p><a class="text-white" href="{{route('faq')}}">FAQ</a></p>
                <p><a class="text-white" href="{{route('privacyPolicy')}}">Privacy policy</a></p>
                <p><a class="text-white" href="{{route('contacs')}}">Contatti</a></p>
                <p><a class="text-white" href="{{route('serviceEvaluation')}}">Valutazione del servizio</a></p>
            </div>
            <hr class="w-100 clearfix d-md-none" />
            <!-- column 4 -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3 mb-3 text-center">
                <img src="/images/open_data_logo.png" alt="Open Data Logo" style="width: 100%;max-width: 200px;" />
                <?php if(isset($settings) && isset($settings['logo_footer_column_4']) && $settings['logo_footer_column_4']->valueconfig!=''){?>
                    <br /><br /><a class="text-white" target="_blank" href="<?php if(isset($settings) && isset($settings['link_logo_footer_column_4'])){?><?=$settings['link_logo_footer_column_4']->valueconfig;?><?php }else echo '#'; ?>" title="<?php if(isset($settings) && isset($settings['link_logo_footer_column_4'])){?><?=$settings['link_logo_footer_column_4']->valueconfig;?><?php }else echo ''; ?>" style="white-space: nowrap;">
                        <img class="d-inline-block" src="/images/<?=$settings['logo_footer_column_4']->valueconfig;?>" width="250" title="<?php if(isset($settings) && isset($settings['link_logo_footer_column_4'])){?><?=$settings['link_logo_footer_column_4']->valueconfig;?><?php }else echo ''; ?>" alt="<?php if(isset($settings) && isset($settings['link_logo_footer_column_4'])){?><?=$settings['link_logo_footer_column_4']->valueconfig;?><?php }else echo ''; ?>" />                        
                    </a>         
                <?php } ?>       
                <?php if(isset($settings) && isset($settings['last_update_text'])){?>
                    <h5 class="text-white mt-5">Last Update: <?=html_entity_decode($settings['last_update_text']->valueconfig,ENT_QUOTES,'utf-8');?></h5>
                <?php } ?>
            </div>
            <hr class="w-100 clearfix d-md-none" />

            <?php if(isset($settings) && isset($settings['logo_footer_bottom']) && $settings['logo_footer_bottom']->valueconfig!=''){?>
                <div class="col-md-12 mx-auto mt-3 text-center">
                    <div class="d-flex justify-content-center">
                        <a class="text-white" target="_blank" href="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=$settings['link_logo_footer_bottom']->valueconfig;?><?php }else echo '#'; ?>" title="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=$settings['link_logo_footer_bottom']->valueconfig;?><?php }else echo ''; ?>" style="white-space: nowrap;">
                            <img class="d-inline-block" src="/images/<?=$settings['logo_footer_bottom']->valueconfig;?>" width="250" title="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=$settings['link_logo_footer_bottom']->valueconfig;?><?php }else echo ''; ?>" alt="<?php if(isset($settings) && isset($settings['link_logo_footer_bottom'])){?><?=$settings['link_logo_footer_bottom']->valueconfig;?><?php }else echo ''; ?>" />
                        </a>
                    </div>         
                </div>
            <?php } ?>
            <div class="col-12 pb-3">
            </div>
        </div>
    </div>
</footer>