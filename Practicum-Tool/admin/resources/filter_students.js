function loadStudents(page = 1) {
    let fullname = $("#filter_name").val();
    let eligible = $("#filter_eligible").val();
    let form = $("#filter_form").val();
    let campus = $("#filter_campus").val();
    let year = $("#filter_year").val();
    let limit = $("#filter_limit").val();

    limit = limit && !isNaN(limit) && parseInt(limit) > 0 ? parseInt(limit) : 25;

    $.ajax({
        url: "api/fetch_students.php",
        method: "POST",
        data: {
            fullname: fullname,
            campus: campus,
            eligible: eligible,
            form: form,
            year: year,
            limit: limit,
            page: page
        },
        success: function (response) {
            $("#display_students").html(response);
        }
    });
}

$(document).on("click", ".page-btn", function (e) {
    e.preventDefault();
    let page = $(this).data("page");
    if (page) {
        loadStudents(page);
    }
});

$(document).ready(function () {
    $("#filter_campus, #filter_eligible, #filter_form, #filter_year, #filter_limit").on("change", function () {
        loadStudents(1);
    });

    $("#filter_name").on("input", function () {
        loadStudents(1);
    });
});

