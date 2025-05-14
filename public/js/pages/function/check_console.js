
var apiRequestTimeout;
var isRefreshing = false;
var totalDataCount = 0; 

function addTimeToSession() {
    var imei = $("#search").val(); // Get the IMEI value
    if (!imei) {
        $("#imeiAlert").show(); // Show the alert
        return; // Don't make the API request if IMEI is empty
    } else {
        $("#imeiAlert").hide(); // Hide the alert if IMEI is not empty
    }
    $("#apiResponseContainer").empty();
    var currentTime = new Date().toLocaleString();
    console.log(currentTime);
    imei = $("#search").val();
    if(imei){
        $.ajax({
            type: "POST",
            url: SITEURL + "/admin/add_time_to_session",
            data: {
                currentTime: currentTime,
                imei: imei,
            },
            success: function (response) {
                console.log(response);
                console.log("Current time added to session successfully.");
            },
        });
    }
}

function makeApiRequest() {
    // $("#apiResponseContainer").empty();
    if (isRefreshing) {
        // Don't make the API request if the page is being refreshed
        return;
    }
    imei = $("#search").val();
    // console.log(imei);
    
    //check imei
    $.ajax({
        type: "GET",
        url : SITEURL+'/admin/check_valid_imei',
        data: {'imei': imei},
        success: function(data){
            response = data.replace(/^\s+|\s+$/g, "");
            const responseData = JSON.parse(response);
            // console.log(responseData.imei);
            // console.log(responseData.valid);
         
            if (responseData.imei == 1) {
                if (responseData.valid == 1) {
                    // IMEI is valid, proceed with the API request
                    $.ajax({
                        type: "GET",
                        url: SITEURL + "/admin/getRegisteredData",
                        success: function (response) {
                            response = response.replace(/^\s+|\s+$/g, "");
                            const responseData = JSON.parse(response);
            
                            totalDataCount += responseData.length;
            
                            if (totalDataCount >= 250) {
                                swal({
                                    title: 'Max Data Limit Reached',
                                    type: "alert",
                                    html: true,
                                    text: 'Do you wish to reset and continue?',
                                    showCancelButton: true,
                                    cancelButtonText: 'Cancel',
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        $("#apiResponseContainer").empty();
                                        totalDataCount = 0; // Reset the total data count
                                        makeApiRequest();
                                    } else {
                                        location.reload();
                                    }
                                });
                        }
        
                        // Initialize an empty HTML string to hold the content
                        let htmlContent = "";
        
                        // Iterate through the response data and create HTML elements
                        responseData.forEach((item) => {
                            htmlContent += `
                            <div class="value">
                                <span class="event-title">Raw data   :</span>
                                <span class="event-value">${item.data}</span>
                            </div>
                            
                            <div class="value">
                                <span class="event-title">Date   :</span>
                                <span class="event-value">${item.created_time}</span>
                            </div>
                            <br>
                            <br>
                        `;
                        });
        
                        // Append the HTML content to the container
                        $("#apiResponseContainer").append(htmlContent);
                        $("#apiResponseContainer").show();
                    },
                    error: function (xhr, status, error) {
                        console.error("API request failed:", error);
                        apiRequestTimeout = setTimeout(makeApiRequest, 60000);
                    },
                });
            } else {
                // IMEI is valid but not able to search
                swal({
                    title: 'Validation Error',
                    type: "error",
                    html: true,
                    text: 'You did\'t have access to view details of this device.Only respective Distributor/Dealer can view details. ',
                }, function (isConfirm) {
                    location.reload();
                });
            }
        } else {
            // IMEI not found
            swal({
                title: 'IMEI Not Found',
                type: "error",
                html: true,
                text: 'IMEI number is not found',
            }, function (isConfirm) {
                location.reload();
            });
        }
        

    }
})



}

// Function to schedule the next API request after 1 minute (60000 milliseconds)
function scheduleNextApiRequest() {
    apiRequestTimeout = setTimeout(function () {
        makeApiRequest();
        scheduleNextApiRequest();
    }, 60000);
}

function stopApiRequestOnRefresh() {
    isRefreshing = true;
    clearTimeout(apiRequestTimeout);
}


$("#searchfiltersubmit").click(function () {
    isRefreshing = false; // Reset the refreshing flag
    clearTimeout(apiRequestTimeout); // Clear any existing timeout
    makeApiRequest(); // Start the API requests
    scheduleNextApiRequest(); // Schedule subsequent API requests
});

// Add an event listener for the beforeunload event to stop the API request when refreshing
window.addEventListener("beforeunload", stopApiRequestOnRefresh);