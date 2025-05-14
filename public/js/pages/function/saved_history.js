$(function () {
    $('#start_date,#end_date').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    $('#start_time,#end_time').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        clearButton: true,
        time: true,
        shortTime: true,
        date: false
    });


});
$(document).on("click", "#showBtn", function () {
    console.log("haii camee");
    if ($('#search_form_validation').valid()) {
        var imei_no = $("#imei_no").val();
        var formData = new FormData($('#search_form_validation')[0]);

        var start_date = $('#start_date').val();
        var end_time = $('#end_time').val();
        var start_time = $('#start_time').val();
        //formData.append('upload_csv', $('input[type=file]')[0].files[0]);
        $.post(SITEURL + "admin/save_his_data", {
            'imei_no': imei_no,
            'start_date': start_date,
            'end_time': end_time,
            'start_time': start_time
        }, function (data) {
            // console.log(data);
            var gotResult = JSON.parse(data);
            var data = gotResult.model_list.data;

            alert(data);


        });
    }
});


$(document).ready(function () {

    var value = $("#s_company_id").val();
    if (value === '') {
        return true;
    }
    $.post(SITEURL + "admin/fetch_saved_history", {}, function (data) {
        data = JSON.parse(data);
        // console.log("Data"+data.);
        if (data.list && data.list.length === 0) {
            showWithTitleMessage('No Product Records Found.', '');
        }
        var i = 1;
        var html = "";
        if (data.list && data.list.length) {
            $.each(data.list, function (resKey, resValue) {
                // html += '<option value="' + resValue.p_product_id + '">' + resValue.p_product_name + '</option>';
                var history = data.list[i - 1];
                html +=
                    `<tr><td>${i}</td><td>${history.imei}</td><td>${
                        history.date
                        }</td><td>${history.start_time}</td><td>${history.end_time}</td><td><a class="view" href="/admin/check_device_data/${history.date}/${history.start_time}/${history.end_time}/${history.imei}">
<i class="fa fa-eye"></i></a></td><td><a class="removeItem" href="/admin/delete_saved_history/${history.id}"><i class="fa fa-trash"></i></a></td></tr>`;

                i++;
            });
        }
        console.log(html);
        // tBody.innerHTML = dataHtml;
        $('#savedHistory').html(html);
        setTimeout(function () {
            $('#s_product_id').selectpicker('refresh');
        }, 1000);

    });


});


