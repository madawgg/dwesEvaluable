<?php
    include_once 'Material.php';

 abstract class Invento {
    protected string $nombre;
    protected int $nivel;
    protected static array $inventosPrevios = [];
    protected int $puntos = 0;
    protected string $zonaCreacion;
    protected string $metodoCreacion;
    protected DateTime $tiempoInicial;
    protected int $tiempoCreacion= 0;
    protected DateTime $tiempoFinal;

    abstract public function calcularEficiencia(): float; 
    abstract public function calcularPuntuacion(): int;
    abstract public function calcularTiempoCreacion(): int;
    

    public function __construct(string $nombre, int $nivel = 1,int $tiempoCreacion = 0){
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->tiempoInicial = new DateTime();
         
    }
    
    public function __get($atributo){
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
    public function getNombre(): string {
        return $this->nombre;
    }
    
    public function getNivel(): int{
        return $this->nivel;
    }

    public function getPuntos(): int{
        return $this->puntos;
    }

    //setters
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
        //return $this;
    }

    public function setNivel(int $nivel): void{
        $this->nivel = $nivel;
        //return $this;
    }
    public function setPuntos(int $puntos): void{
        //WARNING
        //si es necesario sumar puntos sobre puntos existentes, necesito modificar esta funcion
        $this->puntos=$puntos;
        //return $this; 
    }
    public function calcularTiempoTotal($tiempoCreacion): DateTime{
        $intervalo = new DateInterval('PT' . $tiempoCreacion . 'S');
        $horaFinal = (clone $this->tiempoInicial)->add($intervalo);
        return $horaFinal;
    }
    public static function probarInvento(array $argumentos = []): void{
        echo htmlspecialchars( 'El invento se ha probado.') .'</br>';
    }

    public function __toString(): string{
        $html = "<table class='invento-table'>";
        $html .= "<tr><th>Nombre</td><td>{$this->nombre}</th></tr>";
        $html .= "<tr><th>Nivel</td><td>{$this->nivel}</th></tr>"; 
         // Cerrar la tabla    
        return $html;
    }
}
?>