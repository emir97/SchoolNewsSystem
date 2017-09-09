function validateInsert() {
	var postTitle = document.forms["zanimanje"]["title"].value;
    var postContent = document.forms["zanimanje"]["postContent"].value;
    var postImage = document.getElementById("postImage").value;
    var ext = $('#postImage').val().split('.').pop().toLowerCase();
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 199) {
        new PNotify({
							title: 'GREŠKA U NASLOVU ',
							text: 'Naslov mora sadržavati najmanje 5 a najviše 200 karaktera. Molimo ispravite grešku.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    if (postContent == null || postContent == "") {
        new PNotify({
							title: 'GREŠKA U SADRŽAJU ',
							text: 'Niste popunili sadržaj zanimanja. molimo ispravite grešku',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    if(postImage == "" || postImage==null){
         new PNotify({
							title: 'GREŠKA KOD SLIKE ',
							text: 'Niste izabrali sliku. Molimo ispravite grešku.',
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

function validateEdit() {
	var postTitle = document.forms["zanimanje"]["title"].value;
    var postContent = document.forms["zanimanje"]["postContent"].value;
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
							title: 'GREŠKA U NASLOVU ',
							text: 'Naslov mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravite grešku.',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    if (postContent == null || postContent == "") {
        new PNotify({
							title: 'GREŠKA U SADRŽAJU ',
							text: 'Niste popunili sadržaj zanimanja. molimo ispravite grešku',
							type: 'error',
							styling: 'fontawesome'
						});
        return false;
    }
    
}