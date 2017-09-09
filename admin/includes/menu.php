<?php 
        checkIfLoggedIn($_SESSION['AUTHOR_USERID'], $_SESSION["AUTHOR_EMAIL"]);
        
        $connectOnDb = connectToDb();
        $fetchAuthor = $connectOnDb->prepare("SELECT * FROM authors WHERE author_id = :ID LIMIT 1");
        $fetchAuthor->bindParam(":ID", $_SESSION['AUTHOR_USERID']);
        $fetchAuthor->execute();
        if($fetchAuthor->rowCount() == 1){
                foreach ($fetchAuthor as $row) {
                        $firstName = $row['author_name'];
                        $lastName = $row['author_surname'];
                        $image = $row['author_image'];
                }
        }
        $connectOnDb = NULL;




?>
<header id="topnav" class="navbar navbar-midnightblue navbar-fixed-top clearfix" role="banner">

        <span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg">
		<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar"><span class="icon-bg"><i class="fa fa-fw fa-bars"></i></span></a>
        </span>

        <a class="navbar-brand" href="index.html">ETS Admin</a>

        <span id="trigger-infobar" class="toolbar-trigger toolbar-icon-bg">
		<a data-placement="left"></a>
	</span>


        <div class="yamm navbar-left navbar-collapse collapse in">
                <ul class="nav navbar-nav">

                        <li class="dropdown" id="widget-classicmenu">
                                <a href="../index.php" target="_blank">Pogledaj stranicu</a>
                                
                        </li>
                </ul>
        </div>

        <ul class="nav navbar-nav toolbar pull-right">
                <li class="dropdown toolbar-icon-bg">
                        <a href="#" id="navbar-links-toggle" data-toggle="collapse" data-target="header>.navbar-collapse">
                                <span class="icon-bg">
					<i class="fa fa-fw fa-ellipsis-h"></i>
				</span>
                        </a>
                </li>

                <li class="dropdown toolbar-icon-bg demo-search-hidden">
                        <a href="#" class="dropdown-toggle tooltips" data-toggle="dropdown"><span class="icon-bg"><i class="fa fa-fw fa-search"></i></span></a>

                        <div class="dropdown-menu dropdown-alternate arrow search dropdown-menu-form">
                                <div class="dd-header">
                                        <span>Tražilica...</span>
                                        <span><a href="#">Pretraga...</a></span>
                                </div>
                                <div class="input-group">
                                        <input type="text" class="form-control" placeholder="">

                                        <span class="input-group-btn">
						
						<a class="btn btn-primary" href="#">Pretraga...</a>
					</span>
                                </div>
                        </div>
                </li>

                <li class="toolbar-icon-bg demo-headerdrop-hidden">
                        <a href="#" id="headerbardropdown"><span class="icon-bg"><i class="fa fa-fw fa-level-down"></i></span></i></a>
                </li>

                <li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
                        <a href="#" class="toggle-fullscreen"><span class="icon-bg"><i class="fa fa-fw fa-arrows-alt"></i></span></i></a>
                </li>
                <li class="dropdown toolbar-icon-bg">
                        <a href="#" class="dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="fa fa-fw fa-user"></i></span></a>
                        <ul class="dropdown-menu userinfo arrow">
                                <li><a href="account.php"><span class="pull-left">Račun</span> <i class="pull-right fa fa-user"></i></a></li>
                                <li><a href="account_settings.php"><span class="pull-left">Postavke računa</span> <i class="pull-right fa fa-cog"></i></a></li>
                                <li><a href="#"><span class="pull-left">Sigurnost</span> <i class="pull-right fa fa-shield"></i></a></li>
                                <li><a href="logout.php"><span class="pull-left">Odjavi se</span> <i class="pull-right fa fa-sign-out"></i></a></li>
                                <li class="divider"></li>
                                <li><a href="#"><span class="pull-left">Pomoć</span> <i class="pull-right fa fa-plus-square"></i></a></li>
                                <li><a href="#"><span class="pull-left">Prijava problema</span> <i class="pull-right fa fa-warning"></i></a></li>
                                <li><a href="#"><span class="pull-left">Podaci o stranici</span> <i class="pull-right fa fa-sign-out"></i></a></li>

                        </ul>
                </li>

        </ul>

</header>

<div id="wrapper">
        <div id="layout-static">
                <div class="static-sidebar-wrapper sidebar-midnightblue">
                        <div class="static-sidebar">
                                <div class="sidebar">
                                <div class="widget stay-on-collapse" id="widget-welcomebox">
                                        <div class="widget-body welcome-box tabular">
                                            <div class="tabular-row">
                                                <div class="tabular-cell welcome-avatar">
                                                    <a href="#"><?php echo "<img src='../images/prof/$image' class='avatar'>"; ?></a>
                                                </div>
                                                <div class="tabular-cell welcome-options">
                                                    <span class="welcome-text">Dobrdošli,</span>
                                                    <a href="account.php" class="name"><?php echo $firstName ." " .$lastName; ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget stay-on-collapse" id="widget-sidebar">
                                                <nav role="navigation" class="widget-body">
                                                        <ul class="acc-menu">

                                                                <li class="nav-separator"></li>

                                                                <li><a href="index.php"><i class="fa fa-home"></i><span>Početna</span></a></li>

                                                                <li><a href="javascript:;"><i class="fa fa-columns"></i><span>Slider</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><a href="slider.php">Pogledaj Slider</a></li>
                                                                        <li><?php echo '<a href="slider.php?'.base64_url_encode("postNewSlide") .'='.base64_url_encode("new").'">Dodaj Novi Slider</a>'; ?></li>
                                                                    </ul>
                                                                </li>
                                                                
                                                                 <li><a href="javascript:;"><i class="fa fa-edit"></i><span>Vijesti</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><a href="vijesti.php">Pogledaj Vijesti</a></li>
                                                                        <li><?php echo '<a href="vijesti.php?'.base64_url_encode("postNewNews") .'='.base64_url_encode("new").'">Dodaj Novu Vijest</a>'; ?></li>
                                                                    </ul>
                                                                 </li>

                                                                 <li><a href="javascript:;"><i class="fa fa-car"></i><span>Događaji</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><a href="dogadjaji.php">Pogledaj Događaje</a></li>
                                                                        <li><?php echo '<a href="izmjena_dogadjaji.php?'.base64_url_encode("action")."=".base64_url_encode("insert").'">Dodaj Novi Događaj</a>'; ?></li>
                                                                    </ul>
                                                                 </li>
                                                                 <li><a href="javascript:;"><i class="fa fa-film"></i><span>Galerija</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><a href="gallery.php">Pogledaj Albume</a></li>
                                                                        <li><?php echo '<a href="gallery.php">Dodaj Novi Album</a>'; ?></li>
                                                                    </ul>
                                                                 </li>
                                                                <li><a href="obavijesti.php"><i class="fa  fa-info-circle"></i><span>Obavijesti</span></a></li>
                                                                
                                                                <?php if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                    echo '<li><a href="anketa.php"><i class="fa fa-male"></i><span>Anketa</span></a></li>';
                                                                }
                                                                ?>
                                                                <?php if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                            echo '
                                                                             <li><a href="javascript:;"><i class="fa fa-bank"></i><span>Zanimanja</span></a>
                                                                                 <ul class="acc-menu">';
                                                                                 $url4God = "zanimanja.php?".base64_url_encode("trajanjeSkolovanja")."=".base64_url_encode("4");
                                                                                 $url3God = "zanimanja.php?".base64_url_encode("trajanjeSkolovanja")."=".base64_url_encode("3");
                                                                                  echo '  <li><a href="'.$url4God.'">4 Godine</a></li>
                                                                                    <li><a href="'.$url3God.'">3 Godine</a></li>
                                                                                </ul>
                                                                             </li>';
                                                                        }
                                                                 ?>
                                                                <?php if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                    echo '
                                                                <li><a href="javascript:;"><i class="fa fa-tasks"></i><span>Upis</span></a>
                                                                    <ul class="acc-menu">';
                                                                        
                                                                        $staUpisat = "upis.php?".base64_url_encode("stranica")."=".base64_url_encode("staupisati");
                                                                        $postupakBodovanjeNaUpisu = "upis.php?".base64_url_encode("stranica")."=".base64_url_encode("postupakIBodovanjePriUpisu");
                                                                        $prijaveKandidata = "upis.php?".base64_url_encode("stranica")."=".base64_url_encode("prijaveKandidata");
                                                                        $vodicZaStudente = "upis.php?".base64_url_encode("stranica")."=".base64_url_encode("vodicZaStudente");
                                                                        echo '
                                                                        <li><a href="'.$staUpisat.'">Šta upisati...?</a></li>
                                                                        <li><a href="'.$postupakBodovanjeNaUpisu.'">Postupak i bodovanje pri upisu</a></li>
                                                                        <li><a href="'.$vodicZaStudente.'">Vodič za učenike</a></li>
                                                                    </ul>
                                                                 </li>';
                                                                 }
                                                                ?>
                                                                <?php if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                    $onama = "o_skoli.php?".base64_url_encode("stranica")."=".base64_url_encode("oNama");
                                                                    $rijeciDirektora = "o_skoli.php?".base64_url_encode("stranica")."=".base64_url_encode("rijeciDirektora");
                                                                    $PravilaSkole = "o_skoli.php?".base64_url_encode("stranica")."=".base64_url_encode("pravilaSkole");
                                                                    echo '<li><a href="javascript:;"><i class="fa fa-book"></i><span>Podaci o školi</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><a href="'.$onama.'">O Nama</a></li>
                                                                        <li><a href="'.$rijeciDirektora.'">Riječi direktora</a></li>
                                                                        <li><a href="'.$PravilaSkole.'">Pravila škole</a></li>
                                                                    </ul>
                                                                 </li>';
                                                                }
                                                                ?>
                                                                <li><a href="komentari.php"><i class="fa  fa-comments"></i><span>Komentari</span></a></li>
                                                                <?php $urlDownload = "ostalo.php?".base64_url_encode("stranica")."=".base64_url_encode("download");
                                                                echo '
                                                                    <li><a href="'.$urlDownload.'"><i class="fa  fa-download"></i><span>Download</span></a></li>
                                                                    ';
                                                                ?>
                                                                <li><a href="javascript:;"><i class="fa fa-folder"></i><span>Ostalo</span></a>
                                                                    <ul class="acc-menu">
                                                                        <li><?php echo '<a href="ostalo.php?'.base64_url_encode("stranica")."=".base64_url_encode("sekcija").'">Sekcije</a>'; ?></li>
                                                                        <li><?php echo '<a href="ostalo.php?'.base64_url_encode("stranica")."=".base64_url_encode("kutakzaroditelje").'">Kutak za roditelje</a>'; ?></li>
                                                                    </ul>
                                                                 </li>
                                                                <?php if(checkIsAdmin($_SESSION['AUTHOR_USERID'])) {
                                                                    echo '<li><a href="active_author.php"><i class="fa fa-users"></i><span>Potvrda autora</span></a></li>';
                                                                }
                                                                ?>
                                                               <br><br><br>
                                                               
                                                        </ul>
                                                </nav>
                                        </div>
                                </div>
                        </div>
                </div>
        
               