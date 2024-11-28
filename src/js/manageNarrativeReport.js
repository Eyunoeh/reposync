function treeListener() {
    document.querySelectorAll('.tree-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const subtree = button.nextElementSibling;

            if (subtree) {
                subtree.classList.toggle('hidden');
                const icon = button.querySelector('i');
                if (subtree.classList.contains('hidden')) {
                    icon.classList.replace('fa-angle-down', 'fa-plus');
                } else {
                    icon.classList.replace('fa-plus', 'fa-angle-down');
                }
            }
        });
    });
}

async function narrativeReportsTree() {
    let { data: SemAcadYears } = await $.ajax({
        url: '../ajax.php?action=AcadYears',
        method: 'GET',
        dataType: 'json'
    });

    let htmlTree = '';
    let narrativeReportsTree = SemAcadYears.reduce((acc, item) => {
        let key = `${item.ayStarting}-${item.ayEnding}`;
        if (!acc[key]) {
            acc[key] = { starting: item.ayStarting, ending: item.ayEnding, semesters: [] };
        }
        acc[key].semesters.push({ semester: item.Semester, current: item.Curray_sem, ay_id: item.id });
        return acc;
    }, {});

    narrativeReportsTree = Object.values(narrativeReportsTree);

    for (const acadYear of narrativeReportsTree) {
        htmlTree += `<ul class="list-none">
                        <li>
                            <button class="tree-toggle hover:bg-slate-200">
                                <i class="fa-solid fa-plus"></i> AY ${acadYear.starting}-${acadYear.ending}
                            </button>
                            <ul class="ml-4 hidden">`;

        for (const sem of acadYear.semesters) {
            const programs = await acadYearPrograms(sem.ay_id, acadYear.starting, acadYear.ending, sem.semester);

            htmlTree += `<li>
                            <button class="tree-toggle hover:bg-slate-200">
                                <i class="fa-solid fa-plus"></i> ${sem.semester} Semester
                            </button>
                            <ul class="ml-8 hidden flex flex-col gap-2">
                                ${programs}
                            </ul>
                        </li>`;
        }

        htmlTree += `     </ul>
                        </li>
                    </ul>`;
    }

    $('#treeview').html(htmlTree);
}

async function acadYearPrograms(ay_id, ayStarting, ayEnding, Semester) {
    let { data: programs } = await $.ajax({
        url: `../ajax.php?action=acadyearPrograms&ay_id=${ay_id}`,
        method: 'GET',
        dataType: 'json'
    });

    let htmlPrograms = '';
    programs.forEach(program => {
        htmlPrograms += `<li>
            <a href="dashboardViewnarrativeReports.php?ayStarting=${ayStarting}&ayEnding=${ayEnding}&Semester=${Semester}&program=${program.program_code}" 
               class="link link-info">${program.program_code} </a>
        </li>`;
    });
    return htmlPrograms;
}
