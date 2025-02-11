<?php
include_once 'Invento.php';
include_once 'Material.php';

class Cuerda extends Invento{
    private float $longitud;
    private Material $material;   
    private int $puntuacion;
    private float $eficiencia;


    public function __construct(string $nombre, float $longitud, Material $material){
        parent::__construct($nombre, 1);
        $this->longitud = $longitud;
        $this->material = $material;
        $this->eficiencia = $this->calcularEficiencia();
        $this->puntuacion = $this->calcularPuntuacion();
    }
    //getters
    public function getLongitud(): float {
        return $this->nombre;
    }
    public function getMaterial(): Material {
        return $this->material;
    }
    public function getPuntuacion(): int {
        return $this->puntuacion;
    }
    public function getEficiencia(): float {
        return $this->eficiencia;
    }

    //setters
    public function setLongitud(float $longitud): void{
        $this->longitud = $longitud;
    }
    public function setMaterial(Material $material): void{
        $this->material = $material;
    }
    public function setPuntuacion(int $puntuacion): void{
        $this->puntuacion = $puntuacion;
    }
    public function setEficiencia(float $eficiencia): void{
        $this->eficiencia = $eficiencia;
    }
    
    public function encriptar($text): string{
        $salt = '!&';
        $textoEncriptado = crypt($text, $salt);
        
        if ($textoEncriptado === "*0") {
            return $textoEncriptado;
        }
        
        $longitudCuerda = $this->longitud;
        
        if (strlen($textoEncriptado) < $longitudCuerda) {
            $textoEncriptado = str_pad($textoEncriptado, $longitudCuerda, $salt);
        } else {
            $textoEncriptado = substr($textoEncriptado, 0, $longitudCuerda);
        }     
        return $textoEncriptado;
    }
    public function calcularEficiencia(): float{
        try {

            return $this->material->calcularEficiencia([
                'beneficiosos' => ['resistenciaTraccion', 'flexibilidad'],
                'perjudiciales' => ['densidad', 'coeficienteDesgaste']
            ]);

        } catch (Exception $e) {
            return 0.00;
        }
    }

    public function calcularPuntuacion(): int{
        $nombreEncriptado = $this->encriptar($this->material->nombre);
        $factorEncriptacion = $this->longitud % 10;
        $ajustarEficiencia = $this->eficiencia + strlen($nombreEncriptado) + $factorEncriptacion;
        $this->puntuacion = round($ajustarEficiencia);

    return $this->puntuacion;
    }

    public function __toString(): string{
        $html = parent::__toString();
        $html .= '<tr><th>Eficiencia</th><td>' . htmlspecialchars($this->calcularEficiencia()) . '</td></tr>';
        $html .= '<tr><th>Puntos</th><td>' . htmlspecialchars($this->calcularPuntuacion()) . '</td></tr>';
        $html .= '<tr><th>Longitud</th><td>' . htmlspecialchars($this->longitud).' m' . '</td></tr>';
        $html .= '<tr><th>Material</th><td>';
        $html .=  $this->material->__toString();
        $html .= '<tr><th>Resistencia a la tracción</th><td>' . htmlspecialchars($this->material->resistenciaTraccion) . '</td></tr>';
        $html .= '<tr><th>Flexibilidad</th><td>' . htmlspecialchars($this->material->flexibilidad) . '</td></tr>';
        $html .= '<tr><th>Densidad</th><td>' . htmlspecialchars($this->material->densidad) . '</td></tr>';
        $html .= '<tr><th>Coeficiente de desgaste</th><td>' . htmlspecialchars($this->material->coeficienteDesgaste) . '</td></tr>';
        $html .= '</table></table>';
        
        return $html;
    }

    public static function probarInvento(array $argumentos = []): void{
        $cuerda = new Cuerda(...$argumentos);

        echo $cuerda->__toString();
    }

}

?>