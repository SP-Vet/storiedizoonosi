<div class="container">
    <div>
        <a class="font-white" href="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo '#'; ?>" title="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo 'Storie di Zoonosi'; ?>" style="white-space: nowrap;">
            <?php if(isset($settings) && isset($settings['image_one_home'])){?>
                <img class="d-inline-block" src="/images/<?=$settings['image_one_home']->valueconfig;?>" style="width:100%;max-width: 200px;height: auto;max-height: 50px;" title="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo 'Storie di Zoonosi'; ?>" alt="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo 'Storie di Zoonosi'; ?>" />
            <?php } ?>
            <?php if(isset($settings) && isset($settings['image_two_home'])){?>
                <img class="d-inline-block  ms-3" src="/images/<?=$settings['image_two_home']->valueconfig;?>" style="width:100%;max-width: 30px;height: auto;max-height: 50px;" title="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo 'Storie di Zoonosi'; ?>" alt="<?php if(isset($settings) && isset($settings['link_images_home'])){?><?=$settings['link_images_home']->valueconfig;?><?php }else echo 'Storie di Zoonosi'; ?>" />
            <?php } ?>
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav  mt-2 mt-lg-0">
          <!-- inserire classe active per voce in grasetto --> 
          <li class="nav-item"><a class="nav-link evidenzia" href="/" title="Home">Home</a></li>
          <li class="nav-item btn-newstory me-2"><a class="btn nav-link" style="color:  #00007f !important;font-weight: 600 !important;" href="{{route('getReportStory')}}" title="Segnalaci una storia">Inserimento di una nuova storia di zoonosi</a></li>
          <li class="nav-item container-logreg me-2"><a class="nav-link evidenzia d-inline btn btn-login" href="{{route('searchStories')}}" title="Ricerca">Motore di Ricerca</a></li>
          <li class="nav-item container-logreg">
              <a class="nav-link evidenzia d-inline btn btn-login" href="/login" title="Log in"><i class="fa fa-sign-in pe-2"></i>Log in</a><a class="nav-link evidenzia d-inline btn-registrati" href="{{route('getRegistration')}}" title="Registrati"><i class="fa fa-user-plus pe-2"></i>Registrati</a>
          </li>
        </ul>
    </div>
</div>