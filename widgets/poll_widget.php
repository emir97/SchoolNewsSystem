  <div class="widget-main" ng-controller="anketa">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Anketa</h4>
                    </div>
                    <div class="widget-inner">
                      <p class="anket-naslov">{{question}}</p>
                      
                        <form method="get" action="poll_vote.php">
                            <div class="answer" ng-repeat="answer in answers">
                                <input type="radio" id="poll" value="{{answer.ID}}" name="vote"> &nbsp;&nbsp;&nbsp;{{answer.answer}}<br>
                            </div><br>
                        <input type="submit" class="btn btn-primary" value="Glasaj">&nbsp;&nbsp;&nbsp;
                        <a href="rezultat_ankete.php?pollID={{ID}}" class="btn btn-default">Rezultati</a>
                        </form>
                      
                        <!-- /.galler-small-thumbs -->
                    </div>
                    <!-- /.widget-inner -->
                </div>
                <!-- /.widget-main -->
               