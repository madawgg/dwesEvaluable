<?php

include_once 'Invento.php';
include_once 'Material.php';
include_once 'Cuerda.php';
include_once 'ArcoFlecha.php';
include_once 'Cesta.php';
include_once 'src/traits/CalculosGeometricos.php';
include_once 'src/interfaces/Medible.php';

class Trampa extends Invento implements Medible{
    use CalculosGeometricos;
    private const FIGURA = 'Cilindro';
    private Cuerda $cuerda;
    private Cesta $cesta;
    private ArcoFlecha $arcoFlecha; 
    private float $visibilidad;
    private float $eficiencia;
    private int $puntuacion;
    private array $tamanyo;
    protected static array $inventosPrevios = [];

    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected int $tiempoCreacion;

    public function __construct(string $nombre,Cuerda $cuerda, Cesta $cesta, ArcoFlecha $arcoFlecha, float $visibilidad, string $zonaCreacion = null, string $metodoCreacion = null){
        parent::__construct($nombre, 1);

        $this->cuerda = $cuerda;
        $this->cesta = $cesta;
        $this->arcoFlecha = $arcoFlecha;
        $this->visibilidad = $visibilidad;
        self::$inventosPrevios = [
            'Cuerda' => 1,
            'Cesta' => 1,
            'ArcoFlecha' =>1
        ];
        $this->tamanyo = $cesta->getTamanyo();

        $this->zonaCreacion = $zonaCreacion ?? 'pradera';
        $this->metodoCreacion = $metodoCreacion ?? 'tradicional';
        $this->eficiencia = $this->calcularEficiencia();
        $this->tiempoCreacion = $this->calcularTiempoCreacion();
        $this->tiempoFinal = $this->calcularTiempoTotal($this->tiempoCreacion);
        $this->puntuacion = $this->calcularPuntuacion();

    }
    public function getFigura(){
        return self::FIGURA;
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

    //getters
    public function getCuerda(): Cuerda{
        return $this->cuerda;
    }
    public function getCesta(): Cesta{
        return $this->cesta;
    }
    public function getArcoFlecha(): ArcoFlecha{
        return $this->arcoFlecha;
    }
    public function getVisibilidad(): float{
        return $this->visibilidad;
    }
    public function getEficiencia(): float{
        return $this->eficiencia;
    }
    public function getPuntuacion(): int{
        return $this->puntuacion;
    }
    
    //setters
    public function setCuerda(Cuerda $cuerda): void{
        $this->cuerda = $cuerda;
    }
    public function setCesta(Cesta $cesta): void{
        $this->cesta = $cesta;
    }
    public function setArcoFlecha(ArcoFlecha $arcoFlecha): void{
        $this->arcoFlecha = $arcoFlecha;
    }
    public function setVisibilidad(float $visibilidad): void{
        $this->visibilidad = $visibilidad;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }
    public function calcularEficiencia(): float{
        try{
            $eficienciaCuerda = $this->cuerda->getEficiencia();
            $eficienciaCesta = $this->cesta->getEficiencia();
            $eficienciaArcoFlecha = $this->arcoFlecha->getEficiencia();

            $eficienciaTrampa = round(($eficienciaCuerda+$eficienciaCesta+$eficienciaArcoFlecha)/3, 2);
            return $eficienciaTrampa;

        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularTiempoCreacion(): int {
        $tiempoBase = 60 * $this->nivel; 
        $tiempoFinal = $tiempoBase;

        switch ($this->metodoCreacion) {
            case 'tradicional':
                $tiempoFinal;
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
        $eficienciaRedondeada = round($this->eficiencia);
        $restaVisibilidad = 1 - $this->visibilidad;
        
        $puntuacion = $eficienciaRedondeada * $restaVisibilidad;
        
        return floor($puntuacion);
    }

    public function calcularPeso(): float{
        $pesoArco = $this->arcoFlecha->calcularPeso(); 
        $pesoCuerda = $this->cuerda->calcularPeso();
        $pesoCesta = $this->cesta->calcularPeso();
        
        $peso = $pesoArco + $pesoCuerda + $pesoCesta;
        return $peso;
    }
    public function calcularVolumen(): float{
       $volumen =  $this->volumen($this->getFigura(), $this->tamanyo);
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
        $radio = $this->tamanyo['radio'];
        $altura = $this->tamanyo['altura'];
        $grosor = $this->tamanyo['grosor'];
        $peso = $this->calcularPeso();
        $volumen = $this->calcularVolumen();
        $area = $this->calcularArea();
        $superficie = $this->calcularSuperficie();

        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->eficiencia) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->puntuacion) . '</td></tr>';
        
        $html .= '<tr><th>Zona de creación</th><td>' . htmlspecialchars($this->zonaCreacion) . '</td></tr>';
        $html .= '<tr><th>Metodo de creación</th><td>' . htmlspecialchars($this->metodoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de inicio</th><td>' . htmlspecialchars($this->tiempoInicial->format('Y-m-d H:i:s')) . '</td></tr>';
        $html .= '<tr><th>Tiempo de creación</th><td>' . htmlspecialchars($this->tiempoCreacion) . '</td></tr>';
        $html .= '<tr><th>Tiempo de fin</th><td>' . htmlspecialchars($this->tiempoFinal->format('Y-m-d H:i:s')) . '</td></tr>';
        
        $html .= '<tr><th colspan=2>Inventos previos</th></tr>';
        foreach (self::$inventosPrevios as $invento => $valor) {
            $html .= '<tr><th>'.$invento.'</th><td>' . $valor . '</td></tr>';
        }
        $html .= '<tr><th>Visibilidad</th><td>' . htmlspecialchars($this->visibilidad) . '</td></tr>';
        $html .= '<tr><th>Figura</th><td>' . htmlspecialchars($figura) . '</td></tr>';
        $html .= '<tr><th>Tamaño (radio x altura x grosor)</th><td>' . htmlspecialchars($radio . ' x ' . $altura . ' x ' . $grosor) . '</td></tr>';
        $html .= '<tr><th>Peso</th><td>' . htmlspecialchars($peso) . '</td></tr>';
        $html .= '<tr><th>Volumen</th><td>' . htmlspecialchars($volumen) . '</td></tr>';
        $html .= '<tr><th>Área</th><td>' . htmlspecialchars($area) . '</td></tr>';
        $html .= '<tr><th>Superficie</th><td>' . htmlspecialchars($superficie) . '</td></tr>';
        $html .= '</td><tr><th>Cuerda</th><td>';
        $html .= $this->cuerda->__toString(); 
        $html .= '</td><tr><th>Cesta</th><td>';
        $html .= $this->cesta->__toString(); 
        $html .= '</td><tr><th>Arco y flecha</th><td>';
        $html .= $this->arcoFlecha->__toString();
        $html.= '</td></table>';

        return $html;
    }

    public static function probarInvento(array $argumentos= []): void{
        $trampa = new Trampa(...$argumentos);
        echo $trampa->__toString();
    }

}
?>