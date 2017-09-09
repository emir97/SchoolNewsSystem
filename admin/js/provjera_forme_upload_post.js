  function validateForm() {
    var postTitle = document.forms["updatePost"]["postTitle"].value;
    var postKeywords = document.forms["updatePost"]["postKeywords"].value;
    var postImage = document.getElementById("postImage").value;
    var ext = $('#postImage').val().split('.').pop().toLowerCase();
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
							title: 'GREŠKA U NASLOVU ',
							text: 'Naslov vijesti mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravite grešku.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    if(postImage == "" || postImage==null){
         new PNotify({
							title: 'GREŠKA KOD SLIKE ',
							text: 'Niste izabrali sliku. Vijest mora sadržavati sliku. Molimo ispravite grešku.',
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
    if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
        new PNotify({
							title: 'GREŠKA KOD SLIKE ',
							text: 'Neispravna ekstenzija slika. Slika može sadržavati samo ekstenzije .jpg; .jpeg; .png; .',
							type: 'error',
							styling: 'fontawesome'
						});
    return false;
    }
    
    
}