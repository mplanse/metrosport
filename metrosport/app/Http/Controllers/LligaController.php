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
use App\Models\DiaHora;
use App\Models\Partit;
use App\Models\PartitHasEquip;
use App\Models\EstatPartit;

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
        try {
            // Modificamos para evitar cargar relaciones que puedan causar problemas
            $lliga = Lliga::with([
                'equips.ubicacioCamp'
            ])->find($id);

            // Cargamos los partidos de forma separada con eager loading adecuado
            if ($lliga) {
                $partits = Partit::with(['ubicacio', 'estat', 'diaHora'])
                    ->where('lliga_id_lliga', $id)
                    ->get();

                $lliga->setAttribute('partits', $partits);
            }

            if (!$lliga) {
                return response()->json(['error' => 'Lliga no trobada'], 404);
            }

            // Añadir información de si el usuario ya está inscrito
            $usuarioInscrito = false;
            $yaEnOtraLiga = false;

            if (auth()->check()) {
                $usuarioId = auth()->user()->id_usuari;

                // Verificar si el usuario ya tiene un equipo en esta liga
                $usuarioInscrito = $lliga->equips()->where('usuari_id_usuari', $usuarioId)->exists();

                // Verificar si el usuario ya está en alguna otra liga
                $equipo = Equip::where('usuari_id_usuari', $usuarioId)->first();
                if ($equipo && $equipo->lliga_id_lliga && $equipo->lliga_id_lliga != $id) {
                    $yaEnOtraLiga = true;
                }
            }

            // Añadir la información al resultado
            $result = $lliga->toArray();
            $result['usuario_inscrito'] = $usuarioInscrito;
            $result['ya_en_otra_liga'] = $yaEnOtraLiga;

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error en getLligaInfo: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);

            return response()->json([
                'error' => 'Error al cargar la información de la liga',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_lliga' => 'required|string|max:45',
            'nro_equips_participants' => 'required|integer|min:2',
            'preu_entrada' => 'required|integer|min:0',
            'url_imagen' => 'required|image',
            // 'data_inici' => 'required|date',
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
        // $dataInici = Carbon::parse($validated['data_inici']);
        // $semanasAfegir = $validated['nro_equips_participants'] * 2;
        // $dataFi = $dataInici->copy()->addWeeks($semanasAfegir);

        // Crear la nova lliga amb participants_actualment a 0
        $lliga = Lliga::create([
            'nom_lliga' => $validated['nom_lliga'],
            'lloc_lliga' => $ubicacio->nom_ubicacio, // s'obté de l'ubicació del usuari
            'nro_equips_participants' => $validated['nro_equips_participants'],
            'preu_entrada' => $validated['preu_entrada'],
            'url_imagen' => $imagePath,
            // 'data_inici' => $dataInici,
            // 'data_fi' => $dataFi,
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
            $disponibilitat = $equip->diaHoras->map(function ($dh) {
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

    public function disponibilitatIA($id)
    {
        try {
            $disponibilitats = $this->getLligaDetallada($id)->getData(true);

            // Convertir $disponibilitats a JSON
            $disponibilitatsJson = json_encode($disponibilitats, JSON_PRETTY_PRINT);

            $prompt = <<<EOT
A partir del següent JSON, genera un text en català amb la disponibilitat general dels equips. Cada entrada de disponibilitat és una combinació de dia (1 = dilluns, ..., 7 = diumenge) i hora (0 = 00:00h fins a 24 = 24:00h). Si una mateixa franja horària apareix en diversos equips, no la repeteixis.

Per cada dia, indica:

Franges contínues de disponibilitat en format: de HH:00h a HH:00h (per exemple, de 17:00h a 20:00h si hi ha disponibilitat a les 17, 18 i 19).

Hores soltes en format: a les HH:00h

Escriu totes les franges i hores disponibles per dia separades per comes, i posa “Dilluns:”, “Dimarts:”, etc. al principi de cada línia.

Ordena les hores dins de cada dia de manera ascendent.

Mostra només els dies que tenen disponibilitat, sense cap error login a l'hora de formular el text, que mai es repeteixi cap hora i estiguin sempre ordenades.

El text ha de ser clar, en format pla i només text, sense afegir cap explicació.

Aquest és el JSON:
$disponibilitatsJson

EOT;

            $messages = [
                ["role" => "system", "content" => "Eres un asistente inteligente"],
                ["role" => "user", "content" => $prompt]
            ];

            $apiKey = env('OPENROUTER_API_KEY');

            $response = Http::withHeaders([
                "Authorization" => "Bearer $apiKey",
                "Content-Type" => "application/json",
                "HTTP-Referer" => "https://metrosport.example.com"
            ])->timeout(6000)->post("https://openrouter.ai/api/v1/chat/completions", [
                        "model" => "google/gemini-2.0-flash-001",
                        "messages" => $messages,
                        "max_tokens" => 120000,
                        "temperature" => 0.2
                    ]);

            $json = $response->json();

            if (!isset($json['choices']) || !isset($json['choices'][0]['message']['content'])) {
                throw new \Exception("Error al generar la disponibilitat amb OpenRouter.");
            }

            $content = $json['choices'][0]['message']['content'];

            // Devuelve el contenido como texto plano
            return response($content, 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            \Log::error("Error en disponibilitatIA: " . $e->getMessage());
            return response("Error al generar la disponibilitat. Torna a intentar-ho més tard.", 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function callOpenRouter($id)
    {
        $detailResponse = $this->getLligaDetallada($id)->getData(true);

        // Contar equipos para calcular jornadas requeridas
        $equipos = count($detailResponse['lliga']['equips'] ?? []);
        $totalPartidos = $equipos * ($equipos - 1); // ida y vuelta
        $jornadasNecesarias = ceil($totalPartidos / ($equipos / 2)); // 4 partidos por jornada

        // Obtener la fecha actual
        $fechaHoy = Carbon::now()->format('Y-m-d');

        $prompt = <<<EOT
Eres un generador experto de calendarios deportivos para torneos de liga (doble round-robin). Recibirás información de una liga con $equipos equipos.

REQUISITOS CRÍTICOS:
1. Cada equipo debe enfrentarse a todos los demás exactamente DOS veces (ida y vuelta): una como LOCAL y otra como VISITANTE.
2. Solo se puede asignar un partido si AMBOS equipos están disponibles en ese día y hora.
3. El campo de juego debe ser la UBICACIÓN del equipo local.
4. Cada jornada debe tener exactamente ${equipos}/2 partidos (ej: 4 si hay 8 equipos).
5. Debes generar EXACTAMENTE $jornadasNecesarias jornadas, enumeradas de 1 a $jornadasNecesarias.
6. Cada partido debe incluir una fecha (`data`) calculada a partir de la fecha de hoy ($fechaHoy). Las jornadas deben ser consecutivas, con al menos 1 día de diferencia entre jornadas.

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
 "ubicacion": "nombre_ubicacion",
 "data": "YYYY-MM-DD"
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
            ["role" => "system", "content" => "Responde estrictamente en JSON con el formato solicitado."],
            ["role" => "user", "content" => $prompt . "\n\n" . json_encode($detailResponse, JSON_PRETTY_PRINT)]
        ];

        $apiKey = env('OPENROUTER_API_KEY');

        $response = Http::withHeaders([
            "Authorization" => "Bearer $apiKey",
            "Content-Type" => "application/json",
            "HTTP-Referer" => "https://metrosport.example.com"
        ])->timeout(300)->post("https://openrouter.ai/api/v1/chat/completions", [
                    "model" => "google/gemini-2.0-flash-001",
                    "messages" => $messages,
                    "max_tokens" => 120000,
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
                    $partidoModel->data = $partido['data']; // Guardar la fecha del partido
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

        // Si se crearon partidos, enviar notificaciones a todos los equipos
        if ($stats['partidos_creados'] > 0) {
            // Obtener info de la liga
            $liga = \App\Models\Lliga::find($lligaId);

            // Obtener todos los equipos de la liga
            $equipos = \App\Models\Equip::where('lliga_id_lliga', $lligaId)->get();

            // Crear notificación para cada equipo
            foreach ($equipos as $equipo) {
                try {
                    \App\Models\Notificacions::create([
                        'missatge_notificacio' => "Ya tienes partidos asignados para la liga: {$liga->nom_lliga}. Consulta el calendario para ver los detalles.",
                        'equip_usuari_id_usuari' => $equipo->usuari_id_usuari,
                        'timestamp' => now(),
                        'tipus_notificacio' => 1 // Tipo 1 para calendario
                    ]);
                } catch (\Exception $e) {
                    $stats['errores'][] = "Error al crear notificación para el equipo {$equipo->nom_equip}: " . $e->getMessage();
                    \Log::error('Error al crear notificación', [
                        'error' => $e->getMessage(),
                        'equipo' => $equipo->nom_equip
                    ]);
                }
            }

            $stats['notificaciones_enviadas'] = $equipos->count();
        }

        return [
            'success' => $stats['partidos_creados'] > 0,
            'stats' => $stats
        ];
    }

    /**
     * Verifica si el usuario actual puede unirse a una liga basado en su disponibilidad horaria
     *
     * @param int $id ID de la liga
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarCompatibilidadUnirse($id)
    {
        // Obtener el ID del usuario actual
        // $usuarioId = auth()->user()->id_usuari;
        $usuarioId = auth()->user()->id_usuari;


        // Obtener el equipo del usuario actual
        $equipoUsuario = \App\Models\Equip::where('usuari_id_usuari', $usuarioId)->first();

        if (!$equipoUsuario) {
            return response()->json([
                'compatible' => false,
                'mensaje' => 'No tienes un equipo creado. Debes crear un equipo antes de unirte a una liga.'
            ]);
        }

        // Obtener la disponibilidad horaria del equipo del usuario
        $disponibilidadUsuario = DiaHora::whereHas('equips', function ($query) use ($usuarioId) {
            $query->where('equip_usuari_id_usuari', $usuarioId);
        })->get()->map(function ($dh) {
            return [
                'dia' => $dh->dia,
                'hora' => $dh->hora
            ];
        })->toArray();

        if (empty($disponibilidadUsuario)) {
            return response()->json([
                'compatible' => false,
                'mensaje' => 'No has configurado tu disponibilidad horaria. Configúrala antes de unirte a una liga.'
            ]);
        }

        // Obtener la información detallada de la liga
        $ligaResponse = $this->getLligaDetallada($id)->getData(true);

        if (!isset($ligaResponse['lliga']) || !isset($ligaResponse['lliga']['equips'])) {
            return response()->json([
                'compatible' => false,
                'mensaje' => 'No se pudo obtener información de la liga.'
            ]);
        }

        // Si no hay equipos en la liga, no hay problema de compatibilidad
        if (count($ligaResponse['lliga']['equips']) == 0) {
            return response()->json([
                'compatible' => true,
                'mensaje' => 'Puedes unirte a esta liga.'
            ]);
        }

        // Añadir la información del equipo del usuario a los datos de la liga
        $equiposData = $ligaResponse['lliga']['equips'];
        $equiposData[] = [
            'nom' => $equipoUsuario->nom_equip,
            'ubicacio' => $equipoUsuario->ubicacioCamp ? $equipoUsuario->ubicacioCamp->nom_ubicacio : null,
            'disponibilitat' => $disponibilidadUsuario,
            'es_nuevo' => true // Marcamos que este es el equipo que quiere unirse
        ];

        // Preparar datos para enviar a la IA
        $dataParaIA = [
            'lliga' => [
                'id' => $ligaResponse['lliga']['id'],
                'nom' => $ligaResponse['lliga']['nom'],
                'equips' => $equiposData
            ]
        ];

        // Construir el prompt para la IA
        $prompt = <<<EOT
Eres un asistente experto en verificar la compatibilidad de horarios para ligas deportivas.
Analiza si un nuevo equipo puede unirse a una liga existente basándose en su disponibilidad horaria.

DATOS A ANALIZAR:
- El último equipo en la lista (marcado con "es_nuevo": true) es el que quiere unirse a la liga
- Cada equipo tiene disponibilidad en ciertos días y horas
- Para que el nuevo equipo pueda unirse, debe tener suficientes franjas horarias compatibles con CADA UNO de los equipos existentes

ANÁLISIS REQUERIDO:
1. Verifica si el nuevo equipo tiene al menos una franja horaria compatible con cada equipo existente
2. Un equipo necesita jugar dos veces contra cada otro equipo (local y visitante)
3. Para ser compatible, el nuevo equipo debe poder jugar contra todos los equipos actuales

FORMATO DE RESPUESTA REQUERIDO (JSON):
{
 "compatible": true/false,
 "mensaje": "Explicación simple para el usuario"
}

IMPORTANTE: Solo necesito saber si puede unirse (true) o no (false), y un mensaje simple para el usuario.
EOT;

        $messages = [
            ["role" => "system", "content" => "Responde estrictamente en JSON con formato simple: compatible (true/false) y mensaje."],
            ["role" => "user", "content" => $prompt . "\n\n" . json_encode($dataParaIA, JSON_PRETTY_PRINT)]
        ];

        $apiKey = env('OPENROUTER_API_KEY');

        $response = Http::withHeaders([
            "Authorization" => "Bearer $apiKey",
            "Content-Type" => "application/json",
            "HTTP-Referer" => "https://metrosport.example.com"
        ])->timeout(60)->post("https://openrouter.ai/api/v1/chat/completions", [
                    "model" => "google/gemini-2.0-flash-001",
                    "messages" => $messages,
                    "max_tokens" => 120000,
                    "temperature" => 0.2
                ]);

        $json = $response->json();

        if (!isset($json['choices']) || !isset($json['choices'][0]['message']['content'])) {
            return response()->json([
                'compatible' => false,
                'mensaje' => 'Error al verificar compatibilidad. Inténtalo más tarde.'
            ], 500);
        }

        $content = $json['choices'][0]['message']['content'];

        try {
            // Intentar extraer el JSON de la respuesta
            if (preg_match('/(\{(?:[^{}]|(?R))*\})/s', $content, $matches)) {
                $jsonContent = $matches[0];
            } else {
                $jsonContent = preg_replace('/^```json\n|^```json|^```$|\n```$/m', '', trim($content));
                $jsonContent = preg_replace('/^[^{]*(\{.*\})[^}]*$/s', '$1', $jsonContent);
            }

            $parsed = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            // Asegurar que solo devolvemos compatible y mensaje
            return response()->json([
                'compatible' => $parsed['compatible'] ?? false,
                'mensaje' => $parsed['mensaje'] ?? 'No es posible verificar la compatibilidad en este momento.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'compatible' => false,
                'mensaje' => 'Error al verificar compatibilidad. Inténtalo más tarde.'
            ], 500);
        }
    }

    /**
     * Inscribe al equipo del usuario actual en una liga
     *
     * @param int $id ID de la liga
     * @return \Illuminate\Http\RedirectResponse
     */
    public function inscribirEquipo($id)
    {
        try {
            // Verificar que la liga existe
            $liga = Lliga::find($id);
            if (!$liga) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Liga no encontrada'], 404);
                }
                return redirect()->back()->with('error', 'Liga no encontrada');
            }

            // Verificar que el usuario tiene un equipo
            $usuarioId = auth()->user()->id_usuari;
            $equipo = Equip::where('usuari_id_usuari', $usuarioId)->first();

            if (!$equipo) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'No tienes un equipo creado'], 400);
                }
                return redirect()->back()->with('error', 'No tienes un equipo creado');
            }

            // Verificar que no está inscrito ya en esta liga
            $yaInscrito = $liga->equips()->where('usuari_id_usuari', $usuarioId)->exists();
            if ($yaInscrito) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Ya estás inscrito en esta liga'], 400);
                }
                return redirect()->back()->with('error', 'Ya estás inscrito en esta liga');
            }

            // Verificar que no está inscrito en otra liga
            if ($equipo->lliga_id_lliga && $equipo->lliga_id_lliga != $id) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Ya estás inscrito en otra liga. No puedes participar en múltiples ligas simultáneamente.'], 400);
                }
                return redirect()->back()->with('error', 'Ya estás inscrito en otra liga. No puedes participar en múltiples ligas simultáneamente.');
            }

            // Verificar si la liga está completa
            if ($liga->participants_actualment >= $liga->nro_equips_participants) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Esta liga ya está completa. No se pueden aceptar más inscripciones.'], 400);
                }
                return redirect()->back()->with('error', 'Esta liga ya está completa. No se pueden aceptar más inscripciones.');
            }

            // Verificar compatibilidad
            $compatibilidad = $this->verificarCompatibilidadUnirse($id)->getData(true);
            if (!$compatibilidad['compatible']) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $compatibilidad['mensaje']], 400);
                }
                return redirect()->back()->with('error', $compatibilidad['mensaje']);
            }

            // Actualizar el equipo con el ID de la liga
            $equipo->lliga_id_lliga = $id;
            $equipo->save();

            // Incrementar contador de participantes
            $liga->participants_actualment = ($liga->participants_actualment ?? 0) + 1;
            $liga->save();

            // Crear notificación
            Notificacions::create([
                'missatge_notificacio' => "Te has inscrito correctamente en la liga: {$liga->nom_lliga}",
                'equip_usuari_id_usuari' => $usuarioId,
                'timestamp' => now(),
                'tipus_notificacio' => 2 // Tipo 2 para inscripción
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Te has inscrito correctamente a la liga!',
                    'lliga' => $liga
                ]);
            }
            return redirect()->back()->with('success', '¡Te has inscrito correctamente a la liga!');
        } catch (\Exception $e) {
            \Log::error('Error al inscribirse en liga', [
                'error' => $e->getMessage(),
                'liga_id' => $id,
                'usuario_id' => auth()->user()->id_usuari
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al inscribirse en la liga: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al inscribirse en la liga');
        }
    }

    public function mostrarClassificacio($id)
    {
        try {
            // Obtener la liga por su ID
            $liga = Lliga::findOrFail($id);

            // Obtener los partidos de la liga
            $partidos = Partit::with([
                'equips' => function ($query) {
                    $query->select('equip.nom_equip', 'partit_has_equip.local_visitant', 'partit_has_equip.gols');
                },
                'diaHora',
                'ubicacio'
            ])
                ->where('lliga_id_lliga', $id)
                ->get();

            // Agrupar los partidos por jornada para evitar duplicados
            $partidosAgrupados = $partidos->groupBy('jornada');

            // Pasar los datos a la vista
            return view('lligues.classificacio', compact('liga', 'partidosAgrupados'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar la clasificación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo cargar la clasificación.');
        }
    }
}
