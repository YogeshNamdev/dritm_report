$(document).ready(function () {

    // Initialize Select2
    $("#source").select2({
        placeholder: "Select Source",
        width: '100%'
    });

    $("#destination").select2({
        placeholder: "Select Destination",
        width: '100%'
    });

    //-------------------------------------------------------
    // Search Button Click
    //-------------------------------------------------------

    $("#btnSearch").click(function () {

        searchRoute();

    });

    //-------------------------------------------------------
    // Search Function
    //-------------------------------------------------------

    function searchRoute() {

        var source = $("#source").val();

        var destination = $("#destination").val();

        $("#resultArea").hide();
        $("#noResult").hide();

        if (source == "") {

            alert("Please Select Source");

            $("#source").focus();

            return false;

        }

        if (destination == "") {

            alert("Please Select Destination");

            $("#destination").focus();

            return false;

        }

        if (source == destination) {

            alert("Source and Destination cannot be same.");

            return false;

        }

        $("#loader").show();

        $.ajax({

            url: base_url + "hrtc/search",

            type: "POST",

            dataType: "json",

            data: {

                source: source,

                destination: destination

            },

            success: function (response) {

                $("#loader").hide();

                if (response.status == false) {

                    alert(response.message);

                    return false;

                }

                if (response.data.length == 0) {

                    $("#noResult").show();

                    return false;

                }

                createTable(response.data);

            },

            error: function () {

                $("#loader").hide();

                alert("Something Went Wrong.");

            }

        });

    }

    //-------------------------------------------------------
    // Create Table
    //-------------------------------------------------------

    function createTable(result) {

        var html = "";

        $.each(result, function (i, row) {

            html += "<tr>";

            html += "<td>" + (i + 1) + "</td>";

            html += "<td>" + row.ServiceNo + "</td>";

            html += "<td>" + row.DepotName + "</td>";

            html += "<td>" + row.BusType + "</td>";

            html += "<td>" + row.DepartureTime + "</td>";

            html += "<td>" + row.ArrivalTime + "</td>";

            html += "<td>" + row.TimeTaken + "</td>";

            html += "<td>" + row.TotalStops + "</td>";

            html += "<td>";

            html += "<button ";

            html += "class='btn btn-success btn-sm btnRoute' ";

            html += "data-route='" + row.RouteId + "' ";

            html += "data-source='" + $("#source").val() + "' ";

            html += "data-destination='" + $("#destination").val() + "'>";

            html += "<i class='fa fa-map'></i> View Route";

            html += "</button>";

            html += "</td>";

            html += "</tr>";

        });

        $("#resultTable tbody").html(html);

        $("#resultArea").show();

    }

    //-------------------------------------------------------
    // View Route
    //-------------------------------------------------------

    $(document).on("click", ".btnRoute", function () {

        var routeId = $(this).data("route");

        var source = $(this).data("source");

        var destination = $(this).data("destination");

        window.location =
            base_url +
            "hrtc/route/" +
            routeId +
            "?source=" +
            source +
            "&destination=" +
            destination;

    });

});