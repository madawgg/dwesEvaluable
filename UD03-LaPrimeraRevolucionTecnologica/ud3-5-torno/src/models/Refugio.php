<?php

include_once 'Material.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Refugio extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Prisma Rectangular';
    private const ESPACIO_PERSONAL = 2;
    private Material $materialTecho;
    private Material $materialParedes;
    private Material $materialSuelo;
    private float $eficienciaTecho;
    private float $eficienciaParedes;
    private float $eficienciaSuelo;
    private float $eficiencia;
    private int $superficie;
    private float $capacidadBase = 0;
    private int $capacidadPersonas;
    private int $puntuacion;
    private array $tamanyo = [];
    private array $tamanyoPrismas = [];
    private float $volumenPrismas = 0;
    protected static array $inventosPrevios=[];

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre, Material $materialTecho, Material $materialParedes, Material $materialSuelo, array $tamanyo, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 1);
        $this->materialTecho = $materialTecho;
        $this->materialParedes= $materialParedes;
        $this->materialSuelo = $materialSuelo;
        $this->tamanyo = $tamanyo;
        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
        $this->volumenPrismas = $this->calcularVolumenPrismas();
        $this->eficiencia = $this->calcularEficiencia();
        $this->superficie = $this->superficie($this->getFigura(), $tamanyo);
        $this->capacidadBase = $this->superficie / self::ESPACIO_PERSONAL;
        $this->capacidadPersonas = $this->calcularCapacidadPersonas();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();
      

    }
    
    public static function getInventosPrevios(): array {
        return self::$inventosPrevios;
    }

    public function __get( $atributo){
        if(property_exists($this, $atributo)){
            return $this->$atributo;
         }else{
               echo 'La propiedad '. $atributo .' no existe';
        }
    }
        
    public function __set($atributo, $valor){
        if(property_exists($this, $atributo)){
            $this->$atributo = $valor;
        }else{
            echo 'No se ha podido introducir el valor de la propiedad '. $atributo;
        }
    }

    // Getters
    public function getFigura(): string{
        return self::FIGURA;
    }
    public function getMaterialTecho(): Material {
        return $this->materialTecho;
    }

    public function getMaterialParedes(): Material {
        return $this->materialParedes;
    }

    public function getMaterialSuelo(): Material {
        return $this->materialSuelo;
    }

    public function getTamanyo(): array{
        return $this->tamanyo;
    }

    public function getGrosor(): float{
        return $this->grosor;
    }

    public function getEficienciaTecho(): float{
        return $this->eficienciaTecho;
    }

    public function getEficienciaParedes(): float{
        return $this->eficienciaParedes;
    }

    public function getEficienciaSuelo(): float{
        return $this->eficienciaSuelo;
    }

    public function getSuperficie(): int{
        return $this->superficie;
    }

    public function getCapacidadBase(): float{
        return $this->capacidadBase;
    }

    public function getCapacidadPersonas(): int{
        return $this->capacidadPersonas;
    }

    public function getEspacioPersonal(): int{
        return self::ESPACIO_PERSONAL;
    }
    public function getPuntuacion():int{
        return $this->puntuacion;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }

    // Setters
    public function setMaterialTecho(Material $materialTecho): void{
        $this->materialTecho = $materialTecho;
    }

    public function setMaterialParedes(Material $materialParedes): void{
        $this->materialParedes = $materialParedes;
    }

    public function setMaterialSuelo(Material $materialSuelo): void{
        $this->materialSuelo = $materialSuelo;
    }

    public function setTamanyo(array $tamanyo): void{
        $this->tamanyo = $tamanyo;
    }
    public function setGrosor(float $grosor): void{
        $this->grosor = $grosor;
    }

    public function setEficienciaTecho(float $eficienciaTecho): void{
        $this->eficienciaTecho = $eficienciaTecho;
    }

    public function setEficienciaParedes(float $eficienciaParedes): void{
        $this->eficienciaParedes = $eficienciaParedes;
    }

    public function setEficienciaSuelo(float $eficienciaSuelo): void{
        $this->eficienciaSuelo = $eficienciaSuelo;
    }

    public function setSuperficie(int $superficie): void{
        $this->superficie = $superficie;
    }

    public function setCapacidadBase(float $capacidadBase): void{
        $this->capacidadBase = $capacidadBase;
    }

    public function setCapacidadPersonas(int $capacidadPersonas): void{
        $this->capacidadPersonas = $capacidadPersonas;
    }

    private function calcularPromedioResistencias(Material $material): float {
        $resistencias = [
            $material->resistenciaCompresion,
            $material->resistenciaHumedad,
            $material->resistenciaTemperatura,
            $material->resistenciaViento
        ];
        return array_sum($resistencias) / count($resistencias);
    }
    
    private function calcularCapacidadPersonas(): int{
        $capacidadBase = $this->capacidadBase;
        
        $promedioTecho = $this->calcularPromedioResistencias($this->materialTecho);
        $promedioParedes = $this->calcularPromedioResistencias($this->materialParedes);
        $promedioSuelo = $this->calcularPromedioResistencias($this->materialSuelo);
        $resistenciaPromedio = ($promedioTecho+$promedioParedes+$promedioSuelo) / 3;

        $capacidadPersonas = max(0, round($capacidadBase-((1-($resistenciaPromedio/100))*$capacidadBase)));
        return $capacidadPersonas;
    }

    public function calcularEficiencia(): float{
        try {
                
            $eficienciaTecho = $this->materialTecho->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $eficienciaParedes = $this->materialParedes->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            $eficienciaSuelo = $this->materialSuelo->calcularEficiencia([
                'beneficiosos' => ['resistenciaCompresion', 'resistenciaHumedad', 'resistenciaTemperatura','resistenciaViento'],
                'perjudiciales' => ['densidad', 'fragilidad']
            ]);
            
            $this->eficienciaTecho = $eficienciaTecho;
            $this->eficienciaParedes = $eficienciaParedes;
            $this->eficienciaSuelo = $eficienciaSuelo;
            
            $eficienciaRefugio = round(($eficienciaTecho + $eficienciaParedes + $eficienciaSuelo) / 3,2);
            
            return $eficienciaRefugio;

        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularTiempoCreacion(): int {
        $tiemposMateriales = [];

        $tiempoBase = 60 * $this->nivel; 
        
        $tiemposMateriales[] = $this->materialParedes->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        $tiemposMateriales[] = $this->materialTecho->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        $tiemposMateriales[] = $this->materialSuelo->calcularTiempoCreacion($this->zonaCreacion, $tiempoBase);
        
        $tiempoFinal = max($tiemposMateriales);
    
        switch ($this->metodoCreacion) {
            case 'tradicional':
                break; 
            case 'rapido':
                $tiempoFinal *= 0.75;
                $this->eficiencia -= 10;
                break;
            case 'detallado':
                $tiempoFinal *= 1.5;
                $this->eficiencia += 10;
                break;
            default:
                echo 'El método de creación no existe';
                return 0;
        }
    
        return round($tiempoFinal);
    }

    public function calcularPuntuacion(): int{
        if($this->eficiencia <= 0){
            return 0;
        }
        $puntuacion = match (true) {
            $this->capacidadPersonas > $this->capacidadBase * 0.75 => round($this->eficiencia) * 1.2,
            $this->capacidadPersonas > $this->capacidadBase * 0.50 => round($this->eficiencia) * 1.1,
            default => round($this->eficiencia)
        };
        return round($puntuacion);
    }

    //medible.php
    public function calcularPeso(): float{
        $densidadParedes = $this->materialParedes->densidad *4;
        $densidadTecho = $this->materialTecho->densidad;
        $densidadSuelo = $this->materialSuelo->densidad;
        $volumenPrismas = $this->volumenPrismas;

        $pesoParedes = $densidadParedes * $volumenPrismas;
        $pesoSuelo = $densidadSuelo * $volumenPrismas;
        $pesoTecho = $densidadTecho * $volumenPrismas;
        $peso = $pesoParedes + $pesoSuelo + $pesoTecho;
        return $peso;
    }
    public function calcularVolumenPrismas():float{
        $tamanyoPrismas = $this->tamanyo;
        $tamanyoPrismas['altura'] = $tamanyoPrismas['grosor'];
        $volumenPrismas = $this->volumen($this->getFigura(), $tamanyoPrismas);
        return $volumenPrismas;
    }
    public function calcularVolumen(): float{
        $volumen = $this->volumen($this->getFigura(), $this->tamanyo);
        return $volumen;
    }
    public function calcularArea(): float{
        $area = $this->area($this->getFigura(), $this->tamanyo);
        return $area;
    }
    public function calcularSuperficie(): float{
        $superficie = $this->superficie($this->getFigura(), $this->tamanyo);
        return $superficie;
    }
    public function obtenerTamanyo(): array{
        $tamanyo = $this->tamanyo;
        return $tamanyo;
    }

    public function __toString(): string{
        $figura = $this->getFigura();
        $longitud = $this->tamanyo['longitud'];
        $ancho = $this->tamanyo['ancho'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion). '</td></tr>';

        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';

        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (longitud x ancho x altura x grosor)</th><td>' . htmlspecialchars($longitud . ' x ' . $ancho .' x '.$altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($this->superficie) . '</td></tr>';
        $html .= '<tr><th>Capacidad Personas</th><td>' . htmlspecialchars($this->capacidadPersonas) . '</td></tr>';
        $html .= '<tr><th>Material Techo</th><td>';
        $html .=  $this->materialTecho->__toString();
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialTecho->resistenciaCompresion) . '</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialTecho->resistenciaHumedad) . '</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialTecho->resistenciaTemperatura) . '</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialTecho->resistenciaViento) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialTecho->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialTecho->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Eficiencia Techo</th><td>' . htmlspecialchars($this->eficienciaTecho) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '<tr><th>Material Paredes</th><td>';
        $html .=  $this->materialParedes->__toString();
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialParedes->resistenciaCompresion) . '</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialParedes->resistenciaHumedad) . '</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialParedes->resistenciaTemperatura) . '</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialParedes->resistenciaViento) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialParedes->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialParedes->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Eficiencia Paredes</th><td>' . htmlspecialchars($this->eficienciaParedes) . '</td></tr>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '<tr><th>Material Suelo</th><td>';
        $html .=  $this->materialSuelo->__toString();
        $html .= '<tr><th>ResistenciaCompresión</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaCompresion) . '</td></tr>';
        $html .= '<tr><th>ResistenciaHumedad</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaHumedad) . '</td></tr>';
        $html .= '<tr><th>ResistenciaTemperatura</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaTemperatura) . '</td></tr>';
        $html .= '<tr><th>ResistenciaViento</th><td>' . htmlspecialchars($this->materialSuelo->resistenciaViento) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->materialSuelo->densidad) . '</td></tr>';
        $html .= '<tr><th>Fragilidad</th><td>' . htmlspecialchars($this->materialSuelo->fragilidad) . '</td></tr>';
        $html .= '<tr><th>Eficiencia Suelo</th><td>' . htmlspecialchars($this->eficienciaSuelo) . '</td></tr>';
        $html .= '</table> </table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $refugio = new Refugio(...$argumentos);
        echo $refugio->__toString();
    }

}

?>