@extends('layouts.chat')

@section('title')
 · Preguntes freqüents
@endsection

@section('content')
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preguntes Freqüents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Preguntes Freqüents</h2>
    <div class="accordion" id="faqAccordion">
        <!-- Pregunta 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Com em registro i inscric el meu equip?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Pots registrar-te fàcilment a través del formulari de registre de la plataforma. Durant el procés, t'inscriuràs com a equip.
                </div>
            </div>
        </div>

        <!-- Pregunta 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Com puc crear un esdeveniment o afegir-lo a una lliga?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Un cop iniciada la sessió, trobaràs al menú l'opció de crear una lliga. El formulari de creació inclou camps per a nom, descripció, data, ubicació, preu, aforament, entre d'altres.
                </div>
            </div>
        </div>

        <!-- Pregunta 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Com es visualitza la classificació de la lliga?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    La classificació de cada lliga es genera automàticament a partir dels resultats dels partits. Podràs consultar-la en una secció al menú, on es mostra el rànquing amb punts, guanys, empatats i perduts de cada equip.
                </div>
            </div>
        </div>

        <!-- Pregunta 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Puc modificar o cancel·lar un esdeveniment un cop creat?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Sí, com a organitzador tens la possibilitat d'editar o cancel·lar qualsevol esdeveniment que hagis creat. Els canvis es reflectiran immediatament en la plataforma i s'enviaran notificacions als participants afectats.
                </div>
            </div>
        </div>

        <!-- Pregunta 5 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Com rebré notificacions sobre els esdeveniments?
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    La plataforma t'enviarà notificacions mitjançant un xat que hi ha situat al menú, per informar-te de novetats com canvis d'horari, resultats, cancel·lacions o recordatoris de propers partits.
                </div>
            </div>
        </div>

        <!-- Pregunta 6 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    Què faig si trobo algun error o problema tècnic mentre utilitzo la plataforma?
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Si detectes qualsevol error, pots contactar amb el servei d'atenció al client. El nostre equip tècnic estarà encantat d'ajudar-te i resoldre qualsevol incidència.
                </div>
            </div>
        </div>

        <!-- Pregunta 7 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeven">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                    Quina seguretat ofereix la plataforma per a les meves dades?
                </button>
            </h2>
            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    La seguretat és una prioritat. Utilitzem tecnologies avançades d'autenticació i encriptació per protegir les teves dades. A més, el sistema de gestió de la base de dades assegura la integritat i confidencialitat de tota la informació registrada.
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>

@endsection
