function validateForm() {
    var postTitle = document.forms["events"]["title"].value;
    var postKeywords = document.forms["events"]["place"].value;
    var postImage = document.getElementById("postImage").value;
    var startDate = document.forms["events"]["startDate"].value;
    var startTime = document.forms["events"]["startTime"].value;
    var endTime = document.forms["events"]["endTime"].value;
    var ext = $('#postImage').val().split('.').pop().toLowerCase();
    var time = startTime.split(":");
    var endt = endTime.split(":");
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
                            title: 'GREŠKA U NASLOVU ',
                            text: 'Naslov mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(postImage == "" || postImage == null){
         new PNotify({
                            title: 'GREŠKA KOD SLIKE ',
                            text: 'Niste izabrali sliku. Događaj mora sadržavati sliku. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(startDate == null || startDate == ""){
         new PNotify({
                            title: 'GREŠKA U DATUMU ',
                            text: 'Morate izabrati datum kada će se održati događaj. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(startTime == null || startTime == ""){
        new PNotify({
                            title: 'GREŠKA U VREMENU ',
                            text: 'Morate izabrati vrijeme kada će se održati događaj. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(time[0] > 23 || time[0] < 0){
        new PNotify({
                            title: 'GREŠKA U VREMENU ',
                            text: 'Dan može maksimalno sadržavati 23 sata. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(time[1] > 59 || time[1] < 0){
        new PNotify({
                            title: 'GREŠKA U VREMENU ',
                            text: 'Sat može maksimalno sadržavati 59 minuta. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(endt[0] > 23 || endt[0] < 0){
        new PNotify({
                            title: 'GREŠKA U VREMENU ',
                            text: 'Dan može maksimalno sadržavati 23 sata. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(endt[1] > 59 || endt[1] < 0){
        new PNotify({
                            title: 'GREŠKA U VREMENU ',
                            text: 'Sat može maksimalno sadržavati 59 minuta. Molimo ispravite grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if (postKeywords == null || postKeywords.length < 4) {
        new PNotify({
                            title: 'GREŠKA U MJESTU ',
                            text: 'Morate izabrati mjesto gdje će se održavati događaj. Molimo ispravite grešku.',
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
