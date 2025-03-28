<?php

namespace App\Http\Controllers;

use App\Models\Lliga;
use Illuminate\Http\Request;
use App\Models\Equip;
use App\Models\UbicacioCamp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Notificacions;

class LligaController extends Controller
{
    public function index()
    {
        return view('lligues.index');
    }

    public function getLligues()
    {
        return response()->json(Lliga::all());
    }

    public function getLligaInfo($id)
    {
        $lliga = Lliga::with([
            'equips.ubicacioCamp',  // esto ahora funcionará
            'partits.estat',
            'partits.ubicacio'
        ])->find($id);

        if (!$lliga) {
            return response()->json(['error' => 'Lliga no trobada'], 404);
        }

        return response()->json($lliga);
    }

    public function store(Request $request)
    {
        // Validar les dades entrant del formulari (lloc_lliga eliminat)
        $validated = $request->validate([
            'nom_lliga' => 'required|string|max:45',
            // 'lloc_lliga' => 'required|string|max:255',  // Eliminat
            'nro_equips_participants' => 'required|integer|min:2',
            'preu_entrada' => 'required|integer|min:0',
            'url_imagen' => 'required|image',
            'data_inici' => 'required|date',
            'esport' => 'required|string|max:255',
            'persones_equip' => 'required|integer|min:1',
        ]);

        // Obtenir l'id de l'usuari (suponiendo que Auth::user() està disponible)
        $userId = auth()->user()->id_usuari;

        // Comprovar si l'usuari té la ubicació configurada
        $ubicacio = UbicacioCamp::where('equip_usuari_id_usuari', $userId)->first();
        if (!$ubicacio) {
            return redirect()->back()->with('error', 'No tens la ubicació configurada. Configura-la abans de crear una lliga.');
        }

        // Processar la pujada de la imatge
        $imagePath = $request->file('url_imagen')->store('lligues_imagenes', 'public');

        // Calcular data final: 2 setmanes per cada equip participant
        $dataInici = Carbon::parse($validated['data_inici']);
        $semanasAfegir = $validated['nro_equips_participants'] * 2;
        $dataFi = $dataInici->copy()->addWeeks($semanasAfegir);

        // Crear la nova lliga amb participants_actualment a 0
        $lliga = Lliga::create([
            'nom_lliga' => $validated['nom_lliga'],
            'lloc_lliga' => $ubicacio->nom_ubicacio, // s'obté de l'ubicació del usuari
            'nro_equips_participants' => $validated['nro_equips_participants'],
            'preu_entrada' => $validated['preu_entrada'],
            'url_imagen' => $imagePath,
            'data_inici' => $dataInici,
            'data_fi' => $dataFi,
            'participants_actualment' => 0,
            'esport' => $validated['esport'],
            'persones_equip' => $validated['persones_equip'],
            // Si necessites generar un codi lliga, pots afegir la lògica aquí
        ]);

        // Crear una notificació després de crear la lliga
        Notificacions::create([
            'missatge_notificacio' => 'Se ha creado una nueva liga: ' . $lliga->nom_lliga,
            'equip_usuari_id_usuari' => $userId,
            'timestamp' => now(),
        ]);

        return redirect()->route('lligues.index')->with('success', 'Lliga creada correctament!');
    }

    /**
     * Retorna una estructura JSON amb tota la informació requerida per una lliga.
     *
     * Endpoint: GET /api/lliga/{id}/detall
     */
    public function getLligaDetallada($id)
    {
        $lliga = Lliga::with([
            'equips.ubicacioCamp',
            'equips.diaHoras',
            'equips.partitsJugats.estat',
            'equips.partitsJugats.ubicacio',
            'equips.partitsJugats.equips'  // per calcular el rival
        ])->find($id);

        if (!$lliga) {
            return response()->json(['error' => 'Lliga no trobada'], 404);
        }

        $result = [
            'lliga' => [
                'id' => $lliga->id_lliga,
                'nom' => $lliga->nom_lliga,
                'equips' => []
            ]
        ];

        foreach ($lliga->equips as $equip) {
            // Disponibilitat horària (convertim cada DiaHora a array)
            $disponibilitat = $equip->diaHoras->map(function($dh) {
                return [
                    'dia' => $dh->dia,
                    'hora' => $dh->hora,
                ];
            })->toArray();

            // Partits jugats: recorrer els partits associats a l'equip
            $partitsJugats = [];
            foreach ($equip->partitsJugats as $partit) {
                // Calcular "rival": si el partit té exactament 2 equips, agafa el nom de l'altre
                $rival = null;
                if ($partit->equips->count() == 2) {
                    foreach ($partit->equips as $otherEquip) {
                        if ($otherEquip->usuari_id_usuari != $equip->usuari_id_usuari) {
                            $rival = $otherEquip->nom_equip;
                            break;
                        }
                    }
                }
                $partitsJugats[] = [
                    'rival'     => $rival,
                    'gols'      => $partit->pivot->gols,
                    'ubicacio'  => $partit->ubicacio ? $partit->ubicacio->nom_ubicacio : null,
                    'estat'     => $partit->estat ? $partit->estat->nom_estat : null,
                ];
            }

            $result['lliga']['equips'][] = [
                'nom' => $equip->nom_equip,
                'ubicacio' => $equip->ubicacioCamp ? $equip->ubicacioCamp->nom_ubicacio : null,
                'disponibilitat' => $disponibilitat,
                'partits_jugats' => $partitsJugats
            ];
        }

        return response()->json($result);
    }
}
