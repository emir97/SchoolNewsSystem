		<div class="widget-main">
                    <div class="widget-main-title">
                        <h4 class="widget-title">Obavijesti</h4>
                    </div>
                    <div class="widget-inner">
                        <div id="slider-testimonials">
                            <ul>
                                <?php 
                                    $notifyResult = $connectToDb->query("SELECT * FROM notify LIMIT 6");
                                    foreach($notifyResult as $row){
                                        $notifyContent = $row['notify_content'];
                                        $authorID = $row['author_id'];
                                        
                                        $author = $connectToDb->query("SELECT * FROM authors WHERE author_id=$authorID LIMIT 1");
                                        foreach($author as $authorRow){
                                            $authorName = $authorRow['author_name'];
                                            $authorSurname = $authorRow['author_surname'];
                                            break;
                                        }
                                        echo '
                                                <li>
                                                  <p>'.$notifyContent.' <strong class="dark-text"> '.$authorName . "&nbsp;" . $authorSurname.'</strong></p>
                                                </li>
                                        
                                             ';
                                     
                                    }
                                ?>
                            </ul>
                            <a class="prev fa fa-angle-left" href="./index.php"></a>
                            <a class="next fa fa-angle-right" href="./index.php"></a>
                        </div>
                    </div> 
                    <!-- /.widget-inner -->
                </div>
                <!-- /.widget-main -->