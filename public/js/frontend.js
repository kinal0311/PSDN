var Base64 = {

    // private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}
var startNo = 0;
var lang = '?lang=en&device_id=device_id&version=1.1&platform=W';
function trackiniginfo(search) 
{
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .tracking').addClass('active');
    }, 1000)
    var info = window.localStorage.getItem('info');
    if (!info) {
       // alert('if_1');
        window.location.href = base_url;
        return false
    }
    var data = JSON.parse(Base64.decode(info));
    
    if (data && !data.id) {
        //alert('if_2');
        window.location.href = base_url;
        return false
    }
    if (!search) {
        search = '';
    }
     var Obj = {};
     Obj.id = data.id;
    Obj.start = startNo;
    Obj.search = search;
    if (Obj.search.length > 0) {
        Obj.start = 0;
        startNo = 0;
        $('#tbody').html('');
        //$('[name=table_search]').val('');
    }
    Obj.limit = 10;
    //alert("called function")
   
    $.post(base_url + 'portal/cerificate_list' + lang, Base64.encode(JSON.stringify(Obj)), function(response) 
    {
        response = JSON.parse(Base64.decode(response));
        //var res = Base64.decode(response);
        console.log(response.data);
        var html = '<tr><td colspan="5">' + response.no_records_msg + '</td></tr>'
        if (response.data && response.data.length) {
            html = '';
            $('#showMore').remove();
            var trstart = $('#tbody tr').length;
            var sosimg=base_url+'public/images/sos.png';
            response.data.map(function(res, val) {
                 //console.log("commit aaiten==>"+JSON.stringify(res));
                //  console.log("result==>"+val);
                res.viewTrackingInfo = res.viewTrackingInfo.replace('/admin/tracking', '/portal/tracking');
                res.s_imei = (res.s_imei) ? res.s_imei : "--";
                res.s_mobile = (res.s_mobile) ? res.s_mobile : "--";
                html += '<tr>';
                html += '<th>' + (parseInt(trstart) + 1) + '</th>';
                html += '<th>' + res.vehicleNum + '</th>';
                html += '<th>' + res.s_imei + '</th>';
                html += '<th>' + res.serialNum + '</th>';
                html += '<th>' + res.s_mobile + '</th>';
                html += '<th>' + res.validUpto + '</th>';
                html += '<th><a onClick="return SOSMSG(event,this);" imei="'+res.s_imei+'" href="javascript:void(0)"><img style="height:30px;width:30px;cursor: pointer;"  src="'+sosimg+'" /></a></th>';
                html += '<th> <a href="' + res.viewTrackingInfo + '" >Track Now</a></th>';
                html += '</tr>';
                trstart++;
            });
            $('#tbody').append(html);
            html = '<tr id="showMore"><td style="text-align:right;" colspan="7"><a onClick="return trackiniginfo()" href="javascript:void(0);">Show More</a></td></tr>';
            $("#tbody tr:last").after(html);
            if (response.nextStart) {
                startNo = response.nextStart;
            }
        } else {

            $.alert({
                title: 'No Records',
                content: 'No More Records found',
            });

        }

    });
}

function view_tracking() 
{
    //alert("called")
    setTimeout(function() {
        $('.sidebar-menu .active').removeClass('active');
        $('.sidebar-menu .tracking').addClass('active');
    }, 1000)
    
    // var info = window.localStorage.getItem('info');
    
    // if (!info) {
    //     alert("if....1");
    //     window.location.href = base_url;
    //     return false
    // }

    // var data = JSON.parse(Base64.decode(info));
    // if (data && !data.id) {
    //     alert("if....2");
    //     window.location.href = base_url;
    //     return false
    // }

    var Obj = {};
    Obj.vehId = vehId;
    $.post(base_url + 'portal/view_tracking' + lang, Base64.encode(JSON.stringify(Obj)), function(response) {
        //alert("func called");
        console.log("RESPONSE==>"+response);
        //console.log(Base64.decode(response));
        response = JSON.parse(Base64.decode(response));
        //alert("socketurl====>"+SOCKET_URL);
        //console.log("RESPONSE==>"+response.data);
        if (response && response.data && response.data[0]) {
            for (var i = 0; i < Object.keys(response.data[0]).length; i++) {
                var cur = Object.keys(response.data[0])[i];
                var value = response.data[0][cur];

                if (cur === 'lastupdatedTime') {
                    value = new Date(value * 1000);
                }

                $('#' + cur).text(value);
            }

        }
        if (response && response.data && response.data[1]) {
            for (var i = 0; i < Object.keys(response.data[1]).length; i++) {
                var cur = Object.keys(response.data[1])[i];
                var value = response.data[1][cur];

                if (cur === 'lastupdatedTime') {
                    value = new Date(value * 1000);
                }

                $('#' + cur).text(value);
            }

        }
    });
}

function search_vehicle() 
{ 
    let input = document.getElementById('searchbar').value 
    input=input.toLowerCase(); 
    let x = document.getElementsByClassName('vehicles'); 
      
    for (i = 0; i < x.length; i++) {  
        if (!x[i].innerHTML.toLowerCase().includes(input)) { 
            x[i].style.display="none"; 
        } 
        else { 
            x[i].style.display="list-item";                  
        } 
    } 
}

