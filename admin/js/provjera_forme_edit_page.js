  function validateForm() {
    var postTitle = document.forms["updatePost"]["postTitle"].value;
    var postKeywords = document.forms["updatePost"]["postKeywords"].value;
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
							title: 'GREŠKA U NASLOVU ',
							text: 'Naslov vijesti mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravite grešku.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    if (postKeywords == null || postKeywords.length < 4) {
        new PNotify({
							title: 'GREŠKA U OZNACI ',
							text: 'Sadržaj oznake mora sadržavati najmanje 4 karaktera, u suprotnom slučaju biće onemogućeno pretraživanje ove vijesti. Molimo ispravite grešku.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    
    
}