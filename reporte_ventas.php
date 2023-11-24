<?php
    require("_core.php");
    require("num2letras.php");
    require('fpdf/fpdf.php');

    

    $process=$_GET['process'];//definira si el reporte que quiere generar ya sea por vendedor o por general

    class PDF extends FPDF{
        private $encabezado;
        private $barra_lateral;
        private $cuerpo;
        private $datos_adicionales;

        function setEncabezado($encabezado){
            $this->encabezado=$encabezado;
        }
        function setBarraLateral($barra_lateral){
            $this->barra_lateral=$barra_lateral;
        }
        function setCuerpo($cuerpo){
            $this->cuerpo=$cuerpo;
        }
        function setDatosAdicionales($datos_adicionales){
            $this->datos_adicionales=$datos_adicionales;
        }
        
        public function LineWriteB($array){
          $resolver=$this->GetX();
          $ygg=0;
          $maxlines=1;
          $array_a_retornar=array();
          $array_max= array();
          foreach ($array as $key => $value) {
            // /Descripcion/
            $nombr=utf8_decode($value[0]);
            // /fpdf width/
            $size=$value[1];
            // /fpdf alignt/
            $aling=$value[2];
            $jk=0;
            $w = $size;
            $h  = 0;
            $txt=$nombr;
            $border=0;
            if(!isset($this->CurrentFont))
              $this->Error('No font has been set');
            $cw = &$this->CurrentFont['cw'];
            if($w==0)
              $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',$txt);
            $nb = strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
              $nb--;
            $b = 1;
    
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;
            while($i<$nb)
            {
              // Get next character
              $c = $s[$i];
              if($c=="\n")
              {
                $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
                $array_a_retornar[$ygg]["size"][]=$size;
                $array_a_retornar[$ygg]["aling"][]=$aling;
                $jk++;
    
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if($border && $nl==2)
                  $b = $b2;
                continue;
              }
              if($c==' ')
              {
                $sep = $i;
                $ls = $l;
                $ns++;
              }
              $l += $cw[$c];
              if($l>$wmax)
              {
                // Automatic line break
                if($sep==-1)
                {
                  if($i==$j)
                    $i++;
                  $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
                  $array_a_retornar[$ygg]["size"][]=$size;
                  $array_a_retornar[$ygg]["aling"][]=$aling;
                  $jk++;
                }
                else
                {
                  $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
                  $array_a_retornar[$ygg]["size"][]=$size;
                  $array_a_retornar[$ygg]["aling"][]=$aling;
                  $jk++;
    
                  $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if($border && $nl==2)
                  $b = $b2;
              }
              else
                $i++;
            }
            // Last chunk
            if($this->ws>0)
            {
              $this->ws = 0;
            }
            if($border && strpos($border,'B')!==false)
              $b .= 'B';
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;
            $ygg++;
            if ($jk>$maxlines) {
              // code...
              $maxlines=$jk;
            }
          }
    
          $ygg=0;
          foreach($array_a_retornar as $keys)
          {
            for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
              // code...
              $array_a_retornar[$ygg]["valor"][]="";
              $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
              $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
            }
            $ygg++;
          }
          $data=$array_a_retornar;
          $total_lineas=count($data[0]["valor"]);
          $total_columnas=count($data);
    
    
          $he = 4*$total_lineas;
          $y = $this->GetY();
            if($this->DefOrientation=='L'){
                if($y > 185){
                    if($he>5){
                        $this-> AddPage();
                    }
                    
                } 
            }

          for ($i=0; $i < $total_lineas; $i++) {
            // code...
            $y = $this->GetY();

            for ($j=0; $j < $total_columnas; $j++) {
              if($j==0){
                $this->SetX($resolver);
              }
              // code...
              $salto=0;
              $abajo="LR";
              if ($i==0) {
                // code...
                $abajo="TLR";
              }
              if ($j==$total_columnas-1) {
                // code...
                $salto=1;
              }
              if ($i==$total_lineas-1) {
                // code...
                $abajo="BLR";
              }
              if ($i==$total_lineas-1&&$i==0) {
                // code...
                $abajo="1";
              }
              // if ($j==0) {
              //   // code...
              //   $abajo="0";
              // }
              $str = $data[$j]["valor"][$i];
              if ($str=="\b")
              {
                $abajo="0";
                $str="";
              }
              //$abajo="0";
              
              
              $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i],0);
            }
    
            //$this->setX(55);
          }
        }

        function Header(){
            $this->SetFont('Times', '', 7);
            $fecha_object=new DateTime();
            $fecha=$fecha_object->format('d/m/Y h:i:s');
            if($this->PageNo()==1){
                if($this->DefOrientation=="L"){
                    $this->setXY(220, 10);
                    $this->Cell(40,10,utf8_decode('fecha y hora de impresión: '.$fecha),0,1,'L');
                    $this->Image(getLogo(),8,4,30,25);
                    $set_x = $this->getX();
                    $set_y = $this->getY();
                    //$this->SetFont('Courier', 'B', 14);

                    $set_y+=2;
                    $set_x+=75;
                    $this->SetY($set_y);
                    $this->SetX($set_x);
                    $this->SetFont('Times', 'B', 8);
                    $this->SetTextColor(128, 64, 0);
                    $this->Multicell(100, 5, utf8_decode($this->encabezado['titulo']), 0, 'C');
                    $this->Ln();                  
                }else{
                    $this->setXY(160, 10);
                    $this->Cell(40,10,utf8_decode('fecha y hora de impresión: '.$fecha),0,1,'L');
                    $this->Image(getLogo(),8,4,30,25);
                    $set_x = $this->getX();
                    $set_y = $this->getY();
                    //$this->SetFont('Courier', 'B', 14);

                    $set_y+=2;
                    $set_x+=50;
                    $this->SetY($set_y);
                    $this->SetX($set_x);
                    $this->SetFont('Times', 'B', 14);
                    $this->SetTextColor(128, 64, 0);
                    $this->Multicell(100, 10, utf8_decode($this->encabezado['titulo']), 0, 'C');
                    $this->Ln();  
                }

            }

        }


    }

    function generar_total_factura_por_mes($pdf){
        $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
        $fecha_hasta=MD($_GET['hasta']);
        
        $encabezado=array();

        $encabezado['titulo']='REPORTE TOTAL DE FACTURAS EMITIDAS POR VENDEDOR ENTRE'.chr(10).$fecha_desde.' HASTA '.$fecha_hasta.'';
        
        $pdf->setEncabezado($encabezado);

        $jdas="";
        $pdf->SetMargins(15,15);
        $pdf->SetTopMargin(10);
        $pdf->SetLeftMargin(13);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,15);;
        $pdf->AddPage();

        
        $sql_obtener_ventas_mes="SELECT CONCAT(e.nombre, ' ', e.apellido) AS vendedor, f.fecha, ROUND(sum(f.total),2) AS total 
                                 FROM factura as f LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado 
                                 LEFT JOIN cliente AS c ON f.id_cliente=c.id_cliente 
                                 WHERE f.anulada=0 AND f.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' 
                                 GROUP BY f.id_empleado, vendedor"; 
        
        $query_obtener_ventas_mes=_query($sql_obtener_ventas_mes);


        
        //dibujando los resultados del reporte
        $filas=0;
        
        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
        
        
        $set_y+=10;
        $set_x=10;
        $pdf->SetY($set_y);
        $pdf->SetX($set_x);


        
        if(_num_rows($query_obtener_ventas_mes)>0){
            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetTextColor(128, 64, 0);
            $encabezado_venta=array(
                array('VENDEDOR', 140, "L"),
                array("TOTAL", 50, "L")
            );
            $pdf->LineWriteB($encabezado_venta);
            $pdf->SetTextColor(1, 1, 1);
            $pdf->SetFont('Times', '', 10);
            while($row_obtener_ventas=_fetch_array($query_obtener_ventas_mes)){
                if($filas<20){
                    $pdf->SetX($set_x);
                    $data_venta=array(
                        array($row_obtener_ventas['vendedor'], 140, "L"),
                        array('$'.$row_obtener_ventas['total'], 50, 'R')
                    );
                    $pdf->LineWriteB($data_venta);  
                    $filas++; 
                }else{
                    $filas=0;
                }

            }

            //obteniendo el total de todo el reporte
            $sql_total_ventas="SELECT ROUND(sum(f.total),2) AS total FROM factura as f 
            LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado 
            LEFT JOIN cliente AS c ON f.id_cliente=c.id_cliente WHERE f.anulada=0 AND f.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";

            $query_total=_query($sql_total_ventas);
            $pdf->SetTextColor(128, 64, 0);
            $pdf->SetFont('Times', 'B', 10);
            while($row_total=_fetch_array($query_total)){
                $pdf->SetX($set_x);
                $data_total=array(
                    array(utf8_decode('Total'),140, "L" ),
                    array('$'.$row_total['total'], 50, 'R')
        
                );
                $pdf->LineWriteB($data_total);
            }



        }
        //de lo contrario no hay registros que mostrar
        $pdf->Output("receta_pdf.pdf","I");
    }

    function generar_reporte_por_cliente_y_vendedor($pdf){
        $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
        $fecha_hasta=MD($_GET['hasta']);
        $id_vendedor=$_GET['id_vendedor'];
        $id_cliente=$_GET['id_cliente'];


        $encabezado=array();
        //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
        $encabezado['titulo']='REPORTE DE FACTURAS DEL DIA'.chr(10).ED($fecha_desde).' hasta '.ED($fecha_hasta);
        $pdf->setEncabezado($encabezado);
                

        $pdf->SetMargins(15,15);
        $pdf->SetTopMargin(10);
        $pdf->SetLeftMargin(13);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,15);;
        $pdf->AddPage();

        $sql_obtener_detalle_venta="SELECT f.id_factura, CONCAT(e.nombre, '',e.apellido) 
        AS vendedor, CONCAT(c.nombre) AS cliente ,f.numero_doc, f.fecha, f.total 
        FROM factura AS f LEFT JOIN empleado as e on f.id_empleado=e.id_empleado 
        LEFT JOIN cliente AS c ON f.id_cliente=c.id_cliente 
        WHERE  f.id_empleado=$id_vendedor  
        AND f.id_cliente=$id_cliente 
        AND f.fecha BETWEEN CAST( '$fecha_desde' AS DATE ) AND CAST('$fecha_hasta' AS DATE )";
        
        $query_get_ventas_clien_vent=_query($sql_obtener_detalle_venta);

        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
        
        //dibujando los resultados del reporte
        
        //contara el numero de filas que se vayan dibujando
        $filas=0;

        $set_y+=10;
        $set_x+=5;
        $pdf->SetY($set_y);
        $pdf->SetX($set_x);
        //echo _num_rows($query_get_ventas_clien_vent);
        if(_num_rows($query_get_ventas_clien_vent)>0){
            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetTextColor(128, 64, 0);
            $encabezado_venta=array(
                array('VENDEDOR', 40, "L"),
                array('CLIENTE', 40, "L"),
                array("No FACTURA", 40, "L"),
                array("FECHA", 30, "L"),
                array("TOTAL", 25, "L")
            );
            $pdf->LineWriteB($encabezado_venta);
            $pdf->SetFont('Times', '', 10);
            $pdf->SetTextColor(1, 1, 1);
            while($row_obtener_ventas=_fetch_array($query_get_ventas_clien_vent)){
                if($filas<20){
                    
                    $pdf->SetX($set_x);
                    $data_venta=array(
                        array($row_obtener_ventas['vendedor'], 40, "L"),
                        array($row_obtener_ventas['cliente'],40,"L" ),
                        array($row_obtener_ventas['numero_doc'], 40, "L"),
                        array(ED($row_obtener_ventas['fecha']), 30, "L"),
                        array('$'.$row_obtener_ventas['total'], 25, 'R')
                    );
                    $pdf->LineWriteB($data_venta);  
                    $filas++; 
                }else{
                    $filas=0;
                }
    
            }
    
                //obteniendo el total de todo el reporte
                $sql_total_ventas="SELECT ROUND(SUM(f.total), 2) AS total FROM factura AS f 
                LEFT JOIN empleado as e on f.id_empleado=e.id_empleado 
                LEFT JOIN cliente AS c ON f.id_cliente=c.id_cliente 
                WHERE  f.id_empleado=$id_vendedor  AND f.id_cliente=$id_cliente AND f.fecha 
                BETWEEN CAST( '$fecha_desde' AS DATE ) AND CAST('$fecha_hasta' AS DATE )";
    
                $query_total=_query($sql_total_ventas);
                $pdf->SetFont('Times', 'B', 10);
                $pdf->SetTextColor(128, 64, 0);
                while($row_total=_fetch_array($query_total)){
                    $pdf->SetX($set_x);
                    $data_total=array(
                        array(utf8_decode('Total'),150, "L" ),
                        array('$'.$row_total['total'], 25, 'R')
            
                    );
                    $pdf->LineWriteB($data_total);
                }
        }


        $pdf->Output("reporte_facturas.pdf","I");

    }

    function generar_reporte_de_cuentasxcobrar($pdf){
        $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
        $fecha_hasta=MD($_GET['hasta']);
        $id_vendedor=$_GET['id_vendedor'];
        $id_cliente=$_GET['id_cliente'];

        $encabezado=array();
        //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
        $encabezado['titulo']='ESTADO DE CUENTAS POR COBRAR DEL DIA '.chr(10).ED($fecha_desde).' hasta '.ED($fecha_hasta);
        $pdf->setEncabezado($encabezado);
                

        $pdf->SetMargins(15,15);
        $pdf->SetTopMargin(10);
        $pdf->SetLeftMargin(13);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,15);;
        $pdf->AddPage();

        $sql_obtener_estado_cuenta="";
        $sql_total_estados="";
        
            $sql_obtener_estado_cuenta="SELECT f.num_fact_impresa AS docx, 
            cr.id_credito, cr.fecha, c.nombre AS cliente, 
            CONCAT(e.nombre, ' ',e.apellido) AS vendedor, ROUND(cr.total, 2) AS saldo, 
            ROUND(cr.abono, 2) AS abono, ROUND(cr.saldo, 2) AS saldo_pendiente  
            FROM credito AS cr LEFT JOIN cliente AS c 
            on cr.id_cliente=c.id_cliente LEFT JOIN factura AS f 
            ON cr.id_factura=f.id_factura LEFT JOIN empleado AS e 
            ON f.id_empleado=e.id_empleado WHERE cr.id_cliente=$id_cliente AND 
            f.id_empleado=$id_vendedor AND cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND cr.saldo!=0";
            


        $query_obtener_estados_cuenta=_query($sql_obtener_estado_cuenta);

        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
        
        //dibujando los resultados del reporte
        
        //contara el numero de filas que se vayan dibujando
        $filas=0;

        $set_y+=10;
        $set_x+=5;
        $pdf->SetY($set_y);
        $pdf->SetX($set_x);
        //echo _num_rows($query_get_ventas_clien_vent);
        if(_num_rows($query_obtener_estados_cuenta)>0){
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(128, 64, 0);
            $encabezado_estados=array(
                array('FECHA', 17, "L"),
                array('CLIENTE', 40, "L"),
                array('VENDEDOR', 40, "L"),
                array("N° DOC", 20, "C"),
                array("SALDO", 20, "L"),
                array("ABONO", 20, "L"),
                array("SALDO PENDIENTE", 30, "C")                
            );
            $pdf->LineWriteB($encabezado_estados);
            $pdf->SetTextColor(1, 1, 1);
            while($row_obtener_estados=_fetch_array($query_obtener_estados_cuenta)){
                if($filas<20){
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->SetX($set_x);
                    $data_estados=array(
                        array(ED($row_obtener_estados['fecha']), 17, "L"),
                        array($row_obtener_estados['cliente'],40,"L" ),
                        array($row_obtener_estados['vendedor'], 40, "L"),
                        array($row_obtener_estados['docx'], 20, 'C'),
                        array('$'.$row_obtener_estados['saldo'], 20, "R"),
                        array('$'.$row_obtener_estados['abono'], 20, "R"),
                        array('$'.$row_obtener_estados['saldo_pendiente'], 30, 'R')                        
                    );
                    $pdf->LineWriteB($data_estados);  
                    $filas++; 
                }else{
                    $filas=0;
                }
    
            }
                $pdf->SetFont('Arial', 'B', 10);
                //obteniendo el total de todo el reporte
                $sql_total_estados="SELECT  ROUND(SUM(cr.total), 2) AS saldo, ROUND(SUM(cr.abono), 2) AS abono, 
                ROUND(SUM(cr.saldo), 2) AS saldo_pendiente  FROM credito AS cr 
                LEFT JOIN cliente AS c on cr.id_cliente=c.id_cliente 
                LEFT JOIN factura AS f ON cr.id_factura=f.id_factura
                LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado
                WHERE cr.id_cliente=$id_cliente AND 
                f.id_empleado=$id_vendedor AND 
                cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND cr.saldo!=0";
    
                $query_total=_query($sql_total_estados);
                $pdf->SetTextColor(128, 64, 0);
                while($row_total=_fetch_array($query_total)){
                    $pdf->SetX($set_x);
                    $data_total=array(
                        array(utf8_decode('Totales'),117, "L" ),
                        array('$'.$row_total['saldo'], 20, 'R'),
                        array('$'.$row_total['abono'], 20, 'R'),
                        array('$'.$row_total['saldo_pendiente'], 30, 'R')
            
                    );
                    $pdf->LineWriteB($data_total);
                }
        }


        $pdf->Output("reporte_facturas.pdf","I");
        

    }

    function generar_reporte_cuentasxcobrar_gen($pdf){
        $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
        $fecha_hasta=MD($_GET['hasta']);

        $encabezado=array();
        //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
        $encabezado['titulo']='ESTADO DE CUENTAS POR COBRAR GENERAL DEL DIA '.chr(10).ED($fecha_desde).' hasta '.ED($fecha_hasta);
        $pdf->setEncabezado($encabezado);
                

        $pdf->SetMargins(15,15);
        $pdf->SetTopMargin(10);
        $pdf->SetLeftMargin(13);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,15);;
        $pdf->AddPage();

        $sql_obtener_estado_cuenta="";
        $sql_total_estados="";

        $sql_empleado="SELECT * FROM empleado";

        $query_empleado=_query($sql_empleado);

        while($row_empleado=_fetch_array($query_empleado)){
            //obteniendo el id de todos los empleados parasacar los credito que tengan con los clientes
            $id_empleado=$row_empleado['id_empleado'];
            $nombre_empleado=$row_empleado['nombre'];
            $apellido_empleado=$row_empleado['apellido'];
            $nombres_empleado=$nombre_empleado.' '.$apellido_empleado;

            $sql_obtener_estado_cuenta="SELECT cr.id_credito, cr.fecha, c.nombre AS cliente, 
            CONCAT(e.nombre, ' ',e.apellido) AS vendedor, ROUND(cr.total, 2) AS saldo, 
            ROUND(cr.abono, 2) AS abono, ROUND(cr.saldo, 2) AS saldo_pendiente  
            FROM credito AS cr LEFT JOIN cliente AS c 
            on cr.id_cliente=c.id_cliente LEFT JOIN factura AS f 
            ON cr.id_factura=f.id_factura LEFT JOIN empleado AS e 
            ON f.id_empleado=e.id_empleado WHERE  
            f.id_empleado=$id_empleado AND cr.saldo!=0 AND cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";

            $query_obtener_estados_cuenta=_query($sql_obtener_estado_cuenta);

            $set_x=$pdf->GetX();
            $set_y=$pdf->GetY();
    
            //dibujando los resultados del reporte
    
            //contara el numero de filas que se vayan dibujando
            $filas=0;
    
            $set_y+=10;
            $set_x=$pdf->GetX();
            $pdf->SetY($set_y);
            $pdf->SetX($set_x);
            //echo _num_rows($query_get_ventas_clien_vent);
            if(_num_rows($query_obtener_estados_cuenta)>0){                
                $pdf->SetFont('Times', 'B', 10);
                $pdf->SetTextColor(128, 64, 0);
                $pdf->LineWriteB(array(
                    array($nombres_empleado, 185, "C")
                ));
                $encabezado_estados=array(
                    array('FECHA', 25, "L"),
                    array('CLIENTE', 80, "L"),
                    array("SALDO", 25, "L"),
                    array("ABONO", 20, "L"),
                    array("SALDO PENDIENTE", 35, "C")
                );

                $pdf->SetX($set_x);
                $pdf->LineWriteB($encabezado_estados);
                $pdf->SetTextColor(1,1,1);
                $pdf->SetFont('Times', '', 10);
                while($row_obtener_estados=_fetch_array($query_obtener_estados_cuenta)){
                    if($filas<20){
                        $pdf->SetX($set_x);
                        $data_estados=array(
                            array(ED($row_obtener_estados['fecha']), 25, "L"),
                            array($row_obtener_estados['cliente'],80,"L" ),
                            array('$'.$row_obtener_estados['saldo'], 25, "R"),
                            array('$'.$row_obtener_estados['abono'], 20, "R"),
                            array('$'.$row_obtener_estados['saldo_pendiente'], 35, 'R')
                        );
                        $pdf->LineWriteB($data_estados);  
                        $filas++; 
                    }else{
                        $filas=0;
                    }
    
                }

                $sql_total_estados="SELECT  ROUND(SUM(cr.total), 2) AS saldo, ROUND(SUM(cr.abono), 2) AS abono, 
                ROUND(SUM(cr.saldo), 2) AS saldo_pendiente  FROM credito AS cr 
                LEFT JOIN cliente AS c on cr.id_cliente=c.id_cliente 
                LEFT JOIN factura AS f ON cr.id_factura=f.id_factura
                LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado
                WHERE cr.saldo!=0 AND f.id_empleado=$id_empleado AND cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
    
                    $query_total=_query($sql_total_estados);
                    $pdf->SetFont('Times', 'B', 10);
                    $pdf->SetTextColor(128,64,0);
                    while($row_total=_fetch_array($query_total)){
                        $pdf->SetX($set_x);
                        $data_total=array(
                            array(utf8_decode('Totales'),105, "L" ),
                            array('$'.$row_total['saldo'], 25, 'R'),
                            array('$'.$row_total['abono'], 20, 'R'),
                            array('$'.$row_total['saldo_pendiente'], 35, 'R')
                
                        );
                        $pdf->LineWriteB($data_total);
                    }
            }


        }

        $pdf->Output("reporte_facturas.pdf","I");


    }
    function generar_reporte_creditosxvendedor($pdf){
        $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
        $fecha_hasta=MD($_GET['hasta']);
        $id_vendedor=$_GET['id_vendedor'];

        $sql_vendedor="SELECT * FROM empleado WHERE id_empleado=$id_vendedor";
        $query_vendedor=_query($sql_vendedor);
        $nombres_empleado="";
        $pdf->SetFont('Times', 'B', 5);
        $pdf->SetTextColor(128,64,0);
        while($row_vendedor=_fetch_array($query_vendedor)){
            $nombre=$row_vendedor['nombre'];
            $apellido=$row_vendedor['apellido'];
            $nombres_empleado=$nombre.' '.$apellido;
        }

        $encabezado=array();
        //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
        $encabezado['titulo']='ESTADO DE CUENTAS POR COBRAR GENERAL DEL DIA '.chr(10).ED($fecha_desde).' HASTA EL '.ED($fecha_hasta);
        $pdf->setEncabezado($encabezado);
                

        $pdf->SetMargins(5,5);
        $pdf->SetTopMargin(5);
        $pdf->SetLeftMargin(15);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,5);;
        $pdf->AddPage();

        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
        //dibuja el nombre del vendedor
        $pdf->SetFont('Times', 'B', 8);
        $pdf->SetTextColor(128,64,0);
        $set_y-=5;
        $set_x+=100;
        $pdf->SetY($set_y);
        $pdf->SetX($set_x);
        $pdf->Cell(50,3, $nombres_empleado, 0,1,'C');


        $sqlcliente="SELECT * FROM cliente ORDER BY nombre";

        $query_cliente=_query($sqlcliente);
        $vend=1;
        $set_y+=5;
        $pdf->SetY($set_y);
        while ($row_cliente=_fetch_array($query_cliente)) {
            $id_cliente=$row_cliente['id_cliente'];

            $sql_obtener_estado_cuenta="SELECT cr.id_credito,f.num_fact_impresa, f.tipo_documento , c.nombre AS cliente, CONCAT(e.nombre, ' ',e.apellido) AS vendedor, suc.direccion AS sucursal, f.saldo AS saldo_inicial, cr.fecha, cr.dias, cr.saldo AS saldo_actual FROM credito AS cr LEFT JOIN cliente AS c on cr.id_cliente=c.id_cliente LEFT JOIN factura AS f ON cr.id_factura=f.id_factura LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado LEFT JOIN sucursal as suc on f.id_sucursal=suc.id_sucursal WHERE f.id_empleado=$id_vendedor AND f.id_cliente=$id_cliente AND cr.saldo!=0 AND cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
            
            $query_obtener_estados_cuenta=_query($sql_obtener_estado_cuenta);

            $set_x=$pdf->GetX();
            $set_y=$pdf->GetY();


            //dibujando los resultados del reporte

            //contara el numero de filas que se vayan dibujando
            $filas=0;

            //$set_y+=10;
            $set_x=$pdf->GetX();
            $pdf->SetY($set_y);
            $pdf->SetX($set_x);
            //echo _num_rows($query_get_ventas_clien_vent);
            
            if(_num_rows($query_obtener_estados_cuenta)>0){
                $pdf->SetFont('Times', 'B', 5);
                $pdf->SetTextColor(128,64,0);

                $encabezado_estados=array(
                    array('FECHA E.', 20, "L"),
                    array('DOC', 15, 'L'),
                    array('CLIENTE', 50, "L"),
                    array("S.INICIAL", 20, "L"),
                    array("PLAZO", 20, "L"),
                    array("FECHA VENC.", 25, "C"),
                    array("S. ACTUAL", 20, "C"),
                    array("DIAS T.", 20, "C"),
                    array("STATUS", 35, "C"),
                    array("FECHA CANCEL.", 25, "C"),


                );
                $pdf->SetY($set_y);
                $pdf->SetX($set_x);
                $pdf->LineWriteB($encabezado_estados);
                $pdf->SetFont('Times', '', 5);
                $pdf->SetTextColor(1,1,1);
                $filas_cliente=0;
                while($row_obtener_estados=_fetch_array($query_obtener_estados_cuenta)){
                    $filas_cliente++;
                        $pdf->SetX($set_x);


                        $fecha=array(ED($row_obtener_estados['fecha']), 20, "L");
                        $tipo_documento=$row_obtener_estados['tipo_documento'];
                        $numero_factura=null;
                        if($tipo_documento=="CCF"){
                            $numero_factura=array('CCF'.$row_obtener_estados['num_fact_impresa'], 15, "R");
                        }else{
                            $numero_factura=array('CO'.$row_obtener_estados['num_fact_impresa'], 15, "R");

                        }

                        if($filas_cliente==1){
                            $cliente=array($row_obtener_estados['cliente'],50,"L" ); 
                        }else{
                            $cliente=array("",50,"L" );

                        }
                        $saldo_inicial=array('$'.$row_obtener_estados['saldo_inicial'], 20, "R");
                        $fecha_emicion=array($row_obtener_estados['fecha'], 20, "R");

                        
                        $saldo_actual=array('$'.$row_obtener_estados['saldo_actual'], 20, "R");
                        $fecha_cancelacion='';
                        if($row_obtener_estados['saldo_actual']==0){
                            $fecha_cancelacion=$row_obtener_estados['fecha'];
                        }
                        $row_fecha_cancelacion=array($fecha_cancelacion, 25, 'L');

                        $estado=array("CANCELADO", 35, "L" );
                        if($row_obtener_estados['saldo_actual']>0){
                            $estado=array("PENDIENTE", 35, "L" );
                        }

                        $fecha_emicion_cal= $row_obtener_estados['fecha'];

                       
                        $dias_plazo=$row_obtener_estados['dias'];

                        $plazo=array($dias_plazo.' dias', 20,"R");

                        //echo $fecha_emicion_cal;
                        $fecha_vencimiento= date('Y-m-d', strtotime(''.$row_obtener_estados['fecha']."+ ".$row_obtener_estados['dias']." days"));
                        $fecha_hoy=date('Y-m-d');
                        //echo gettype($fecha_vencimiento);
          
                        $f1=new DateTime($fecha_vencimiento);
                        $f2=new DateTime($fecha_hoy);


                        $interval=$f2->diff($f1);
                        
                        //echo $interval->d;
                        //echo $intervalo->format("%d dias");
                        $dias_tardados=array("0 dias", 20, "R");
                        if($f2>$f1){//calculando la diferencia de dias tardados
                            $dias_tardados=array($interval->d,20,"R");
                        }

                        $row_fecha_vencimiento=array(ED($fecha_vencimiento), 25, "R");

                        $data_estados=array(
                            $fecha,
                            $numero_factura,
                            $cliente, 
                            $saldo_inicial,
                            $plazo,
                            $row_fecha_vencimiento,
                            $saldo_actual,
                            $dias_tardados,
                            $estado,
                            $row_fecha_cancelacion
                        );

                        $pdf->LineWriteB($data_estados);  
                        $filas++; 
                    

                }
      


                $sql_total_estados="SELECT cr.id_credito,f.num_fact_impresa, f.tipo_documento , c.nombre AS cliente, CONCAT(e.nombre, ' ',e.apellido) AS vendedor, suc.direccion AS sucursal, ROUND(SUM(f.saldo), 2) AS total_saldo_inicial, cr.fecha, cr.dias, ROUND(SUM(cr.saldo), 2) AS total_saldo_actual FROM credito AS cr LEFT JOIN cliente AS c on cr.id_cliente=c.id_cliente LEFT JOIN factura AS f ON cr.id_factura=f.id_factura LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado LEFT JOIN sucursal as suc on f.id_sucursal=suc.id_sucursal WHERE f.id_empleado=$id_vendedor AND f.id_cliente=$id_cliente AND cr.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta';";

                $query_total=_query($sql_total_estados);
                $pdf->SetFont('Times', 'B', 5);    
                $pdf->SetTextColor(128,64,0);
                while($row_total=_fetch_array($query_total)){
                    $pdf->SetX($set_x);
                    $data_total=array(
                        array(utf8_decode('Totales'),85, "L" ),
                        array('$'.$row_total['total_saldo_inicial'], 20, 'R'),
                        array("", 45,'R'),
                        array('$'.$row_total['total_saldo_actual'], 20, 'R'),
                        array('',80,'R')

                    );
                    $pdf->LineWriteB($data_total);
                }
                $num_filas=_num_rows($query_obtener_estados_cuenta);
                //echo $set_y.'<br>';

                if($set_y>164){
                    if($num_filas>=1){
                        $pdf->AddPage();                    
                    }
                }
            }

        }


        $pdf->Output("reporte_facturas.pdf","I");        
}


function generar_reporte_vendedorxmarca($pdf){
    $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
    $fecha_hasta=MD($_GET['hasta']);

    $encabezado=array();
    //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
    $encabezado['titulo']='REPORTE DE VENTAS VENDEDOR POR MARCA DE PRODUCTO'.chr(10).ED($fecha_desde).' hasta '.ED($fecha_hasta);
    $pdf->setEncabezado($encabezado);
            

    $pdf->SetMargins(15,15);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(13);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);;
    $pdf->AddPage();


    $sql_empleado="SELECT * FROM empleado";

    $query_empleado=_query($sql_empleado);

    while($row_empleado=_fetch_array($query_empleado)){
            //obteniendo el id de todos los empleados 
            $id_empleado=$row_empleado['id_empleado'];
            $nombre_empleado=$row_empleado['nombre'];
            $apellido_empleado=$row_empleado['apellido'];
            $nombres_empleado=$nombre_empleado.' '.$apellido_empleado;

            $sql_obtener_venta_marca="SELECT p.descripcion AS producto, fd.fecha,f.id_factura, p.marca, 
            CONCAT(e.nombre, ' ', e.apellido) AS vendedor, COUNT(p.marca) AS cantidad, 
            sum(fd.subtotal) AS total FROM factura_detalle AS fd LEFT JOIN factura AS f ON 
            f.id_factura=fd.id_factura LEFT JOIN producto AS p ON p.id_producto=fd.id_prod_serv 
            LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado 
            WHERE fd.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND f.id_empleado=$id_empleado 
            GROUP BY f.id_empleado, p.marca" ;
            
            $query_obtener_venta_marca=_query($sql_obtener_venta_marca);
        
            $set_x=$pdf->GetX();
            $set_y=$pdf->GetY();
        
        
            //dibujando los resultados del reporte
        
            //contara el numero de filas que se vayan dibujando
            $filas=0;
        
            $set_y+=10;
            $set_x=$pdf->GetX();
            $pdf->SetY($set_y);
            $pdf->SetX($set_x);
            //echo _num_rows($query_get_ventas_clien_vent);
            if(_num_rows($query_obtener_venta_marca)>0){
                $pdf->SetTextColor(128,64,0);
                $pdf->SetFont('Times', 'B', 10);
                $pdf->LineWriteB(array(
                    array($nombres_empleado, 185, "C")
                ));
        
                $encabezado_ven=array(
                    array('FECHA', 25, "L"),
                    array('PRODUCTO', 80, "L"),
                    array("MARCA", 25, "L"),
                    array("CANTIDAD", 30, "L"),
                    array("TOTAL", 25, "L")
                );
        
                $pdf->SetX($set_x);
                $pdf->LineWriteB($encabezado_ven);
                $pdf->SetFont('Times', '', 10);
                $pdf->SetTextColor(1,1,1);
                while($row_vendedor_marca=_fetch_array($query_obtener_venta_marca)){
                    if($filas<20){
                        $pdf->SetX($set_x);
                        $data_estados=array(
                            array(ED($row_vendedor_marca['fecha']), 25, "L"),
                            array($row_vendedor_marca['producto'],80,"L" ),
                            array($row_vendedor_marca['marca'], 25, "L"),
                            array($row_vendedor_marca['cantidad'], 30, "R"),
                            array('$'.$row_vendedor_marca['total'], 25, "R"),
                        );
                        $pdf->LineWriteB($data_estados);  
                        $filas++; 
                    }else{
                        $filas=0;
                    }
        
                }
        
        
                $sql_total_marca="SELECT COUNT(p.marca) AS cantidad, ROUND(sum(fd.subtotal), 2) AS total 
                FROM factura_detalle AS fd LEFT JOIN 
                factura AS f ON f.id_factura=fd.id_factura LEFT JOIN 
                producto AS p ON p.id_producto=fd.id_prod_serv LEFT JOIN 
                empleado AS e ON f.id_empleado=e.id_empleado 
                WHERE fd.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND f.id_empleado=$id_empleado GROUP BY f.id_empleado";
        
                $query_total=_query($sql_total_marca);
                $pdf->SetFont('Times', 'B', 10);   
                $pdf->SetTextColor(128,64,0); 
                while($row_total=_fetch_array($query_total)){
                    $pdf->SetX($set_x);
                    $data_total=array(
                        array(utf8_decode('Totales'),130, "L" ),
                        array($row_total['cantidad'], 30, 'R'),
                        array('$'.$row_total['total'], 25, 'R')
        
                    );
                    $pdf->LineWriteB($data_total);
                }
        
            }

    }

    $pdf->Output("reporte_facturas.pdf","I");        
}

function generar_reporte_venta_diaria($pdf){
    $fecha_sel=MD($_GET['desde']);
    $id_vendedor=$_GET['id_vendedor'];
    
    $sql_vendedor="SELECT * FROM empleado WHERE id_empleado=$id_vendedor";
    $query_vendedor=_query($sql_vendedor);
    $nombres_empleado="";
    $pdf->SetFont('Arial', 'B', 10);
    while($row_vendedor=_fetch_array($query_vendedor)){
        $nombre=$row_vendedor['nombre'];
        $apellido=$row_vendedor['apellido'];
        $nombres_empleado=$nombre.' '.$apellido;
    }
    $encabezado=array();
    //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
    $encabezado['titulo']='REPORTE DE VENTA DIARIO FECHA: '.ED($fecha_sel);
    $pdf->setEncabezado($encabezado);
    
    $pdf->SetMargins(15,15);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(13);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);
    $pdf->AddPage();

    $sql_obtener_venta_diaria="SELECT f.id_factura, f.num_fact_impresa , f.retencion, f.total_menos_retencion, f.subtotal, 
    f.total FROM factura as f LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado 
    LEFT JOIN cliente AS c on f.id_cliente=c.id_cliente 
    WHERE f.fecha='$fecha_sel' AND f.id_empleado=$id_vendedor AND f.anulada=0";
    $query_obtener_venta_diaria=_query($sql_obtener_venta_diaria);

    $set_x=$pdf->GetX();
    $set_y=$pdf->GetY();


    //dibujando los resultados del reporte

    //contara el numero de filas que se vayan dibujando
    $filas=0;

    $set_y+=10;
    $set_x=$pdf->GetX();
    $pdf->SetY($set_y);
    $pdf->SetX($set_x);

    if(_num_rows($query_obtener_venta_diaria)>0){
        $pdf->SetTextColor(128,64,0);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->LineWriteB(array(
            array($nombres_empleado, 191, "C")
        ));

        $encabezado_estados=array(
            array('ID', 25, "L"),
            array('N# FACTURA', 20, "C"),
            array("RETENCION", 26, "L"),
            array("TOTAL RETENCION", 35, "C"),
            array("SUBTOTAL", 40, "C"),
            array("TOTAL", 45, "C"),
        );
        
        $pdf->SetX($set_x);
        $pdf->LineWriteB($encabezado_estados);
        $pdf->SetFont('Times', '', 10);
        $pdf->SetTextColor(1,1,1);
        
        while($row_obtener_ventas=_fetch_array($query_obtener_venta_diaria)){
            if($filas<20){
                $pdf->SetX($set_x);
                $data_estados=array(
                    array($row_obtener_ventas['id_factura'], 25, "L"),
                    array($row_obtener_ventas['num_fact_impresa'],20,"L" ),
                    array('$'.$row_obtener_ventas['retencion'], 26, "R"),
                    array('$'.$row_obtener_ventas['total_menos_retencion'], 35, "R"),
                    array('$'.$row_obtener_ventas['subtotal'], 40, "R"),
                    array('$'.$row_obtener_ventas['total'], 45, 'R'),
                );
                $pdf->LineWriteB($data_estados);  
                $filas++; 
            }else{
                $filas=0;
            }

        }


        $sql_total_estados="SELECT  ROUND(SUM(f.retencion), 2) AS tretencion, 
        ROUND(SUM(f.total_menos_retencion), 2) AS total_retencion, ROUND(SUM(f.subtotal), 2) AS tsubtotal, ROUND(SUM(f.total), 2) AS total FROM factura as f LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado LEFT JOIN cliente AS c on f.id_cliente=c.id_cliente 
        WHERE f.fecha='$fecha_sel' AND f.id_empleado=$id_vendedor AND f.anulada=0" ;

        $query_total=_query($sql_total_estados);
        $pdf->SetFont('Times', 'B', 10);    
        $pdf->SetTextColor(128,64,0);
        while($row_total=_fetch_array($query_total)){
            $pdf->SetX($set_x);
            $data_total=array(
                array(utf8_decode('Totales'),45, "L" ),
                array('$'.$row_total['tretencion'], 26, 'R'),
                array('$'.$row_total['total_retencion'], 35, 'R'),
                array('$'.$row_total['tsubtotal'], 40, 'R'),
                array('$'.$row_total['total'], 45, 'R')

            );
            $pdf->LineWriteB($data_total);
        }

    }
    $pdf->Output("reporte_facturas.pdf","I");      


}

function generar_reporte_venta_diaria_gen($pdf){
    //$fecha_hoy=date('Y-m-d');
    $fecha_desde=MD($_GET['desde']);//obteniendo parametros para este reporte
    $fecha_hasta=MD($_GET['hasta']);
    
    $sql_vendedor="SELECT * FROM empleado";
    $query_vendedor=_query($sql_vendedor);
    $nombres_empleado="";
    $pdf->SetFont('Arial', 'B', 10);
        //echo 'REPORTE DE VENTA DEL MES DE '.$mes_str;
        $encabezado['titulo']="REPORTE DE VENTA DEL DIA".chr(10).ED($fecha_desde)." HASTA EL ".ED($fecha_hasta);
        $pdf->setEncabezado($encabezado);
        
        $pdf->SetMargins(15,15);
        $pdf->SetTopMargin(10);
        $pdf->SetLeftMargin(13);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true,15);
        $pdf->AddPage();
    
    while($row_vendedor=_fetch_array($query_vendedor)){
        $id_vendedor= $row_vendedor['id_empleado'];
        $nombre=$row_vendedor['nombre'];
        $apellido=$row_vendedor['apellido'];
        $nombres_empleado=$nombre.' '.$apellido;
        $encabezado=array();

        $sql_obtener_venta_diaria="SELECT f.id_factura, f.fecha, f.num_fact_impresa , f.retencion, f.total_menos_retencion, f.subtotal, 
        f.total FROM factura as f LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado 
        LEFT JOIN cliente AS c on f.id_cliente=c.id_cliente 
        WHERE f.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND f.id_empleado=$id_vendedor AND f.anulada=0 ORDER BY f.fecha";
        $query_obtener_venta_diaria=_query($sql_obtener_venta_diaria);
    
        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
    
    
        //dibujando los resultados del reporte
    
        //contara el numero de filas que se vayan dibujando
        $filas=0;
    
        $set_y+=10;
        $set_x=$pdf->GetX();
        $pdf->SetY($set_y);
        $pdf->SetX($set_x);
    
        if(_num_rows($query_obtener_venta_diaria)>0){
            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetTextColor(128, 64, 0);
            $pdf->LineWriteB(array(
                array($nombres_empleado, 189, "C")
            ));
            
            
            $encabezado_estados=array(
                array('ID', 15, "L"),
                array('FECHA', 26, "L"),
                array('N° FACT', 15, "L"),
                array("RETENCION", 25, "L"),
                array("TOTAL-RETENCION", 38, "L"),
                array("SUBTOTAL", 35, "C"),
                array("TOTAL", 35, "C"),
            );
            
            $pdf->SetX($set_x);
            $pdf->LineWriteB($encabezado_estados);
            $pdf->SetFont('Times', '', 10);
            //$pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(1,1, 1);
            
            while($row_obtener_ventas=_fetch_array($query_obtener_venta_diaria)){
                if($filas<20){
                    $pdf->SetX($set_x);
                    $data_estados=array(
                        array($row_obtener_ventas['id_factura'], 15, "L"),
                        array($row_obtener_ventas['fecha'],26,"L" ),
                        array($row_obtener_ventas['num_fact_impresa'],15,"L" ),
                        array('$'.$row_obtener_ventas['retencion'], 25, "R"),
                        array('$'.$row_obtener_ventas['total_menos_retencion'], 38, "R"),
                        array('$'.$row_obtener_ventas['subtotal'], 35, "R"),
                        array('$'.$row_obtener_ventas['total'], 35, 'R'),
                    );
                    $pdf->LineWriteB($data_estados);  
                    $filas++; 
                }else{
                    $filas=0;
                }
    
            }
    
    
            $sql_total_estados="SELECT ROUND(SUM(f.retencion),2) AS tretencion, ROUND(SUM(f.total_menos_retencion),2) AS total_retencion, 
            ROUND(SUM(f.subtotal),2) AS tsubtotal, ROUND(SUM(f.total),2) AS total 
            FROM factura as f LEFT JOIN empleado AS e ON f.id_empleado=e.id_empleado LEFT JOIN cliente AS c on f.id_cliente=c.id_cliente 
            WHERE f.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND f.id_empleado=$id_vendedor AND f.anulada=0";
    
            $query_total=_query($sql_total_estados);
            $pdf->SetFont('Arial', 'B', 10);    
            $pdf->SetTextColor(128, 64, 0);
            while($row_total=_fetch_array($query_total)){
                $pdf->SetX($set_x);
                $data_total=array(
                    array(utf8_decode('Totales'),56, "L" ),
                    array('$'.$row_total['tretencion'], 25, 'R'),
                    array('$'.$row_total['total_retencion'], 38, 'R'),
                    array('$'.$row_total['tsubtotal'], 35, 'R'),
                    array('$'.$row_total['total'], 35, 'R')
    
                );
                $pdf->LineWriteB($data_total);
            }
    
        }
    }

    $pdf->Output("reporte_facturas.pdf","I");      


}



    date_default_timezone_set("America/El_Salvador");
    $pdf = new PDF('P','mm', 'Letter');
    

    switch($_GET['process']){
        case 'por_mes_total':
            generar_total_factura_por_mes($pdf);
            break;
        case 'por_cliente_vendedor':
            generar_reporte_por_cliente_y_vendedor($pdf);
            break;
        case 'cuentas_por_cobrar':
            generar_reporte_de_cuentasxcobrar($pdf);
            break;
        case 'cuentas_cobrar_gen':
            generar_reporte_cuentasxcobrar_gen($pdf);
            break;
        case 'creditos_por_vendedor':
            $pdf = new PDF('L','mm', 'Letter');
            generar_reporte_creditosxvendedor($pdf);
            break;
        case 'marca_vendedor_gen':
            generar_reporte_vendedorxmarca($pdf);
            break;
        case 'reporte_venta_diario':
            generar_reporte_venta_diaria($pdf);
            break;
        case 'generar_reporte_venta_diaria_gen':
            generar_reporte_venta_diaria_gen($pdf);
            break;
    }

?>