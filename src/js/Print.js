

async function printTable(TableDataType) {
    try {
        let {data: exportableData} = await $.ajax({
            url: '../ajax.php?action=exportData&type=' + TableDataType,
            method: 'GET',
            dataType: 'json'
        });

        if (exportableData && exportableData.length) {
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.json_to_sheet(exportableData);

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            XLSX.writeFile(workbook, 'exported_data.xlsx');
        } else {
            alert('No data available to export.');
        }
    } catch (error) {
        console.error('Error exporting data:', error);
        alert('Failed to export data.');
    }
}
