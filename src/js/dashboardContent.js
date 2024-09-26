(async () => {
    try {
        let pendingNoteCount = await getTotalPendingNotes();
        let unreadWeeklyreports = await getTotalUnreadStudentWeeklyReport();
        let pendingUploadNarrative =  await getTotalPendingUploadNarrative();
        let declinedUploadNarrative =  await  getTotalDeclinedUploadNarrative();
        let totalAdv = await totalUser('','');
        $('#pendingNoteCount').html(pendingNoteCount)
        $('#UnreadStudWeeklyReport').html(unreadWeeklyreports)
        $('#pendingUploadNarrativeReport').html(pendingUploadNarrative);
        $('#declinedUploadNarrativeReport').html(declinedUploadNarrative);
        $('#totalAdvisory').html(totalAdv);

    } catch (error) {
        console.error('Error:', error);
    }
})();