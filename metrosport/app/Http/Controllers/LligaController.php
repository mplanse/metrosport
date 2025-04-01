<?php

namespace App\Http\Controllers;

use App\Models\Lliga;
use Illuminate\Http\Request;
use App\Models\Equip;
use App\Models\UbicacioCamp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Notificacions;
use Illuminate\Support\Facades\Http;

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
            'equips.diaHoras'
            // Eliminamos las relaciones de partidos jugados
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

            $result['lliga']['equips'][] = [
                'nom' => $equip->nom_equip,
                'ubicacio' => $equip->ubicacioCamp ? $equip->ubicacioCamp->nom_ubicacio : null,
                'disponibilitat' => $disponibilitat
                // Eliminamos 'partits_jugats'
            ];
        }

        return response()->json($result);
    }

    public function callOpenRouter($id)
    {
        $detailResponse = $this->getLligaDetallada($id)->getData(true);

        // Contar equipos para calcular jornadas requeridas
        $equipos = count($detailResponse['lliga']['equips'] ?? []);
        $totalPartidos = $equipos * ($equipos - 1); // ida y vuelta
        $jornadasNecesarias = ceil($totalPartidos / ($equipos / 2)); // 4 partidos por jornada

        $prompt = <<<EOT
Eres un generador experto de calendarios deportivos para torneos de liga (doble round-robin). Recibirás información de una liga con $equipos equipos.

REQUISITOS CRÍTICOS:
1. Cada equipo debe enfrentarse a todos los demás exactamente DOS veces (ida y vuelta): una como LOCAL y otra como VISITANTE.
2. Solo se puede asignar un partido si AMBOS equipos están disponibles en ese día y hora.
3. El campo de juego debe ser la UBICACIÓN del equipo local.
4. Cada jornada debe tener exactamente ${equipos}/2 partidos (ej: 4 si hay 8 equipos).
5. Debes generar EXACTAMENTE $jornadasNecesarias jornadas, enumeradas de 1 a $jornadasNecesarias.

FORMATO ESTRICTO:
{
  "calendario": [
    {
      "jornada": 1,
      "partidos": [
        {
          "equipo_local": "nombre_equipo",
          "equipo_visitante": "nombre_equipo",
          "dia": número_dia,
          "hora": número_hora,
          "ubicacion": "nombre_ubicacion"
        }
      ]
    },
    {
      "jornada": 2,
      "partidos": [...]
    }
  ]
}

IMPORTANTE:
- Usa solo horarios en los que AMBOS equipos estén disponibles.
- Distribuye de forma equitativa los equipos como local y visitante.
- No incluyas texto ni explicación, solo el JSON estrictamente con ese formato.
EOT;

        $messages = [
            ["role" => "system", "content" => "Responde estrictamente en JSON. No incluyas ningún texto fuera del JSON. Cada jornada debe tener partidos válidos con horarios coincidentes entre ambos equipos."],
            ["role" => "user", "content" => $prompt . "\n\n" . json_encode($detailResponse, JSON_PRETTY_PRINT)]
        ];

        $apiKey = env('OPENROUTER_API_KEY');

        $response = Http::withHeaders([
            "Authorization" => "Bearer $apiKey",
            "Content-Type" => "application/json",
            "HTTP-Referer" => "https://metrosport.example.com"
        ])->timeout(300)->post("https://openrouter.ai/api/v1/chat/completions", [
            "model" => "mistralai/mistral-small-3.1-24b-instruct:free",
            "messages" => $messages,
            "max_tokens" => 94000,
            "temperature" => 0.2
        ]);

        $json = $response->json();

        if (!isset($json['choices']) || !isset($json['choices'][0]['message']['content'])) {
            return response()->json([
                'error' => 'Respuesta inválida de OpenRouter',
                'response' => $json
            ], 500);
        }

        $content = $json['choices'][0]['message']['content'];
        \Log::info('Contenido original:', ['content' => $content]);

        if (preg_match('/(\{(?:[^{}]|(?R))*\})/s', $content, $matches)) {
            $jsonContent = $matches[0];
            \Log::info('JSON extraído con regex:', ['content' => $jsonContent]);
        } else {
            $jsonContent = preg_replace('/^```json\n|^```json|^```$|\n```$/m', '', trim($content));
            $jsonContent = preg_replace('/^[^{]*(\{.*\})[^}]*$/s', '$1', $jsonContent);
            \Log::info('JSON limpiado:', ['content' => $jsonContent]);
        }

        try {
            $parsed = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            if (!isset($parsed['calendario'])) {
                foreach ($parsed as $key => $value) {
                    if (is_array($value) && isset($value['calendario'])) {
                        $parsed = $value;
                        break;
                    }
                }
            }

            if (!isset($parsed['calendario']) || count($parsed['calendario']) < $jornadasNecesarias) {
                return response()->json([
                    'error' => 'El calendario generado no contiene todas las jornadas necesarias',
                    'jornadasEsperadas' => $jornadasNecesarias,
                    'jornadasRecibidas' => isset($parsed['calendario']) ? count($parsed['calendario']) : 0,
                    'calendario' => $parsed
                ], 422);
            }

            // Guardar el calendario en la base de datos
            $resultadoGuardado = $this->guardarCalendario($id, $parsed);

            // Añadir información del guardado a la respuesta
            $parsed['resultado_guardado'] = $resultadoGuardado;

            return response()->json($parsed);
        } catch (\Throwable $e) {
            if (preg_match_all('/\{(?:[^{}]|(?R))*\}/s', $content, $allMatches)) {
                usort($allMatches[0], function ($a, $b) {
                    return strlen($b) - strlen($a);
                });

                foreach ($allMatches[0] as $potentialJson) {
                    try {
                        $candidate = json_decode($potentialJson, true, 512, JSON_THROW_ON_ERROR);
                        if (isset($candidate['calendario'])) {
                            return response()->json($candidate);
                        }
                    } catch (\Throwable $innerE) {
                        continue;
                    }
                }
            }

            return response()->json([
                'error' => 'Error al parsear JSON del modelo',
                'raw' => $content,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guarda los partidos generados por la IA en la base de datos
     *
     * @param int $lligaId ID de la liga
     * @param array $calendario Datos del calendario generado
     * @return array Resultado de la operación
     */
    public function guardarCalendario($lligaId, $calendarioData)
    {
        // Verificar que el calendario tenga el formato correcto
        if (!isset($calendarioData['calendario']) || !is_array($calendarioData['calendario'])) {
            return [
                'success' => false,
                'message' => 'Formato de calendario inválido'
            ];
        }

        // Estadísticas para el resultado
        $stats = [
            'total_jornadas' => count($calendarioData['calendario']),
            'total_partidos' => 0,
            'partidos_creados' => 0,
            'errores' => []
        ];

        // Obtener ID del estado "pendiente" (si es siempre 1, podríamos hardcodearlo)
        $estatPendienteId = 1; // ID del estado "pendiente"

        // Procesar cada jornada
        foreach ($calendarioData['calendario'] as $jornada) {
            // Verificar que la jornada tenga el formato correcto
            if (!isset($jornada['partidos']) || !is_array($jornada['partidos'])) {
                $stats['errores'][] = "Jornada {$jornada['jornada']} sin partidos válidos";
                continue;
            }

            // Procesar cada partido de la jornada
            foreach ($jornada['partidos'] as $partido) {
                $stats['total_partidos']++;

                try {
                    // 1. Encontrar los equipos por nombre
                    $equipoLocal = \App\Models\Equip::where('nom_equip', $partido['equipo_local'])
                        ->where('lliga_id_lliga', $lligaId)
                        ->first();

                    $equipoVisitante = \App\Models\Equip::where('nom_equip', $partido['equipo_visitante'])
                        ->where('lliga_id_lliga', $lligaId)
                        ->first();

                    if (!$equipoLocal || !$equipoVisitante) {
                        $stats['errores'][] = "Equipo no encontrado: " .
                            (!$equipoLocal ? $partido['equipo_local'] : $partido['equipo_visitante']);
                        continue;
                    }

                    // 2. Encontrar la ubicación
                    $ubicacion = \App\Models\UbicacioCamp::where('nom_ubicacio', $partido['ubicacion'])
                        ->first();

                    if (!$ubicacion) {
                        // Si no se encuentra la ubicación, usar la del equipo local
                        $ubicacion = \App\Models\UbicacioCamp::where('equip_usuari_id_usuari', $equipoLocal->usuari_id_usuari)
                            ->first();

                        if (!$ubicacion) {
                            $stats['errores'][] = "Ubicación no encontrada: {$partido['ubicacion']}";
                            continue;
                        }
                    }

                    // 3. Encontrar el registro de dia_hora correspondiente
                    $diaHora = \App\Models\DiaHora::where('dia', $partido['dia'])
                        ->where('hora', $partido['hora'])
                        ->first();

                    if (!$diaHora) {
                        $stats['errores'][] = "Día/hora no encontrado: {$partido['dia']}/{$partido['hora']}";
                        continue;
                    }

                    // 4. Insertar en la tabla partit
                    $partidoModel = new \App\Models\Partit();
                    $partidoModel->jornada = $jornada['jornada'];
                    $partidoModel->estat_partit_id_estat = $estatPendienteId;
                    $partidoModel->ubicacio_camp_id_ubicacio_camp = $ubicacion->id_ubicacio_camp;
                    $partidoModel->lliga_id_lliga = $lligaId;
                    $partidoModel->dia_hora_id = $diaHora->id; // Asignar el ID de dia_hora
                    $partidoModel->save();

                    // 5. Insertar en la tabla partit_has_equip para el equipo local
                    $partidoModel->equips()->attach($equipoLocal->usuari_id_usuari, [
                        'partit_lliga_id_lliga' => $lligaId,
                        'local_visitant' => 'local',
                        'gols' => 0 // Inicialmente sin goles
                    ]);

                    // 6. Insertar en la tabla partit_has_equip para el equipo visitante
                    $partidoModel->equips()->attach($equipoVisitante->usuari_id_usuari, [
                        'partit_lliga_id_lliga' => $lligaId,
                        'local_visitant' => 'visitant',
                        'gols' => 0 // Inicialmente sin goles
                    ]);

                    $stats['partidos_creados']++;
                } catch (\Exception $e) {
                    $stats['errores'][] = "Error al crear partido: " . $e->getMessage();
                    \Log::error('Error al crear partido', [
                        'error' => $e->getMessage(),
                        'partido' => $partido
                    ]);
                }
            }
        }

        return [
            'success' => $stats['partidos_creados'] > 0,
            'stats' => $stats
        ];
    }
}
