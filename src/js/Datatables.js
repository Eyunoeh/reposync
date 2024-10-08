
// customize data table ko kung babasahin ko
// to sa future hindi ko rin na maiintindihan goodluck nalang AHAHHA
let page_no = 1;
let  totalRecPerpage = 10;
let total_page = 0;
let lastSortedColumn = -1; // Track the last sorted column index


function handleSearch(inputId, tableId) {
    let input, filter, programFilter, table, tbody, tr, td, i, txtValue;
    input = document.getElementById(inputId);
    filter = input.value.toUpperCase();
    programFilter = document.getElementById("programFilter");

    if (programFilter) {
        programFilter = programFilter.value;
    } else {
        programFilter = "";
    }
    table = document.getElementById(tableId);
    tbody = table.getElementsByTagName("tbody")[0];
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        let found = false;
        let programMatch = false;
        for (let j = 0; j < td.length; j++) {
            let cell = td[j];
            if (cell) {
                txtValue = cell.textContent || cell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        if (programFilter === "" || programFilter === "Select Program" || programFilter === td[2].textContent.trim()) {
            programMatch = true;
        }
        if (found && programMatch) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

function sortTable(columnIndex, table_id) {
    let table = document.getElementById(table_id);
    let rows = Array.from(table.rows).slice(1); // Skip header
    let ascending = true; // Default sort order is ascending

    if (columnIndex === lastSortedColumn) {
        // If same column, toggle the sort order
        ascending = table.getAttribute("data-order") === "asc";
    } else {
        // Reset sort order to ascending if a new column is clicked
        ascending = true;
        lastSortedColumn = columnIndex; // Update the last sorted column
    }


    const headers = table.querySelectorAll('th');
    headers.forEach((th, idx) => {
        const icon = th.querySelector('.sort-icon');
        if (icon) {
            icon.textContent = ''; // Clear all icons
        }
    });

    // Sort the rows
    rows.sort(function(rowA, rowB) {
        let cellA = rowA.cells[columnIndex].innerText;
        let cellB = rowB.cells[columnIndex].innerText;

        if (!isNaN(cellA) && !isNaN(cellB)) {  // Numeric comparison
            return ascending ? cellA - cellB : cellB - cellA;
        }

        return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });

    // Re-append sorted rows
    rows.forEach(row => table.tBodies[0].appendChild(row));

    // Set the sort direction in the table's data attribute
    table.setAttribute("data-order", ascending ? "desc" : "asc");

    // Set the appropriate icon for the current column
    let icon = headers[columnIndex].querySelector('.sort-icon');
    icon.textContent = ascending ? ' ▲' : ' ▼';  // Show ascending/descending icon
}

function resetDataTable(){
    page_no = 1;
    lastSortedColumn = -1; //reset last sorted col
    $('#pageInfo').html(`Page ` + page_no );
    totalRecPerpage = 10;
}




function renderPage_lim(renderLim, next_Pagebtn, prev_Pagebtn){
    let tbl_dis_nxtData = document.getElementById(next_Pagebtn);
    let tbl_dis_prevData = document.getElementById(prev_Pagebtn);


    totalRecPerpage = renderLim;
    page_no = 1;
    $('#pageInfo').html(`Page ` + page_no );


    tbl_dis_nxtData.disabled = false;
    tbl_dis_prevData.disabled = false;
}
function nextPage(nextbtn_id, prevbtn_id){
    let tbl_dis_nxtData = document.getElementById(nextbtn_id);
    let tbl_dis_prevData = document.getElementById(prevbtn_id);
    if (page_no === total_page){
        tbl_dis_nxtData.disabled = true;

    }else {
        tbl_dis_prevData.disabled = false;
        page_no += 1;

        $('#pageInfo').html(`Page ` + page_no );
    }
}
function prevPage(prevbtn_id, nextbtn_id){
    let tbl_dis_nxtData = document.getElementById(nextbtn_id);

    let tbl_dis_prevData = document.getElementById(prevbtn_id);

    if (page_no === 1){
        tbl_dis_prevData.disabled = true;
    }else{
        tbl_dis_nxtData.disabled = false;
        page_no -= 1;
        $('#pageInfo').html(`Page ` + page_no );
    }
}