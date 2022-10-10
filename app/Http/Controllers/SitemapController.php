<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
  
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request=$request;
        $this->mod_log=new LogPersonal($request);
    }
        
    
    public function generatesitemap(Request $request){
        //priority from 0.1 > 1
        Sitemap::create()
            //homepage
            ->add(Url::create('/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(1))
                
                
            //ilprogetto
            ->add(Url::create('/ilprogetto')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.3))
                //inserimento storia
            ->add(Url::create('/crowdsourcing/submission')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3))
                //ricerca
            ->add(Url::create('/ricerca')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3))
                //login
            ->add(Url::create('/login')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3))
                //registrazione
            ->add(Url::create('/registrazione')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.3))
                //privacy-policy
            ->add(Url::create('/privacy-policy')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1))
                //developmentby
            ->add(Url::create('/developmentby')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.1))
                //comingsoon
            ->add(Url::create('/comingsoon')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_NEVER)
            ->setPriority(0.1))
                //faq
            ->add(Url::create('/faq')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.5))
                
                
            //elencostorie
            ->add(Url::create('/elencostorie')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.9))
            //elencostorie listeria
            ->add(Url::create('/elencostorie/listeria')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                //elencostorie lyssavirus
            ->add(Url::create('/elencostorie/lyssavirus')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                //elencostorie salmonella
            ->add(Url::create('/elencostorie/salmonella')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                //elencostorie scabbia
            ->add(Url::create('/elencostorie/scabbia')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                //elencostorie tubercolosi
            ->add(Url::create('/elencostorie/tubercolosi')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                //elencostorie brucella
            ->add(Url::create('/elencostorie/brucella')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5))
                
                
                //analisi-di-un-caso-di-salmonellosi-in-un-allevamento-familiare-con-gravi-conseguenze-sulla-salute-umana
            ->add(Url::create('/storia/analisi-di-un-caso-di-salmonellosi-in-un-allevamento-familiare-con-gravi-conseguenze-sulla-salute-umana')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //il-caso-di-west-caucasian-bat-lyssavirus-di-arezzo-una-nuova-malattia-emergente
            ->add(Url::create('/storia/il-caso-di-west-caucasian-bat-lyssavirus-di-arezzo-una-nuova-malattia-emergente')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //rogna-sarcoptica-del-cane-sarcoptes-scabiei-canis
            ->add(Url::create('/storia/rogna-sarcoptica-del-cane-sarcoptes-scabiei-canis')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //un-complesso-caso-di-tubercolosi-mycobacterium-bovis-in-un-allevamento-bovino-allo-stato-semibrado
            ->add(Url::create('/storia/un-complesso-caso-di-tubercolosi-mycobacterium-bovis-in-un-allevamento-bovino-allo-stato-semibrado')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //infezione-mycobacterium-tuberculosis-trasmessa-dalluomo-ad-una-elefantessa-un-caso-di-zoonosi-inversa
            ->add(Url::create('/storia/infezione-mycobacterium-tuberculosis-trasmessa-dalluomo-ad-una-elefantessa-un-caso-di-zoonosi-inversa')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //un-caso-di-listeriosi-negli-insaccati
            ->add(Url::create('/storia/un-caso-di-listeriosi-negli-insaccati')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))
                //sorveglianza-sanitaria-sulla-brucellosi-brucella-abortus-brucella-ovis-in-un-vasto-terrritorio-montano-collinare
            ->add(Url::create('/storia/sorveglianza-sanitaria-sulla-brucellosi-brucella-abortus-brucella-ovis-in-un-vasto-terrritorio-montano-collinare')
            ->setLastModificationDate(Carbon::tomorrow()->subDay())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8))



       ->writeToFile(public_path('sitemap.xml'));
        
        //echo asset('sitemap.xml');
    }
    
    public function show(){
        echo asset('sitemap.xml');
    }
  
}
