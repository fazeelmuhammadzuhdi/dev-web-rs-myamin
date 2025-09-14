<style>
    @media (max-width: 768px) {
        .pertanyaan {
            padding: 7px !important;
        }

    }
</style>

<div class="heading-block fancy-title border-bottom-0 title-bottom-border">
    <h3><span class="text-black">Profil</span></h3>
</div>
<div class="blog-thumb-v3 mb-2 pertanyaan">
    <large>
        <strong>Alamat :</strong>
    </large>

    <br> <small>
        <?= $profil['alamat'] ?>
    </small>
    <hr class="hr-xs">

</div>
<div class="blog-thumb-v3 mb-2 pertanyaan">
    <large><strong>Telpon :</strong></large><br> <small><?= $profil['telepon'] ?></small>
    <hr class="hr-xs">

</div>
<div class="blog-thumb-v3 mb-2 pertanyaan">
    <large><strong>Fax :</strong></large><br> <small><?= $profil['fax'] ?></small>
    <hr class="hr-xs">

</div>
<div class="blog-thumb-v3 mb-2 pertanyaan">
    <large><strong>Email :</strong></large><br> <small><?= $profil['email'] ?></small>
    <hr class="hr-xs">

</div>