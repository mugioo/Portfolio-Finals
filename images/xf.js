$(document).ready(function () {
    // Variables to handle pagination and search
    var recordsPerPage = 10;
    var totalRecords = 0;
    var currentPage = 1;
    var searchValue = ''; 
    var searchTimeout;

    // Load initial data on page load
    load_data(currentPage);

    // Search functionality
    $("#search").on("keyup", function () {
        clearTimeout(searchTimeout);
        searchValue = $(this).val();
        currentPage = 1; 
        resetPagination();
        searchTimeout = setTimeout(function () {
            load_data(currentPage, searchValue);
        }, 100);
    });

    // Pagination click event
    $(document).on("click", ".pagination li a", function (e) {
        e.preventDefault();
        var page = $(this).attr("data-page");
        currentPage = page;
        load_data(currentPage, searchValue);
    });

    // Load data via Ajax
    function load_data(page, searchValue = '') {
        console.log("Loading data for page:", page, "and searchValue:", searchValue);
        $.ajax({
            url: "../main/main.table.php",
            method: "POST",
            data: { page: page, recordsPerPage: recordsPerPage, search: searchValue },
            dataType: "html",
            success: function (data) {
            console.log("Ajax success! Data received:", data);
            $("#table-data").html(data);
            totalRecords = parseInt($("#total-records").data("total"));
            currentPage = parseInt($("#current-page").data("page"));

            updatePagination();
        },

            error: function (xhr, status, error) {
                console.error("Ajax error:", error);
            }
        });
    }

    // Update pagination links based on the current page and total records
    function updatePagination() {
        console.log("Updating pagination...");
        var totalPages = Math.ceil(totalRecords / recordsPerPage);
        console.log("Total pages:", totalPages);

        var pagination = "";

        for (var i = 1; i <= totalPages; i++) {
            pagination += "<li ";
            if (i === parseInt(currentPage)) {
                pagination += "class='active'";
            }
            pagination += "><a href='#' data-page='" + i + "'>" + i + "</a></li>";
        }

        console.log("Pagination HTML:", pagination);

        $(".pagination").html(pagination);
    }
    function resetPagination() {
        currentPage = 1;
        updatePagination();
    }

    // Call resetPagination to set the initial pagination
    resetPagination();
});




