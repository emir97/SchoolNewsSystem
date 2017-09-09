
 <div class="widget-main">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Oznake</h4>
                    </div>
                    <div class="widget-inner">
                        <div class="blog-categories">
                            <div class="row" ng-controller="oznake">
                                <ul class="col-md-6">
                                    <li ng-repeat="key1 in keywords1"><a ng-href='{{key1.URL}}'>{{key1.key}}</a></li>
                                </ul>
                                <ul class="col-md-6">
                                   <li ng-repeat="key2 in keywords2"><a ng-href='{{key2.URL}}'>{{key2.key}}</a></li>
                                </ul>
                            </div>
                        </div> <!-- /.blog-categories -->
                    </div> <!-- /.widget-inner -->
                </div> <!-- /.widget-main -->