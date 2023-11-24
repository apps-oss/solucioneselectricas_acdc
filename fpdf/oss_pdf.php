<?php
require('fpdf/mc_table.php');

/**
 * Clase OSS_PDF para generar informes PDF personalizados.
 */
class OSS_PDF extends PDF_MC_Table
{
    /** @var array Información de la sucursal. */
    private $sucursal;

    /** @var string Título del informe. */
    private $tituloReporte;

    /** @var int Altura de las celdas. */
    private $alturaCelda = 5;

    /** @var string Fuente de la familia de fuentes. */
    private $familyFont = 'Arial';

    /** @var int Tamaño de fuente. */
    private $sizeFont = 12;

    /**
     * @var array Informacion adicional para mostrar en el enbezado despues del titulo de reporte,
     * una linea por cada index del arreglo
     * */
    private $encabezado_adicional = [];

    public function setEncabezadoAdicional($array)
    {
        $this->encabezado_adicional = $array;
    }

    /**
     * Establece la información de la sucursal.
     *
     * @param array $sucursal Información de la sucursal.
     */
    public function setSucursal($sucursal)
    {
        $this->sucursal = $sucursal;
    }

    /**
     * Establece el título del informe.
     *
     * @param string $titulo Título del informe.
     */
    public function setTituloReporte($titulo)
    {
        $this->tituloReporte = $titulo;
    }

    /**
     * Establece la altura de las celdas.
     *
     * @param int $altura Altura de las celdas.
     */
    public function setAlturaCelda($altura)
    {
        $this->alturaCelda = $altura;
    }

    /**
     * Método para definir la lógica de la cabecera de la tabla.
     */
    public function cabeceraTabla()
    {
        // Reemplaza esta función en subclases para personalizar la cabecera de la tabla.
    }

    /**
     * Establece la familia de fuente a utilizar.
     *
     * @param string $font Nombre de la familia de fuentes.
     */
    public function setFamilyFont($font)
    {
        $this->familyFont = $font;
    }

    /**
     * Establece el tamaño de fuente a utilizar.
     *
     * @param int $size Tamaño de fuente.
     */
    public function setSizeFont($size)
    {
        $this->sizeFont = $size;
    }

    /**
     * Genera la cabecera del documento PDF.
     */
    function Header()
    {
        $this->SetFont($this->familyFont, 'B', 12);
        $sucursal = $this->sucursal;

        if (!empty($sucursal)) {
            $telefonos = '';
            $nrc_nit = '';
            $width_page = $this->GetPageWidth();
            $ancho_celda = 0;
            $x_con_imagen = 50;
            $imagen = false;

            // LOGO
            $logo = isset($sucursal['logo']) ? $sucursal['logo'] : '';

            if (!empty($logo) && file_exists($logo)) {
                $this->Image($logo, $this->GetX(), $this->GetY(), -1150);
                $ancho_celda = $width_page - 100;
                $imagen = true;
            }

            // Telefonos
            if (isset($sucursal['telefono']) && !empty($sucursal['telefono'])) {
                $telefonos = 'TEL: ' . $sucursal['telefono'];
            }

            if (isset($sucursal['telefono1']) && !empty($sucursal['telefono1'])) {
                $telefonos = (empty($telefonos) ? 'TEL: ' : '/') . $sucursal['telefono1'];
            }

            if (isset($sucursal['telefono2']) && !empty($sucursal['telefono2'])) {
                $telefonos .= (empty($telefonos) ? 'TEL: ' : '/') . $sucursal['telefono2'];
            }

            // NRC Y NIT
            if (isset($sucursal['nrc']) && !empty($sucursal['nrc'])) {
                $nrc_nit .= 'NRC: ' . $sucursal['nrc'];
            }

            // IMPRIMIR

            if (isset($sucursal['nit']) && !empty($sucursal['nit'])) {
                $nrc_nit .= (empty($nrc_nit) ? '' : ' / ') . 'NIT: ' . $sucursal['nit'];
            }

            if (isset($sucursal['descripcion']) && !empty($sucursal['descripcion'])) {
                if ($imagen) {
                    $this->SetX($x_con_imagen);
                }
                $this->Cell($ancho_celda, $this->alturaCelda, $sucursal['descripcion'], 0, 1, 'C');
            }

            if (isset($sucursal['direccion']) && !empty($sucursal['direccion'])) {
                if ($imagen) {
                    $this->SetX($x_con_imagen);
                }
                $this->MultiCell($ancho_celda, $this->alturaCelda, $sucursal['direccion'], 0, 'C');
            }

            if (!empty($telefonos)) {
                if ($imagen) {
                    $this->SetX($x_con_imagen);
                }
                $this->Cell($ancho_celda, $this->alturaCelda, $telefonos, 0, 1, 'C');
            }

            if (!empty($nrc_nit)) {
                if ($imagen) {
                    $this->SetX($x_con_imagen);
                }
                $this->Cell($ancho_celda, $this->alturaCelda, $nrc_nit, 0, 1, 'C');
            }
        }

        if (!empty($this->tituloReporte)) {
            if ($imagen) {
                $this->SetX($x_con_imagen);
            }
            $this->Cell($ancho_celda, $this->alturaCelda, $this->tituloReporte, 0, 1, 'C');
        }

        if (!empty($this->encabezado_adicional)) {
            foreach ($this->encabezado_adicional as $valor) {
                if ($imagen) {
                    $this->SetX($x_con_imagen);
                }
                $this->Cell($ancho_celda, $this->alturaCelda, $valor, 0, 1, 'C');
            }
        }

        $this->Ln();

        $this->SetFont($this->familyFont, 'B', $this->sizeFont - 1);
        $this->cabeceraTabla();
        $this->SetFont($this->familyFont, '', $this->sizeFont);
    }

    /**
     * Genera el pie de página del documento PDF.
     */
    function Footer()
    {
        // Go to 1.5 cm from the bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo()), 0, 0, 'C');
    }
}
