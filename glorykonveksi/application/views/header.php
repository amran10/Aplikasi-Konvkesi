<nav class="navbar navbar-expand-lg bg-primary fixed-top ">
        <div class="container">
            <div class="navbar-translate">
                <!-- <a class="navbar-brand" rel="tooltip" title="BUMN" data-placement="bottom" target="_blank" style="margin-top: 0px; margin-left: 10%;" >
                    <img src="./assets2/img/bumn1.png" class="creative-tim-logo" style="margin-top: 15px; margin-left: 10%;" />
                </a> -->
               <!--  <a class="navbar-toggler navbar-toggler" rel="tooltip" title="WIKA" data-placement="bottom" target="_blank">
                    <img src="./assets2/img/wika-logo_blue.png" class="creative-tim-logo" style="margin-top: 150px; margin-left: 300%;" />
                </a> -->
                <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
          <!--   <div class="collapse navbar-collapse justify-content-end" id="navigation" data-nav-image="./assets2/img/blurred-image-1.jpg"> -->
                <ul class="navbar-nav" style="padding: 0 3%;">
                    <li class="nav-item">
                        <a class="nav-link btn btn-neutral" href="<?php echo base_url().$nav_beranda; ?>">
                            <!--<i class="now-ui-icons arrows-1_cloud-download-93"></i>-->
                            <p><b>Beranda</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_lowongan; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>Pemesanan</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_kalender; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>Kalender</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_pengumuman; ?>">
                            <!--<i class="now-ui-icons arrows-1_share-66"></i>-->
                            <p><b>Gallery</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_kontak; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>Hubungi Kami</b></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_faq; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>FAQ</b></p>
                        </a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" href="http://www.wika.co.id" target="_blank">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>Tentang Kami</b></p>
                        </a>
                    </li>
                    <?php
                if($this->session->userdata('nm_user') <> ''){  
            ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="now-ui-icons users_single-02" aria-hidden="true"></i> <?php echo substr($this->session->userdata('nm_user'),0,5); ?> 
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="<?php echo base_url()."hal_data_diri"; ?>">CURRICULUM VITAE</a>
                            <a class="dropdown-item" href="<?php echo base_url()."hal_data_diri/seleksi"; ?>">STATUS SELEKSI</a>
                            <a class="dropdown-item" href="<?php echo base_url()."auth_wj/logout"; ?>">LOGOUT</a>
                        </div>
                    </li>
            <?php }else{ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url().$nav_login; ?>">
                            <!--<i class="now-ui-icons files_paper"></i>-->
                            <p><b>LOGIN/SINGUP</b></p>
                        </a>
                    </li>
            <?php } ?>
                </ul>
                <a class="collapse2" href="#" rel="tooltip" title="WIKA" data-placement="bottom" target="_blank">
                    <img src="./assets2/img/wika-logo_blue.png" class="creative-tim-logo" />
                </a>
            </div>
            
        </div>
    </nav>