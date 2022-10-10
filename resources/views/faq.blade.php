@extends('layout.base')
@section('content')
<h1 class="text-center mt-5">MICRO EPIDEMIC ONE HEALTH</h1>
<h2 class="text-center mb-5">Frequently Asked Questions</h2>   
<div style="height: 1000px;">
    <div class="container-fluid pb-3 flex-grow-1 d-flex flex-column flex-sm-row overflow-auto" style="height: 90%;">
        <div class="row flex-grow-sm-1 flex-grow-0">
            <aside class="col-sm-3 flex-grow-sm-1 flex-shrink-1 flex-grow-0 pb-sm-0 pb-3"> <!-- class sticky-top removed -->
                <div class="bg-grey-transp border-wheat-2 rounded-3 p-1 h-100 "> <!-- class sticky-top removed -->
                    <div class="p-2 mb-3 border-red rounded-3">
                        <a target="_blank" href="/documents/FAQ_Storie_di_Zoonosi_Ver_2.pdf" class="btn bg-red-pastel fw-bold w-100 hover-color-white">Scarica il PDF delle FAQ</a>    
                    </div>
                    <h3 class="p-2">INDICE</h3>
                    <ul class="nav nav-pills flex-sm-column flex-row mb-auto justify-content-between">
                        <li class="nav-item">
                            <a href="#cosa-sono-storie-di-zoonosi" class="nav-link px-2">
                                <i class="bi bi-house fs-5"></i>
                                <span class="d-sm-inline">COSA SONO LE “STORIE DI ZOONOSI”?</span>
                            </a>
                        </li>
                        <li>
                            <a href="#il-progetto" class="nav-link px-2">
                                <i class="bi bi-speedometer fs-5"></i>
                                <span class="d-sm-inline">Il Progetto Micro Epidemic One Health in due parole</span>
                            </a>
                        </li>
                        <li>
                            <a href="#mission-progetto" class="nav-link px-2"><i class="bi bi-card-text fs-5"></i>
                                <span class="d-sm-inline">Quale mission ha il Progetto MEOH</span> </a>
                        </li>
                        <li>
                            <a href="#quali-lettori" class="nav-link px-2"><i class="bi bi-bricks fs-5"></i>
                                <span class="d-sm-inline">A quali lettori è rivolto il Progetto</span> </a>
                        </li>
                        <li>
                            <a href="#caratteristiche-informatiche" class="nav-link px-2"><i class="bi bi-people fs-5"></i>
                                <span class="d-sm-inline">Caratteristiche informatiche dell’ambiente MEOH</span> </a>
                        </li>
                        <li>
                            <a href="#bibliografia" class="nav-link px-2"><i class="bi bi-people fs-5"></i>
                                <span class="d-sm-inline">Bibliografia</span> </a>
                        </li>
                    </ul>
                </div>
            </aside>
            <main class="col overflow-auto h-100" style="display: block;">
                <div class="bg-grey-transp border-wheat-2 rounded-3 p-3">
                    <h3><b>COSA SONO LE “STORIE DI ZOONOSI”? - WHAT ARE THE “STORIES OF ZOONOSIS”? - ¿CUÁLES SON LAS “HISTORIAS DE ZOONOSIS”?</b></h3>
                    <p>Il Progetto Micro Epidemic One Health in due parole - El Proyecto Micro Epidemic One Health en dos palabras – <i>Raoul Ciappelloni</i></p>
                    <p>Quale mission ha il Progetto MEOH ? Why this MEOH Project ? ¿Qué misión tiene el Proyecto MEOH? – <i>Raoul Ciappelloni, Maria Luisa Marenzoni</i></p>
                    <p>A quali lettori è rivolto il Progetto? MEOH Project target audience & readers - ¿A qué lectores está dirigido el proyecto? – <i>Raoul Ciappelloni, Monica Cagiola</i></p>
                    <h3><b>L’AMBIENTE MEOH - MEOH ENVIRONMENT - EL ENTORNO MEOH</b></h3>
                    <p>Caratteristiche informatiche dell’ambiente MEOH - OVERVIEW OF MEOH environment - Características de TI del entorno MEOH - <i>Eros Rivosecchi</i></p>
                    <h3 class="mb-5"><b>BIBLIOGRAFIA – LITERATURE</b></h3>
                    <hr>
                    <h4 id="cosa-sono-storie-di-zoonosi" class="mt-5 mb-5"><b>COSA SONO LE “STORIE DI ZOONOSI”? IL PROFILO PROGETTUALE - WHAT ARE THE “STORIES OF ZOONOSIS”? THE PROJECT OUTLINE - ¿CUÁLES SON LAS “HISTORIAS DE ZOONOSIS”? EL PERFIL DEL PROYECTO</b></h4>
                    <h4 id="il-progetto"><b>Il Progetto Micro Epidemic One Health in due parole – The Micro Epidemic One Health in just two worlds - El Proyecto Micro Epidemic One Health en dos palabras</b> - <i>Raoul Ciappelloni</i></h4>
                    <p><i>Proposizione iniziale</i>: Una corretta comunicazione scientifica sulle zoonosi, rapidamente aggiornabile, comprensibile, che utilizza la letteratura scientifica e database dedicati, deve poter disporre di uno specifico canale editoriale, accessibile via Web, basato su narrazioni di Casi di Studio.</p>
                    <div class="ps-4">
                        <p><i>Initial Proposition</i>: Correct scientific communication on zoonoses, updated, understandable, based on scientific literature and databases, must have a specific digital editorial channel, based on the narratives of Case Studies and accessible via the Web.</p>
                        <p><i>Proposición inicial</i>: La comunicación científica correcta sobre zoonosis, actualizada, comprensible, basada en literatura científica y bases de datos, debe tener un canal editorial digital específico, basado en las narrativas de Casos de Estudio y accesible vía Web.</p>
                    </div>
                    <p>In Micro Epidemic One Health, i racconti, tratti da esperienze in campo di Testimoni privilegiati quali gli Operatori del Sistema Sanitario ed Agro Alimentare, annotati da esperti e collegati dinamicamente alle risorse bibliografiche peer reviewed sono messi a disposizione degli interessati tramite un Repository Open Access (<a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>), liberamente consultabile dalla Rete.
                    <br/>Tale raccolta di narrazioni sull'epidemiologia delle zoonosi, è così facilmente aggiornabile, adattabile, accessibile, e propone informazioni scientificamente corrette.</p>
                    <div class="ps-4">
                        <p>The stories, drawn from experiences in the field of privileged Witnesses such as Healthcare and Agro-Food System Operators, annotated by experts and dynamically linked to peer reviewed bibliographic resources, are made available to people through an Open Access Repository (<a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>), freely available on the Internet.
                            <br />Such a collection of narratives on zoonoses epidemiology is easily updated, adaptable, accessible, and offers scientifically correct information.</p>
                        <p>En Micro Epidemic One Health, las historias, extraídas de las experiencias en el campo de Testigos privilegiados como los operadores de sistemas de salud y agroalimentarios, anotadas por expertos y vinculadas dinámicamente a recursos bibliográficos revisados por pares, se ponen a disposición de las partes interesadas a través de un repositorio de acceso abierto. (<a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>), disponible gratuitamente en Internet.
                            <br />Esta colección de narrativas sobre la epidemiología de las zoonosis es, por lo tanto, fácilmente actualizada, adaptable, accesible y ofrece información científicamente correcta.</p>
                    </div>
                    <p>La documentazione risponde alle esigenze di diversi lettori (veterinari, medici, operatori sanitari, studenti, società civile) ed è organizzata per comunicare in modo scientificamente appropriato:
                        <ul>
                            <li>Trattamento / limitazione delle Zoonosi;</li>
                            <li>Comportamenti e situazioni predisponenti di queste malattie infettive;</li>
                            <li>Riconoscimento dei fattori di rischio;</li>
                            <li>Obblighi di legge correlati.</li>
                        </ul>
                    </p>
                    <div class="ps-4">
                        <p>The documentation meets the needs of different readers (veterinarians, doctors, practitioners, students, civil society) and is organized to communicate the zoonoses in a proper scientific way:
                        <ul>
                            <li>Treatment / limitation of zoonoses;</li>
                            <li>Behaviors and predisposing situations, of these infectious diseases;</li>
                            <li>Recognition of risk factors;</li>
                            <li>Related legal obligations.</li>
                        </ul>
                        </p>
                    </div>
                    <div class="ps-4">
                        <p>La documentación responde a las necesidades de diferentes lectores (veterinarios, médicos, profesionales de la salud, estudiantes, sociedad civil) y está organizada para comunicar de manera científicamente adecuada:
                        <ul>
                            <li>Tratamiento / limitación de Zoonosis;</li>
                            <li>Comportamientos y situaciones predisponentes de estas enfermedades;</li>
                            <li>Reconocimiento de factores de riesgo;</li>
                            <li>Obligaciones legales relacionadas.</li>
                        </ul>
                        </p>
                    </div>
                    <p>Il Progetto MEOH è legato all’esperienza di editoria elettronica partecipativa collegata ad attività di ricerca e di formazione dell’e-Journal SPVet.it dell’Istituto Zooprofilattico Sperimentale dell’Umbria e delle Marche e dall’attività didattica e di ricerca del Dipartimento di Medicina Veterinaria dell’Università degli Studi di Perugia.</p>
                    <div class="ps-4">
                        <p>MEOH Project is basically related to the participatory electronic publishing experience, linked to the research and training activities of the SPVet.it e-Journal of the Experimental Zooprophylactic Institute of Umbria and Marche and to teaching and research activity of the Department of Medicine. Veterinary, University of Perugia (Italy).</p>
                        <p>El Proyecto MEOH está vinculado a la experiencia de publicación electrónica participativa vinculada a las actividades de investigación y formación del SPVet.it e-Journal del Instituto Zooprofiláctico Experimental de Umbria y Marche y a la actividad docente e investigadora del Departamento de Medicina Veterinaria de la Universidad de Perugia (Italie).</p>
                    </div>
                    <p>Le informazioni (background) sull’iniziativa MEOH e la documentazione elaborata sono accessibili in Italiano all’indirizzo: <a href="https://spvet.it/microepidemic.html">https://spvet.it/microepidemic.html</a>,
                        <br />La sintesi (excerpta) del progetto è disponibile all’indirizzo:
                        <a href="https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf">https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf</a>
                        <br />La descrizione in Inglese è accessibile all’indirizzo:
                        <a href="https://spvet.it/archivio/numero-130/edi130.html">https://spvet.it/archivio/numero-130/edi130.html</a>
                    </p>
                    <div class="ps-4 mb-5">
                        <p>Background information on the MEOH initiative and the documentation developed is accessible in Italian at the address: <a href="https://spvet.it/microepidemic.html">https://spvet.it/microepidemic.html</a>
                            <br />The synthesis (excerpta) of the Project is available at:
                            <a href="https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf">https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf</a>
                            <br />The description in English is accessible at the address:
                            <a href="https://spvet.it/archivio/numero-130/edi130.html">https://spvet.it/archivio/numero-130/edi130.html</a>
                        </p>
                        <p>La información (antecedentes) sobre la iniciativa MEOH y la documentación preparada están disponibles en italiano en la dirección: <a href="https://spvet.it/microepidemic.html">https://spvet.it/microepidemic.html</a>
                            <br />El resumen (extracto) del proyecto está disponible en:
                            <a href="https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf">https://spvet.it/microepidemic/documenti/Estratto_Progetto_MicroEpidemic_2021-2022.pdf</a>
                            <br />La descripción en inglés está disponible en la dirección:
                            <a href="https://spvet.it/archivio/numero-130/edi130.html">https://spvet.it/archivio/numero-130/edi130.html</a> 
                        </p>
                    </div>
                    <h4 id="mission-progetto" class="mt-5"><b>Quale mission ha il Progetto MEOH ? Why this MEOH Project ? ¿Qué misión tiene el Proyecto MEOH?</b> - <i>Raoul Ciappelloni, Maria Luisa Marenzoni</i></h4>
                    <p>Il Progetto Micro Epidemic One Health ha lo scopo di creare un canale editoriale collaborativo basato su un repository narrativo Open Access dedicato alle zoonosis. Le narrazioni sono raccolte da testimoni privilegiati o anche attraverso crowdsourcing/interviste, coinvolgendo vari soggetti anche in ambito Citizen Science, nel contest One Health .
                        <br />Lo storytelling caratterizzato dall'introduzione di appropriate licenze Copyright Creative Commons (principalmente CCBY / CCBYSA) che garantiranno una diffusione di questa letteratura a vantaggio delle Aziende (startup; spinoff) del settore biomedico ed editoriale.
                    </p>
                    <div class="ps-4">
                        <p>The Micro Epidemic One Health Project aims to create a new collaborative editorial channel based on a narrative Open Access repository on zoonosis collected by privileged witnesses directly or even through crowdsourcing/interview, involving various subjects also in the Citizen Science sphere, in the One Health contest.
                            <br />The storytelling is characterized the introduction of appropriate Copyright Creative Commons licenses (Mainly CCBY / CCBYSA) which will ensure a dissemination of this literature in the interest of biomedical and publishing startups.</p>
                        <p>El proyecto Micro Epidemic One Health tiene como objetivo crear un canal editorial colaborativo basado en un repositorio narrativo de acceso abierto dedicado a la zoonosis. Las narraciones son recogidas por testigos privilegiados o incluso a través de crowdsourcing/entrevistas, involucrando diversos temas también en el campo de la Ciencia Ciudadana, en el concurso One Health.
                            <br />Storytelling caracterizado por la introducción de las correspondientes licencias Copyright Creative Commons (principalmente CCBY/CCBYSA) que garantizarán la difusión de esta literatura en beneficio de las empresas (startups, spinoffs) del sector biomédico y editorial.</p>
                    </div>
                    <p>Gli obiettivi più rilevanti del progetto MEOH sono mostrare come:
                    <ul>
                        <li>Raccogliere e diffondere informazioni e dati operativi per il contrasto delle zoonosi utili per le comunità locali anche come patrimonio culturale;</li>
                        <li>Promuovere l'informazione e la cooperazione fra cittadini, Citizen scientists, ricercatori ed operatrori del Servizio Sanitario utili per iniziative nazionali e regionali;</li>
                        <li>Produrre un flusso informative, documenti politici, relazioni annuali e simili utili per le attività di divulgazione e formative;</li>
                        <li>Produrre un archivio online di zoonosi da aggiornare regolarmente.</li>
                    </ul>
                    </p>
                    <div class="ps-4">
                        <p>The most relevant objectives of the MEOH project are:
                            <ul>
                                <li>Collect and disseminate information and operational data for the fight against zoonoses, relevant to local communities also for the cultural heritage;</li>
                                <li>Promote information and cooperation between citizens, Citizen scientists, researchers and operators of the Health Service useful for national and regional initiatives;</li>
                                <li>Produce a flow of information, political documents, annual reports and the like useful for dissemination and training activities;</li>
                                <li>Produce an online Open Access archive of zoonoses to be updated regularly.</li>
                            </ul>
                        </p>
                        <p>Los objetivos más relevantes del proyecto MEOH son mostrar cómo:
                            <ul>
                                <li>Recopilar y difundir información y datos operativos para la lucha contra las zoonosis útiles para las comunidades locales también como patrimonio cultural;</li>
                                <li>Promover la información y la cooperación entre ciudadanos, ciudadanos científicos, investigadores y operadores del Servicio de Salud útil para iniciativas nacionales y regionales;</li>
                                <li>Producir un flujo de información, documentos políticos, informes anuales y similares útiles para actividades de difusión y capacitación;</li>
                                <li>Producir un archivo en línea de zoonosis para ser actualizado periódicamente.</li>
                            </ul>
                        </p>
                    </div>
                    <p>In pratica, il Progetto MEOH permette di interagire con uno spazio informativo generalista (TV, giornale, blogsphere) al fine di proporre uno storytelling digitale, adatto a comunicare efficacemente le attività del progetto "Storie di zoonosi" ad un vasto pubblico. Attraverso il Progetto sarà possibile realizzare campagne di sensibilizzazione sulla tutela della salute in relazione alle malattie che si trasmettono dagli animali all'uomo e viceversa, diffondere iniziative su rilevanti opportunità formative a livello internazionale, nazionale e regionale. Last but not tea, diffondere le conoscenze, le migliori pratiche e i suggerimenti raccolti sulle zoonosi alla più ampia comunità di stakeholder nel servizio sanitario e nelle scuole.</p>
                    <div class="ps-4">
                        <p>In practical terms, the MEOH Project allow to interact with a generalist information space (TV, newspaper, blogsphere) in order to propose a digital storytelling, suitable for effectively communicating the activities of the "Stories of zoonoses" Project to a wide audience. Through the Project, it will be possible to carry out awareness campaigns on health protection in relation to diseases that are transmitted from animals to humans and vice versa, disseminate initiatives on relevant training opportunities at an international, national and regional level. Last but non teast, disseminate knowledge, best practices and suggestions collected on zoonoses to the wider community of stakeholders in the health service and schools.</p>
                        <p>En la práctica, el Proyecto MEOH le permite interactuar con un espacio de información generalista (TV, periódico, blogsfera) para proponer una narración digital, adecuada para comunicar de manera efectiva las actividades del proyecto "Historias de zoonosis" a una amplia audiencia. A través del Proyecto será posible realizar campañas de concientización sobre protección de la salud en relación a enfermedades que se transmiten de animales a humanos y viceversa, difundir iniciativas sobre oportunidades de capacitación relevantes a nivel internacional, nacional y regional. Por último, pero no té, difundir el conocimiento, las mejores prácticas y las sugerencias recopiladas sobre zoonosis a la comunidad más amplia de partes interesadas en el servicio de salud y las escuelas.</p>
                    </div>
                    <h4 id="quali-lettori"><b>A quali lettori è rivolto il Progetto MEOH ? MEOH Project target audience & readers - ¿A qué lectores está dirigido el Proyecto MEOH ?</b> – <i>Raoul Ciappelloni, Monica Cagiola</i></h4>
                    <p>Gli utenti / lettori che possono trarre vantaggio dalla documentazione online di Micro Epidemic One Health sono essenzialmente: i professionisti del Servizio Sanitario Nazionale che operano in ambito medico, veterinario, in sanità pubblica e sicurezza alimentare, in produzione e gestione alimenti e in qualità ambientale; studenti di materie biomediche, allevatori e agricoltori che devono essere informati sui fattori di rischio che impattano sulla salute globale che interessano trasversalmente l’ambito umano, animale e l’ecosistema ambientale.
                        <br />Diffondere storie di zoonosi per educare alla salute per prevenire e contrastare la diffusione di agenti patogeni è la principale <i>mission</i> del progetto, soprattutto trovando soluzioni innovative interdisciplinari per rispondere alle sfide globali e creare un sistema di standardizzazione per la raccolta e l’organizzazione dei dati.
                        <br />In quanto storytelling tale sistema di comunicazione diretta e trasparente è rivolto a qualsiasi utente lettore, anche non appartenente al Servizio Sanitario. Esso favorisce le conoscenze multidisciplinari e serve a chiarire in maniera semplice le dinamiche che devono essere conosciute per affrontare le complesse problematiche che si possono riscontrare in ambito della Sanità Pubblica e della Sicurezza Alimentare. Il lettore “informato MEOH” sarà un individuo più attento ai temi della salute e potrà essere di ausilio nella segnalazione di fenomeni insoliti portando alla scoperta precoce di focolai, aiutando così a prevenire successive epidemie o pandemie.
                    </p>
                    <div class="ps-4 mb-5">
                        <p>The users / readers who can benefit from Micro Epidemic One Health's online documentation are essentially: National Health Service Practitioners of who work in the medical, veterinary, public health and food safety sectors, in food production, food management and in environmental quality; students, especially of biomedical disciplines, breeders and farmers who must be informed about threat that impact on global health that transversally affect: humans, animals and the ecosystem.
                            <br />Disseminating zoonoses story to educate about health to counteract the spread of pathogens is the main <i>mission</i> of this project, especially by finding innovative solutions to respond to global challenges and create a standard system for the collection and organization of data.
                            <br />As storytelling, this direct and transparent communication system is applicable to any reader, even if not belonging to the Health Services. It favors the dissemination of multidisciplinary knowledge and serves to clarify in a simple way the dynamics that must be known to address the complex problems that can be encountered in the field of Public Health and Food Safety. The “MEOH informed” reader will be an individual who is aware od the zoonoses issues and may be of help in reporting unusual phenomena leading to the early discovery of outbreaks, thus helping to prevent subsequent epidemics or pandemics.
                        </p>
                        <p>Los usuarios/lectores que pueden beneficiarse de la documentación en línea de Micro Epidemic One Health son esencialmente: los profesionales del Servicio Nacional de Salud que trabajan en los campos médico, veterinario, salud pública y seguridad alimentaria, producción y gestión de alimentos y calidad ambiental; estudiantes de materias biomédicas, criadores y agricultores quienes deben estar informados sobre los factores de riesgo que impactan en la salud global que afectan transversalmente al ecosistema humano, animal y ambiental.
                            <br />Difundir historias de zoonosis para educar sobre la salud para prevenir y contrarrestar la propagación de patógenos es la <i>misión</i> principal del proyecto, especialmente al encontrar soluciones interdisciplinarias innovadoras para responder a los desafíos globales y crear un sistema de estandarización para la recopilación y organización de datos.
                            <br />Como storytelling, este sistema de comunicación directo y transparente está dirigido a cualquier usuario lector, aunque no pertenezca al Servicio de Salud. Favorece el conocimiento multidisciplinar y sirve para esclarecer de forma sencilla la dinámica que se debe conocer para afrontar los complejos problemas que se pueden encontrar en el campo de la Salud Pública y la Seguridad Alimentaria. El lector “informado por MEOH” será una persona más atenta a los problemas de salud y puede ser de ayuda para informar sobre fenómenos inusuales que conduzcan al descubrimiento temprano de brotes, lo que ayudará a prevenir epidemias o pandemias posteriores.
                        </p>
                    </div>
                    <h4 class="mt-4"><b>L’AMBIENTE MEOH - MEOH ENVIRONMENT - EL ENTORNO MEOH</b></h4>
                    <h4 id="caratteristiche-informatiche"><b>Caratteristiche informatiche dell’ambiente MEOH - OVERVIEW OF MEOH environment - Características de TI del entorno MEOH</b> - <i>Eros Rivosecchi</i></h4>
                    <p>La Piattaforma Micro Epidemic One Health (MEOH), è stata realizzata in modo che sia possibile per un Editorial Board di ricercatori e professionsti del Sistema sanitario, gestire in modo completo l’immissione e revisione delle storie di zoonosi.
                        <br />Questa piattaforma, raggiungibile al link <a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>, è sviluppata in gran parte utilizzando il linguaggio di programmazione PHP e con tecnologia web full-responsive. Utilizza Laravel come framework di sviluppo così da garantire un'architettura MVC (Model View Controller) ed utilizza un database relazionale ad oggetti PostgreSQL open source.
                        <br />Il Content Management System permette a chiunque di gestire il lavoro di pubblicazione di questa antologia di storie di zoonosi, senza la necessità di particolari conoscenze di programmazione Web.
                        <br />L'applicazione è divisa in due parti che possono essere viste come: un front-office e un back-office. Nel front office vengono mostrate ai lettori le narrazioni recensite dal comitato scientifico. Le integrazioni e le annotazioni rilasciate dagli utenti che interagiscono con il Sistema (previa registrazione), nonché le recensioni e tutto il materiale multimediale raccolto, sono messe a disposizione di tutti gli interessati.
                        <br />Risulta anche possibile ricercare argomenti nelle narrazioni pubblicate attraverso un motore di ricerca interno.
                        <br />Attraverso il back-office, accessibile solo dai membri della Redazione tramite autenticazione, è possibile gestire integralmente tutte le narrazioni e le annotazioni rilasciate dal pubblico.
                    </p>
                    <div class="ps-4">
                        <p>The Micro Epidemic One Health (MEOH) Platform has been created in such a way that it is possible, for an Editorial Board of researchers and professionals of the Health System, to manage the take over and review of zoonosis stories.
                            <br />This platform (accessible at the link <a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>), was developed largely using the PHP programming language and with full-responsive Web technology. It uses Laravel as a development framework in order to guarantee an MVC (Model View Controller) architecture and uses an Open Source PostgreSQL object relational database.
                            <br />The Content Management System makes it possible for anybody to manage the publishing work for this anthology of zoonoses stories, without the need of special Web programming knowledge. The application is divided into two parts which can be seen as: a front-office and a back-office. In the front office, the narratives reviewed by the scientific board are shown to the readers. The integrations and annotations released by the users who interact wuth the System (after registration) as well as reviews and all the multimedia material collected, are made freely available for anybody concerned.
                            <br />It is also possible to search for topics in published narratives through an internal search engine.
                            <br />Through the back-office, only accessible by members of the Editorial Board through authentication, it is possible to fully manage all the narratives and annotations released by the public.
                        </p>
                        <p>Esta plataforma, accesible en el enlace <a href="https://storiedizoonosi.spvet.it">https://storiedizoonosi.spvet.it</a>, se desarrolla en gran medida utilizando el lenguaje de programación PHP y con tecnología web full-responsive. Utiliza Laravel como marco de desarrollo para garantizar una arquitectura MVC (Model View Controller) y utiliza una base de datos relacional de objetos PostgreSQL de código abierto.
                            <br />El Gestor de Contenidos permite que cualquiera pueda gestionar el trabajo de publicación de esta antología de relatos de zoonosis, sin necesidad de conocimientos especiales de programación Web.
                            <br />La aplicación se divide en dos partes que se pueden ver como: un front-office y un back-office. Las narraciones revisadas por el comité científico se muestran a los lectores en la oficina principal. Las adiciones y anotaciones emitidas por los usuarios que interactúan con el Sistema (previo registro), así como las reseñas y todo el material multimedia recopilado, se ponen a disposición de todos los interesados.
                            <br />También es posible buscar temas en las narrativas publicadas a través de un buscador interno.
                            <br />A través del back-office, al que solo pueden acceder los miembros de la redacción mediante autenticación, es posible gestionar completamente todas las narraciones y anotaciones publicadas por el público.
                        </p>
                    </div>
                    <h4 id="bibliografia"><b>BIBLIOGRAFIA - LITERATURE</b></h4>
                    <p>
                    <ul>
                        <li class="mb-3">Angelico G., Pieralisi S., Canonico C., Maiolatesi D., Giulia Talevi G., Nardi S., Di Lullo S., Rocchegiani E., Ottaviani D. (2022). Monografia: Potenzialità e applicazioni dei Bdellovibrio and like organisms (BALOs) nel settore biotecnologico e della filiera alimentare. Sanità Pubblica Veterinaria, n. 131, Febbraio.</li>
                        <li class="mb-3">Ciappelloni R., Marenzoni M. L., Grilli L., Rivosecchi E., Duranti A., Maresca C., Torlone M. P., Cagiola M. (2022). Open repository for transmedia storytelling on zoonoses: an effective method to share scientific knowledge with health practitioners and civil society stakeholders. Sanità Pubblica Veterinaria, n. 130, Febbraio.</li>
                        <li class="mb-3">Ciappelloni R. (2022). Bombe climatiche e stile di vita sostenibile - Carbon bombs and sustainable living. Sanità Pubblica Veterinaria, n. 130, Febbraio.</li>
                        <li class="mb-3">Ciappelloni R., Duranti A., Maresca C., Marenzoni M. L., Grilli L., Lepri E., Capuccella M., Paola Torlone M. P. (2021). Comunicazione scientifica ampliata sulle zoonosi: storytelling visuale, multicanalità, Open Data in un progetto di One Health - Expanded scientific communication on zoonosis: multi-channel visual storytelling, and Open Data for One Health project. Sanità Pubblica Veterinaria, n. 124, Febbraio.</li>
                        <li class="mb-3">Di Virgilio F. (2022). La medicina narrativa come strumento formativo e di divulgazione scientifica: analisi di un caso di salmonellosi in un allevamento familiare, con gravi conseguenze sulla salute umana - Narrative Medicine as a scientific training and dissemination tool: Analysis of a case of salmonellosis in a family farm, with serious consequences for human health. Sanità Pubblica Veterinaria, n. 130, Febbraio.</li>
                        <li class="mb-3">Glukhovetska I. (2020). L'uso dei videogame in Medicina Veterinaria per facilitare l'apprendimento dei concetti di malattie infettive - Use of videogames in Veterinary Medicine to make the learning of infectious disease concepts easier. Sanità Pubblica Veterinaria, n. 122, Ottobre.</li>
                        <li>Palmerini G. (2021). Medicina Narrativa Veterinaria: il caso di West Caucasian Bat Lyssavirus di Arezzo, una nuova malattia emergente? - Narrative Veterinary Medicine: the case of West Caucasian Bat Lyssavirus in Arezzo, a new emerging disease? Sanità Pubblica Veterinaria, n. 128, Ottobre.</li>
                    </ul>
                    </p>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection


