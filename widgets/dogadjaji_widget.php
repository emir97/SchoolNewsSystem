
<script>
     app.controller('eventsWidget', function($scope, $http) {
      $http.get('response/widgets.php').success(function(response){
        $scope.eventsWidgets = response.events;
      });
    });
     </script>
<div class="widget-main">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Događaji</h4>
                    </div>
                    <div class="widget-inner" ng-controller="eventsWidget">
                        <div class="event-small-list clearfix" ng-repeat="eventWidget in eventsWidgets">
                            <div class="calendar-small">
                                <span class="s-month">{{eventWidget.MonthStart}}</span>
                                <span class="s-date">{{eventWidget.DayStart}}</span>
                            </div>
                            <div class="event-small-details">
                                <h5 class="event-small-title"><a ng-href="{{eventWidget.URL}}">{{eventWidget.Title}}</a></h5>
                                <p class="event-small-meta small-text">Počinje: {{eventWidget.DateStart}}</p>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.widget-inner -->
                </div>
                <!-- /.widget-main -->