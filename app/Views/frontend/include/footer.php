 <?php
    $profil = (new \App\Models\Profil())->first();
    ?>

 <footer id="footer" class="dark">
     <div class="container">
         <div class="row col-mb-50 mt-4">
             <div class="col-lg-8">

                 <div class="row col-mb-50">
                     <div class="col-md-6">

                         <div class="widget clearfix">
                             <img src="<?= base_url(); ?>/assets/images/rsud.png" alt="Image" class="footer-logo">
                         </div>

                     </div>

                 </div>

             </div>

             <div class="col-lg-4">
                 <div class="row col-mb-50">
                     <div class="col-md-12 text-center text-md-end">
                         <div class="d-flex justify-content-center justify-content-md-end">
                             <a href="https://www.facebook.com/rsudprofmyamin.sh" target="_blank" class="social-icon si-small si-colored si-facebook" aria-label="Visit our Facebook page">
                                 <i class="icon-facebook"></i>
                                 <i class="icon-facebook"></i>
                             </a>

                             <a href="https://www.youtube.com/@rsudpariaman1818" target="_blank" class="social-icon si-small si-colored si-youtube" aria-label="Visit our YouTube channel">
                                 <i class="icon-youtube"></i>
                                 <i class="icon-youtube"></i>
                             </a>


                             <a href="https://www.instagram.com/rsudprofmyamin.sh" target="_blank" class="social-icon si-small si-colored si-instagram" aria-label="Visit our Instagram page">
                                 <i class="icon-instagram"></i>
                                 <i class="icon-instagram"></i>
                             </a>

                             <a href="https://www.tiktok.com/@rsud_profmyaminsh" target="_blank" class="social-icon si-small si-colored si-tiktok" aria-label="Visit our TikTok page">
                                 <i class="icon-tiktok"></i>
                                 <i class="icon-tiktok"></i>
                             </a>
                         </div>

                         <div class="clear"></div>

                         <i class="icon-envelope2"></i> <?= $profil['email'] ?> <br>
                         <i class="icon-call"></i> <?= $profil['telepon'] ?>
                     </div>
                 </div>
             </div>
         </div>

     </div>
     <div id="copyrights" style="padding: 20px !important;">
         <div class="container">

             <div class="row">
                 <div class="col-12 text-center">
                     <span class="text-white fw-bold" style="font-size: 0.8rem !important;">&copy; 2024 Instalasi IT <?= $profil['nama'] ?></span>
                 </div>

             </div>

         </div>
     </div>

 </footer>