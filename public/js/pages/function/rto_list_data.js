function showConfirmmesage(rto_id) {
    console.log(rto_id)
    $.ajax({
        url: SITEURL + "admin/check_rto",
        method: "POST",
        data:  { 'rto_id': rto_id } ,
        success: function (data) {
            data = data.replace(/^\s+|\s+$/g, "");
            data = JSON.parse(data);
            if(data != 0)
                {
                    swal({
                            title: "<bold>ERROR!</bold>",						
                            type: "error",	
                            html: true,
                            text: 'Cannot delete this RTO, Certificate already created with this RTO',						
                        }, function (isConfirm) {
                            if(isConfirm)
                            {
                                // location.reload();
                            }
                        });						
                }
                else{
                    // if(confirm('Are you sure you want to delete it?')) 
                    
                    Swal.fire({
                title: 'Enter your password',
                input: 'password',
                inputPlaceholder: 'Please Enter The Delete Password',
                showCancelButton: true,
                inputAttributes: {
                autocapitalize: 'off',
                autocomplete: 'new-password',
                autocorrect: 'off',
                customClass: {
                container: 'swal2-title',
                title: 'swal2-title',
                input: 'swal2-title',
                confirmButton: 'swal2-title'
              }
            },
            preConfirm: (password) => {
            console.log(password);
            return new Promise((resolve) => {
                // You can perform additional validation or checks here
                resolve(password);
              });
            }
            }).then((result) => {
                if (result.isConfirmed) {
                        const password = result.value;
                        // Use the password as needed
                        if (password == null) {
                            Swal.fire("Error!","Please Enter The Password!")
                            return false;
                        }

                        if (password === "") {
                            Swal.fire("Error!","Please Enter The Password!");
                        return false
                        }
                        if (password != "" && password != null) {
                        // return true;
                        $.post(SITEURL + "admin/delete_rto", { 'rto_id': rto_id ,"password":password}, function (data) {
                            data = data.replace(/^\s+|\s+$/g, "");
                            data = JSON.parse(data);
                            if (data.error) {
                                // showWithTitleMessage(data.error, '');
                                Swal.fire("Error!",data.message)
                            }
                            if (data.success) {
                                swal({
                                    title: "<bold>SUCCESS</bold>",						
                                    type: "success",	
                                    html: true,
                                    text: 'Rto deleted successfully',						
                                }, function (isConfirm) {
                                if(isConfirm)
                                {
                                    location.reload();
                                }
                                });	
                            }
                            })
                            }
                    }
                })
                    }
    },
    error: function() {
        alert('Error saving data!');
    }
    });
}