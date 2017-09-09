function validateForm() {
    var postTitle = document.forms["updatePost"]["postTitle"].value;
    var postImage = document.getElementById("postImage").value;
    var ext = $('#postImage').val().split('.').pop().toLowerCase();
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
                            title: 'GREŠKA U NASLOVU ',
                            text: 'Naslov mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravi grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(postImage == "" || postImage == null){
         new PNotify({
                            title: 'GREŠKA KOD SLIKE ',
                            text: 'Niste izabrali sliku. Događaj mora sadržavati sliku. Molimo ispravi grešku.',
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
function validateForm2() {
    var postTitle = document.forms["updatePost"]["postTitle"].value;
    
    if (postTitle == null || postTitle.length < 5 || postTitle.length > 99) {
        new PNotify({
                            title: 'GREŠKA U NASLOVU ',
                            text: 'Naslov mora sadržavati najmanje 5 a najviše 100 karaktera. Molimo ispravi grešku.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    
    
}
