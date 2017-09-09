function validateForm() {
    var title = document.forms["addPoll"]["title"].value;
    var question = document.forms["addPoll"]["question"].value;
    var answerNumber = document.forms["addPoll"]["answerNumber"].value;
    
    if(title == "" || title == null){
         new PNotify({
                            title: 'GREŠKA U NAZIVU ANKETE ',
                            text: 'Niste unijeli naziv ankete.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(question == null || question == ""){
         new PNotify({
                            title: 'GREŠKA U PITANJU ANKETE ',
                            text: 'Niste unijeli pitanje za anketu.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    if(answerNumber < 1 || answerNumber > 9){
        new PNotify({
                            title: 'GREŠKA U BROJU ODGOVORA ',
                            text: 'Broj odgovora mora biti između 2 i 9.',
                            type: 'error',
                            styling: 'fontawesome'
                        });
        return false;
    }
    
    
}
