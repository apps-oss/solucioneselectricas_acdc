//obtener subtotal cantidad x precio
function subt(qty,price){
  subtotal=parseFloat(qty)*parseFloat(price);
  subtotal=round(subtotal,4);
  return subtotal;
}
//function to round 2 decimal places
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

//function para scroll con perfect-scroll plugin
function scrolltable(){
  //scroll
  $('.js-pscroll').each(function(){
      let ps = new PerfectScrollbar(this);

      $(window).on('resize', function(){
        ps.update();
      })
    });
  //end scroll
}
//genera la peticion server side de un datable segun los parametros previos
function generateDT(dt_element,url,obj_order ){
  console.log(dt_element+" "+url+" "+obj_order)
	dataTable = $(dt_element).DataTable().destroy()
	dataTable = $(dt_element).DataTable( {
			"pageLength": 50,
			"order":obj_order,
			"language": {
				"url": "js/Spanish.json"
			},
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :url, // json datasource
					}
				});
	dataTable.ajax.reload()
}
function roundNumberV2(num, scale) {
  if (Math.round(num) != num) {
    if (Math.pow(0.1, scale) > num) {
      return 0;
    }
    let sign = Math.sign(num);
    let arr = ("" + Math.abs(num)).split(".");
    if (arr.length > 1) {
      if (arr[1].length > scale) {
        let integ = +arr[0] * Math.pow(10, scale);
        let dec = integ + (+arr[1].slice(0, scale) + Math.pow(10, scale));
        let proc = +arr[1].slice(scale, scale + 1)
        if (proc >= 5) {
          dec = dec + 1;
        }
        dec = sign * (dec - Math.pow(10, scale)) / Math.pow(10, scale);
        return dec;
      }
    }
  }
  return num;
}
function roundNumberV1(num, scale) {
  if(!("" + num).includes("e")) {
    return +(Math.round(num + "e+" + scale)  + "e-" + scale);
  } else {
    let arr = ("" + num).split("e");
    let sig = ""
    if(+arr[1] + scale > 0) {
      sig = "+";
    }
    let i = +arr[0] + "e" + sig + (+arr[1] + scale);
    let j = Math.round(i);
    let k = +(j + "e-" + scale);
    return k;
  }
}
function truncateDecimals (num, digits) {
    var numS = num.toString(),
        decPos = numS.indexOf('.'),
        substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
        trimmedResult = numS.substr(0, substrLength),
        finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

    return parseFloat(finalResult);
}

let isDUI =(str)=>{

    if(str!="00000000-0"){
   let regex = /(^\d{8})-(\d$)/,
       parts = str.match(regex);
    // verficar formato y extraer digitos junto al digito verificador
    if(parts !== null){
      let digits = parts[1],
          dig_ve = parseInt(parts[2], 10),
          sum    = 0;
      // sumar producto de posiciones y digitos
      for(let i = 0, l = digits.length; i < l; i++){
        let d = parseInt(digits[i], 10);
        sum += ( 9 - i ) * d;
      }
      return dig_ve === (10 - ( sum % 10 ))%10;
    }else{
      return false;
    }
  }else{
    return false;
  }
}
function reload_url(urlprocess) {
  let duration = 800;
   $({to:0}).animate({to:1}, duration, function() {
    location.href = urlprocess;
   })
}