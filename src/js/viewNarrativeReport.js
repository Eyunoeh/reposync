document.addEventListener('DOMContentLoaded', function (){
    dashboard_student_NarrativeReports();
})
async function dashboard_student_NarrativeReports() {
    //ayStarting=2023 & ayEnding=2024 &Semester= First & program=BSIT

    let program =  new URLSearchParams(window.location.search).get('program');
    let ayStarted =  new URLSearchParams(window.location.search).get('ayStarting');
    let ayEnded =  new URLSearchParams(window.location.search).get('ayEnding');
    let Semester =  new URLSearchParams(window.location.search).get('Semester');
    let user = await user_info();
    let user_data = user.data;
    let table_head = `
  <tr class="font-semibold text-[0.95rem] sticky top-0 z-20 text-secondary-dark bg-slate-200 rounded text-neutral">
    ${user_data.user_type === 'admin' ? `
      <th onclick="sortTable(0, 'narrativeReportsTable')" class="p-3 text-start">Student No.<span class="sort-icon text-xs"></span></th>
      <th onclick="sortTable(1, 'narrativeReportsTable')" class="p-3 text-start min-w-10">Name<span class="sort-icon text-xs"></span></th>
      <th onclick="sortTable(2, 'narrativeReportsTable')" class="p-3 text-start min-w-10">OJT Adviser<span class="sort-icon text-xs"></span></th>
    ` : `
      <th onclick="sortTable(0, 'narrativeReportsTable')" class="p-3 text-start min-w-10">Name<span class="sort-icon text-xs"></span></th>
    `}
    <th class="p-3 text-end">Action</th>
  </tr>`;
    $('#narrativeListThead').html(table_head);


    const  narratives  = await $.ajax({
        url: '../ajax.php?action=getPublishedNarrativeReport' ,
        method: 'GET',
        dataType: 'json'
    });
    let narrative_listData = narratives.data
    let narratives_length = narrative_listData && Object.keys(narrative_listData).length
    let narrativeTblData = '';
    if (narratives_length === 0){
        $('#tableLoader').html(`<p class="text-slate-700 font-sans">No result</p>`)
    }
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


    console.log(advisers)




    let array_narrativeList = []



    Object.entries(narrative_listData).forEach(([key, narrative]) => {
        console.log(narrative);
        if (narrative.program_code === program &&
            narrative.ayStarting === parseInt(ayStarted) &&
            narrative.ayEnding === parseInt(ayEnded) &&
            narrative.Semester === Semester &&
            narrative.file_status === 'Approved'){
            array_narrativeList.push(narrative)
        }

    });
    if (array_narrativeList.length === 0){
        $('#tableLoader').html(`<p class="text-slate-700 font-sans">No result</p>`)

    }else {
        let offset = (page_no - 1) * totalRecPerpage;
        total_page = Math.ceil( array_narrativeList.length/ totalRecPerpage);


        let paginatedList = array_narrativeList.slice(offset, offset + totalRecPerpage);

        paginatedList.forEach(narrative =>{
            let startingAC = narrative.ayStarting
            let endingAC =  narrative.ayEnding
            let formattedSem = {
                First: '1st',
                Second: '2nd',
                Summer: 'Summer'
            };
            narrativeTblData += `
  <tr class="border-b border-dashed last:border-b-0 p-3">
    ${user_data.user_type === 'admin' ? `
      <td class="p-3 text-start">
        <span class="font-semibold text-light-inverse text-sm">${narrative.enrolled_stud_id}</span>
      </td>` : ''}
    <td class="p-3 text-start min-w-32">
      <span class="font-semibold text-light-inverse text-md/normal break-words">${narrative.first_name} ${narrative.last_name}</span>
    </td>
    ${user_data.user_type === 'admin' ? `
      <td class="p-3 text-start min-w-32">
        <span class="font-semibold text-light-inverse text-md/normal break-words">${advisers[narrative.ojt_adv_id]?.name || 'N/A'}</span>
      </td>` : ''}
    <td class="p-3 text-end">
      ${user_data.user_type === 'admin' ? `
        <div class="tooltip tooltip-warning tooltip-bottom" data-tip="Archive">
          <a onclick="openModalForm('archiveNarrativeModal'); $('#archiveNarrative').attr('data-narrative', $(this).attr('data-narrative'));" 
             id="archive_narrative" 
             data-narrative="${narrative.narrative_id}" 
             class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-info">
            <i class="fa-solid fa-pen-to-square"></i>
          </a>
        </div>` : ''}
      <a href="flipbook.php?view=${narrative.narrative_id}" target="_blank" class="hover:cursor-pointer mb-1 font-semibold transition-colors duration-200 ease-in-out text-lg/normal text-secondary-inverse hover:text-accent mr-2">
        <i class="fa-regular fa-eye"></i>
      </a>
    </td>
  </tr>`;

        })
        $('#tableLoader').empty()

        $('#narrativeReportsTableBody').html(narrativeTblData);
    }

}