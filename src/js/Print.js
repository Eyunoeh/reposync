

async function printStudentOJTSummary() {
    try {
        let {data: exportableData} = await $.ajax({
            url: '../ajax.php?action=getStudentsList',
            method: 'GET',
            dataType: 'json'
        });
        if (exportableData && exportableData.length) {
            const transformedData = exportableData.map(item => ({
                Name: `${item.last_name} ${item.first_name} ${item.middle_name || ''}`.trim(), // Combine names
                Student_no: item.enrolled_stud_id,
                Email: item.email,
                Sex: item.sex,
                OJT_Center: item.ojt_center,
                OJT_Started: item.OJT_started !== null ? item.OJT_started : 'N/A',
                Date_Completed: item.OJT_ended  !== null ? item.OJT_ended : 'N/A',
            }));

            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.json_to_sheet(transformedData);

            worksheet['!cols'] = new Array(Object.keys(transformedData[0]).length).fill({ wch: 23 });

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
