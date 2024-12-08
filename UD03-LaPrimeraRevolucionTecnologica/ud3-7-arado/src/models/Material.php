<?php

class Material
{
	protected array $densidadesReales;
	protected array $atributosMaterial;

	public function __construct(
		private string $nombre,
		private string $descripcion,
		private string $categoria, // Categoría o tipo de material
		private float $flexibilidad, // Capacidad de un material para doblarse sin romperse
		private float $resistenciaTraccion, // Resistencia de un material a ser estirado
		private float $dureza, // Resistencia de un material a ser rayado
		private float $densidad, // Masa por unidad de volumen
		private float $coeficienteDesgaste, // Resistencia de un material a ser desgastado por fricción
		private float $elasticidad, // Capacidad de un material para recuperar su forma original después de ser deformado
		private float $resistenciaHumedad, // Resistencia de un material a la humedad
		private float $fragilidad, // Propiedad de un material de romperse fácilmente
		private float $inflamabilidad, // Capacidad de un material para arder
		private float $densidadEnergetica, // Capacidad de un material para almacenar energía
		private float $resistenciaOxidacion, // Resistencia de un material a la oxidación
		private float $tenacidad, // Resistencia de un material a ser fracturado
		private float $resistenciaCompresion, // Resistencia de un material a ser comprimido
		private float $resistenciaTemperatura, // Resistencia de un material a la temperatura
		private float $resistenciaViento, // Resistencia de un material al viento
		private float $coeficienteFriccion, // Resistencia de un material a ser desgastado por fricción
		private float $resistenciaQuimica, // Resistencia de un material a productos químicos
		private float $porosidad, // Proporción de espacios vacíos en un material
		private float $toxicidad, // Capacidad de un material para ser tóxico
		private float $resistenciaUV // Resistencia de un material a la radiación ultravioleta
	) {
		$this->densidadesReales = [
			// Rocas
			'Sílex'     => 2.65,
			'Obsidiana' => 2.4,
			'Granito'   => 2.7,

			// Minerales
			'Caolinita'                       => 2.6,
			'Illita'                          => 2.7,
			'Montmorillonita'                 => 2.35,
			'Cuarzo'                          => 2.65,
			'Grafito'                         => 2.2,
			'Minerales semiconductores'       => 5.0,
			'Cristales naturales'             => 2.5,
			'Materiales magnéticos naturales' => 5.2,

			// Arenas
			'Arena de sílice' => 1.6,
			'Arena de cuarzo' => 1.55,
			'Arena de playa'  => 1.4,

			// Metales
			'Hierro' => 7.874,
			'Cobre'  => 8.96,
			'Estaño' => 7.28,
			'Plata'  => 10.49,
			'Oro'    => 19.3,
			'Plomo'  => 11.34,

			// Elementos
			'Silicio'   => 2.33,
			'Carbono'   => 2.2,
			'Hidrógeno' => 0.00008988, // gas
			'Oxígeno'   => 0.001429, // gas
			'Nitrógeno' => 0.0012506, // gas
			'Fósforo'   => 1.82,
			'Azufre'    => 2.07,
			'Neodimio'  => 7.0,
			'Lantano'   => 6.146,
			'Cerio'     => 6.77,
			'Uranio'    => 18.9,

			// Maderas
			'Roble'          => 0.71,
			'Pino'           => 0.55,
			'Cedro'          => 0.38,
			'Madera de pino' => 0.55,
			'Madera seca'    => 0.45,

			// Fibras
			'Cáñamo'     => 1.5,
			'Lino'       => 1.4,
			'Yute'       => 1.3,
			'Caña común' => 0.6,
			'Totora'     => 0.5,
			'Carrizo'    => 0.4,
			'Bambú'      => 0.7,
			'Algodón'    => 1.54,
			'Lana'       => 1.3,

			// Resinas
			'Ámbar'               => 1.05,
			'Goma arábiga'        => 1.4,
			'Látex'               => 0.92,
			'Resinas inflamables' => 1.0,

			// Orgánicos
			'Pieles'                         => 1.2,
			'Huesos'                         => 1.9,
			'Tendones'                       => 1.2,
			'Cuero'                          => 0.9,
			'Plumas'                         => 0.5,
			'Carbón natural'                 => 1.35,
			'Aceites vegetales'              => 0.92,
			'Agua'                           => 1.0,
			'Sal'                            => 2.16,
			'Tierras fértiles'               => 1.2,
			'Gases naturales'                => 0.0008,
			'Materiales aislantes naturales' => 0.15
		];
	}

	public function getDensidadReal(): float{
		$densidadReal = $this->densidadesReales[$this->nombre] ?? 0.00;
		return $densidadReal;
	}
	/**
	 * Función mágica para obtener propiedades de la clase.
	 */
	public function __get($propiedad)
	{
		if (property_exists($this, $propiedad)) {
			return $this->$propiedad;
		}

		return null;
	}

	/**
	 * Función mágica para establecer propiedades de la clase.
	 */
	public function __set($propiedad, $valor)
	{
		if (property_exists($this, $propiedad)) {
			$this->$propiedad = $valor;
		}
	}

	/**
	 * Función mágica para convertir el objeto a string.
	 */
	public function __toString(): string
	{
		$result = "<table class='invento-table'>";
		$result .= "<tr><th>Nombre</th><td>{$this->nombre}</td></tr>";
		$result .= "<tr><th>Descripción</th><td>{$this->descripcion}</td></tr>";
		$result .= "<tr><th>Categoría</th><td>{$this->categoria}</td></tr>";

		return $result;
	}

	/**
	 * Calcula la eficiencia de un material en base a sus atributos beneficiosos y perjudiciales.
	 */
	public function calcularEficiencia($atributos): float
	{
		$sumaBeneficiosos         = 0;
		$sumaPerjudiciales        = 0;
		$sumaBeneficiososMaximos  = 0;
		$sumaPerjudicialesMaximos = 0;
		$numBeneficiosos          = count($atributos['beneficiosos']);
		$numPerjudiciales         = count($atributos['perjudiciales']);

		foreach ($atributos['beneficiosos'] as $atributo) {
			$sumaBeneficiosos += $this->$atributo;
			$sumaBeneficiososMaximos += $this->calcularMaximo($atributo);
		}

		foreach ($atributos['perjudiciales'] as $atributo) {
			$sumaPerjudiciales += $this->$atributo;
			$sumaPerjudicialesMaximos += $this->calcularMinimo($atributo);
		}

		$promedioBeneficiosos         = $sumaBeneficiosos / $numBeneficiosos;
		$promedioPerjudiciales        = $sumaPerjudiciales / $numPerjudiciales;
		$promedioBeneficiososMaximos  = $sumaBeneficiososMaximos / $numBeneficiosos;
		$promedioPerjudicialesMaximos = $sumaPerjudicialesMaximos / $numPerjudiciales;
		$eficienciaMaxima             = $promedioBeneficiososMaximos / $promedioPerjudicialesMaximos;
		$eficiencia                   = $promedioBeneficiosos / $promedioPerjudiciales;

		//Regla de 3 si el maximo es 100 cuanto es la eficiencia del material
		return round(($eficiencia * 100) / $eficienciaMaxima, 2);
	}

	/**
	 * Obtiene el valor máximo de un atributo para materiales de la misma categoría.
	 */
	public function calcularMaximo(string $atributo): float
	{
		$maximo = 1;
		foreach (Material::getMateriales() as $material) {
			if ($material->categoria === $this->categoria) {
				$maximo = max($maximo, $material->$atributo);
			}
		}

		return $maximo;
	}

	/**
	 * Obtiene el valor mínimo de un atributo para materiales de la misma categoría.
	 */
	public function calcularMinimo(string $atributo): float
	{
		$minimo = 100;
		foreach (Material::getMateriales() as $material) {
			if ($material->categoria === $this->categoria) {
				$minimo = min($minimo, $material->$atributo);
			}
		}

		return $minimo;
	}

	public function calcularTiempoCreacion(string $zona, int $tiempoBase): int{
		$condiciones = new Class($zona){
			public $temperatura;
			public $humedad;
			public $viento;
			public $presion;

			public function __construct($zona){
				switch($zona){
					case 'bosque':
						$this->temperatura = rand(10,25);
						$this->humedad = rand(60, 90);
						$this->viento = rand(0,15);
						$this->presion = rand(950, 1050) % 100;
						break;
					case 'selva':
						$this->temperatura = rand(25,35);
						$this->humedad = rand(80, 100);
						$this->viento = rand(0,10);
						$this->presion = rand(950, 1050) % 100;
						break;
					case 'pradera':
						$this->temperatura = rand(15,25);
						$this->humedad = rand(40, 70);
						$this->viento = rand(5,20);
						$this->presion = rand(950, 1050) % 100;
						break;
					case 'desierto':
						$this->temperatura = rand(30,45);
						$this->humedad = rand(10, 30);
						$this->viento = rand(10, 25);
						$this->presion = rand(950, 1050) % 100;
						break;
					case 'montaña':
						$this->temperatura = rand(-10, 10);
						$this->humedad = rand(20, 50);
						$this->viento = rand(20, 40);
						$this->presion = rand(950, 1050) % 100;
						break;
					case 'polo':
						$this->temperatura = rand(-50, -10);
						$this->humedad = rand(50, 80);
						$this->viento = rand(10, 30);
						$this->presion = rand(950, 1050) % 100;
						break;
					default:
						throw new Exception("La zona $zona no existe o no se ha encontrado.");
						break;
				}
			}
		};

		//calcular factores.
		$factorTemperatura = max(0.7, min(1.08, 1 + (($condiciones->temperatura - 20) / 100) * (1 - min($this->resistenciaTemperatura, 80) / 100)));
		$factorHumedad = max(0.9, min(1.6, 1 + (($condiciones->humedad - 50) / 100) * (1 - min($this->resistenciaHumedad, 80) / 100)));
		$factorViento = max(0.76, min(1.0, 1 - ($condiciones->viento / 100) * (1 - min($this->resistenciaViento, 80) / 100)));
		$factorPresion = max(0.95, min(1.06, 1 + (($condiciones->presion - 50) / 1000)));

		//calcular tiempo ajustado

		$tiempoAjustado = $tiempoBase * $factorTemperatura * $factorHumedad * $factorViento * $factorPresion;

		return round(max($tiempoAjustado,0));

	 }

	/**
	 * Obtiene los materiales disponibles.
	 */
	public static function getMateriales()
	{
		return [
			'silex' => new Material(
				'Sílex',
				'Roca sedimentaria formada por sílice microcristalina',
				'Roca',
				52,
				93,
				15,
				72,
				61,
				21,
				83,
				87,
				92,
				60,
				44,
				94,
				63,
				3,
				22,
				53,
				85,
				20,
				15,
				90
			),
			'obsidiana' => new Material(
				'Obsidiana',
				'Roca volcánica vítrea',
				'Roca',
				2,
				88,
				30,
				38,
				2,
				64,
				94,
				96,
				78,
				44,
				67,
				92,
				49,
				49,
				91,
				59,
				90,
				5,
				10,
				85
			),
			'granito' => new Material(
				'Granito',
				'Roca ígnea plutónica de textura granular',
				'Roca',
				42,
				92,
				60,
				80,
				15,
				62,
				39,
				54,
				61,
				50,
				36,
				72,
				22,
				51,
				7,
				21,
				80,
				15,
				20,
				75
			),
			'caolinita' => new Material(
				'Caolinita',
				'Mineral arcilloso de silicato de aluminio hidratado',
				'Mineral',
				73,
				39,
				18,
				4,
				89,
				60,
				82,
				68,
				99,
				20,
				70,
				19,
				37,
				60,
				71,
				44,
				60,
				70,
				25,
				65
			),
			'illita' => new Material(
				'Illita',
				'Mineral arcilloso del grupo de las micas',
				'Mineral',
				8,
				47,
				35,
				78,
				81,
				36,
				47,
				93,
				87,
				92,
				13,
				47,
				79,
				93,
				63,
				18,
				65,
				65,
				20,
				70
			),
			'montmorillonita' => new Material(
				'Montmorillonita',
				'Mineral arcilloso del grupo de las esmectitas',
				'Mineral',
				25,
				44,
				10,
				30,
				85,
				48,
				92,
				89,
				95,
				32,
				67,
				43,
				52,
				63,
				48,
				23,
				70,
				75,
				15,
				60
			),
			'arena_silice' => new Material(
				'Arena de sílice',
				'Arena compuesta principalmente por dióxido de silicio',
				'Arena',
				90,
				32,
				4,
				12,
				34,
				22,
				15,
				81,
				45,
				39,
				71,
				54,
				12,
				81,
				57,
				14,
				75,
				45,
				10,
				80
			),
			'arena_cuarzo' => new Material(
				'Arena de cuarzo',
				'Arena formada por cristales de cuarzo',
				'Arena',
				88,
				30,
				10,
				11,
				32,
				23,
				9,
				79,
				37,
				42,
				65,
				44,
				34,
				71,
				49,
				26,
				80,
				40,
				5,
				85
			),
			'arena_playa' => new Material(
				'Arena de playa',
				'Arena natural encontrada en playas',
				'Arena',
				85,
				20,
				9,
				10,
				40,
				17,
				18,
				60,
				55,
				37,
				57,
				39,
				31,
				66,
				45,
				16,
				70,
				50,
				5,
				75
			),
			'hierro' => new Material(
				'Hierro',
				'Metal extraído de la hematita y magnetita',
				'Metal',
				13,
				75,
				88,
				99,
				52,
				28,
				60,
				23,
				10,
				89,
				88,
				87,
				91,
				67,
				19,
				66,
				30,
				10,
				40,
				55
			),
			'cobre' => new Material(
				'Cobre',
				'Metal extraído de la calcopirita y malaquita',
				'Metal',
				55,
				40,
				77,
				96,
				53,
				52,
				52,
				22,
				12,
				81,
				74,
				85,
				89,
				51,
				32,
				64,
				45,
				5,
				35,
				65
			),
			'estaño' => new Material(
				'Estaño',
				'Metal extraído de la casiterita',
				'Metal',
				63,
				23,
				68,
				94,
				69,
				61,
				54,
				42,
				20,
				73,
				72,
				81,
				87,
				56,
				26,
				70,
				40,
				5,
				30,
				60
			),
			'plata' => new Material(
				'Plata',
				'Metal precioso extraído de la argentita',
				'Metal',
				35,
				28,
				72,
				91,
				45,
				68,
				48,
				27,
				15,
				61,
				78,
				65,
				82,
				42,
				39,
				65,
				85,
				2,
				25,
				70
			),
			'oro' => new Material(
				'Oro',
				'Metal precioso en estado nativo',
				'Metal',
				45,
				22,
				78,
				98,
				72,
				58,
				56,
				33,
				7,
				67,
				76,
				67,
				85,
				61,
				40,
				62,
				95,
				1,
				20,
				75
			),
			'plomo' => new Material(
				'Plomo',
				'Metal extraído de la galena',
				'Metal',
				49,
				18,
				91,
				100,
				88,
				41,
				70,
				11,
				5,
				52,
				79,
				62,
				86,
				30,
				42,
				69,
				35,
				5,
				90,
				50
			),
			'cuarzo' => new Material(
				'Cuarzo',
				'Mineral compuesto de sílice',
				'Mineral',
				7,
				81,
				66,
				85,
				65,
				33,
				42,
				37,
				85,
				54,
				48,
				72,
				71,
				46,
				28,
				58,
				85,
				15,
				10,
				80
			),
			'silicio' => new Material(
				'Silicio',
				'Elemento semiconductor',
				'Elemento',
				8,
				68,
				61,
				84,
				48,
				41,
				55,
				50,
				70,
				67,
				43,
				65,
				74,
				55,
				41,
				54,
				75,
				20,
				25,
				70
			),
			'roble' => new Material(
				'Roble',
				'Madera dura de árbol de roble',
				'Madera',
				58,
				66,
				26,
				65,
				61,
				74,
				25,
				40,
				80,
				32,
				60,
				40,
				43,
				69,
				22,
				43,
				25,
				55,
				5,
				40
			),
			'pino' => new Material(
				'Pino',
				'Madera de árbol de pino',
				'Madera',
				63,
				61,
				22,
				62,
				67,
				68,
				36,
				33,
				87,
				25,
				62,
				39,
				32,
				78,
				18,
				48,
				20,
				60,
				10,
				35
			),
			'cedro' => new Material(
				'Cedro',
				'Madera de árbol de cedro',
				'Madera',
				68,
				55,
				15,
				59,
				71,
				70,
				32,
				25,
				90,
				30,
				65,
				43,
				39,
				74,
				25,
				51,
				30,
				50,
				5,
				45
			),
			'cañamo' => new Material(
				'Cáñamo',
				'Fibra natural de la planta de cannabis',
				'Fibra',
				72,
				78,
				5,
				30,
				55,
				85,
				47,
				12,
				95,
				20,
				78,
				63,
				32,
				75,
				81,
				84,
				40,
				65,
				5,
				55
			),
			'lino' => new Material(
				'Lino',
				'Fibra natural de la planta de lino',
				'Fibra',
				75,
				74,
				7,
				25,
				59,
				90,
				42,
				15,
				87,
				18,
				76,
				60,
				38,
				72,
				75,
				82,
				35,
				70,
				5,
				50
			),
			'yute' => new Material(
				'Yute',
				'Fibra natural de la planta de yute',
				'Fibra',
				68,
				70,
				8,
				23,
				61,
				93,
				33,
				19,
				80,
				22,
				79,
				61,
				41,
				73,
				72,
				80,
				30,
				75,
				5,
				45
			),
			'caña_comun' => new Material(
				'Caña común',
				'Tallo de la planta de caña común',
				'Fibra',
				65,
				81,
				16,
				40,
				66,
				89,
				38,
				20,
				85,
				35,
				80,
				54,
				43,
				76,
				85,
				78,
				25,
				70,
				5,
				40
			),
			'totora' => new Material(
				'Totora',
				'Planta acuática utilizada como material',
				'Fibra',
				80,
				77,
				18,
				32,
				73,
				91,
				30,
				25,
				90,
				30,
				83,
				59,
				47,
				72,
				83,
				77,
				20,
				80,
				5,
				35
			),
			'carrizo' => new Material(
				'Carrizo',
				'Planta gramínea utilizada como material',
				'Fibra',
				73,
				71,
				21,
				35,
				60,
				88,
				45,
				29,
				78,
				32,
				85,
				57,
				42,
				79,
				78,
				79,
				25,
				75,
				5,
				40
			),
			'madera_pino' => new Material(
				'Madera de pino',
				'Madera procesada de pino',
				'Madera',
				68,
				62,
				30,
				58,
				62,
				66,
				31,
				33,
				87,
				28,
				60,
				35,
				41,
				68,
				67,
				55,
				25,
				65,
				15,
				35
			),
			'bambu' => new Material(
				'Bambú',
				'Planta gramínea de tallo leñoso',
				'Fibra',
				83,
				75,
				35,
				65,
				75,
				84,
				30,
				20,
				85,
				38,
				82,
				57,
				51,
				77,
				82,
				80,
				35,
				60,
				5,
				45
			),
			'algodon' => new Material(
				'Algodón',
				'Fibra natural de la planta de algodón',
				'Fibra',
				80,
				64,
				25,
				38,
				52,
				95,
				29,
				22,
				88,
				33,
				75,
				50,
				42,
				74,
				84,
				81,
				20,
				85,
				5,
				30
			),
			'ambar' => new Material(
				'Ámbar',
				'Resina fósil',
				'Resina',
				62,
				48,
				50,
				60,
				35,
				58,
				25,
				32,
				70,
				45,
				60,
				45,
				38,
				66,
				75,
				44,
				55,
				35,
				10,
				65
			),
			'goma_arabiga' => new Material(
				'Goma arábiga',
				'Resina natural de árbol de acacia',
				'Resina',
				73,
				55,
				22,
				35,
				49,
				65,
				22,
				25,
				72,
				38,
				67,
				44,
				35,
				62,
				70,
				50,
				45,
				45,
				15,
				55
			),
			'latex' => new Material(
				'Látex',
				'Resina natural de árbol de caucho',
				'Resina',
				85,
				61,
				27,
				30,
				40,
				80,
				31,
				27,
				78,
				40,
				70,
				53,
				41,
				68,
				65,
				48,
				50,
				40,
				20,
				45 // resistenciaQuimica, porosidad, toxicida
			),
			'pieles' => new Material(
				'Pieles',
				'Material orgánico animal',
				'Orgánico',
				70,
				50,
				25,
				20,
				40,
				85,
				70,
				40,
				85,
				20,
				55,
				45,
				30,
				65,
				50,
				60,
				45,
				75,
				15,
				40
			),
			'huesos' => new Material(
				'Huesos',
				'Material orgánico animal',
				'Orgánico',
				50,
				80,
				70,
				90,
				60,
				45,
				50,
				60,
				10,
				60,
				70,
				70,
				80,
				90,
				40,
				40,
				70,
				25,
				10,
				65
			),
			'tendones' => new Material(
				'Tendones',
				'Tejido conectivo animal',
				'Orgánico',
				90,
				85,
				40,
				10,
				70,
				95,
				80,
				20,
				70,
				30,
				60,
				85,
				50,
				75,
				60,
				70,
				55,
				35,
				5,
				45
			),
			'lana' => new Material(
				'Lana',
				'Fibra natural animal',
				'Fibra',
				80,
				60,
				20,
				15,
				50,
				70,
				90,
				50,
				95,
				10,
				80,
				50,
				25,
				60,
				40,
				55,
				40,
				80,
				5,
				35
			),
			'cuero' => new Material(
				'Cuero',
				'Piel animal procesada',
				'Orgánico',
				85,
				70,
				45,
				25,
				55,
				80,
				85,
				30,
				85,
				25,
				65,
				75,
				40,
				70,
				55,
				65,
				60,
				45,
				10,
				55
			),
			'plumas' => new Material(
				'Plumas',
				'Material orgánico animal',
				'Orgánico',
				95,
				30,
				10,
				5,
				20,
				95,
				95,
				70,
				90,
				5,
				90,
				40,
				10,
				50,
				30,
				25,
				30,
				85,
				5,
				40
			),
			'madera_seca' => new Material(
				'Madera seca',
				'Madera procesada y secada',
				'Madera',
				65,
				55,
				30,
				40,
				60,
				70,
				30,
				35,
				95,
				20,
				55,
				40,
				30,
				60,
				70,
				50,
				45,
				65,
				5,
				45
			),
			'carbon_natural' => new Material(
				'Carbón natural',
				'Material orgánico fosilizado',
				'Orgánico',
				50,
				60,
				50,
				70,
				70,
				40,
				20,
				50,
				20,
				80,
				30,
				60,
				60,
				80,
				30,
				40,
				75,
				35,
				15,
				60
			),
			'aceites_vegetales' => new Material(
				'Aceites vegetales',
				'Aceites extraídos de plantas',
				'Orgánico',
				90,
				20,
				5,
				15,
				20,
				80,
				95,
				10,
				95,
				90,
				20,
				25,
				15,
				50,
				20,
				15,
				35,
				90,
				10,
				25
			),
			'resinas_inflamables' => new Material(
				'Resinas inflamables',
				'Resinas naturales combustibles',
				'Resina',
				40,
				40,
				30,
				35,
				40,
				55,
				20,
				45,
				100,
				70,
				35,
				50,
				40,
				40,
				45,
				35,
				40,
				75,
				20,
				30
			),
			'carbono' => new Material(
				'Carbono',
				'Elemento químico básico',
				'Elemento',
				30,
				90,
				100,
				60,
				85,
				70,
				10,
				20,
				5,
				85,
				100,
				95,
				100,
				100,
				50,
				75,
				90,
				15,
				25,
				85
			),
			'hidrogeno' => new Material(
				'Hidrógeno',
				'Elemento químico gaseoso',
				'Elemento',
				95,
				5,
				5,
				1,
				5,
				95,
				10,
				5,
				5,
				100,
				5,
				5,
				5,
				5,
				5,
				5,
				80,
				100,
				30,
				15
			),
			'oxigeno' => new Material(
				'Oxígeno',
				'Elemento químico gaseoso',
				'Elemento',
				90,
				15,
				10,
				5,
				10,
				90,
				20,
				10,
				5,
				80,
				10,
				10,
				15,
				10,
				10,
				10,
				85,
				100,
				25,
				20
			),
			'nitrogeno' => new Material(
				'Nitrógeno',
				'Elemento químico gaseoso',
				'Elemento',
				80,
				10,
				8,
				4,
				8,
				85,
				25,
				15,
				5,
				60,
				15,
				15,
				10,
				8,
				8,
				12,
				80,
				100,
				20,
				25
			),
			'fosforo' => new Material(
				'Fósforo',
				'Elemento químico',
				'Elemento',
				75,
				30,
				35,
				40,
				50,
				60,
				40,
				50,
				95,
				70,
				50,
				55,
				30,
				40,
				45,
				35,
				65,
				45,
				80,
				30
			),
			'azufre' => new Material(
				'Azufre',
				'Elemento químico',
				'Elemento',
				60,
				25,
				30,
				35,
				40,
				50,
				15,
				55,
				80,
				40,
				60,
				40,
				25,
				35,
				35,
				30,
				70,
				40,
				75,
				35
			),
			'agua' => new Material(
				'Agua',
				'Compuesto esencial',
				'Compuesto',
				100,
				5,
				1,
				1,
				2,
				100,
				100,
				1,
				1,
				5,
				1,
				5,
				5,
				1,
				10,
				1,
				95,
				100,
				5,
				40
			),
			'sal' => new Material(
				'Sal',
				'Compuesto mineral',
				'Compuesto',
				50,
				20,
				15,
				25,
				35,
				25,
				35,
				60,
				1,
				20,
				20,
				35,
				20,
				25,
				25,
				20,
				85,
				35,
				15,
				55
			),
			'tierras_fertiles' => new Material(
				'Tierras fértiles',
				'Suelo rico en nutrientes',
				'Recurso natural',
				85,
				45,
				20,
				30,
				30,
				60,
				90,
				30,
				1,
				30,
				40,
				55,
				40,
				30,
				30,
				45,
				45,
				80,
				10,
				45
			),
			'gases_naturales' => new Material(
				'Gases naturales',
				'Mezcla de gases combustibles',
				'Recurso natural',
				90,
				10,
				10,
				5,
				20,
				90,
				15,
				10,
				5,
				80,
				15,
				10,
				15,
				10,
				10,
				10,
				75,
				100,
				35,
				30
			),
			'materiales_aislantes' => new Material(
				'Materiales aislantes naturales',
				'Materiales con propiedades aislantes',
				'Recurso natural',
				95,
				20,
				10,
				10,
				20,
				80,
				95,
				20,
				10,
				25,
				20,
				25,
				30,
				10,
				20,
				15,
				55,
				75,
				15,
				65
			),
			'neodimio' => new Material(
				'Neodimio',
				'Metal de tierras raras',
				'Elemento',
				40,
				80,
				60,
				85,
				90,
				50,
				10,
				60,
				1,
				90,
				85,
				70,
				80,
				85,
				50,
				65,
				80,
				25,
				45,
				70
			),
			'lantano' => new Material(
				'Lantano',
				'Metal de tierras raras',
				'Elemento',
				50,
				70,
				55,
				75,
				85,
				45,
				20,
				50,
				1,
				80,
				80,
				65,
				70,
				75,
				60,
				60,
				75,
				30,
				40,
				65
			),
			'cerio' => new Material(
				'Cerio',
				'Metal de tierras raras',
				'Elemento',
				45,
				65,
				50,
				70,
				80,
				40,
				15,
				45,
				1,
				75,
				75,
				60,
				65,
				70,
				55,
				55,
				70,
				35,
				35,
				60
			),
			'grafito' => new Material(
				'Grafito',
				'Forma cristalina del carbono',
				'Mineral',
				70,
				90,
				85,
				60,
				80,
				55,
				10,
				35,
				1,
				70,
				90,
				85,
				85,
				95,
				40,
				75,
				85,
				20,
				15,
				75
			),
			'semiconductores' => new Material(
				'Minerales semiconductores',
				'Materiales con propiedades semiconductoras',
				'Mineral',
				55,
				80,
				70,
				75,
				75,
				60,
				25,
				40,
				1,
				75,
				85,
				70,
				80,
				85,
				50,
				65,
				80,
				25,
				20,
				80
			),
			'cristales' => new Material(
				'Cristales naturales',
				'Minerales cristalinos',
				'Mineral',
				65,
				85,
				75,
				80,
				85,
				65,
				15,
				45,
				1,
				85,
				85,
				80,
				75,
				90,
				60,
				70,
				75,
				20,
				10,
				85
			),
			'magneticos' => new Material(
				'Materiales magnéticos naturales',
				'Materiales con propiedades magnéticas',
				'Mineral',
				50,
				75,
				70,
				80,
				85,
				40,
				10,
				50,
				1,
				80,
				80,
				65,
				70,
				85,
				50,
				65,
				85,
				15,
				25,
				75
			),
			'uranio' => new Material(
				'Uranio',
				'Elemento radiactivo',
				'Elemento',
				20,
				100,
				95,
				100,
				100,
				20,
				5,
				80,
				1,
				100,
				100,
				90,
				100,
				100,
				90,
				80,
				95,
				10,
				100,
				90
			),
		];
	}
}