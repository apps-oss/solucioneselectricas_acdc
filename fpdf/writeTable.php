<?php

class PDFwT extends FPDF
{
    // Variables del analizador html
    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;

    /**
     * Contiene el path de la imagen del header
     *
     * @var string header image
     */
    protected $header_image;

    /**
     * Contiene el path de la imagen del footer
     *
     * @var string header image
     */
    protected $footer_image;

    /**
     * Nombre de la empresa a mostrar en el reporte
     *
     * @var string
     */
    protected $company_name;

    /**
     * Nombre del reporte
     *
     * @var string report name
     */
    protected $report_name;

    /**
     * Nombre del reporte
     *
     * @var string report name
     */
    protected $subtitle;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'letter')
    {
        // Llamar al constructor principal
        parent::__construct($orientation, $unit, $format);
        // Inicialización
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
        $this->fontlist = array('arial', 'times', 'courier', 'helvetica', 'symbol');
        $this->issetfont = false;
        $this->issetcolor = false;

        $this->header_image = 'img/logo_sys.png';
        $this->company_name = "OPERACIONES DG";
        $this->report_name = "";
        $this->subtitle = "";
    }

    /**
     * Contiene los ajustes del reporte
     *
     * @return void
     */
    protected function docSettings()
    {

        // $this->SetMargins(10, 10);
        // $this->SetLeftMargin(5);
        $this->AliasNbPages();
        $this->SetAutoPageBreak(true, 15);
        $this->AliasNbPages();
    }

    /**
     * Establece el nombre del reporte
     *
     * @param string $report_name
     * @return void
     */
    public function setReportName(string $report_name)
    {
        $this->report_name = $report_name;
    }

    /**
     * Establece el suptitulo del reporte
     *
     * @param string $report_name
     * @return void
     */
    public function setSubtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Crea el header del reporte
     *
     * Se ejecuta automaticamente al agregar una pagina
     *
     * @return void
     */
    public function header()
    {
        // set default settings
        $this->docSettings();
        //$this->SetAutoPageBreak(true, 25);

        $this->SetAutoPageBreak(false);
        // draw the header logo
        $this->Image($this->header_image, 10, 5, 20, 20);

        // show company name
        // $this->setX(5);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(
            $this->width(),
            5,
            utf8_decode(mb_strtoupper($this->company_name)),
            0,
            1,
            'C',
            0
        );

        // show report name
        $this->SetFont('Arial', '', 10);
        $this->Cell(
            $this->width(),
            5,
            utf8_decode(mb_strtoupper($this->report_name)),
            0,
            1,
            'C',
            0
        );

        // show report name
        $this->SetFont('Arial', '', 10);
        $this->Cell(
            $this->width(),
            5,
            utf8_decode(mb_strtoupper($this->subtitle)),
            0,
            1,
            'C',
            0
        );

        $this->Ln();
    }

    /**
     * Crea el footer del documento
     *
     * Se ejecuta automaticamente al agregar una pagina
     *
     * @return void
     */
    public function footer()
    {
        // Write printing date
        $this->SetY(-20);
        $this->SetX(10);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(
            100,
            10,
            (utf8_decode('Fecha de impresión: ') . date('d-m-Y')),
            0,
            0,
            'L'
        );

        // Write page number
        $this->SetTextColor(255, 255, 255);
        $this->SetX($this->w - 118);
        $this->Cell(
            110,
            10,
            utf8_decode('Página ') . $this->PageNo() . '/{nb}',
            0,
            0,
            'R'
        );
    }

    /**
     * Imprime una linea de texto, permitiendo ajustar el alineado y el tamaño de
     * fuente, por defecto tomara todo el espacio del documento para el width y un
     * alto de celda de 4mm
     *
     * @param string $txt Cadena a imprimir
     * @param string $align Permite centrar o alinear el texto. Los posibles valores son:
     *
     * L o cadena vacía: alineación a la izquierda (valor predeterminado)
     * C: centro
     * R: alineación a la derecha
     * FJ: forzar justificado
     *
     * @param integer $fontSize tamaño de fuente por, Si es 0 tomara el valor por defecto
     * @param integer $width Ancho de celda. Si es 0, la celda se extiende hasta el margen derecho.
     * @param integer $heigth Altura de la celda (valor predeterminado 4)
     * @return void
     */
    public function writeLine(string $txt, string $align = 'L', int $fontSize = 0, int $width = 0, int $heigth = 4)
    {
        // Almacenamos el tamaño anterior de la fuente
        $old_font_size = $this->FontSizePt;

        $txt = utf8_decode($txt);

        if ($fontSize != 0) {
            $this->SetFontSize($fontSize);
        }

        $this->cell($width, $heigth, $txt, 0, 0, $align, false);
        $this->Ln();

        // Regresamos la fuente a su tamaño original
        if ($fontSize != 0) {
            $this->SetFontSize($old_font_size);
        }
    }

    /**
     * Imprime un parrafo de texto, permitiendo ajustar el alineado y el tamaño de
     * fuente, por defecto tomara todo el espacio del documento para el width y un
     * alto de celda de 4mm
     *
     * @param string $txt Cadena a imprimir
     * @param string $align Permite centrar o alinear el texto. Los posibles valores son:
     *
     * L o cadena vacía: alineación a la izquierda (valor predeterminado)
     * C: centro
     * R: alineación a la derecha
     * FJ: forzar justificado
     *
     * @param integer $fontSize tamaño de fuente por, Si es 0 tomara el valor por defecto
     * @param integer $width Ancho de celda. Si es 0, la celda se extiende hasta el margen derecho.
     * @param integer $heigth Altura de la celda (valor predeterminado 4)
     * @return void
     */
    public function writeMultiLine(string $txt, string $align = 'L', int $fontSize = 0, int $width = 0, int $heigth = 4)
    {
        // Almacenamos el tamaño anterior de la fuente
        $old_font_size = $this->FontSizePt;

        $txt = utf8_decode($txt);

        if ($fontSize != 0) {
            $this->SetFontSize($fontSize);
        }

        $this->multiCell($width, $heigth, $txt, 0, $align, false);
        $this->Ln();

        // Regresamos la fuente a su tamaño original
        if ($fontSize != 0) {
            $this->SetFontSize($old_font_size);
        }
    }


    /**
     * Imprime una celda (área rectangular) con bordes opcionales, color de fondo y cadena de caracteres. La esquina
     * superior izquierda de la celda corresponde a la posición actual. El texto se puede alinear o centrar. Después
     * de la llamada, la posición actual se mueve hacia la derecha o hacia la siguiente línea. Es posible poner un
     * enlace al texto. Si el salto de página automático está habilitado y la celda supera el límite, se realiza un
     * salto de página antes de la salida
     *
     * Es una reinterpretacion de la funcion Cell que permite forzar el justificado del texto.
     *
     * @param integer $w Ancho de celda. Si es 0, la celda se extiende hasta el margen derecho.
     * @param integer $h Altura de la celda. Valor predeterminado: 0.
     * @param string $txt Cadena para imprimir. Valor predeterminado: cadena vacía.
     * @param integer|string $border Indica si se deben dibujar bordes alrededor de la celda.
     * El valor puede ser un número:
     *
     * 0: sin borde
     * 1: marco
     *
     * o una cadena que contenga algunos o todos los siguientes caracteres (en cualquier orden):
     *
     * L: izquierda
     * T: arriba
     * R: derecha
     * B: abajo
     *
     * Valor predeterminado: 0.
     *
     * @param integer $ln Indica dónde debe ir la posición actual después de la llamada. Los posibles valores son:
     *
     * 0: a la derecha
     * 1: al principio de la siguiente línea
     * 2: abajo
     *
     * @param string $align Permite centrar o alinear el texto. Los posibles valores son:
     *
     * L o cadena vacía: alineación a la izquierda (valor predeterminado)
     * C: centro
     * R: alineación a la derecha
     * FJ: forzar justificado
     *
     * @param boolean $fill Indica si el fondo de la celda debe estar pintado
     * (verdadero) o transparente (falso). Valor predeterminado: falso.
     * @param string $link URL o identificador devuelto por AddLink().
     * @return void
     */
    public function cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $k = $this->k;
        if ($this->y + $h > $this->PageBreakTrigger
            && !$this->InHeader
            && !$this->InFooter
            && $this->AcceptPageBreak()
        ) {
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw', $ws * $k));
            }
        }
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $s = '';
        if ($fill || $border == 1) {
            if ($fill) {
                $op = ($border == 1) ? 'B' : 'f';
            } else {
                $op = 'S';
            }
            $s = sprintf(
                '%.2F %.2F %.2F %.2F re %s ',
                $this->x * $k,
                ($this->h - $this->y) * $k,
                $w * $k,
                -$h * $k,
                $op
            );
        }
        if (is_string($border)) {
            $x = $this->x;
            $y = $this->y;
            if (is_int(strpos($border, 'L'))) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    $x * $k,
                    ($this->h - $y) * $k,
                    $x * $k,
                    ($this->h - ($y + $h)) * $k
                );
            }
            if (is_int(strpos($border, 'T'))) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    $x * $k,
                    ($this->h - $y) * $k,
                    ($x + $w) * $k,
                    ($this->h - $y) * $k
                );
            }
            if (is_int(strpos($border, 'R'))) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    ($x + $w) * $k,
                    ($this->h - $y) * $k,
                    ($x + $w) * $k,
                    ($this->h - ($y + $h)) * $k
                );
            }
            if (is_int(strpos($border, 'B'))) {
                $s .= sprintf(
                    '%.2F %.2F m %.2F %.2F l S ',
                    $x * $k,
                    ($this->h - ($y + $h)) * $k,
                    ($x + $w) * $k,
                    ($this->h - ($y + $h)) * $k
                );
            }
        }
        if ($txt != '') {
            if ($align == 'R') {
                $dx = $w - $this->cMargin - $this->GetStringWidth($txt);
            } elseif ($align == 'C') {
                $dx = ($w - $this->GetStringWidth($txt)) / 2;
            } elseif ($align == 'FJ') {
                // Establecer espaciado de palabras
                $wmax = ($w - 2 * $this->cMargin);
                if (substr_count($txt, ' ') != 0) {
                    $this->ws = ($wmax - $this->GetStringWidth($txt)) / substr_count($txt, ' ');
                    $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                } else {
                    $this->ws = 1;
                    $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                }
                $dx = $this->cMargin;
            } else {
                $dx = $this->cMargin;
            }
            $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
            if ($this->ColorFlag) {
                $s .= 'q ' . $this->TextColor . ' ';
            }
            $s .= sprintf(
                'BT %.2F %.2F Td (%s) Tj ET',
                ($this->x + $dx) * $k,
                ($this->h - ($this->y + .5 * $h + .3 * $this->FontSize)) * $k,
                $txt
            );
            if ($this->underline) {
                $s .= ' ' . $this->_dounderline(
                    $this->x + $dx,
                    $this->y + .5 * $h + .3 * $this->FontSize,
                    $txt
                );
            }
            if ($this->ColorFlag) {
                $s .= ' Q';
            }
            if ($link) {
                if ($align == 'FJ') {
                    $wlink = $wmax;
                } else {
                    $wlink = $this->GetStringWidth($txt);
                }
                $this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $wlink, $this->FontSize, $link);
            }
        }
        if ($s) {
            $this->_out($s);
        }
        if ($align == 'FJ') {
            //Remove word spacing
            $this->_out('0 Tw');
            $this->ws = 0;
        }
        $this->lasth = $h;
        if ($ln > 0) {
            $this->y += $h;
            if ($ln == 1) {
                $this->x = $this->lMargin;
            }
        } else {
            $this->x += $w;
        }
    }

    /**
     * Este método permite imprimir texto con saltos de línea. Pueden ser automáticos (tan pronto como el texto llegue
     * al borde derecho de la celda) o explícitos (a través del carácter \ n). Se emiten tantas celdas como sea
     * necesario, una debajo de la otra. El texto puede estar alineado, centrado o justificado. Se puede enmarcar el
     * bloque de celdas y pintar el fondo.
     *
     * Es una reinterpretacion de la funcion MultiCell que permite forzar el justificado del texto.
     *
     * @param integer $w Ancho de celdas. Si es 0, se extienden hasta el margen derecho de la página.
     * @param integer $h Altura de las celdas.
     * @param string $txt Cadena para imprimir.
     * @param integer $border Indica si se deben dibujar bordes alrededor de la celda.
     * El valor puede ser un número:
     *
     * 0: sin borde
     * 1: marco
     *
     * o una cadena que contenga algunos o todos los siguientes caracteres (en cualquier orden):
     *
     * L: izquierda
     * T: arriba
     * R: derecha
     * B: abajo
     *
     * Valor predeterminado: 0.
     * @param string $align Permite centrar o alinear el texto. Los posibles valores son:
     *
     * L o cadena vacía: alineación a la izquierda (valor predeterminado)
     * C: centro
     * R: alineación a la derecha
     * FJ: forzar justificado
     * @param boolean $fill Indica si el fondo de la celda debe estar pintado
     * @return void
     */
    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        // Output text with automatic or explicit line breaks
        if (!isset($this->CurrentFont)) {
            $this->Error('No font has been set');
        }
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $b = 0;
        if ($border) {
            if ($border == 1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (strpos($border, 'L') !== false) {
                    $b2 .= 'L';
                }
                if (strpos($border, 'R') !== false) {
                    $b2 .= 'R';
                }
                $b = (strpos($border, 'T') !== false) ? $b2 . 'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $this->cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2) {
                    $b = $b2;
                }
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    $this->cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
                } else {
                    if ($align == 'J') {
                        $this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws * $this->k));
                    }
                    $this->cell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill);
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl == 2) {
                    $b = $b2;
                }
            } else {
                $i++;
            }
        }
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        if ($border && strpos($border, 'B') !== false) {
            $b .= 'B';
        }
        $this->cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
        $this->x = $this->lMargin;
    }

    public function write($h, $txt, $align = '', $link = '')
    {
        // Salida de texto en modo fluido
        if (!isset($this->CurrentFont)) {
            $this->Error('No font has been set');
        }
        $cw = &$this->CurrentFont['cw'];
        $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            // Obtener el siguiente caracter
            $c = $s[$i];
            if ($c == "\n") {
                // Salto de línea explícito
                $this->cell($w, $h, substr($s, $j, $i - $j), 0, 2, $align, false, $link);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                if ($nl == 1) {
                    $this->x = $this->lMargin;
                    $w = $this->w - $this->rMargin - $this->x;
                    $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
                }
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                // Salto de línea automático
                if ($sep == -1) {
                    if ($this->x > $this->lMargin) {
                        // Mover a la siguiente línea
                        $this->x = $this->lMargin;
                        $this->y += $h;
                        $w = $this->w - $this->rMargin - $this->x;
                        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
                        $i++;
                        $nl++;
                        continue;
                    }
                    if ($i == $j) {
                        $i++;
                    }
                    $this->cell($w, $h, substr($s, $j, $i - $j), 0, 2, $align, false, $link);
                } else {
                    $this->cell($w, $h, substr($s, $j, $sep - $j), 0, 2, $align, false, $link);
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                if ($nl == 1) {
                    $this->x = $this->lMargin;
                    $w = $this->w - $this->rMargin - $this->x;
                    $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
                }
                $nl++;
            } else {
                $i++;
            }
        }
        // Último fragmento
        if ($i != $j) {
            $this->cell($l / 1000 * $this->FontSize, $h, substr($s, $j), 0, 0, $align, false, $link);
        }
    }

    /**
     * Interpreta el codigo HTML permitiendole agregar estilos al texto
     *
     * @param String $html
     * @return void
     */
    public function writeHTML($html, $align = '', $fontSize = 0)
    {
        $html = utf8_decode($html);

        // Almacenamos el tamaño anterior de la fuente
        $old_font_size = $this->FontSizePt;

        if ($fontSize != 0) {
            $this->SetFontSize($fontSize);
        }

        //HTML parser
        $html = strip_tags($html, "<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
        $html = str_replace("\n", ' ', $html); //remplace retour à la ligne par un espace
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                //Text
                if ($this->HREF) {
                    $this->putLink($this->HREF, $e);
                } else {
                    $this->write(5, stripslashes($this->txtentities($e)), $align);
                }
            } else {
                //Tag
                if ($e[0] == '/') {
                    $this->closeTag(strtoupper(substr($e, 1)));
                } else {
                    //Extract attributes
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3)) {
                            $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                    $this->openTag($tag, $attr);
                }
            }
        }

        if ($fontSize != 0) {
            $this->SetFontSize($old_font_size);
        }
    }


    /**
     * Abre la etiqueta HTML
     *
     * @param string $tag
     * @param string $attr
     * @return void
     */
    protected function openTag($tag, $attr)
    {
        // Etiquete de apertura
        switch ($tag) {
            case 'STRONG':
                $this->setStyle('B', true);
                break;
            case 'EM':
                $this->setStyle('I', true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->setStyle($tag, true);
                break;
            case 'A':
                $this->HREF = $attr['HREF'];
                break;
            case 'IMG':
                if (isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if (!isset($attr['WIDTH'])) {
                        $attr['WIDTH'] = 0;
                    }
                    if (!isset($attr['HEIGHT'])) {
                        $attr['HEIGHT'] = 0;
                    }
                    $this->Image(
                        $attr['SRC'],
                        $this->GetX(),
                        $this->GetY(),
                        $this->px2mm($attr['WIDTH']),
                        $this->px2mm($attr['HEIGHT'])
                    );
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR'] != '') {
                    $coul = $this->hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'], $coul['V'], $coul['B']);
                    $this->issetcolor = true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont = true;
                }
                break;
        }
    }

    /**
     * Cierra la etiqueta HTML
     *
     * @param string $tag
     * @return void
     */
    protected function closeTag(string $tag)
    {
        // Etiqueta de cierre
        if ($tag == 'STRONG') {
            $tag = 'B';
        }
        if ($tag == 'EM') {
            $tag = 'I';
        }
        if ($tag == 'B' || $tag == 'I' || $tag == 'U') {
            $this->setStyle($tag, false);
        }
        if ($tag == 'A') {
            $this->HREF = '';
        }
        if ($tag == 'FONT') {
            if ($this->issetcolor == true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont = false;
            }
        }
    }

    protected function setStyle($tag, $enable)
    {
        //Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    protected function putLink($URL, $txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0, 0, 255);
        $this->setStyle('U', true);
        $this->cell(5, $txt, $URL);
        $this->setStyle('U', false);
        $this->SetTextColor(0);
    }

    /**
     * Funcion hex2dec
     * devuelve una matriz asociativa (claves: R, G, B) de
     * un código html hexadecimal (por ejemplo, # 3FE5AA)
     *
     * @param string $color color hexadecimal
     *
     * @return array
     */
    protected function hex2dec($color = "#000000")
    {
        $R = substr($color, 1, 2);
        $red = hexdec($R);
        $G = substr($color, 3, 2);
        $green = hexdec($G);
        $B = substr($color, 5, 2);
        $blue = hexdec($B);
        $tbl_color = array();
        $tbl_color['R'] = $red;
        $tbl_color['V'] = $green;
        $tbl_color['B'] = $blue;
        return $tbl_color;
    }

    /**
     * Conversion de pixeles a milimetros
     *
     * @param integer $px tamaño en pixeles
     * @return void
     */
    protected function px2mm(int $px)
    {
        return $px * 25.4 / 72;
    }

    /**
     * Retorna el texto como entidades HTML
     *
     * @param string $html
     * @return string
     */
    public function txtentities(string $html)
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }

    public function drawTable(array $header, array $data, array $title = [])
    {
        $tw = 0;

        $th = $header['header'];
        foreach ($th as $item) {
            $tw += $item[2];
        }

        if (!empty($title)) {
            $this->drawHeader([[$title['title'], $title['align'], $tw]], $title['hColor'], $title['tColor']);
        }

        // Dibujamos el header
        $this->drawHeader($th);

        // Dibujamos los datos
        if (!empty($data)) {
            $i=1;
            foreach ($data as $item) {
                $cell = [];

                foreach ($item as $key => $value) {
                    $cell[] = [$value, $th[$key][1], $th[$key][2]];
                }

                $this->tableRow($cell);

                $i++;
                $currentY = $this->GetY();

                if ($this->CurOrientation=='L' && $this->GetY() + 5 > (185.4 - 4)) {
                    $this->AddPage();
                    $this->drawHeader($th);
                }
                if ($this->CurOrientation=='P' && $this->GetY() + 5 > (279.4 - 4)) {
                    $this->AddPage();
                    $this->drawHeader($th);
                }
            }
        } else {
            $this->Ln();
            $this->writeLine("NO SE ENCONTRARON DATOS", "C");
        }
    }


    public function createTableHeader(array $header, bool $fill = false)
    {
        return array(
            "header" => $header,
            "fill" => $fill
        );
    }

    /**
     * Retorna el arreglo para dibujar una celda del header
     *
     * @param string $text
     * @param string $aling
     * @param integer $width
     * @return void
     */
    public function th(string $text, string $align, int $width)
    {
        return array(
            $text,
            $align,
            $width
        );
    }

    /**
     * Crea el titulo de la tabla
     *
     * @param string $title Titulo que se mostrara
     * @param string $align Alineacion del texto por defecto 'C'
     * @param string $headerColor Color del relleno en HEX ejemplo #000000
     * @param string $textColor Color del texto en HEX ejemplo #FFFFFF
     * @return array
     */
    public function createTableTitle(
        string $title,
        string $align = 'C',
        string $headerColor = '',
        string $textColor = ''
    ) {
        return array(
            'title' => $title,
            'align' => $align,
            'hColor' => $headerColor,
            'tColor' => $textColor
        );
    }

    protected function drawHeader(array $header, $headerColor = '', $textColor = '')
    {
        $hColor = [224,224,224];
        if ($headerColor != '') {
            $hColor = $this->hex2dec($headerColor);
            $hColor = [$hColor['R'], $hColor['V'], $hColor['B']];
        }

        $tColor = [0, 0, 0];
        if ($textColor != '') {
            $tColor = $this->hex2dec($textColor);
            $tColor = [$tColor['R'], $tColor['V'], $tColor['B']];
        }
        // draw table header
        $this->SetFillColor($hColor[0], $hColor[1], $hColor[2]);
        $this->SetTextColor($tColor[0], $tColor[1], $tColor[2]);
        $this->tableRow($header, true);
        $this->SetTextColor(0, 0, 0);
        // $this->SetTextColor($tColor[0], $tColor[1], $tColor[2]);
    }

    /**
     * Escribe una fila de una tabla a partir de una matriz.
     *
     * Recibe un arreglo con la informacion de cada una de las celdas
     *
     * $array [[
     *     'Texto',
     *     'L',
     *     '100'
     * ]]
     *
     * @param array $array
     * @param boolean $fill
     * @param boolean $is_border
     * @return void
     */
    public function tableRow(array $array, bool $fill = false, bool $is_border = true)
    {
        $total_width = 0;
        foreach ($array as $item) {
            $total_width += $item[2];
        }


        $max_line = 1;
        $data = array();

        foreach ($array as $key => $item) {
            // get the information to draw
            $text  = $item[0];
            $align = $item[1];
            $width  = $item[2];

            $jk = 0;
            $w = $width;
            $border = 0;

            if (!isset($this->CurrentFont)) {
                $this->Error('No font has been set');
            }

            $caracter_width = &$this->CurrentFont['cw'];

            if ($w == 0) {
                $w = $this->w - $this->rMargin - $this->x;
            }

            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $text);
            $nb = strlen($s);

            if ($nb > 0 && $s[$nb - 1] == "\n") {
                $nb--;
            }

            $b = 1;

            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;

            while ($i < $nb) {
                // Get next character
                $c = $s[$i];
                if ($c == "\n") {
                    $data[$key][0][] = substr($s, $j, $i - $j);
                    $data[$key][1][] = $width;
                    $data[$key][2][] = $align;
                    $jk++;

                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                }
                if ($c == ' ') {
                    $sep = $i;
                    $ns++;
                }
                $l += $caracter_width[$c];
                if ($l > $wmax) {
                    // Automatic line break
                    if ($sep == -1) {
                        if ($i == $j) {
                            $i++;
                        }
                        $data[$key][0][] = substr($s, $j, $i - $j);
                        $data[$key][1][] = $width;
                        $data[$key][2][] = $align;
                        $jk++;
                    } else {
                        $data[$key][0][] = substr($s, $j, $sep - $j);
                        $data[$key][1][] = $width;
                        $data[$key][2][] = $align;
                        $jk++;

                        $i = $sep + 1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                } else {
                    $i++;
                }
            }
            // Last chunk
            if ($this->ws > 0) {
                $this->ws = 0;
            }
            if ($border && strpos($border, 'B') !== false) {
                $b .= 'B';
            }
            $data[$key][0][] = substr($s, $j, $i - $j);
            $data[$key][1][] = $width;
            $data[$key][2][] = $align;
            $jk++;
            $key++;
            if ($jk > $max_line) {
                $max_line = $jk;
            }
        }

        foreach ($data as $key => $item) {
            for ($i = count($item[0]); $i < $max_line; $i++) {
                $data[$key][0][]  = "";
                $data[$key][1][] = $data[$key][1][0];
                $data[$key][2][] = $data[$key][2][0];
            }
        }

        $data = $data;
        $total_lines   = count($data[0][0]);
        $total_columns = count($data);

        for ($i = 0; $i < $total_lines; $i++) {
            for ($j = 0; $j < $total_columns; $j++) {
                $ln = 0;
                $border = 0;
                if ($is_border) {
                    $border = "LR";
                    if ($i == 0) {
                        $border = "TLR";
                    }
                    if ($i == $total_lines - 1) {
                        $border = "BLR";
                    }
                    if ($i == $total_lines - 1 && $i == 0) {
                        $border = "1";
                    }
                }
                if ($j == $total_columns - 1) {
                    $ln = 1;
                }

                // A line break is created if the cell is cut to half a page
                // todo: Modificar de acuerdo a los diferentes tamaños de pagina
                $currentY = $this->GetY();
                $currentX = $this->GetX();
                if ($this->GetY() + 5 > (279.4 - 4)) {
                    $this->Line(
                        $this->GetX(),
                        $this->GetY(),
                        $this->GetX() + $total_width,
                        $this->GetY()
                    );
                }

                // draw the cell
                $this->cell(
                    $data[$j][1][$i],
                    5,
                    utf8_decode($data[$j][0][$i]),
                    $border,
                    $ln,
                    $data[$j][2][$i],
                    $fill
                );

                if ($currentY + 5 > (279.4 - 4)) {
                    $this->Line(
                        $currentX,
                        $this->GetY(),
                        $currentX + $total_width,
                        $this->GetY()
                    );
                }
            }
        }
    }

    protected function width()
    {
        return $this->w - $this->lMargin - $this->rMargin;
    }
}
