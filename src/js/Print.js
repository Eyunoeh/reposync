

async function printStudentOJTSummary() {
    try {
        let {data: studList} = await $.ajax({
            url: '../ajax.php?action=getStudentsList',
            method: 'GET',
            dataType: 'json'
        });
        if (studList && studList.length) {
            const transformedData = studList.map(item => ({
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

async function printNarrativeReportsList(){
    try {
        let program =  new URLSearchParams(window.location.search).get('program');
        let ayStarted =  new URLSearchParams(window.location.search).get('ayStarting');
        let ayEnded =  new URLSearchParams(window.location.search).get('ayEnding');
        let Semester =  new URLSearchParams(window.location.search).get('Semester');
        let {data: narrativeList} = await $.ajax({
            url: '../ajax.php?action=getPublishedNarrativeReport',
            method: 'GET',
            dataType: 'json'
        });
        const  adviserList  = await $.ajax({
            url: '../ajax.php?action=getAdvisers' ,
            method: 'GET',
            dataType: 'json'
        });
        let advisers = adviserList.data.reduce((acc, adviser) => {
            let { user_id, first_name, last_name } = adviser;
            if (!acc[user_id]) {
                acc[user_id] = { name: `${first_name} ${last_name}`, user_id: user_id };
            }
            return acc;
        }, {});
        let array_narrativeList = []



        Object.entries(narrativeList).forEach(([key, narrative]) => {
            console.log(narrative);
            if (narrative.program_code === program &&
                narrative.ayStarting === parseInt(ayStarted) &&
                narrative.ayEnding === parseInt(ayEnded) &&
                narrative.Semester === Semester &&
                narrative.file_status === 'Approved'){
                array_narrativeList.push(narrative)
            }

        });
        if (array_narrativeList && array_narrativeList.length) {
            const transformedData = array_narrativeList.map(item => ({
                Student_no: item.enrolled_stud_id,
                Name: `${item.last_name} ${item.first_name} ${item.middle_name || ''}`.trim(), // Combine names
                Adviser: advisers[item.ojt_adv_id]?.name || 'N/A',
                Sex: item.sex,
                AcadYear: item.ayStarting + ' - ' + item.ayEnding,
                Semester: item.Semester

            }));
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.json_to_sheet(transformedData);

            worksheet['!cols'] = new Array(Object.keys(transformedData[0]).length).fill({ wch: 23 });

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
            XLSX.writeFile(workbook, Semester+'_'+ ayStarted + '_' + ayEnded + '_narrativeList.xlsx');
        } else {
            Alert('notifBox','No data available to export.','error');
        }
    } catch (error) {
        console.error('Error exporting data:', error);
        alert('Failed to export data.');
    }
}
