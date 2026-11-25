<?php
class ManejadorImagenes {
    private $directorioSubida;
    private $tamanoMaximo = 2097152; // 2MB
    private $formatosPermitidos = ['image/jpeg', 'image/png'];
    private $dimensionMaxima = 800;

    public function __construct() {
        $this->directorioSubida = $_SERVER['DOCUMENT_ROOT'] . '/CodigoYrecursos/assets/team/';
        if (!file_exists($this->directorioSubida)) {
            mkdir($this->directorioSubida, 0777, true);
        }
    }

    public function procesarImagenPerfil($archivo) {
        try {
            // Validaciones básicas
            if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error en la subida de la imagen');
            }

            // Validar formato
            if (!in_array($archivo['type'], $this->formatosPermitidos)) {
                throw new Exception('Formato no permitido. Use JPG o PNG');
            }

            // Validar tamaño
            if ($archivo['size'] > $this->tamanoMaximo) {
                throw new Exception('La imagen no debe superar 2MB');
            }

            // Generar nombre único
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('perfil_') . '.' . $extension;
            $rutaDestino = $this->directorioSubida . $nuevoNombre;

            // Optimizar y guardar
            $this->optimizarImagen($archivo['tmp_name'], $rutaDestino, $extension);

            return 'assets/team/' . $nuevoNombre;
        } catch (Exception $e) {
            throw new Exception('Error al procesar la imagen: ' . $e->getMessage());
        }
    }

    private function optimizarImagen($origen, $destino, $extension) {
        list($ancho, $alto) = getimagesize($origen);
        
        // Calcular nuevas dimensiones
        $ratio = $ancho / $alto;
        if ($ancho > $this->dimensionMaxima) {
            $ancho = $this->dimensionMaxima;
            $alto = $ancho / $ratio;
        }

        $nueva = imagecreatetruecolor($ancho, $alto);
        
        // Preservar transparencia para PNG
        if ($extension == 'png') {
            imagealphablending($nueva, false);
            imagesavealpha($nueva, true);
        }

        // Cargar imagen original
        $original = ($extension == 'png') ? 
                   imagecreatefrompng($origen) : 
                   imagecreatefromjpeg($origen);

        // Redimensionar
        imagecopyresampled($nueva, $original, 0, 0, 0, 0, $ancho, $alto, imagesx($original), imagesy($original));
        
        // Guardar imagen optimizada
        if ($extension == 'png') {
            imagepng($nueva, $destino, 8);
        } else {
            imagejpeg($nueva, $destino, 80);
        }

        // Liberar memoria
        imagedestroy($nueva);
        imagedestroy($original);
    }
}
?>