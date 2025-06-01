$(document).ready(function() {
    $(document).on("click", "#btn_print", function(e) {
        e.preventDefault();
        $("#student_list select").each(function() {
            var selectedText = $(this).find("option:selected").text();
            $(this).after('<span class="print-text">' + selectedText + '</span>').hide();
        });
        window.print();
        $("#student_list select").show();
        $(".print-text").remove();
    });

    $(document).on("click", "#btn_excel", function (e) {
        e.preventDefault();
        let csvContent = [];
        $("#student_list tr").each(function (rowIndex) {
        let rowData = [];
            $(this).find("th, td").each(function (index) {
                if (index === $(this).parent().children().length - 1) return;

                let text = "";

                let select = $(this).find("select");
                if (select.length) {
                    text = select.find("option:selected").text();
                } else {
                    text = $(this).text().trim(); 
                }

                text = text.replace(/"/g, '""'); 

                if (rowIndex === 0) {
                    if (index === 4) text = "Decision"; 
                    if (index === 5) text = "Secured";  
                }
                rowData.push(`"${text}"`); 
            });
            csvContent.push(rowData.join(","));
        });
        let csvBlob = new Blob([csvContent.join("\n")], { type: "text/csv" });
        let a = $("<a>")
            .attr("href", URL.createObjectURL(csvBlob))
            .attr("download", "student_list.csv")
            .appendTo("body");

        a[0].click();
        a.remove(); 
    });
});