app.directive('newsCard', function() { 
  return { 
    restrict: 'E', 
    scope: { 
      info: '=' 
    }, 
    templateUrl: 'directives/news_directive.html' 
  }; 
});