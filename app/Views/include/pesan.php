 <?php
    if (session()->getFlashdata('success')) { ?>
     <div class="ui-pnotify ui-pnotify-fade-normal ui-pnotify-mobile-able ui-pnotify-in ui-pnotify-fade-in ui-pnotify-move" aria-live="assertive" aria-role="alertdialog" style="display: none; width: 300px; right: 36px; top: 36px; cursor: auto;">
         <div id="alertSuccess" class="brighttheme ui-pnotify-container brighttheme-success ui-pnotify-shadow" role="alert" style="min-height: 16px;">
             <div class="ui-pnotify-closer" aria-role="button" tabindex="0" title="Close" style="cursor: pointer; visibility: hidden;"><span class="brighttheme-icon-closer"></span></div>
             <div class="ui-pnotify-sticker" aria-role="button" aria-pressed="false" tabindex="0" title="Stick" style="cursor: pointer; visibility: hidden;"><span class="brighttheme-icon-sticker" aria-pressed="false"></span></div>
             <div class="ui-pnotify-icon"><span class="brighttheme-icon-success"></span></div>
             <h4 class="ui-pnotify-title">Success notice</h4>
             <div class="ui-pnotify-text" aria-role="alert"> <?= session()->getFlashdata('success') ?></div>
             <div class="ui-pnotify-action-bar" style="margin-top: 5px; clear: both; text-align: right; display: none;"></div>
         </div>
     </div>
 <?php } ?>

 <?php
    if (session()->getFlashdata('error')) { ?>
     <div class="ui-pnotify ui-pnotify-fade-normal ui-pnotify-mobile-able ui-pnotify-in ui-pnotify-fade-in ui-pnotify-move" aria-live="assertive" aria-role="alertdialog" style="display: none; width: 300px; right: 36px; top: 36px; cursor: auto;">
         <div id="alertDanger" class="brighttheme ui-pnotify-container brighttheme-error ui-pnotify-shadow" role="alert" style="min-height: 16px;">
             <div class="ui-pnotify-closer" aria-role="button" tabindex="0" title="Close" style="cursor: pointer; visibility: hidden;"><span class="brighttheme-icon-closer"></span></div>
             <div class="ui-pnotify-sticker" aria-role="button" aria-pressed="false" tabindex="0" title="Stick" style="cursor: pointer; visibility: hidden;"><span class="brighttheme-icon-sticker" aria-pressed="false"></span></div>
             <div class="ui-pnotify-icon"><span class="brighttheme-icon-error"></span></div>
             <h4 class="ui-pnotify-title">Error notice</h4>
             <div class="ui-pnotify-text" aria-role="alert"><?= session()->getFlashdata('error') ?></div>
             <div class="ui-pnotify-action-bar" style="margin-top: 5px; clear: both; text-align: right; display: none;"></div>
         </div>
     </div>
 <?php } ?>