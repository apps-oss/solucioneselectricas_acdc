$(document).ready(function(){
    //Ocultando los campos al cargar la pagina
    $(".fecha").datepicker({
        format:"dd-mm-yyyy",
    });
    $("#conte_vendedor").css({
        'display':'none',
    })
    $("#conte_cliente").css({
        'display':'none',
    })
    $("#conte_inicio").css({
        'display':'none',
    })
    $("#conte_fin").css({
        'display':'none',
    })



    $("#tipo_reporte").select2();
    $("#tipo_reporte").change(function(){
        var tipo =$(this).val();
        //alert(tipo);
        if(tipo=="por_cliente_vendedor"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', false);
            $('#txt_cliente').prop('disabled', false);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);
            $("#txt_vendedor").css({
                'border-color':'#0000ff ',
            })
            $("#txt_cliente").css({
                'border-color':'#0000ff ',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })
            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'block',
            })
            $("#conte_cliente").css({
                'display':'block',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })

        }else if(tipo=="cuentas_por_cobrar"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', false);
            $('#txt_cliente').prop('disabled', false);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);

            $("#txt_vendedor").css({
                'border-color':'#0000ff ',
            })
            $("#txt_cliente").css({
                'border-color':'#0000ff ',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })
            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'block',
            })
            $("#conte_cliente").css({
                'display':'block',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })


        }else if(tipo=="por_mes_total"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', true);
            $('#txt_cliente').prop('disabled', true);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);

            $("#txt_vendedor").css({
                'border-color':'#e5e6e7',
            })
            $("#txt_cliente").css({
                'border-color':'#e5e6e7',
            })
            $("#fini").css({
                'border-color':'#0000ff',
            })
            $("#fin").css({
                'border-color':'#0000ff',
            })

            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'none',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })
        }else if(tipo=="cuentas_cobrar_gen"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', true);
            $('#txt_cliente').prop('disabled', true);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);
            $("#txt_vendedor").css({
                'border-color':'#e5e6e7',
            })
            $("#txt_cliente").css({
                'border-color':'#e5e6e7',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })

            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'none',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })

        }else if(tipo=="marca_vendedor_gen"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', true);
            $('#txt_cliente').prop('disabled', true);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);
            $("#txt_vendedor").css({
                'border-color':'#e5e6e7',
            })
            $("#txt_cliente").css({
                'border-color':'#e5e6e7',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })
            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'none',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })

        }else if(tipo=="creditos_por_vendedor"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', false);
            $('#txt_cliente').prop('disabled', true);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);

            $("#txt_vendedor").css({
                'border-color':'#0000ff ',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })
            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'block',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })

        }else if(tipo=="reporte_venta_diario"){
            limpiar_msg()
            var fecha_hoy = new Date();
            var fecha_format=formatearFechaJs(fecha_hoy);
            $('#fini').val(fecha_format);//asignando la fecha actual formateada al input type text
            $("#txt_vendedor").prop('disabled', false);
            $('#fini').prop('disabled', false);
            $("#txt_vendedor").css({
                'border-color':'#0000ff',
            })
            $("#txt_cliente").css({
                'border-color':'#e5e6e7',
            })
            $("#fini").css({
                'border-color':'#0000ff',
            })
            $("#fin").css({
                'border-color':'#e5e6e7',
            })

            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'block',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'none',
            })
        }else if(tipo=="reporte_venta_gen"){
            limpiar_msg();
            $("#txt_vendedor").prop('disabled', true);
            $('#txt_cliente').prop('disabled', true);
            $('#fini').prop('disabled', false);
            $("#fin").prop('disabled', false);
            $("#txt_vendedor").css({
                'border-color':'#e5e6e7',
            })
            $("#txt_cliente").css({
                'border-color':'#e5e6e7',
            })
            $("#fini").css({
                'border-color':'#0000ff ',
            })
            $("#fin").css({
                'border-color':'#0000ff ',
            })

            //ocultando campos que no se ocupen
            $("#conte_vendedor").css({
                'display':'none',
            })
            $("#conte_cliente").css({
                'display':'none',
            })
            $("#conte_inicio").css({
                'display':'block',
            })
            $("#conte_fin").css({
                'display':'block',
            })
        }
    });

    function limpiar_msg(){
        $("#msg_cliente").html("");
        $("#msg_vendedor").html("");
        $("#msg_fini").html("");
        $("#msg_fin").html("");
    }

    $("#txt_vendedor").typeahead({
        highlight: true,
    },{
        limit: 100,
        name: 'vendedores',
        display: 'vendedor',
        source: function show(q, cb, cba) {
          console.log(q);
          var url = 'autocomplete_vendedor.php' + "?&query=" + q;
          $.ajax({
              url: url
            })
            .done(function(res) {
              cba(JSON.parse(res));
            })
            .fail(function(err) {
              alert(err);
            });
        }
    }).on('typeahead:selected', function($e, datum){
        $('.typeahead').typeahead('val', '');
        var vend = datum.vendedor;
        var vendedor = vend.split("|");
        var id_vendedor = vendedor[0]; //corregido 18 ene 2021
        var nombres = vendedor[1];
        $("#vendedor").val(id_vendedor);

    });

    $("#txt_cliente").typeahead({
        highlight: true,
    },{
        limit: 100,
        name: 'clientes',
        display: 'cliente',
        source: function show(q, cb, cba) {
          console.log(q);
          var url = 'autocomplete_cliente1.php' + "?&query=" + q;
          $.ajax({
              url: url
            })
            .done(function(res) {
              cba(JSON.parse(res));
            })
            .fail(function(err) {
              alert(err);
            });
        }
    }).on('typeahead:selected', function($e, datum){
        $('.typeahead').typeahead('val', '');
        var clie = datum.cliente;
        var cliente = clie.split("|");
        var id_cliente = cliente[0];
        var nombre = cliente[1];
        $("#cliente").val(id_cliente);

    });

    $("#submit").click(function(evt){
        var tipo_reporte=$("#tipo_reporte").val();
        if(tipo_reporte.length>0){
            switch(tipo_reporte){
                case 'por_mes_total':
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=por_mes_total&desde="+fecha_inicio+"&hasta="+fecha_fin;
                        new_tab(link);
                    }else{
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_fini").css({
                            'color':'red',
                        });
                        $("#msg_fin").css({
                            'color':'red',
                        });
                    }
                    break;
                case 'por_cliente_vendedor':
                    var id_cliente=$("#cliente").val();
                    var id_vendedor=$("#vendedor").val();
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(id_cliente.length>0 && id_vendedor.length>0 && fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=por_cliente_vendedor"+
                        "&id_cliente="+id_cliente+"&id_vendedor="+id_vendedor+"&desde="+fecha_inicio+""+
                        "&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("Debe de agregar un cliente");
                        $("#msg_vendedor").html("Debe de seleccionar un vendedor");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        });
                        $("#msg_vendedor").css({
                            'color':'red',
                        });
                        $("#msg_fini").css({
                            'color':'red',
                        });
                        $("#msg_fin").css({
                            'color':'red',
                        });
                    }

                    break;
                case 'cuentas_por_cobrar':

                    var id_cliente=$("#cliente").val();
                    var id_vendedor=$("#vendedor").val();
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(id_cliente.length>0 && id_vendedor.length>0 && fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=cuentas_por_cobrar&id_cliente="+id_cliente+"&id_vendedor="+id_vendedor+"&desde="+fecha_inicio+"&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("Debe de agregar un cliente");
                        $("#msg_vendedor").html("Debe de seleccionar un vendedor");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        })
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                        $("#msg_fini").css({
                            'color':'red',
                        })
                        $("#msg_fin").css({
                            'color':'red',
                        })

                    }

                    break;
                case 'creditos_por_vendedor':
                    var id_vendedor=$("#vendedor").val();
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(id_vendedor.length>0 && fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=creditos_por_vendedor&id_vendedor="+id_vendedor+"&desde="+fecha_inicio+"&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("");//aqui no se exgige cliente
                        $("#msg_vendedor").html("Debe de seleccionar un vendedor");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        })
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                        $("#msg_fini").css({
                            'color':'red',
                        })
                        $("#msg_fin").css({
                            'color':'red',
                        })
                    }
                    break;
                case 'cuentas_cobrar_gen':
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=cuentas_cobrar_gen&desde="+fecha_inicio+"&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("");//aqui no se exgige cliente
                        $("#msg_vendedor").html("");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        })
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                        $("#msg_fini").css({
                            'color':'red',
                        })
                        $("#msg_fin").css({
                            'color':'red',
                        })
                    }
                    break;
                case 'marca_vendedor_gen':
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=marca_vendedor_gen&desde="+fecha_inicio+"&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("");//aqui no se exgige cliente
                        $("#msg_vendedor").html("");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        })
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                        $("#msg_fini").css({
                            'color':'red',
                        })
                        $("#msg_fin").css({
                            'color':'red',
                        })
                    }
                    break;

                case "reporte_venta_diario":
                    var id_vendedor=$("#vendedor").val();
                    var fecha_inicio=$('#fini').val();
                    if(id_vendedor.length>0 && fecha_inicio.length>0){
                        var link="reporte_ventas.php?&process=reporte_venta_diario&id_vendedor="+id_vendedor+"&desde="+fecha_inicio;
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("");//aqui no se exgige cliente
                        $("#msg_vendedor").html("Debe seleccionar un vendedor");
                        $("#msg_fini").html("");
                        $("#msg_fin").html("");
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                    }
                    break;
                case "reporte_venta_gen":
                    var fecha_inicio=$('#fini').val();
                    var fecha_fin=$("#fin").val();
                    if(fecha_inicio.length>0 && fecha_fin.length>0){
                        var link="reporte_ventas.php?&process=generar_reporte_venta_diaria_gen&desde="+fecha_inicio+"&hasta="+fecha_fin+"";
                        new_tab(link);
                    }else{

                        $("#msg_cliente").html("");//aqui no se exgige cliente
                        $("#msg_vendedor").html("");
                        $("#msg_fini").html("debe de seleccionar una fecha de inicio");
                        $("#msg_fin").html("debe de seleccionar una fecha final");
                        $("#msg_cliente").css({
                            'color':'red',
                        })
                        $("#msg_vendedor").css({
                            'color':'red',
                        })
                        $("#msg_fini").css({
                            'color':'red',
                        })
                        $("#msg_fin").css({
                            'color':'red',
                        })
                    }
                    break;
            }
        }else{
            $("#msg_reporte").html("Debe seleccionar un tipo de reporte.");
            $("#msg_reporte").css({
                'color':'red',
            })
        }

    });

    function formatearFechaJs(fecha){
        var dia = fecha.getDate();
        var mes = fecha.getMonth()+1;
        var anio = fecha.getFullYear();

        var dia_str="";
        var mes_str="";
        if(dia<10){
            dia_str="0"+dia;
        }else{
            dia_str=""+dia;
        }
        if(mes<10){
            mes_str="0"+mes;
        }else{
            mes_str=""+mes;
        }

        return dia_str+"-"+mes_str+"-"+anio;
    }

    function new_tab(link){
        window.open(link, '_blank');
    }
});
