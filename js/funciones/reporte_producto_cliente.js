$(document).ready(function () {
    $(".datepick").datepicker();
    $(".select2").select2();

    $("#submit").click(function () {
        var process = $("#process").val();

        if (process == "reporte") {
            let id_cliente = $("#id_cliente").val();
            let marca = $("#marca").val();
            let mes_inicial = $('#mes_inicial').val();
            let mes_final = $('#mes_final').val();
            var cadena = "reporte_producto_cliente.php?id_cliente=" + id_cliente
            + "&marca=" + marca
            + "&mes_inicial=" + mes_inicial
            + "&mes_final=" + mes_final;

            window.open(cadena, '', '');
        }
        else {
            cargar();
        }

    })
    $("#submit2").click(function () {
        var process = $("#process").val();

        if (process == "reporte") {
            let id_cliente = $("#id_cliente").val();
            let marca = $("#marca").val();
            let mes_inicial = $('#mes_inicial').val();
            let mes_final = $('#mes_final').val();
            var cadena = "reporte_producto_cliente_xls.php?id_cliente=" + id_cliente
            + "&marca=" + marca
            + "&mes_inicial=" + mes_inicial
            + "&mes_final=" + mes_final;

            window.open(cadena, '', '');
            //alert("aca")
        }
        else {
            cargar();
        }

    })
});
