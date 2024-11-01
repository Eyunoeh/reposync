document.addEventListener('DOMContentLoaded', function (){
    dashboard_student_NarrativeReports();
})
async function dashboard_student_NarrativeReports() {
    let program =  new URLSearchParams(window.location.search).get('program');
    let user = await user_info();
    let user_data = user.data;


    const  narratives  = await $.ajax({
        url: '../ajax.php?action=getPublishedNarrativeReport&program=' + program ,
        method: 'GET',
        dataType: 'json'
    });
    let narrative_listData = narratives.data
    let narratives_length = narrative_listData && Object.keys(narrative_listData).length
    let narrativeTblData = '';
    if (narratives_length === 0){
        $('#tableLoader').html(`<p class="text-slate-700 font-sans">No result</p>`)
    }else {
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



        Object.entries(narrative_listData).forEach(([key, narrative]) => {
            array_narrativeList.push(narrative)


        });

        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( array_narrativeList.length/ totalRecPerpage);


        let paginatedList = array_narrativeList.slice(offset, offset + totalRecPerpage);

        paginatedList.forEach(narrative =>{
            let years = narrative.ay_submitted.split(',');
            let startingAC = years[0].trim();
            let endingAC =  years[1].trim();
            let formattedSem = {
                First: '1st',
                Second: '2nd',
                Summer: 'Summer'
            };
            narrativeTblData += `<tr class="border-b border-dashed last:border-b-0 p-3">
                        <td class="p-3 text-start">
                            <span class="font-semibold text-light-inverse text-sm">${narrative.enrolled_stud_id}</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${narrative.first_name} ${narrative.last_name}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal  break-words">${advisers[narrative.adv_id].name}</span>
                        </td>
                        <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${formattedSem[narrative.sem_submitted]}</span>
                        </td>
                         <td class="p-3 text-start min-w-32">
                            <span class="font-semibold text-light-inverse text-md/normal break-words">${startingAC} - ${endingAC}</span>
                        </td>

                        <td class="p-3 text-end ">
                        `;


                    if (user_data.user_type === 'admin') {
                        narrativeTblData += `
                                        <div class="tooltip tooltip-warning tooltip-bottom" data-tip="Archive">
<a onclick="openModalForm('archiveNarrativeModal'); $('#archiveNarrative').attr('data-narrative', $(this).attr('data-narrative'));" 
   id="archive_narrative" 
   data-narrative="${narrative.narrative_id}" 
   class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info">
   <i class="fa-solid fa-pen-to-square"></i>
</a>
                                        </div>`;
                    }




                      narrativeTblData += `      
                            <a href="flipbook.php?view=${narrative.narrative_id}" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>`;

        })
        $('#tableLoader').empty()

        $('#narrativeReportsTableBody').html(narrativeTblData);

    }

}