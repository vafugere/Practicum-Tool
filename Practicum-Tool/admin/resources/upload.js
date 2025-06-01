$(document).ready(function() {
        $("#excel_file").on("change", function() {
            var fileName = this.files[0] ? this.files[0].name : "No file chosen";
            $("#file_name").text(fileName);
        });

        $("#upload_file").on("submit", function(e) {
            if ($("#campus_id").val() === "-1") {
                e.preventDefault();
            } else {
                $("#loading").css("display", "flex");
            }
        });
    });